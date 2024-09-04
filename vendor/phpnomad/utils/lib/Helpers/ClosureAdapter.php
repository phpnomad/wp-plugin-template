<?php

namespace PHPNomad\Utils\Helpers;

use Closure;
use ReflectionException;
use ReflectionFunction;
use SplFileObject;

class ClosureAdapter
{
    /**
     * Converts a closure to something that can be safely converted to a string.
     *
     * @param Closure $data
     *
     * @return array
     * @throws ReflectionException
     */
    public static function getClosureData(Closure $data): array
    {
        $ref  = new ReflectionFunction($data);
        $file = new SplFileObject($ref->getFileName());
        $file->seek($ref->getStartLine() - 1);
        $content = '';
        while ($file->key() < $ref->getEndLine()) {
            $content .= $file->current();
            $file->next();
        }

        return array(
            $content,
            $ref->getStaticVariables(),
        );
    }
}
