<?php

namespace PHPNomad\Integrations\WordPress\Strategies;

use PHPNomad\Asset\Interfaces\AssetStrategy as AssetStrategyInterface;
use PHPNomad\Auth\Events\UserLoggedIn;
use PHPNomad\Auth\Events\UserLoggedOut;
use PHPNomad\Auth\Events\UserPermissionsInitialized;
use PHPNomad\Auth\Interfaces\CurrentContextResolverStrategy as CurrentContextResolverStrategyInterface;
use PHPNomad\Auth\Interfaces\CurrentUserResolverStrategy as CurrentUserResolverStrategyInterface;
use PHPNomad\Auth\Interfaces\HashStrategy as HashStrategyInterface;
use PHPNomad\Auth\Interfaces\LoginUrlProvider as LoginUrlProviderInterface;
use PHPNomad\Auth\Interfaces\PasswordResetStrategy as PasswordResetStrategyInterface;
use PHPNomad\Auth\Interfaces\SecretProvider as SecretProviderInterface;
use PHPNomad\Cache\Interfaces\CachePolicy as CoreCachePolicy;
use PHPNomad\Cache\Interfaces\CacheStrategy;
use PHPNomad\Cache\Interfaces\HasDefaultTtl;
use PHPNomad\Database\Events\RecordCreated;
use PHPNomad\Database\Events\RecordDeleted;
use PHPNomad\Database\Interfaces\CanConvertDatabaseStringToDateTime;
use PHPNomad\Database\Interfaces\CanConvertToDatabaseDateString;
use PHPNomad\Database\Interfaces\ClauseBuilder as CoreClauseBuilder;
use PHPNomad\Database\Interfaces\HasCharsetProvider;
use PHPNomad\Database\Interfaces\HasCollateProvider;
use PHPNomad\Database\Interfaces\HasGlobalDatabasePrefix;
use PHPNomad\Database\Interfaces\QueryBuilder as CoreQueryBuilder;
use PHPNomad\Database\Interfaces\QueryStrategy as CoreQueryStrategy;
use PHPNomad\Database\Interfaces\TableCreateStrategy as CoreTableCreateStrategyAlias;
use PHPNomad\Database\Interfaces\TableDeleteStrategy as CoreTableDeleteStrategyAlias;
use PHPNomad\Database\Interfaces\TableExistsStrategy as CoreTableExistsStrategyAlias;
use PHPNomad\Database\Interfaces\TableUpdateStrategy as CoreTableUpdateStrategy;
use PHPNomad\Di\Interfaces\CanSetContainer;
use PHPNomad\Di\Traits\HasSettableContainer;
use PHPNomad\Email\Interfaces\EmailStrategy as EmailStrategyInterface;
use PHPNomad\Events\Interfaces\ActionBindingStrategy as CoreActionBindingStrategy;
use PHPNomad\Events\Interfaces\EventStrategy as CoreEventStrategy;
use PHPNomad\Events\Interfaces\HasEventBindings;
use PHPNomad\Framework\Events\PostVisited;
use PHPNomad\Framework\Events\SiteVisited;
use PHPNomad\Framework\Interfaces\PageAuthorResolver;
use PHPNomad\Integrations\WordPress\Adapters\DatabaseDateAdapter;
use PHPNomad\Integrations\WordPress\Auth\User;
use PHPNomad\Integrations\WordPress\Bindings\PostVisitedBinding;
use PHPNomad\Integrations\WordPress\Bindings\SiteVisitedBinding;
use PHPNomad\Integrations\WordPress\Cache\CachePolicy;
use PHPNomad\Integrations\WordPress\Cache\ObjectCacheStrategy;
use PHPNomad\Integrations\WordPress\Database\ClauseBuilder;
use PHPNomad\Integrations\WordPress\Database\QueryBuilder;
use PHPNomad\Integrations\WordPress\Providers\DatabaseProvider;
use PHPNomad\Integrations\WordPress\Providers\DefaultCacheTtlProvider;
use PHPNomad\Integrations\WordPress\Providers\SecretProvider;
use PHPNomad\Integrations\WordPress\Rest\Response;
use PHPNomad\Loader\Interfaces\HasClassDefinitions;
use PHPNomad\Loader\Interfaces\HasLoadCondition;
use PHPNomad\Mutator\Interfaces\MutationStrategy as CoreMutationStrategy;
use PHPNomad\Privacy\Interfaces\TrackingPermissionStrategy as TrackingPermissionStrategyInterface;
use PHPNomad\Rest\Interfaces\FetchStrategy as FetchStrategyInterface;
use PHPNomad\Rest\Interfaces\Response as CoreResponse;
use PHPNomad\Rest\Interfaces\RestStrategy as CoreRestStrategy;
use PHPNomad\Tasks\Interfaces\CanScheduleTasks;
use PHPNomad\Template\Interfaces\CanRender;
use PHPNomad\Template\Interfaces\ScreenResolverStrategy;
use PHPNomad\Template\Strategies\PhpEngine;
use PHPNomad\Translations\Interfaces\TranslationStrategy as CoreTranslationStrategyAlias;
use WP_User;

class WordPressInitializer implements CanSetContainer, HasLoadCondition, HasClassDefinitions, HasEventBindings
{
    use HasSettableContainer;

    public const REQUIRED_WORDPRESS_VERSION = '6.3.0';

    /**
     * @return array
     */
    public function getClassDefinitions(): array
    {
        return [
            EventStrategy::class => CoreEventStrategy::class,
            Response::class => CoreResponse::class,
            MutationStrategy::class => CoreMutationStrategy::class,
            ActionBindingStrategy::class => CoreActionBindingStrategy::class,
            ObjectCacheStrategy::class => CacheStrategy::class,
            CachePolicy::class => CoreCachePolicy::class,
            QueryStrategy::class => CoreQueryStrategy::class,
            DefaultCacheTtlProvider::class => HasDefaultTtl::class,
            TableCreateStrategy::class => CoreTableCreateStrategyAlias::class,
            TableUpdateStrategy::class => CoreTableUpdateStrategy::class,
            TableDeleteStrategy::class => CoreTableDeleteStrategyAlias::class,
            TableExistsStrategy::class => CoreTableExistsStrategyAlias::class,
            TranslationStrategy::class => CoreTranslationStrategyAlias::class,
            QueryBuilder::class => CoreQueryBuilder::class,
            ClauseBuilder::class => CoreClauseBuilder::class,
            RestStrategy::class => CoreRestStrategy::class,
            CurrentContextResolverStrategy::class => CurrentContextResolverStrategyInterface::class,
            CurrentUserResolverStrategy::class => CurrentUserResolverStrategyInterface::class,
            PostAuthorResolver::class => PageAuthorResolver::class,
            DatabaseProvider::class => [HasDefaultTtl::class, HasGlobalDatabasePrefix::class, HasCollateProvider::class, HasCharsetProvider::class],
            DatabaseDateAdapter::class => [CanConvertToDatabaseDateString::class, CanConvertDatabaseStringToDateTime::class],
            AssetStrategy::class => AssetStrategyInterface::class,
            TrackingPermissionStrategy::class => TrackingPermissionStrategyInterface::class,
            PhpEngine::class => CanRender::class,
            AdminScreenResolver::class => ScreenResolverStrategy::class,
            HashStrategy::class => HashStrategyInterface::class,
            EmailStrategy::class => EmailStrategyInterface::class,
            SecretProvider::class => SecretProviderInterface::class,
            PasswordResetStrategy::class => PasswordResetStrategyInterface::class,
            LoginUrlProvider::class => LoginUrlProviderInterface::class,
            TaskScheduler::class => CanScheduleTasks::class,
            FetchStrategy::class => FetchStrategyInterface::class
        ];
    }

    /**
     * Determines if this site meets the minimum criteria for this plugin to function.
     *
     * @return bool
     */
    public function shouldLoad(): bool
    {
        global $wp_version;

        return isset($wp_version) && version_compare($wp_version, static::REQUIRED_WORDPRESS_VERSION, '>=');
    }

    public function getEventBindings(): array
    {
        return [
            RecordDeleted::class => [
                ['action' => 'deleted_user', 'transformer' => fn(int $id) => new RecordDeleted(WP_User::class, ['id' => $id])]
            ],
            RecordCreated::class => [
                ['action' => 'user_register', 'transformer' => fn(int $id) => new RecordCreated(new User(new WP_User($id)))]
            ],
            SiteVisited::class => [
                ['action' => 'wp_loaded', 'transformer' => fn() => $this->container->get(SiteVisitedBinding::class)()]
            ],
            PostVisited::class => [
                ['action' => 'wp', 'transformer' => fn() => $this->container->get(PostVisitedBinding::class)()]
            ],
            UserPermissionsInitialized::class => [
                ['action' => 'set_current_user', 'transformer' => fn() => new UserPermissionsInitialized(new User(wp_get_current_user()))]
            ],
            UserLoggedIn::class => [
                ['action' => 'wp_login', 'transformer' => fn(string $userLogin, WP_User $user) => new UserLoggedIn(new User($user))]
            ],
            UserLoggedOut::class => [
                ['action' => 'wp_logout', 'transformer' => fn($id) => new UserLoggedOut(new User(new WP_User($id)))]
            ]
        ];
    }
}
