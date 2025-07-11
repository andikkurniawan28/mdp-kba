<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Zona;
use App\Models\Parameter;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Models\KategoriParameter;
use Illuminate\Support\Facades\Auth;

class RoleController extends Controller
{
    public function index(Request $request)
    {
        if ($response = $this->checkIzin('akses_master_daftar_role')) {
            return $response;
        }

        if ($request->ajax()) {
            // Ambil langsung daftar akses lengkap dari helper
            $izinList = Role::semua_akses();

            $data = Role::query();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('aksi', function ($row) {
                    $editUrl = route('role.edit', $row->id);
                    $deleteUrl = route('role.destroy', $row->id);

                    return '
                    <a href="' . $editUrl . '" class="btn btn-sm btn-warning">Edit</a>
                    <form action="' . $deleteUrl . '" method="POST" class="d-inline" onsubmit="return confirm(\'Hapus data ini?\')">
                        ' . csrf_field() . method_field('DELETE') . '
                        <button class="btn btn-sm btn-danger">Hapus</button>
                    </form>';
                })
                ->addColumn('izin', function ($row) use ($izinList) {
                    $hasil = [];

                    foreach ($izinList as $key => $label) {
                        $aktif = $row->{$key} ?? false;
                        $hasil[] = ($aktif ? "✅" : "❌") . " $label";
                    }

                    return implode('<br>', $hasil);
                })
                ->rawColumns(['aksi', 'izin'])
                ->make(true);
        }

        return view('role.index');
    }

    public function create()
    {
        if ($response = $this->checkIzin('akses_master_tambah_role')) {
            return $response;
        }

        $semua_akses = Role::semua_akses();

        return view('role.create', compact('semua_akses'));
    }

    public function store(Request $request)
    {
        if ($response = $this->checkIzin('akses_master_tambah_role')) {
            return $response;
        }

        // Validasi nama role
        $validated = $request->validate([
            'nama' => 'required|string|max:255|unique:roles,nama',
        ]);

        // Daftar seluruh nama izin statis & dinamis
        $izinList = Role::semua_akses2();

        // Siapkan data yang akan disimpan
        foreach ($izinList as $izin) {
            $validated[$izin] = $request->has($izin);
        }

        // Simpan ke database
        Role::create($validated);

        return redirect()->route('role.index')->with('success', 'Role berhasil ditambahkan.');
    }


    public function edit(Role $role)
    {
        if ($response = $this->checkIzin('akses_master_edit_role')) {
            return $response;
        }

        $semua_akses = Role::semua_akses();

        return view('role.edit', compact('role', 'semua_akses'));
    }

    public function update(Request $request, Role $role)
    {
        if ($response = $this->checkIzin('akses_master_edit_role')) {
            return $response;
        }

        // Validasi nama (unik kecuali untuk dirinya sendiri)
        $validated = $request->validate([
            'nama' => 'required|string|max:255|unique:roles,nama,' . $role->id,
        ]);

        // Daftar izin statis & dinamis
        $izinList = Role::semua_akses2();

        // Simpan status tiap izin (true jika dicentang, false jika tidak)
        foreach ($izinList as $izin) {
            $validated[$izin] = $request->has($izin);
        }

        // Update data role
        $role->update($validated);

        return redirect()->route('role.index')->with('success', 'Role berhasil diperbarui.');
    }


    public function destroy(Role $role)
    {
        if ($response = $this->checkIzin('akses_master_hapus_role')) {
            return $response;
        }

        $role->delete();

        return redirect()->route('role.index')->with('success', 'Role berhasil dihapus.');
    }
}
