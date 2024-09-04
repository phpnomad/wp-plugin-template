<?php

namespace PHPNomad\Loader\Traits;

trait CanConditionallyLoad
{
    /** @inheritDoc */
    public function load(): void
    {
        if($this->shouldLoad()) {
            $this->loadItem();
        }
    }

    /**
     * @return void
     */
    abstract protected function loadItem(): void;

    /**
     * @return bool
     */
    abstract public function shouldLoad(): bool;
}