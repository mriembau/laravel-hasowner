<?php

namespace Mriembau\LaravelHasOwner\Test;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Mriembau\LaravelHasOwner\LaravelHasOwnerServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
    public function setUp(): void
    {
        parent::setUp();

        $this->setUpDatabase();
    }

    /**
     * @param \Illuminate\Foundation\Application $app
     *
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            LaravelHasOwnerServiceProvider::class
        ];
    }

    /**
     * @param \Illuminate\Foundation\Application $app
     */
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'sqlite');
        $app['config']->set('database.connections.sqlite', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);
    }

    protected function setUpDatabase()
    {
        $this->app['db']->connection()->getSchemaBuilder()->create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->rememberToken();
        });

        $this->app['db']->connection()->getSchemaBuilder()->create('dummies', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class, 'user_id')->nullable();
            $table->string('name');
        });

        collect(range(1,5))->each(function (int $i) {
            User::create([
                'name' => 'User '.$i,
                'email' => 'user'.$i.'@test.com',
                'password' => Hash::make('secret')
            ]);
        });

        collect(range(1, 20))->each(function (int $i) {
            Dummy::create([
                'name' => $i,
                'user_id' => floor($i/5)+1
            ]);
        });
    }

    protected function setDiferentColumnName()
    {
        $this->app['db']->connection()->getSchemaBuilder()->table('dummies', function (Blueprint $table) {
            $table->foreignIdFor(User::class, 'test_id')->nullable();
        });

        $this->app['db']->connection()->getSchemaBuilder()->table('users', function (Blueprint $table) {
            $table->integer('uuid')->nullable();
        });

        DB::table('users')->update(['uuid' => DB::raw('id')]);

        DB::table('dummies')->update(['test_id' => DB::raw('user_id')]);
        collect(range(1,20))->each(function (int $i) {
            DB::table('dummies')->where('id',$i)->update(['user_id' => null]);
        });
    }

    protected function overrideConfig()
    {
        $this->app['config']->set('has-owner.user_foreign_key', 'test_id');
        $this->app['config']->set('has-owner.user_primary_key', 'uuid');
    }
}
