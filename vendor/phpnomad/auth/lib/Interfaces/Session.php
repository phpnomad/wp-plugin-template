<?php

namespace PHPNomad\Auth\Interfaces;

use DateTimeImmutable;
use PHPNomad\Auth\Exceptions\SessionParamNotFound;

interface Session
{
    /**
     * Retrieves the moment this session was received.
     *
     * @return DateTimeImmutable
     */
    public function getStartTime(): DateTimeImmutable;

    /**
     * Gets the duration of the current request, in milliseconds.
     *
     * @return int
     */
    public function getDuration(): int;

    /**
     * @return string
     */
    public function getContext(): string;

    /**
     * Gets the action this session intends to make.
     *
     * @return Action
     */
    public function getIntendedAction(): Action;

    /**
     * Retrieves the value of the parameter with the given name.
     *
     * @param string $name The name of the parameter.
     * @return mixed The value of the parameter.
     * @throws SessionParamNotFound
     */
    public function getParam(string $name);

    /**
     * Sets a parameter with the given name and value.
     *
     * @param string $name The parameter name.
     * @param mixed $value The parameter value.
     * @return void
     */
    public function setParam(string $name, $value);

    /**
     * Retrieves the parameters associated with the current instance.
     *
     * @return array The parameters associated with the current instance.
     */
    public function getParams(): array;
}