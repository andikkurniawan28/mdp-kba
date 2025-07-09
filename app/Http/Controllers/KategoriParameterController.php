<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Models\KategoriParameter;
use Illuminate\Support\Facades\Auth;

class KategoriParameterController extends Controller
{

    public function index(Request $request)
    {
        if ($response = $this->checkIzin('akses_master_daftar_kategori_parameter')) {
            return $response;
        }

        if ($request->ajax()) {
            $data = KategoriParameter::query();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('aksi', function ($row) {
                    $monitoringUrl = route('monitoring_per_kategori.index', $row->id);
                    $editUrl = route('kategori_parameter.edit', $row->id);
                    $deleteUrl = route('kategori_parameter.destroy', $row->id);

                    return '
                        <a target="_blank" href="' . $monitoringUrl . '" class="btn btn-sm btn-info">Monitoring</a>
                        <a href="' . $editUrl . '" class="btn btn-sm btn-warning">Edit</a>
                        <form action="' . $deleteUrl . '" method="POST" class="d-inline" onsubmit="return confirm(\'Hapus data ini?\')">
                            ' . csrf_field() . method_field('DELETE') . '
                            <button class="btn btn-sm btn-danger">Hapus</button>
                        </form>
                    ';
                })
                ->rawColumns(['aksi'])
                ->make(true);
        }

        return view('kategori_parameter.index');
    }

    public function create()
    {
        if ($response = $this->checkIzin('akses_master_tambah_kategori_parameter')) {
            return $response;
        }

        return view('kategori_parameter.create');
    }

    public function store(Request $request)
    {
        if ($response = $this->checkIzin('akses_master_tambah_kategori_parameter')) {
            return $response;
        }

        $validated = $request->validate([
            'nama' => 'required|string|max:255|unique:kategori_parameters,nama',
        ]);

        KategoriParameter::create($validated);

        return redirect()->route('kategori_parameter.index')->with('success', 'Kategori Parameter berhasil ditambahkan.');
    }

    public function edit(KategoriParameter $kategori_parameter)
    {
        if ($response = $this->checkIzin('akses_master_edit_kategori_parameter')) {
            return $response;
        }

        return view('kategori_parameter.edit', compact('kategori_parameter'));
    }

    public function update(Request $request, KategoriParameter $kategori_parameter)
    {
        if ($response = $this->checkIzin('akses_master_edit_kategori_parameter')) {
            return $response;
        }

        $validated = $request->validate([
            'nama' => 'required|string|max:255|unique:kategori_parameters,nama,' . $kategori_parameter->id,
        ]);

        $kategori_parameter->update($validated);

        return redirect()->route('kategori_parameter.index')->with('success', 'Kategori Parameter berhasil diperbarui.');
    }

    public function destroy(KategoriParameter $kategori_parameter)
    {
        if ($response = $this->checkIzin('akses_master_hapus_kategori_parameter')) {
            return $response;
        }

        $kategori_parameter->delete();

        return redirect()->route('kategori_parameter.index')->with('success', 'Kategori Parameter berhasil dihapus.');
    }
}
