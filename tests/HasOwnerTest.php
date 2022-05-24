<?php

namespace Mriembau\LaravelHasOwner\Test;

use Illuminate\Support\Collection;

class HasOwnerTest extends TestCase
{
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
}
