<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Kelas;
use App\Models\Siswa;
use Illuminate\Support\Facades\Hash;

class UserProfileModuleTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_access_profile_edit_page(): void
    {
        $this->seed();
        $user = User::where('role', 'admin')->first();

        $response = $this->actingAs($user)->get(route('profile.edit'));

        $response->assertStatus(200);
        $response->assertSee('Profil Saya');
        $response->assertSee($user->username);
    }

    public function test_user_can_update_profile_details(): void
    {
        $this->seed();
        $user = User::where('role', 'admin')->first();

        $response = $this->actingAs($user)->post(route('profile.update'), [
            'name' => 'Administrator Baru',
            'username' => 'admin_new',
            'email' => 'admin_new@smkn2guguak.sch.id',
            'nip' => '198510102010011001',
            'no_hp' => '081234567890',
            'alamat' => 'Jl. Soekarno-Hatta No. 1, Payakumbuh',
        ]);

        $response->assertStatus(302);
        
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Administrator Baru',
            'username' => 'admin_new',
            'email' => 'admin_new@smkn2guguak.sch.id',
        ]);

        $this->assertDatabaseHas('admin_profiles', [
            'user_id' => $user->id,
            'nip' => '198510102010011001',
            'no_hp' => '081234567890',
            'alamat' => 'Jl. Soekarno-Hatta No. 1, Payakumbuh',
        ]);
    }

    public function test_user_can_change_password(): void
    {
        $this->seed();
        $user = User::where('role', 'admin')->first();

        $response = $this->actingAs($user)->post(route('profile.password'), [
            'current_password' => 'password',
            'password' => 'adminnewpass',
            'password_confirmation' => 'adminnewpass',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHas('success', 'Password berhasil diubah.');

        // Re-fetch user and attempt to check password
        $user->refresh();
        $this->assertTrue(Hash::check('adminnewpass', $user->password));
    }

    public function test_siswa_can_update_profile_and_parents_info(): void
    {
        $this->seed();
        $siswaUser = User::where('role', 'siswa')->first();
        $kelas = Kelas::first();

        $response = $this->actingAs($siswaUser)->post(route('profile.update'), [
            'name' => 'Andi Saputra',
            'username' => 'siswa1',
            'email' => 'andi@smkn2guguak.sch.id',
            'nis' => '20261001',
            'nisn' => '0089123456',
            'jenis_kelamin' => 'L',
            'tempat_lahir' => 'Guguak',
            'tanggal_lahir' => '2008-05-12',
            'alamat' => 'Jl. Tan Bawa No. 12, Guguak',
            'no_wa_siswa' => '081234567891',
            'kelas_id' => $kelas->id,
            'status_siswa' => 'aktif',
            'nama_ayah' => 'Bambang Saputra',
            'nama_ibu' => 'Sari Saputri',
            'no_wa_ortu' => '081298765431',
            'alamat_ortu' => 'Jl. Tan Bawa No. 12, Guguak',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHas('success', 'Profil berhasil diperbarui.');

        $this->assertDatabaseHas('siswas', [
            'user_id' => $siswaUser->id,
            'nis' => '20261001',
            'nisn' => '0089123456',
            'nama_ayah' => 'Bambang Saputra',
            'nama_ibu' => 'Sari Saputri',
            'no_wa_ortu' => '081298765431',
            'alamat_ortu' => 'Jl. Tan Bawa No. 12, Guguak',
            'is_profile_complete' => 1,
        ]);
    }
}
