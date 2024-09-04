<?php

namespace PHPNomad\Database\Interfaces;

/**
 * Interface for ClauseBuilder
 *
 * Used to construct SQL query clauses with support for chaining and grouping conditions.
 */
interface ClauseBuilder
{

    /**
     * Specify which table should be used for statements.
     *
     * @param Table $table
     * @return $this
     */
    public function useTable(Table $table);

    /**
     * Initiates a WHERE clause for a database query.
     *
     * @param string|string[] $field The field to compare, or an array of fields to compare.
     * @param string $operator The comparison operator.
     * @param mixed ...$values The values to compare against. Supports multiple values for operators like "IN".
     *
     * @return $this For method chaining.
     */
    public function where($field, string $operator, ...$values);

    /**
     * Adds an "AND" condition to the query.
     *
     * @param string|string[] $field The field to apply the condition on, or an array of fields to compare.
     * @param string $operator The comparison operator to use for the condition.
     * @param mixed ...$values The values to compare against. Supports multiple values for operators like "IN".
     *
     * @return $this For method chaining.
     */
    public function andWhere($field, string $operator, ...$values);

    /**
     * Adds an "OR" condition to the query.
     *
     * @param string|string[] $field The field to compare, or an array of fields to compare.
     * @param string $operator The comparison operator, including but not limited to "=", "<", ">", etc.
     *                         Supports advanced operators like "IN", "NOT IN", "BETWEEN", "LIKE", and more.
     * @param mixed ...$values The value(s) to compare against. The number and type of values should be compatible with the operator.
     *
     * @return $this For method chaining.
     */
    public function orWhere($field, string $operator, ...$values);

    /**
     * Groups multiple conditions together using a logical operator.
     *
     * @param string $logic The logic operator to group the clauses ("AND" or "OR").
     * @param ClauseBuilder ...$clauses ClauseBuilder instances representing the conditions to group.
     *
     * @return $this For method chaining.
     */
    public function group(string $logic,  ClauseBuilder ...$clauses);

    /**
     * Groups multiple conditions together using a logical operator.
     *
     * @param string $logic The logic operator to group the clauses ("AND" or "OR").
     * @param ClauseBuilder ...$clauses ClauseBuilder instances representing the conditions to group.
     *
     * @return $this For method chaining.
     */
    public function andGroup(string $logic,  ClauseBuilder ...$clauses);

    /**
     * Groups multiple conditions together using a logical operator.
     *
     * @param string $logic The logic operator to group the clauses ("AND" or "OR").
     * @param ClauseBuilder ...$clauses ClauseBuilder instances representing the conditions to group.
     *
     * @return $this For method chaining.
     */
    public function orGroup(string $logic,  ClauseBuilder ...$clauses);

    /**
     * Compiles and returns the SQL query clause as a string, ensuring values are properly sanitized.
     *
     * @return string The SQL query clause.
     */
    public function build(): string;

    /**
     * Resets the instance.
     *
     * @return $this
     */
    public function reset();
}
