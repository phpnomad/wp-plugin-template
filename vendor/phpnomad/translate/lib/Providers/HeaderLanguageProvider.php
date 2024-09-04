<?php

namespace Siren\Translations\Service\Providers;

use PHPNomad\Integrations\WordPress\Interfaces\HasLanguage;

class HeaderLanguageProvider implements HasLanguage
{
    /**
     * Attempts to get the language from the header.
     *
     * @return ?string
     */
    public function getLanguage(): ?string
    {
        if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
            $languages = explode(',', $_SERVER['HTTP_ACCEPT_LANGUAGE']);

            if (!empty($languages[0])) {
                return strtolower(substr($languages[0], 0, 2));
            }
        }

        return null;
    }
}