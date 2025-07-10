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
            $parameters = Parameter::select(['id', 'nama', 'simbol'])->get();
            $kategori_parameters = KategoriParameter::select(['id', 'nama'])->get();
            $zonas = Zona::select(['id', 'nama'])->get();
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
                    </form>
                ';
                })
                ->addColumn('izin', function ($row) use ($parameters, $kategori_parameters, $zonas) {
                    $izinList = [
                        'akses_master_daftar_kategori_parameter' => 'Daftar Role',
                        'akses_master_tambah_kategori_parameter' => 'Tambah Role',
                        'akses_master_edit_kategori_parameter' => 'Edit Role',
                        'akses_master_hapus_kategori_parameter' => 'Hapus Role',
                        'akses_master_daftar_satuan' => 'Daftar Satuan',
                        'akses_master_tambah_satuan' => 'Tambah Satuan',
                        'akses_master_edit_satuan' => 'Edit Satuan',
                        'akses_master_hapus_satuan' => 'Hapus Satuan',
                        'akses_master_daftar_jenis_pilihan_kualitatif' => 'Daftar Jenis Pilihan Kualitatif',
                        'akses_master_tambah_jenis_pilihan_kualitatif' => 'Tambah Jenis Pilihan Kualitatif',
                        'akses_master_edit_jenis_pilihan_kualitatif' => 'Edit Jenis Pilihan Kualitatif',
                        'akses_master_hapus_jenis_pilihan_kualitatif' => 'Hapus Jenis Pilihan Kualitatif',
                        'akses_master_daftar_parameter' => 'Daftar Parameter',
                        'akses_master_tambah_parameter' => 'Tambah Parameter',
                        'akses_master_edit_parameter' => 'Edit Parameter',
                        'akses_master_hapus_parameter' => 'Hapus Parameter',
                        'akses_master_daftar_zona' => 'Daftar Zona',
                        'akses_master_tambah_zona' => 'Tambah Zona',
                        'akses_master_edit_zona' => 'Edit Zona',
                        'akses_master_hapus_zona' => 'Hapus Zona',
                        'akses_master_daftar_titik_pengamatan' => 'Daftar Titik Pengamatan',
                        'akses_master_tambah_titik_pengamatan' => 'Tambah Titik Pengamatan',
                        'akses_master_edit_titik_pengamatan' => 'Edit Titik Pengamatan',
                        'akses_master_hapus_titik_pengamatan' => 'Hapus Titik Pengamatan',
                        'akses_master_daftar_role' => 'Daftar Role',
                        'akses_master_tambah_role' => 'Tambah Role',
                        'akses_master_edit_role' => 'Edit Role',
                        'akses_master_hapus_role' => 'Hapus Role',
                        'akses_master_daftar_user' => 'Daftar User',
                        'akses_master_tambah_user' => 'Tambah User',
                        'akses_master_edit_user' => 'Edit User',
                        'akses_master_hapus_user' => 'Hapus User',
                        'akses_daftar_input_monitoring' => 'Daftar Input Monitoring',
                        'akses_tambah_input_monitoring' => 'Tambah Input Monitoring',
                        'akses_edit_input_monitoring' => 'Edit Input Monitoring',
                        'akses_hapus_input_monitoring' => 'Hapus Input Monitoring',
                        'akses_cetak_barcode' => 'Cetak Barcode',
                        'akses_laporan_shift' => 'Laporan Shift',
                    ];

                    $hasil = [];

                    // Izin statis
                    foreach ($izinList as $key => $label) {
                        $hasil[] = ($row->{$key} ?? false) ? "✅ $label" : "❌ $label";
                    }

                    // Izin dinamis berdasarkan parameter
                    foreach ($parameters as $parameter) {
                        $key = 'akses_input_param' . $parameter->id;
                        $label = "Input ({$parameter->simbol}| {$parameter->nama})";
                        $hasil[] = ($row->{$key} ?? false) ? "✅ $label" : "❌ $label";
                    }

                    // Izin dinamis berdasarkan kategori parameter
                    foreach ($kategori_parameters as $kategori) {
                        $key = 'akses_monitoring_kategori' . Str::slug($kategori->id, '_');
                        $label = "Monitoring Kategori ({$kategori->nama})";
                        $hasil[] = ($row->{$key} ?? false) ? "✅ $label" : "❌ $label";
                    }

                    // Izin dinamis berdasarkan zona
                    foreach ($zonas as $zona) {
                        $key = 'akses_monitoring_zona' . Str::slug($zona->id, '_');
                        $label = "Monitoring Zona ({$zona->nama})";
                        $hasil[] = ($row->{$key} ?? false) ? "✅ $label" : "❌ $label";
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

        $parameters = Parameter::select(['id', 'nama', 'simbol'])->get();
        $kategori_parameters = KategoriParameter::select(['id', 'nama'])->get();
        $zonas = Zona::select(['id', 'nama'])->get();

        return view('role.create', compact(
            'parameters',
            'kategori_parameters',
            'zonas',
        ));
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

        // Daftar seluruh nama izin statis
        $izinList = [
            'akses_master_daftar_kategori_parameter',
            'akses_master_tambah_kategori_parameter',
            'akses_master_edit_kategori_parameter',
            'akses_master_hapus_kategori_parameter',
            'akses_master_daftar_satuan',
            'akses_master_tambah_satuan',
            'akses_master_edit_satuan',
            'akses_master_hapus_satuan',
            'akses_master_daftar_jenis_pilihan_kualitatif',
            'akses_master_tambah_jenis_pilihan_kualitatif',
            'akses_master_edit_jenis_pilihan_kualitatif',
            'akses_master_hapus_jenis_pilihan_kualitatif',
            'akses_master_daftar_parameter',
            'akses_master_tambah_parameter',
            'akses_master_edit_parameter',
            'akses_master_hapus_parameter',
            'akses_master_daftar_zona',
            'akses_master_tambah_zona',
            'akses_master_edit_zona',
            'akses_master_hapus_zona',
            'akses_master_daftar_titik_pengamatan',
            'akses_master_tambah_titik_pengamatan',
            'akses_master_edit_titik_pengamatan',
            'akses_master_hapus_titik_pengamatan',
            'akses_master_daftar_role',
            'akses_master_tambah_role',
            'akses_master_edit_role',
            'akses_master_hapus_role',
            'akses_master_daftar_user',
            'akses_master_tambah_user',
            'akses_master_edit_user',
            'akses_master_hapus_user',
            'akses_daftar_input_monitoring',
            'akses_tambah_input_monitoring',
            'akses_edit_input_monitoring',
            'akses_hapus_input_monitoring',
            'akses_cetak_barcode',
            'akses_laporan_shift',
        ];

        // Tambahkan parameter dinamis
        $parameters = Parameter::select(['id', 'nama', 'simbol'])->get();
        foreach ($parameters as $parameter) {
            $izinList[] = 'akses_input_param' . $parameter->id;
        }

        // Tambahkan kategori parameter dinamis
        $kategori_parameters = KategoriParameter::select(['id', 'nama'])->get();
        foreach ($kategori_parameters as $kategori) {
            $slug = Str::slug($kategori->id, '_');
            $izinList[] = 'akses_monitoring_kategori' . $slug;
        }

        // Tambahkan zona dinamis
        $zonas = Zona::select(['id', 'nama'])->get();
        foreach ($zonas as $zona) {
            $slug = Str::slug($zona->id, '_');
            $izinList[] = 'akses_monitoring_zona' . $slug;
        }

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

        $parameters = Parameter::select(['id', 'nama', 'simbol'])->get();
        $kategori_parameters = KategoriParameter::select(['id', 'nama'])->get();
        $zonas = Zona::select(['id', 'nama'])->get();

        return view('role.edit', compact('role', 'parameters', 'kategori_parameters', 'zonas'));
    }

    public function update(Request $request, Role $role)
    {
        // return $request;

        if ($response = $this->checkIzin('akses_master_edit_role')) {
            return $response;
        }

        // Validasi nama (unik kecuali untuk dirinya sendiri)
        $validated = $request->validate([
            'nama' => 'required|string|max:255|unique:roles,nama,' . $role->id,
        ]);

        // Daftar izin statis
        $izinList = [
            'akses_master_daftar_kategori_parameter',
            'akses_master_tambah_kategori_parameter',
            'akses_master_edit_kategori_parameter',
            'akses_master_hapus_kategori_parameter',
            'akses_master_daftar_satuan',
            'akses_master_tambah_satuan',
            'akses_master_edit_satuan',
            'akses_master_hapus_satuan',
            'akses_master_daftar_jenis_pilihan_kualitatif',
            'akses_master_tambah_jenis_pilihan_kualitatif',
            'akses_master_edit_jenis_pilihan_kualitatif',
            'akses_master_hapus_jenis_pilihan_kualitatif',
            'akses_master_daftar_parameter',
            'akses_master_tambah_parameter',
            'akses_master_edit_parameter',
            'akses_master_hapus_parameter',
            'akses_master_daftar_zona',
            'akses_master_tambah_zona',
            'akses_master_edit_zona',
            'akses_master_hapus_zona',
            'akses_master_daftar_titik_pengamatan',
            'akses_master_tambah_titik_pengamatan',
            'akses_master_edit_titik_pengamatan',
            'akses_master_hapus_titik_pengamatan',
            'akses_master_daftar_role',
            'akses_master_tambah_role',
            'akses_master_edit_role',
            'akses_master_hapus_role',
            'akses_master_daftar_user',
            'akses_master_tambah_user',
            'akses_master_edit_user',
            'akses_master_hapus_user',
            'akses_daftar_input_monitoring',
            'akses_tambah_input_monitoring',
            'akses_edit_input_monitoring',
            'akses_hapus_input_monitoring',
            'akses_cetak_barcode',
            'akses_laporan_shift',
        ];

        // Tambahkan izin dinamis berdasarkan parameter
        $parameters = Parameter::select(['id', 'nama', 'simbol'])->get();
        foreach ($parameters as $parameter) {
            $izinList[] = 'akses_input_param' . $parameter->id;
        }

        // Tambahkan izin dinamis berdasarkan kategori parameter
        $kategori_parameters = KategoriParameter::select(['id', 'nama'])->get();
        foreach ($kategori_parameters as $kategori) {
            $slug = Str::slug($kategori->id, '_');
            $izinList[] = 'akses_monitoring_kategori' . $slug;
        }

        // Tambahkan izin dinamis berdasarkan zona
        $zonas = Zona::select(['id', 'nama'])->get();
        foreach ($zonas as $zona) {
            $slug = Str::slug($zona->id, '_');
            $izinList[] = 'akses_monitoring_zona' . $slug;
        }

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
