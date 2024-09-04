<?php

namespace PHPNomad\Integrations\WordPress\Database;

use PHPNomad\Database\Interfaces\ClauseBuilder as ClauseBuilderInterface;
use PHPNomad\Database\Traits\WithPrependedFields;
use PHPNomad\Integrations\WordPress\Traits\CanGetDataFormats;
use PHPNomad\Utils\Helpers\Arr;

class ClauseBuilder implements ClauseBuilderInterface
{
    use CanGetDataFormats, WithPrependedFields;

    protected array $clauses = [];
    protected array $preparedValues = [];
    protected array $validOperators = ["=", "<", ">", "<=", ">=", "<>", "!=",
        "LIKE", "NOT LIKE", "IN", "NOT IN", "BETWEEN",
        "NOT BETWEEN", "IS NULL", "IS NOT NULL"];

    /**
     * @inheritDoc
     */
    public function where($field, string $operator, ...$values)
    {
        $this->addCondition($field, $operator, $values);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function andWhere($field, string $operator, ...$values)
    {
        $this->addCondition($field, $operator, $values, 'AND');
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function orWhere($field, string $operator, ...$values)
    {
        $this->addCondition($field, $operator, $values, 'OR');
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function group(string $logic, ClauseBuilderInterface ...$clauses)
    {
        $group = ['logic' => $logic, 'clauses' => $clauses];
        $this->clauses[] = $group;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function andGroup(string $logic, ClauseBuilderInterface ...$clauses)
    {
        if (!empty($this->clauses)) {
            $this->clauses[] = 'AND';
        }

        return $this->group($logic, ...$clauses);
    }

    /**
     * @inheritDoc
     */
    public function orGroup(string $logic, ClauseBuilderInterface ...$clauses)
    {
        if (!empty($this->clauses)) {
            $this->clauses[] = 'OR';
        }

        return $this->group($logic, ...$clauses);
    }

    /**
     * Gets the field string, filtering invalid fields.
     *
     * @param $field
     * @return string|null
     */
    protected function getFieldString($field): ?string
    {
        $result = null;

        if (!is_array($field) && $this->tableHasField($field)) {
            $result = $this->prependField($field);
        }

        if (is_array($field)) {
            $fieldStr = Arr::process($field)
                ->filter(fn($field) => $this->tableHasField($field))
                ->map(fn($field) => $this->prependField($field))
                ->setSeparator(', ')
                ->toString();

            if (!empty($fieldStr)) {
                $result = "($fieldStr)";
            }
        }

        return $result;
    }

    /**
     * Adds a condition to the clause builder.
     *
     * @param string|string[] $field The field, or fields to be compared.
     * @param string $operator The operator to be used in the comparison.
     * @param array $values The values to be compared against.
     * @param ?string $logic (optional) The logic operator to be prepended to the condition.
     * @return $this
     */
    protected function addCondition($field, string $operator, array $values, ?string $logic = null): self
    {
        $operator = strtoupper($operator);

        if (!in_array($operator, $this->validOperators)) {
            return $this;
        }

        $placeholder = $this->generatePlaceholder($field, $values, $operator);

        $fieldStr = $this->getFieldString($field);

        if (!empty($this->clauses) && $logic && in_array(strtoupper($logic), ['AND', 'OR'])) {
            $this->clauses[] = strtoupper($logic);
        }

        $this->clauses[] = $fieldStr;
        $this->clauses[] = $operator;
        $this->clauses[] = $placeholder;

        foreach (Arr::whereNotNull($values) as $value) {
            if (is_array($value)) {
                $this->preparedValues = Arr::merge($this->preparedValues, array_values($value));
            } else {
                $this->preparedValues[] = $value;
            }
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function build(): string
    {
        global $wpdb;
        $queryParts = [];
        $allValues = $this->preparedValues; // Initially prepared values
        $subQueryReplacements = [];
        $query = "";
        $marker = 0;

        foreach ($this->clauses as $clause) {
            if (is_string($clause)) {
                // Directly append logical operators or raw SQL parts
                $queryParts[] = $clause;
            } elseif (is_array($clause) && isset($clause['logic'], $clause['clauses'])) {
                // Process group of clauses
                $groupParts = [];
                foreach ($clause['clauses'] as $groupClause) {
                    if ($groupClause instanceof ClauseBuilderInterface) {
                        $marker++;
                        $uniqueMarker = '__NOMADIC_SUBQUERY__' . $marker;
                        $builtClause = $groupClause->build();
                        $subQueryReplacements[$uniqueMarker] = $builtClause;
                        $groupParts[] = $uniqueMarker;
                    }
                }
                if (!empty($groupParts)) {
                    $queryParts[] = '(' . implode(" {$clause['logic']} ", $groupParts) . ')';
                }
            } elseif ($clause instanceof ClauseBuilderInterface) {
                $marker++;
                $uniqueMarker = '__NOMADIC_SUBQUERY__' . $marker;
                $builtClause = $clause->build();
                $subQueryReplacements[$uniqueMarker] = $builtClause;
                $queryParts[] = $uniqueMarker;
            }
        }

        if (!empty($queryParts)) {
            $query = implode(' ', $queryParts);

            // Prepare the query with initial values if available
            if (!empty($allValues)) {
                $query = $wpdb->prepare($query, ...$allValues);
            }

            // Replace subquery markers with their actual queries
            foreach ($subQueryReplacements as $marker => $subQuery) {
                $query = str_replace($marker, $subQuery, $query);
            }
        }

        $this->reset();

        return $query;
    }

    /**
     * @inheritDoc
     */
    public function reset()
    {
        $this->clauses = [];
        $this->preparedValues = [];

        return $this;
    }

    protected function generatePlaceholder($field, array $values, string $operator): string
    {
        $operator = strtoupper($operator);

        if($operator === 'IS NULL' || $operator === 'IS NOT NULL'){
            return "";
        }

        if ($operator === 'IN' || $operator === 'NOT IN') {

            // Group fields with multiple $field values (%s,%s),(%s,%s), else just flatten them %s,%s,%s,%s
            if (is_array($field)) {
                $subgroup = "(" . implode(', ', array_fill(0, count($field), '%s')) . ")";
                $placeholderGroup = implode(', ', array_fill(0, count($values), $subgroup));
            } else {
                $placeholderGroup = implode(', ', array_fill(0, count($values), '%s'));
            }

            return "($placeholderGroup)";
        }

        return '%s';
    }
}