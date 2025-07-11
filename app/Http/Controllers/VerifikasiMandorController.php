<?php

namespace App\Http\Controllers;

use App\Models\Parameter;
use App\Models\Monitoring;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class VerifikasiMandorController extends Controller
{
    public function index(Request $request)
    {
        if ($response = $this->checkIzin('akses_daftar_input_monitoring')) {
            return $response;
        }

        if ($request->ajax()) {
            // Eager load hubungan
            $data       = Monitoring::with('titik_pengamatan')->where('diverifikasi', 0);
            $parameters = Parameter::with('satuan')->get();

            return DataTables::of($data)
                ->addIndexColumn()

                // Nama titik pengamatan
                ->addColumn(
                    'titik_pengamatan_nama',
                    fn($row) =>
                    $row->titik_pengamatan->nama ?? '-'
                )

                // Daftar parameter
                ->addColumn('parameter', function ($row) use ($parameters) {
                    $listItems = '';
                    foreach ($parameters as $parameter) {
                        $field = 'param' . $parameter->id;
                        $value = $row->{$field};

                        if (! is_null($value)) {
                            // Jika kualitatif, jangan tampilkan satuan
                            if ($parameter->jenis === 'kualitatif') {
                                $listItems .= '<li>'
                                    . e($parameter->simbol) . ': '
                                    . e($value)
                                    . '</li>';
                            } else {
                                // kuantitatif: tampilkan satuan
                                $satuan = $parameter->satuan->simbol ?? '';
                                $listItems .= '<li>'
                                    . e($parameter->simbol) . ': '
                                    . e($value) . ' '
                                    . e($satuan)
                                    . '</li>';
                            }
                        }
                    }
                    if ($listItems === '') {
                        return '-';
                    }
                    return '<ul class="mb-0 ps-3">' . $listItems . '</ul>';
                })

                ->addColumn('aksi', function ($row) {
                    return '<input type="checkbox" name="monitoring_ids[]" value="' . $row->id . '" class="form-check-input row-checkbox">';
                })

                ->rawColumns(['parameter', 'aksi'])
                ->make(true);
        }

        return view('monitoring.verifikasi_mandor');
    }

    public function proses(Request $request)
    {
        $ids = $request->input('monitoring_ids');

        if (!is_array($ids) || empty($ids)) {
            return redirect()->back()->with('error', 'Tidak ada data yang dipilih.');
        }

        // Update field diverifikasi menjadi 1
        Monitoring::whereIn('id', $ids)->update(['diverifikasi' => 1]);

        return redirect()->back()->with('success', 'Data berhasil diverifikasi.');
    }
}
