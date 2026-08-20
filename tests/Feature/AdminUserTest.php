<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class AdminUserTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_list_is_ordered_by_role_hierarchy(): void
    {
        $this->seed();

        $admin = User::where('role', 'admin')->first();

        $response = $this->actingAs($admin)->get(route('admin.users.index'));
        $response->assertStatus(200);

        // Verify users displayed match hierarchy
        $users = $response->viewData('users');
        $roles = $users->pluck('role')->toArray();

        // Check that first user is admin
        $this->assertEquals('admin', $roles[0]);

        // Verify search by name
        $searchRes = $this->actingAs($admin)->get(route('admin.users.index', ['search' => 'Admin']));
        $searchRes->assertStatus(200);
        $searchRes->assertSee('Administrator', false);
    }
}
