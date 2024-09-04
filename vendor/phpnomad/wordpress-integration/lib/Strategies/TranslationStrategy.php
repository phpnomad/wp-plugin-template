<?php

namespace PHPNomad\Integrations\WordPress\Strategies;

use PHPNomad\Translations\Interfaces\HasTextDomain;
use PHPNomad\Translations\Interfaces\TranslationStrategy as TranslationStrategyInterface;

//TODO: UPDATE CALLS TO TRANSLATIONSTRATEGY TO CALL TRANSLATIONSERVICE, AND UTILIZE THAT.
// CURRENTLY TRANSLATIONS WILL NOT WORK FOR USERS WHO ARE NOT LOGGED-IN BECAUSE THE LANGUAGE SETTING IS NOT BOUND
// IT'S ALSO NOT POSSIBLE FOR THIRD PARTY DEVELOPERS TO UTILIZE THIS TO TRANSLATE, WHICH WILL BE A PROBLEM SHOULD THIS
// EVER WORK IN OTHER CONTEXTS.
class TranslationStrategy implements TranslationStrategyInterface
{
    protected HasTextDomain $textDomainProvider;

    public function __construct(HasTextDomain $textDomainProvider)
    {
        $this->textDomainProvider = $textDomainProvider;
    }

    /** @inheritDoc */
    public function translate(string $translate, ?string $language = null, $context = null): string
    {
        if ($language !== null) {
            switch_to_locale($language);
        }

        $domain = $this->textDomainProvider->getTextDomain();
        $translated = $context === null ? __($translate, $domain) : _x($translate, $domain);

        if ($language !== null) {
            restore_previous_locale();
        }

        return $translated;
    }
}