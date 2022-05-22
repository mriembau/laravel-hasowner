<?php

namespace Mriembau\LaravelHasOwner\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

use Illuminate\Support\Facades\Auth;

class HasOwnerScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $builder
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return void
     */
    public function apply(Builder $builder, Model $model)
    {
        $user = Auth::user();

        if($model->getOwnerPrimaryKey()) {
            $userIdField = $model->getOwnerPrimaryKey();
        } else {
            $userIdField = config('has-owner.user_primary_key');
        }

        if($model->getOwnerForeignKey()) {
            $foreignKey = $model->getOwnerForeignKey();
        } else {
            $foreignKey = config('has-owner.user_foreign_key');
        }

        $builder->where($foreignKey, $user->$userIdField);
    }
}
