<?php

namespace Mriembau\LaravelHasOwner\Traits;

use Mriembau\LaravelHasOwner\Observers\HasOwnerObserver;
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

        self::observe(HasOwnerObserver::class);
    }

    /**
     * Get the owner foreign key column name.
     *
     * @return string
     */
    public function getOwnerForeignKey()
    {
        if($this->ownerForeignKey) {
            return $this->ownerForeignKey;
        } else {
            return config('has-owner.user_foreign_key');
        }
    }

    /**
     * Get the owner primary key column name.
     *
     * @return string
     */
    public function getOwnerPrimaryKey()
    {
        if($this->ownerPrimaryKey) {
            return $this->ownerPrimaryKey;
        } else {
            return config('has-owner.user_primary_key');
        }
    }
}
