<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class FullCounselingSystemTest extends TestCase
{
    use RefreshDatabase;

    public function test_database_seeder_runs_successfully(): void
    {
        $this->seed();
        $this->assertDatabaseHas('users', ['username' => 'admin', 'role' => 'admin']);
        $this->assertDatabaseHas('users', ['username' => 'gurubk', 'role' => 'guru_bk']);
        $this->assertDatabaseHas('users', ['username' => 'siswa1', 'role' => 'siswa']);
    }

    public function test_login_redirects_to_correct_role_dashboard(): void
    {
        $this->seed();

        $response = $this->post('/login', [
            'username' => 'gurubk',
            'password' => 'password',
        ]);

        $response->assertRedirect(route('guru.dashboard'));
    }

    public function test_unauthorized_role_access_is_blocked(): void
    {
        $this->seed();
        $siswa = User::where('role', 'siswa')->first();

        $response = $this->actingAs($siswa)->get(route('admin.dashboard'));
        $response->assertRedirect(route('siswa.dashboard'));
    }

    public function test_siswa_cannot_see_confidential_fields(): void
    {
        $this->seed();
        $siswa = User::where('role', 'siswa')->first();

        $response = $this->actingAs($siswa)->get(route('siswa.konseling.index'));
        $response->assertStatus(200);
        $response->assertDontSee('catatan_rahasia');
        $response->assertDontSee('hasil_konseling');
    }
}
