<?php

namespace Mriembau\LaravelHasOwner\Observers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class HasOwnerObserver
{
    /**
     * Handle the Dog "saving" event.
     *
     * @param  Model  $model
     * @return void
     */
    public function creating(Model $model)
    {
        $userIdField = $model->getOwnerPrimaryKey();
        $foreignKey = $model->getOwnerForeignKey();

        if(!$model->$foreignKey) {
            $model->user_id = Auth::user()->$userIdField;
        }
    }

}
