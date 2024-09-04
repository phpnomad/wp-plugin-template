<?php

namespace PHPNomad\Utils\Processors;

use PHPNomad\Utils\Helpers\Arr;
use ReflectionException;

final class ArrayProcessor
{

    protected array $subject = [];
    private string $separator = ',';

    public function __construct(array $subject = [])
    {
        $this->subject = $subject;
    }

    public function pluck(string $key, $default = null)
    {
        $this->subject = Arr::pluck($this->subject, $key, $default);

        return $this;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return $this->subject;
    }

    /**
     * @return int
     */
    public function count(): int
    {
        return count($this->subject);
    }

    /**
     * Flips the array, setting the values as the keys and vice-versa.
     * Note if the subject is an array of anything but integers or strings this will fail.
     * If you aren't sure about the values, you may want to use cast(), first.
     *
     * @return $this
     */
    public function flip(): ArrayProcessor
    {
        $this->subject = Arr::flip($this->subject);

        return $this;
    }

    public function keys(): ArrayProcessor
    {
        $this->subject = array_keys($this->subject);

        return $this;
    }

    public function each(callable $callback): ArrayProcessor
    {
        $this->subject = Arr::each($this->subject, $callback);

        return $this;
    }

    public function after(int $position): ArrayProcessor
    {
        $this->subject = Arr::after($this->subject, $position);

        return $this;
    }

    public function before(int $position): ArrayProcessor
    {
        $this->subject = Arr::before($this->subject, $position);

        return $this;
    }

    /**
     * Removes the provided key.
     *
     * @param numeric $key
     *
     * @return $this
     */
    public function remove($key): ArrayProcessor
    {
        $this->subject = Arr::remove($this->subject, $key);

        return $this;
    }

    /**
     * Create an array of new instances given arguments to pass
     *
     * @param $instance string The instance to create
     *
     * @return $this
     */
    public function hydrate(string $instance): ArrayProcessor
    {
        $this->subject = Arr::hydrate($this->subject, $instance);

        return $this;
    }

    /**
     * Flattens arrays of arrays into a single array where the parent array is embedded as an item keyed by the $key param
     * Example:
     * [
     *   'group-1' => [['key' => 'value', 'another' => 'value'], ['key' => 'another-value', 'another' => 'value']],
     *   'group-2' => [['key' => 'value', 'another' => 'value'], ['key' => 'another-value', 'another' => 'value']],
     * ]
     *
     * Becomes:
     *
     * [
     *   ['group' => 'group-1', 'key' => 'value', 'another' => 'value'],
     *   ['group' => 'group-1', 'key' => 'another-value', 'another' => 'value'],
     *   ['group' => 'group-2', 'key' => 'value', 'another' => 'value'],
     *   ['group' => 'group-2', 'key' => 'another-value', 'another' => 'value']
     * ]
     *
     * @param string $groupKey The key to use for the group identifier.
     *
     */
    public function flatten(string $groupKey = 'group'): ArrayProcessor
    {
        $this->subject = Arr::flatten($this->subject, $groupKey);

        return $this;
    }

    public function group(string ...$groups): ArrayProcessor
    {
        $this->subject = Arr::group($this->subject, ...$groups);

        return $this;
    }

    public function toIndexed(string $key = 'key'): ArrayProcessor
    {
        $this->subject = Arr::toIndexed($this->subject, $key);

        return $this;
    }

    /**
     * Strips out duplicate items in the provided array.
     *
     * @return $this
     */
    public function unique(): ArrayProcessor
    {
        $this->subject = Arr::unique($this->subject);

        return $this;
    }

    /**
     * Merges the provided arrays with the array that is being processed.
     *
     * @param array ...$defaults
     *
     * @return $this
     */
    public function merge(array ...$defaults): ArrayProcessor
    {
        $this->subject = Arr::merge($this->subject, ...$defaults);

        return $this;
    }

    /**
     * Combines arrays into a single array, with each item overriding items from the previous array.
     *
     * @param array ...$items
     *
     * @return $this
     */
    public function replaceRecursive(array ...$items): ArrayProcessor
    {
        $this->subject = Arr::replaceRecursive($this->subject, ...$items);

        return $this;
    }

    /**
     * Combines arrays into a single array, with each item overriding items from the previous array.
     *
     * @param array ...$items
     *
     * @return $this
     */
    public function replace(array ...$items): ArrayProcessor
    {
        $this->subject = Arr::replace($this->subject, ...$items);

        return $this;
    }


    /**
     * Adds an item to the beginning of the array.
     *
     * @param mixed ...$items items to add.
     *
     * @return $this
     */
    public function prepend(...$items): ArrayProcessor
    {
        Arr::prepend($this->subject, ...$items);

        return $this;
    }

    /**
     * Adds items to the end of the array.
     *
     * @param mixed ...$items Items to add
     *
     * @return $this
     */
    public function append(...$items): ArrayProcessor
    {
        Arr::append($this->subject, ...$items);

        return $this;
    }

    /**
     * Recursively sorts, and optionally mutates an array of arrays.
     *
     * @type bool $convertClosures If true, closures will be converted to an identifiable string. Default true.
     * @type bool $recursive if true, this function will normalize recursively, manipulating sub-arrays.
     *
     * @throws ReflectionException
     */
    public function normalize($convertClosures = true, $recursive = true): ArrayProcessor
    {
        $this->subject = Arr::normalize($this->subject, $convertClosures, $recursive);

        return $this;
    }

    /**
     * Sorts an array by the keys.
     *
     * @return static
     */
    public function keySort(): ArrayProcessor
    {
        Arr::keySort($this->subject);

        return $this;
    }

    /**
     * Sorts the array.
     *
     * @param callable|int $method The method. Can be any supported flag documented in PHP's asort, or a sorting
     *                              callback.
     *
     * @return static
     */
    public function sort($method = SORT_REGULAR, string $direction = 'asc'): ArrayProcessor
    {
        Arr::sort($this->subject, $method, $direction);

        return $this;
    }

    /**
     * Applies the callback to the elements of the array being processed.
     *
     * @param callable $callback The callback.
     *
     * @return static
     */
    public function map(callable $callback): ArrayProcessor
    {
        $this->subject = Arr::map($this->subject, $callback);

        return $this;
    }

    /**
     * @param callable $callback
     * @param mixed $initial
     *
     * @return $this
     */
    public function reduce(callable $callback, $initial): ArrayProcessor
    {
        $this->subject = Arr::reduce($this->subject, $callback, $initial);

        return $this;
    }

    /**
     * Iterates over each value in the <b>array</b>
     * passing them to the <b>callback</b> function.
     * If the <b>callback</b> function returns true, the
     * current value from <b>array</b> is returned into
     * the result array.
     *
     * @param callable $callback
     *
     * @return static
     */
    public function filter(callable $callback): ArrayProcessor
    {
        $this->subject = Arr::filter($this->subject, $callback);

        return $this;
    }

    public function whereNotNull(): ArrayProcessor
    {
        $this->subject = Arr::whereNotNull($this->subject);

        return $this;
    }

    public function whereNotEmpty(): ArrayProcessor
    {
        $this->subject = Arr::whereNotEmpty($this->subject);

        return $this;
    }

    /**
     * Strips the keys from the array.
     *
     * @return static
     */
    public function values(): ArrayProcessor
    {
        $this->subject = Arr::values($this->subject);

        return $this;
    }

    /**
     * Cast all items in the array to the specified type.
     *
     * @param string $type
     *
     * @return static
     */
    public function cast(string $type): ArrayProcessor
    {
        $this->subject = Arr::cast($this->subject, $type);

        return $this;
    }

    /**
     * Filters the array to only contain values contained in all provided arrays.
     *
     * @param array ...$items
     *
     * @return static
     */
    public function intersect(array ...$items): ArrayProcessor
    {
        $this->subject = Arr::intersect($this->subject, ...$items);

        return $this;
    }

    /**
     * Filters the array to only contain values contained in all provided arrays.
     *
     * @param array ...$items
     *
     * @return static
     */
    public function intersectKeys(array ...$items): ArrayProcessor
    {
        $this->subject = Arr::intersectKeys($this->subject, ...$items);

        return $this;
    }

    /**
     * Filters the array to only contain values only contained in a single array.
     *
     * @param array ...$items
     *
     * @return static
     */
    public function diff(...$items): ArrayProcessor
    {
        $this->subject = Arr::diff($this->subject, ...$items);

        return $this;
    }

    /**
     * Reverses the order of the items in the array.
     *
     * @param bool $preserveKeys If set to true keys are preserved.
     *
     * @return static
     */
    public function reverse(bool $preserveKeys = true): ArrayProcessor
    {
        $this->subject = Arr::reverse($this->subject, $preserveKeys);

        return $this;
    }

    public function setSeparator(string $separator): ArrayProcessor
    {
        $this->separator = $separator;

        return $this;
    }

    /**
     * @return string
     */
    public function toString(): string
    {
        return implode($this->separator, $this->subject);
    }
}
