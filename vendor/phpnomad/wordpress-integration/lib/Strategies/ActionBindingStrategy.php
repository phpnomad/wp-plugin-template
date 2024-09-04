<?php

namespace PHPNomad\Integrations\WordPress\Strategies;

use PHPNomad\Core\Facades\Event;
use PHPNomad\Events\Interfaces\ActionBindingStrategy as ActionBindingStrategyInterface;
use ReflectionException;
use ReflectionFunction;

class ActionBindingStrategy implements ActionBindingStrategyInterface
{
    /** @inheritDoc */
    public function bindAction(string $eventClass, string $actionToBind, ?callable $transformer = null)
    {
        try {
            $numArgs = $transformer ? (new ReflectionFunction($transformer))->getNumberOfRequiredParameters() : 1;
        } catch (ReflectionException $e) {
            $numArgs = 1;
        }

        add_action($actionToBind, function (...$args) use ($transformer, $eventClass) {
            $eventInstance = $transformer ? $transformer(...$args) : new $eventClass(...$args);

            if ($eventInstance) {
                Event::broadcast($eventInstance);
            }
        }, 0, $numArgs);
    }
}