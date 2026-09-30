<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

class UserTableTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_users_are_stored_in_owner_table(): void
    {
        $user = User::factory()->create();

        $this->assertSame('owner', $user->getTable());
        $this->assertModelExists($user);
    }
}
