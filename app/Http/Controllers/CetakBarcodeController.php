<?php

namespace App\Http\Controllers;

use App\Models\Zona;
use App\Models\Monitoring;
use App\Models\TitikPengamatan;
use Illuminate\Http\Request;

class CetakBarcodeController extends Controller
{
    public function index($zona_id)
    {
        if ($response = $this->checkIzin('akses_cetak_barcode')) {
            return $response;
        }

        $zona = Zona::select(['id', 'kode', 'nama'])->whereId($zona_id)->get()->last();

        $titik_pengamatans = TitikPengamatan::select(['id', 'kode', 'nama'])->where('zona_id', $zona_id)->where('aktif', 1)->get();

        return view('cetak_barcode.index', compact('zona', 'titik_pengamatans'));
    }

    public function proses(Request $request)
    {
        if ($response = $this->checkIzin('akses_cetak_barcode')) {
            return $response;
        }

        $jam = date('H:i');

        $jamInt = (int)substr($jam, 0, 2);

        // Tentukan periode
        $periode = ($jamInt >= 6 && $jamInt <= 23)
            ? date('Y-m-d')
            : date('Y-m-d', strtotime('-1 day'));

        $data = Monitoring::updateOrCreate(
            [
                'jam' => $jam,
                'periode' => $periode,
                'titik_pengamatan_id' => $request->titik_pengamatan_id,
            ],
            [] // Tidak ada field lain yang di-update (bisa kamu isi kalau mau update kolom lain)
        );

        return view('cetak_barcode.proses', compact('data'));
    }
}
