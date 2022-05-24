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

        $userIdField = $model->getOwnerPrimaryKey();
        $foreignKey = $model->getOwnerForeignKey();

        $builder->where($foreignKey, $user->$userIdField);
    }
}
