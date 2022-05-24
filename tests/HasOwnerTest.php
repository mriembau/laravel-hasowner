<?php

namespace Mriembau\LaravelHasOwner\Test;

class HasOwnerTest extends TestCase
{
    /** @test */
    public function it_uses_the_correct_fields()
    {
        $user = User::find(1);

        $this->actingAs($user);
        $this->assertAuthenticatedAs($user);

        foreach (Dummy::all() as $dummy) {
            $this->assertEquals($dummy->getOwnerForeignKey(), $this->app['config']->get('has-owner.user_foreign_key'));
            $this->assertEquals($dummy->getOwnerPrimaryKey(), $this->app['config']->get('has-owner.user_primary_key'));
        }

        foreach (DummyWithFields::all() as $dummy) {
            $this->assertNotEquals($dummy->getOwnerForeignKey(), $this->app['config']->get('has-owner.user_foreign_key'));
            $this->assertNotEquals($dummy->getOwnerPrimaryKey(), $this->app['config']->get('has-owner.user_primary_key'));
        }
    }

    /** @test */
    public function it_returns_only_user_owned_elements()
    {
        $user = User::find(1);

        $this->actingAs($user);
        $this->assertAuthenticatedAs($user);

        foreach (Dummy::all() as $dummy) {
            $this->assertEquals($dummy->user_id, $user->id);
        }
    }

    /** @test */
    public function it_dont_return_elements_owned_by_other_user()
    {
        $user = User::find(1);

        $this->actingAs($user);
        $this->assertAuthenticatedAs($user);

        $this->assertDatabaseHas(Dummy::class, ['id' => 5]);
        $dummy = Dummy::find(5);

        $this->assertNull($dummy);
    }

    /** @test */
    public function it_works_with_overrided_config()
    {
        $this->setDiferentColumnName();
        $this->overrideConfig();

        $user = User::find(1);

        $this->actingAs($user);
        $this->assertAuthenticatedAs($user);

        foreach (Dummy::all() as $dummy) {
            $this->assertEquals($dummy->test_id, $user->uuid);
            $this->assertNull($dummy->user_id);
        }
    }

    /** @test */
    public function it_works_with_overrided_config_in_model_fields()
    {
        $this->setDiferentColumnName();

        $user = User::find(1);

        $this->actingAs($user);
        $this->assertAuthenticatedAs($user);

        foreach (DummyWithFields::all() as $dummy) {
            $this->assertEquals($dummy->test_id, $user->uuid);
            $this->assertNull($dummy->user_id);
        }
    }

    /** @test */
    public function it_sets_correct_user_id_on_save()
    {
        $user = User::find(1);

        $this->actingAs($user);
        $this->assertAuthenticatedAs($user);

        Dummy::create([
            'name' => 'Test dummy'
        ]);

        $this->assertDatabaseHas(Dummy::class, ['user_id' => 1, 'name' => 'Test dummy']);

        $this->setDiferentColumnName();

        DummyWithFields::create([
            'name' => 'Test dummy'
        ]);

        $this->assertDatabaseHas(Dummy::class, ['test_id' => 1, 'name' => 'Test dummy']);
    }

    /** @test */
    public function it_sets_correct_user_id_on_save_if_set()
    {
        $user = User::find(1);

        $this->actingAs($user);
        $this->assertAuthenticatedAs($user);

        Dummy::create([
            'name' => 'Test dummy',
            'user_id' => 2
        ]);

        $this->assertDatabaseHas(Dummy::class, ['user_id' => 2, 'name' => 'Test dummy']);

        $this->setDiferentColumnName();

        DummyWithFields::create([
            'name' => 'Test dummy',
            'user_id' => 2
        ]);

        $this->assertDatabaseHas(Dummy::class, ['test_id' => 2, 'name' => 'Test dummy']);
    }

    /** @test */
    public function it_dont_change_user_id_when_updating()
    {
        $user = User::find(1);

        $this->actingAs($user);
        $this->assertAuthenticatedAs($user);

        $dummy = new Dummy();
        $dummy->name = 'Test dummy';
        $dummy->save();

        $this->assertDatabaseHas(Dummy::class, ['user_id' => 1, 'name' => 'Test dummy']);

        $user2 = User::find(2);

        $this->actingAs($user2);
        $this->assertAuthenticatedAs($user2);

        $dummy->name = 'Test dummy 2';
        $dummy->save();

        $this->assertDatabaseHas(Dummy::class, ['user_id' => 1, 'name' => 'Test dummy 2']);
        $this->assertDatabaseMissing(Dummy::class, ['user_id' => 2, 'name' => 'Test dummy 2']);
    }
}
