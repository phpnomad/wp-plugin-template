<?php

namespace PHPNomad\Translations\Interfaces;

interface TranslationStrategy
{
    /**
     * Translates a string
     *
     * @param string $translate
     * @param string|null $language
     * @param string|null $context
     * @return string
     */
    public function translate(string $translate, ?string $language = null, string $context = null): string;
}