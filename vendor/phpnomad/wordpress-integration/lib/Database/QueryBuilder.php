<?php

namespace PHPNomad\Integrations\WordPress\Database;

use PHPNomad\Database\Exceptions\QueryBuilderException;
use PHPNomad\Database\Interfaces\ClauseBuilder;
use PHPNomad\Database\Interfaces\QueryBuilder as QueryBuilderInterface;
use PHPNomad\Database\Interfaces\Table;
use PHPNomad\Database\Traits\WithPrependedFields;
use PHPNomad\Integrations\WordPress\Traits\CanGetDataFormats;
use PHPNomad\Utils\Helpers\Arr;
use wpdb;

class QueryBuilder implements QueryBuilderInterface
{
    use CanGetDataFormats;
    use WithPrependedFields;

    protected array $select = [];

    protected array $from = [];

    protected array $sql = [];

    private array $preparedValues = [];

    private array $prepare = [];

    protected array $raw = [];

    protected array $items = [];

    protected array $operands = [];

    protected array $limit = [];

    protected array $offset = [];

    protected array $orderBy = [];

    protected ?ClauseBuilder $clauseBuilder = null;
    protected array $groupBy = [];

    /** @inheritDoc */
    public function select(string $field, string ...$fields)
    {
        if (empty($this->select)) {
            $this->select = ['SELECT'];
        }

        if ('*' === $field) {
            $this->select[] = '*';
            return $this;
        }

        $this->select[] = Arr::process(Arr::merge([$field], $fields))
            ->each(fn(string $field) => $this->prependField($field))
            ->toString();

        return $this;
    }

    /** @inheritDoc */
    public function from(Table $table)
    {
        $this->useTable($table);
        $this->from = ['FROM', $table->getName(), 'AS', $table->getAlias()];

        return $this;
    }

    /** @inheritDoc */
    public function where(?ClauseBuilder $clauseBuilder)
    {
        $this->clauseBuilder = $clauseBuilder->useTable($this->table);


        return $this;
    }

    /** @inheritDoc */
    public function leftJoin(Table $table, string $column, string $onColumn)
    {
        $join = [
            'LEFT JOIN',
            $table->getName(),
            'AS',
            $table->getAlias(),
            'ON',
            $this->prependField($column),
            '=',
            $this->prependField($onColumn, $table),
        ];

        if (!empty($this->join)) {
            $this->join = Arr::merge($this->join, $join);
        } else {
            // Build join
            $this->join = $join;
        }

        return $this;
    }

    /** @inheritDoc */
    public function rightJoin(Table $table, string $column, string $onColumn)
    {
        $join = [
            'RIGHT JOIN',
            $table->getName(),
            'AS',
            $table->getAlias(),
            'ON',
            $this->prependField($column),
            '=',
            $this->prependField($onColumn, $table),
        ];

        if (!empty($this->join)) {
            $this->join = Arr::merge($this->join, $join);
        } else {
            // Build join
            $this->join = $join;
        }

        return $this;
    }

    /** @inheritDoc */
    public function groupBy(string $column, string ...$columns)
    {
        foreach (Arr::merge([$column], $columns) as $columnToGroup) {
            // Build group by
            if (empty($this->groupBy)) {
                $this->groupBy = ['GROUP BY', $this->prependField($columnToGroup)];
            } else {
                $this->groupBy[] = ',';
                $this->groupBy[] = $this->prependField($columnToGroup);
            }
        }

        return $this;
    }

    /** @inheritDoc */
    public function sum(string $fieldToSum, ?string $alias = null)
    {
        $alias = $alias ?: $fieldToSum . '_sum';
        // Prepare select
        $select = ['SUM(' . $this->prependField($fieldToSum) . ')', 'as', $alias];

        // Add a comma to the end if it isn't the only field
        if (count($this->select) > 1) {
            array_unshift($select, ',');
        }

        if (empty($this->select)) {
            $this->select = ['SELECT'];
        }

        // Merge into select statement.
        $this->select = array_merge($this->select, $select);

        return $this;
    }

    /** @inheritDoc */
    public function count(string $fieldToCount, ?string $alias = null)
    {
        $alias = $alias ?: $fieldToCount . '_count';

        if($fieldToCount !== '*'){
            $fieldToCount = $this->prependField($fieldToCount);
        }

        // Prepare select
        $select = ['COUNT(' . $fieldToCount . ')', 'as', $alias];

        // Add a comma to the end if it isn't the only field
        if (count($this->select) > 1) {
            array_unshift($select, ',');
        }

        if (empty($this->select)) {
            $this->select = ['SELECT'];
        }

        // Merge into select
        $this->select = array_merge($this->select, $select);

        return $this;
    }

    /** @inheritDoc */
    public function limit(int $limit)
    {
        $this->limit = ['LIMIT', $limit];

        return $this;
    }

    /** @inheritDoc */
    public function offset(int $offset)
    {
        $this->offset = ['OFFSET', $offset];

        return $this;
    }

    /** @inheritDoc */
    public function orderBy(string $field, string $order)
    {
        // Ensure order is uppercase
        $order = strtoupper($order);

        // Ensure order is valid
        if (!in_array($order, ['ASC', 'DESC'])) {
            $order = 'ASC';
        }

        // Add order by
        $this->orderBy = ['ORDER BY', $this->prependField($field), $order];

        return $this;
    }

    /** @inheritDoc */
    public function build(): string
    {
        if (empty($this->select)) {
            $this->reset();
            throw new QueryBuilderException('Missing select field');
        }

        if (empty($this->from)) {
            $this->reset();
            throw new QueryBuilderException('Missing from field');
        }

        foreach ($this->operands as $operand) {
            if (!$this->isValidOperand($operand)) {
                $this->reset();
                throw new QueryBuilderException('Invalid operand' . $operand);
            }
        }

        $this->sql = Arr::merge($this->select, $this->from);
        $this->maybeAppend('join');

        // ClauseBuilder handles its own sanitization, so it's not double-processed.
        if ($this->clauseBuilder !== null) {
            $whereClause = $this->clauseBuilder->build();

            if (!empty($whereClause)) {
                $this->sql[] = 'WHERE ' . $whereClause;
            }
        }

        $this->maybeAppend('groupBy');
        $this->maybeAppend('orderBy');
        $this->maybeAppend('limit');
        $this->maybeAppend('offset');

        // Convert to string
        $sql = implode(' ', $this->sql);

        // If necessary, prepare the query
        if (!empty($this->prepare)) {
            $sql = $this->wpdb()->prepare($sql, ...$this->prepare);
        }

        $this->reset();

        return $sql;
    }

    /** @inheritDoc */
    public function reset()
    {
        $this->select = [];
        $this->clauseBuilder = null;
        $this->from = [];
        $this->sql = [];
        $this->preparedValues = [];
        $this->prepare = [];
        $this->raw = [];
        $this->items = [];
        $this->operands = [];
        $this->limit = [];
        $this->offset = [];
        $this->orderBy = [];
        $this->groupBy = [];

        return $this;
    }

    /** @inheritDoc */
    public function resetClauses(string $clause, string ...$clauses)
    {
        $clauses[] = $clause;

        foreach ($clauses as $clauseToReset) {
            if (isset($this->$clauseToReset)) {
                $this->$clauseToReset = [];
            }
        }

        return $this;
    }

    /**
     * Validates operands.
     *
     * @param string $operand The operand to check for
     * @return bool true if it exists, otherwise false.
     * @since 1.2.3
     *
     */
    private function isValidOperand($operand): bool
    {
        return in_array($operand, ['>', '<', '=', '<=', '>=', '!>', '!<', '!=', '!<=', '!>=', 'IN', 'NOT IN', 'LIKE']);
    }

    /**
     * Appends a query clause if it is set.
     *
     * @param string $key The query clause key
     *
     */
    private function maybeAppend(string $key)
    {
        if (isset($this->$key) && is_array($this->$key)) {
            foreach ($this->$key as $id => $value) {
                if (is_array($value)) {
                    $this->prepare[] = $value['value'];
                    $this->$key[$id] = $value['type'];
                }
            }

            $this->sql = array_merge($this->sql, array_values($this->$key));
        }
    }

    /**
     * Gets the WPDB object.
     * @return wpdb
     */
    private function wpdb(): wpdb
    {
        global $wpdb;

        return $wpdb;
    }
}
