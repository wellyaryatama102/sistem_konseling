<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class LandingPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_access_landing_page()
    {
        $response = $this->get(route('landing'));
        $response->assertStatus(200);
        $response->assertSee('SIKS SMKN 2 GUGUAK');
        $response->assertSee('Pendampingan Perkembangan');
        $response->assertSee('Masuk ke Sistem');
    }

    public function test_authenticated_user_redirected_to_dashboard()
    {
        $this->seed();

        $admin = User::where('role', 'admin')->first();
        $response = $this->actingAs($admin)->get(route('landing'));
        $response->assertRedirect(route('admin.dashboard'));

        $guru = User::where('role', 'guru_bk')->first();
        $responseGuru = $this->actingAs($guru)->get(route('landing'));
        $responseGuru->assertRedirect(route('guru.dashboard'));

        $siswa = User::where('role', 'siswa')->first();
        $responseSiswa = $this->actingAs($siswa)->get(route('landing'));
        $responseSiswa->assertRedirect(route('siswa.dashboard'));
    }
}
