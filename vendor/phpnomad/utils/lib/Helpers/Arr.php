<?php

namespace PHPNomad\Utils\Helpers;

use Closure;
use PHPNomad\Core\Exceptions\ItemNotFound;
use PHPNomad\Utils\Processors\ArrayProcessor;

class Arr
{
    /**
     * Process this array
     *
     * @param mixed $subject
     *
     * @return ArrayProcessor
     */
    public static function process($subject): ArrayProcessor
    {
        return new ArrayProcessor(self::wrap($subject));
    }

    /**
     * Applies the callback to the elements of the given array.
     *
     * @param array $subject The array to apply the callback to.
     * @param callable $callback The callback.
     *
     * @return array
     */
    public static function map(array $subject, callable $callback): array
    {
        return array_map($callback, $subject);
    }

    /**
     * Applies the callback to the elements of the given array.
     *
     * @param array $subject
     * @param callable $callback
     * @param mixed $initial
     * @return mixed
     */
    public static function reduce(array $subject, callable $callback, $initial)
    {
        return array_reduce($subject, $callback, $initial);
    }

    /**
     * Filters out items that are not null.
     *
     * @param array $subject
     * @return array
     */
    public static function whereNotNull(array $subject): array
    {
        return self::filter($subject, fn($item) => $item !== null);
    }

    /**
     * Filters out items that are not null.
     *
     * @param array $subject
     * @return array
     */
    public static function whereNotEmpty(array $subject): array
    {
        return self::filter($subject, fn($item) => !empty($item));
    }

    /**
     * Maps values, retaining keys if the array is associative.
     *
     * @param array $subject
     * @param callable $callback
     *
     * @return array
     */
    public static function each(array $subject, callable $callback): array
    {
        if (Arr::isAssociative($subject)) {
            $result = [];
            foreach ($subject as $key => $value) {
                $result[$key] = $callback($value, $key);
            }
        } else {
            $result = Arr::map($subject, $callback);
        }

        return $result;
    }

    /**
     * Gets a value, if set. Falls back to default otherwise.
     *
     * @param array $subject
     * @param string $key
     * @param null $default
     * @return mixed
     */
    public static function get(array $subject, string $key, $default = null)
    {
        if (isset($subject[$key])) {
            return $subject[$key];
        }

        return $default;
    }

    /**
     * Retrieve items after the specified array position.
     *
     * @param array $subject the array
     * @param int $position The position to retrieve after
     *
     * @return array
     */
    public static function after(array $subject, int $position): array
    {
        return array_slice($subject, $position);
    }

    /**
     * Retrieve items before the specified array position.
     *
     * @param array $subject the array
     * @param int $position The position to retrieve before
     *
     * @return array
     */
    public static function before(array $subject, int $position): array
    {
        return Arr::diff(Arr::after($subject, $position), $subject);
    }

    /**
     * Iterates over each value in the <b>array</b>
     * passing them to the <b>callback</b> function.
     * If the <b>callback</b> function returns true, the
     * current value from <b>array</b> is returned into
     * the result array.
     *
     * @param array $subject The items to filter
     * @param callable $callback
     *
     * @return array
     */
    public static function filter(array $subject, callable $callback): array
    {
        return array_filter($subject, $callback, ARRAY_FILTER_USE_BOTH);
    }

    /**
     * Finds a value based on a callback. Returns default value when not found.
     *
     * @param array $subject
     * @param callable $callback
     * @param $default
     * @return mixed|null
     */
    public static function find(array $subject, callable $callback, $default = null)
    {
        foreach ($subject as $key => $item) {
            $found = $callback($item, $key);
            if ($found) {
                return $item;
            }
        }

        return $default;
    }

    public static function findKey(array $subject, callable $callback)
    {
        foreach ($subject as $key => $item) {
            $found = $callback($item, $key);
            if ($found) {
                return $key;
            }
        }

        return null;
    }

    /**
     * Returns the values of the array.
     *
     * @param array $subject
     *
     * @return array
     */
    public static function values(array $subject): array
    {
        return array_values($subject);
    }

    /**
     * Returns the keys of the array.
     *
     * @param array $subject
     *
     * @return array
     */
    public static function keys(array $subject): array
    {
        return array_keys($subject);
    }

    /**
     * Retrieves an item from a dot-based syntax, returning the default value if not set.
     *
     * @param array $subject
     * @param string $dot
     * @param null $default
     * @return mixed
     */
    public static function dot(array $subject, string $dot, $default = null)
    {
        foreach (explode('.', $dot) as $item) {
            if (!isset($subject[$item])) {
                return $default;
            } else {
                $subject = $subject[$item];
            }
        }

        return $subject;
    }

    /**
     * Determine if the given key exists in the provided array using dot notation.
     *
     * @param array $subject
     * @param string $dot
     * @return bool
     */
    public static function has(array $subject, string $dot): bool
    {
        // Split the dot notation into segments
        $segments = explode('.', $dot);
        $segment = array_shift($segments);

        if (isset($subject[$segment])) {
            // There's no more segments to search, return true.
            if (empty($segments)) {
                return true;
            }

            // Recurse.
            return self::has($subject[$segment], implode('.', $segments));
        }

        return false;
    }

    /**
     * Determines if all the specified set of values exist in the subject array.
     *
     * @param array $subject
     * @param $value
     * @param ...$values
     * @return bool
     */
    public static function hasValues(array $subject, $value, ...$values): bool
    {
        return !empty(Arr::intersect($subject, Arr::merge([$value], $values)));
    }

    /**
     * Removes the specified item from the array, if it exists.
     *
     * @param array $subject
     * @param array-key $key
     * @return array
     */
    public static function remove(array $subject, $key): array
    {
        if (isset($subject[$key])) {
            unset($subject[$key]);
        }

        return $subject;
    }

    /**
     * Force an item to be an array, even if it is not an array.
     *
     * @param $item mixed The item to force into an array
     *
     * @return array
     */
    public static function wrap($item)
    {
        if (!is_array($item)) {
            $item = [$item];
        }

        return $item;
    }

    /**
     * Create an array of new instances given arguments to pass
     *
     * @param $array    array The list of items to instantiate
     * @param $instance string The instance to create
     *
     * @return array
     */
    public static function hydrate(array $array, string $instance): array
    {
        $result = [];
        foreach ($array as $item) {
            $result[] = new $instance(...$item);
        }

        return $result;
    }

    /**
     * Flattens arrays of arrays into a single array where the parent array is embedded as an item keyed by the $key
     * param Example:
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
     * @param array $subject The array to flatten
     * @param string $groupKey The key to use for the group identifier.
     *
     */
    public static function flatten(array $subject, string $groupKey = 'group'): array
    {
        $result = [];
        foreach ($subject as $groupId => $items) {
            foreach ($items as $item) {
                $newItem = Arr::wrap($item);
                $newItem[$groupKey] = $groupId;
                $result[] = $newItem;
            }
        }

        return $result;
    }

    /**
     * Groups items in an array based on the group keys that all have matching values.
     *
     * @param array<object|array> $subject The array to group.
     * @param string ...$groupKeys The keys or property names to group by.
     * @return array An array of arrays, each containing items grouped by the specified keys.
     */
    public static function group(array $subject, string ...$groupKeys): array
    {
        $groups = [];
        $validTypesForConcatenation = ['string', 'integer', 'double', 'boolean', 'NULL'];

        foreach ($subject as $item) {
            $groupKeyParts = [];
            foreach ($groupKeys as $key) {
                if (is_object($item)) {
                    try {
                        $value = Obj::pluck($item, $key);
                    } catch (ItemNotFound $e) {
                        $value = null;
                    }
                } else{
                    $value = Arr::get($item, $key);
                }

                // Check if value is of a type that can be directly concatenated; otherwise, use hashing.
                if (!in_array(gettype($value), $validTypesForConcatenation, true)) {
                    try {
                        $value = Str::createHash($value);
                    } catch (\ReflectionException $e) {
                        $value = null;
                    }
                }

                $groupKeyParts[] = $value;
            }

            try {
                $groupKey = implode('|', Arr::normalize($groupKeyParts));
            } catch (\ReflectionException $e) {
                $groupKey = null;
            }

            if (!isset($groups[$groupKey])) {
                $groups[$groupKey] = [];
            }

            $groups[$groupKey][] = $item;
        }

        return array_values($groups);
    }

    /**
     * Updates the array to contain a key equal to the array's key value.
     *
     * @param array $subject
     * @param string $key
     * @param string $valueKey
     *
     * @return array
     */
    public static function toIndexed(array $subject, string $key = 'key', string $valueKey = 'value'): array
    {
        $result = [];

        foreach ($subject as $subjectKey => $value) {
            if (Arr::isAssociative(Arr::wrap($value))) {
                $result[] = Arr::merge([$key => $subjectKey], $value);
            } else {
                $result[] = Arr::merge([$key => $subjectKey], [$valueKey => $value]);
            }
        }

        return $result;
    }

    /**
     * Strips out duplicate items in the provided array.
     *
     * @param array $subject
     *
     * @return array
     */
    public static function unique(array $subject): array
    {
        return array_unique($subject);
    }

    /**
     * Sorts an array by the keys.
     *
     * @param array $subject
     *
     * @return void
     */
    public static function keySort(array &$subject): void
    {
        ksort($subject);
    }

    /**
     * Check if the specified arrays contain the same data regardless of order.
     *
     * @param array $input
     * @param array ...$inputs
     * @return bool
     */
    public static function containsSameData($input, ...$inputs): bool
    {
        $inputs = Arr::merge([$input], $inputs);
        $a = array_shift($inputs);
        $a = static::normalize($a);

        while (!empty($inputs)) {
            $b = array_shift($inputs);

            $b = static::normalize($b);

            if ($a !== $b) {
                return false;
            }
        }

        return true;
    }

    /**
     * Sorts an array.
     *
     * @param array $subject The item to sort
     * @param callable|int $method The method. Can be any supported flag documented in PHP's asort, or a sorting
     *                              callback.
     * @param string $direction - can be "asc", "desc", or "rand"
     *
     * @return void
     */
    public static function sort(array &$subject, $method = SORT_REGULAR, string $direction = 'asc'): void
    {
        if ($direction === 'asc') {
            is_callable($method) ? uasort($subject, $method) : asort($subject, $method);
        } else {
            is_callable($method) ? uasort($subject, $method) : arsort($subject, $method);
        }
    }

    /**
     * Merges arrays together.
     *
     * @param array ...$args
     *
     * @return array
     */
    public static function merge(array ...$args): array
    {
        return array_merge(...$args);
    }

    /**
     * Reverses the order of the items in the array.
     *
     * @param array $subject The input array.
     * @param bool $preserveKeys If set to true keys are preserved.
     *
     * @return array
     */
    public static function reverse(array $subject, bool $preserveKeys = true): array
    {
        return array_reverse($subject, $preserveKeys);
    }

    /**
     * Recursively plucks values from a set of items.
     *
     * @param object[]|array[] $items The list of items.
     * @param string $key The key that the value is set against.
     * @param mixed $default The default value to use when the value is not set.
     *
     * @return array Array of values plucked from the list.
     */
    public static function pluck(array $items, string $key, $default = null): array
    {
        $result = [];
        foreach ($items as $id => $item) {
            if (is_object($item)) {
                try {
                    $result[$id] = Obj::pluck($item, $key);
                } catch (ItemNotFound $e) {
                    $result[$id] = $default;
                    continue;
                }
            } elseif (Arr::isAssociative($item)) {
                $result[$id] = self::get($item, $key, $default);
            } elseif (is_array($item)) {
                $result[$id] = array_merge($result, self::pluck($item, $key, $default));
            } else {
                $result[$id] = $default;
            }
        }

        return $result;
    }

    /**
     * Cast all items in the array to the specified type.
     *
     * @param array $items
     * @param string $type
     *
     * @return array
     */
    public static function cast(array $items, string $type): array
    {
        $result = [];
        foreach ($items as $item) {
            settype($item, $type);
            $result[] = $item;
        }

        return $result;
    }

    /**
     * Returns true if this array is an associative array.
     *
     * @param array $items
     *
     * @return bool
     */
    public static function isAssociative(array $items): bool
    {
        foreach (array_keys($items) as $item) {
            if (is_string($item)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Adds items to the beginning of the array.
     *
     * @param array $array array that the item should be prepended to.
     * @param mixed ...$items Items to add
     *
     * @return void
     */
    public static function prepend(&$array, ...$items): void
    {
        $array = self::wrap($array);
        array_unshift($array, ...$items);
    }

    /**
     * Adds items to the end of the array.
     *
     * @param array $array array that the item should be prepended to.
     * @param mixed ...$items Items to add
     *
     * @return void
     */
    public static function append(&$array, ...$items): void
    {
        $array = self::wrap($array);
        foreach ($items as $item) {
            $array[] = $item;
        }
    }

    public static function flip($array): array
    {
        return array_flip($array);
    }

    /**
     * Recursively sorts, and optionally mutates an array of arrays.
     *
     * @param array $array The array to sort.
     *
     * @type bool $convertClosures If true, closures will be converted to an identifiable string. Default true.
     * @type bool $recursive if true, this function will normalize recursively, manipulating sub-arrays.
     *
     * @return array The normalized array
     * @throws \ReflectionException
     */
    public static function normalize(array $array, $convertClosures = true, $recursive = true): array
    {

        foreach ($array as $key => $value) {
            // Normalize recursively.
            if (is_array($value) && true === $recursive) {
                $args = func_get_args();
                $args[0] = $value;
                $array[$key] = self::normalize(...$args);
            }

            // If closures need converted, and this is a closure, transform this into an identifiable string.
            if (true === $convertClosures && $value instanceof Closure) {
                $array[$key] = ClosureAdapter::getClosureData($value);
            }
        }

        // Sorting behavior depends on if the array is associative, or not.
        if (self::isAssociative($array)) {
            ksort($array);
            asort($array);
        } else {
            sort($array);
        }

        return $array;
    }

    /**
     * Returns an array that contains the values contained in all arrays.
     *
     * @param array ...$items
     *
     * @return array
     */
    public static function intersect(array ...$items): array
    {
        return array_intersect(...self::map(func_get_args(), [Arr::class, 'wrap']));
    }

    /**
     * Returns an array that contains the values contained in all arrays.
     *
     * @param array ...$items
     *
     * @return array
     */
    public static function intersectKeys(array ...$items): array
    {
        return array_intersect_key(...self::map(func_get_args(), [Arr::class, 'wrap']));
    }

    /**
     * Returns an array that contains values only contained in a single array.
     *
     * @param array ...$items
     *
     * @return array
     */
    public static function diff(array ...$items): array
    {
        return array_diff(...self::map(func_get_args(), [Arr::class, 'wrap']));
    }

    /**
     * Combines arrays into a single array, with each item overriding items from the previous array.
     *
     * @param array ...$items
     *
     * @return array
     */
    public static function replaceRecursive(array ...$items): array
    {
        return array_replace_recursive($items);
    }

    /**
     * Combines arrays into a single array, with each item overriding items from the previous array.
     *
     * @param array ...$items
     *
     * @return array
     */
    public static function replace(array ...$items): array
    {
        return array_replace($items);
    }

    /**
     * Gets the first value from the array.
     *
     * @param array $items
     * @param null $default
     * @return mixed
     */
    public static function first(array $items, $default = null)
    {
        return Arr::get(array_values($items), 0, $default);
    }

    /**
     * Gets the first value from the array.
     *
     * @param array $items
     * @param null $default
     * @return mixed
     */
    public static function last(array $items, $default = null)
    {
        $items = array_values($items);

        return Arr::get($items, count($items) - 1, $default);
    }
}
