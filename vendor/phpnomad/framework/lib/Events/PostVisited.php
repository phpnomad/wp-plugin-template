<?php

namespace PHPNomad\Framework\Events;

use PHPNomad\Events\Interfaces\Event;
use PHPNomad\Framework\Interfaces\MayHaveAssociatedInteractorId;
use PHPNomad\Framework\Traits\WithAssociatedInteractorId;

class PostVisited implements Event
{
    protected int $postId;

    public function __construct(int $postId)
    {
        $this->postId = $postId;
    }

    public function getPostId(): int
    {
        return $this->postId;
    }

    public static function getId(): string
    {
        return 'post_visited';
    }
}