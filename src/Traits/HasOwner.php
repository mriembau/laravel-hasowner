<?php

namespace Mriembau\LaravelHasOwner\Traits;

use Mriembau\LaravelHasOwner\Scopes\HasOwnerScope;

trait HasOwner {
    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::addGlobalScope(new HasOwnerScope());
    }

    /**
     * Get the owner foreign key column name.
     *
     * @return string
     */
    public function getOwnerForeignKey()
    {
        return $this->ownerForeignKey;
    }

    /**
     * Get the owner primary key column name.
     *
     * @return string
     */
    public function getOwnerPrimaryKey()
    {
        return $this->ownerPrimaryKey;
    }
}
