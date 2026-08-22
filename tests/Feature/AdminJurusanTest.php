<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Jurusan;
use App\Models\TahunAjaran;

class AdminJurusanTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_access_jurusan_index()
    {
        $this->seed();

        $admin = User::where('role', 'admin')->first();

        $response = $this->actingAs($admin)->get(route('admin.jurusan.index'));
        $response->assertStatus(200);
        $response->assertSee('Manajemen Data Jurusan');
    }

    public function test_admin_can_create_and_update_jurusan()
    {
        $this->seed();

        $admin = User::where('role', 'admin')->first();

        // 1. Create Jurusan
        $response = $this->actingAs($admin)->post(route('admin.jurusan.store'), [
            'nama_jurusan' => 'Teknik Komputer dan Jaringan',
        ]);

        $response->assertRedirect(route('admin.jurusan.index'));
        $this->assertDatabaseHas('jurusan', [
            'nama_jurusan' => 'Teknik Komputer dan Jaringan',
        ]);

        $jurusan = Jurusan::where('nama_jurusan', 'Teknik Komputer dan Jaringan')->first();

        // 2. Edit Page
        $editRes = $this->actingAs($admin)->get(route('admin.jurusan.edit', $jurusan->id_jurusan));
        $editRes->assertStatus(200);

        // 3. Update Jurusan
        $updateRes = $this->actingAs($admin)->put(route('admin.jurusan.update', $jurusan->id_jurusan), [
            'nama_jurusan' => 'TKJ dan Animasi',
        ]);

        $updateRes->assertRedirect(route('admin.jurusan.index'));
        $this->assertDatabaseHas('jurusan', [
            'nama_jurusan' => 'TKJ dan Animasi',
        ]);

        // 4. Delete Jurusan
        $deleteRes = $this->actingAs($admin)->delete(route('admin.jurusan.destroy', $jurusan->id_jurusan));
        $deleteRes->assertRedirect(route('admin.jurusan.index'));
        $this->assertDatabaseMissing('jurusan', [
            'id_jurusan' => $jurusan->id_jurusan,
        ]);
    }

    public function test_admin_can_toggle_and_delete_tahun_ajaran()
    {
        $this->seed();

        $admin = User::where('role', 'admin')->first();

        $ta = TahunAjaran::create([
            'nama_tahun_ajaran' => '2029/2030',
            'status_aktif' => false,
        ]);

        // Toggle Status
        $res = $this->actingAs($admin)->patch(route('admin.tahun-ajaran.toggle-status', $ta->id_tahun_ajaran));
        $res->assertSessionHasNoErrors();
        $this->assertTrue((bool)$ta->fresh()->status_aktif);

        // Delete unused
        $del = $this->actingAs($admin)->delete(route('admin.tahun-ajaran.destroy', $ta->id_tahun_ajaran));
        $del->assertRedirect(route('admin.tahun-ajaran.index'));
        $this->assertDatabaseMissing('tahun_ajaran', [
            'id_tahun_ajaran' => $ta->id_tahun_ajaran,
        ]);
    }
}
