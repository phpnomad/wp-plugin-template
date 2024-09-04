<?php

namespace PHPNomad\Integrations\WordPress\Strategies;

use PHPNomad\Auth\Interfaces\User;
use PHPNomad\Framework\Interfaces\PageAuthorResolver;
use PHPNomad\Integrations\WordPress\Auth\User as WordPressUser;
use WP_User;

class PostAuthorResolver implements PageAuthorResolver
{
    public function getAuthor($pageId): ?User
    {
        $author = get_post_field('post_author', $pageId);

        if(!$author){
            return null;
        }

        return new WordPressUser(new WP_User($author));
    }
}