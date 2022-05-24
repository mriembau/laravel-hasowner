<?php

namespace Mriembau\LaravelHasOwner\Test;

use Illuminate\Database\Eloquent\Model;
use Mriembau\LaravelHasOwner\Traits\HasOwner;

class DummyWithFields extends Model
{
    use HasOwner;

    protected $table = 'dummies';
    protected $guarded = [];
    public $timestamps = false;

    protected $ownerForeignKey = 'test_id';
    protected $ownerPrimarynKey = 'uuid';
}
