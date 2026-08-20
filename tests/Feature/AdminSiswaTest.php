<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Siswa;

class AdminSiswaTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_siswa_index_with_delete_button(): void
    {
        $this->seed();
        $admin = User::where('role', 'admin')->first();
        $siswa = Siswa::first();

        $response = $this->actingAs($admin)->get(route('admin.siswa.index'));
        $response->assertStatus(200);
        $response->assertSee(route('admin.siswa.destroy', $siswa->id_siswa));
        $response->assertSee('Hapus');
    }

    public function test_admin_can_delete_siswa(): void
    {
        $this->seed();
        $admin = User::where('role', 'admin')->first();
        $siswa = Siswa::first();
        $siswaId = $siswa->id_siswa;
        $userId = $siswa->user_id;

        $response = $this->actingAs($admin)->delete(route('admin.siswa.destroy', $siswaId));

        $response->assertRedirect(route('admin.siswa.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseMissing('siswa', ['id_siswa' => $siswaId]);
        if ($userId) {
            $this->assertDatabaseMissing('users', ['id' => $userId]);
        }
    }

    public function test_non_admin_cannot_delete_siswa(): void
    {
        $this->seed();
        $siswaUser = User::where('role', 'siswa')->first();
        $targetSiswa = Siswa::where('user_id', '!=', $siswaUser->id)->first() ?? Siswa::first();

        $response = $this->actingAs($siswaUser)->delete(route('admin.siswa.destroy', $targetSiswa->id_siswa));
        $response->assertStatus(302); // Redirected due to role middleware

        $this->assertDatabaseHas('siswa', ['id_siswa' => $targetSiswa->id_siswa]);
    }
}
