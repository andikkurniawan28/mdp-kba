<?php

namespace App\Http\Controllers;

use App\Models\Zona;
use App\Models\Parameter;
use App\Models\Monitoring;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LaporanShiftController extends Controller
{
    public function index()
    {
        if ($response = $this->checkIzin('akses_laporan_shift')) {
            return $response;
        }

        return view('laporan_shift.index');
    }

    public function proses($tanggal, $shift)
    {
        if ($shift == 3) {
            $range1 = ['21:00:00', '23:59:59'];
            $range2 = ['00:00:00', '04:59:59'];
        } elseif ($shift == 0) {
            // Harian: 06:00:00 hari ini s/d 05:59:59 hari berikutnya
            $range1 = ['06:00:00', '23:59:59'];
            $range2 = ['00:00:00', '05:59:59'];
        } else {
            $jamRanges = [
                1 => ['05:00:00', '12:59:59'],
                2 => ['13:00:00', '20:59:59'],
            ];
            [$start, $end] = $jamRanges[$shift];
        }

        $zonas = Zona::with(['titik_pengamatans.parameter_titik_pengamatans.parameter'])->get();

        $result = [];

        foreach ($zonas as $zona) {
            $zonaData = [
                'zona' => $zona->nama,
                'titik_pengamatans' => []
            ];

            // foreach ($zona->titik_pengamatans as $titik) {
            foreach ($zona->titik_pengamatans->where('aktif', 1) as $titik) {
                // Ambil parameter yang relevan
                $parameters = $titik->parameter_titik_pengamatans->pluck('parameter')->unique('id');

                // Bangun nama kolom yang akan diambil
                $selectedFields = ['id', 'titik_pengamatan_id', 'jam'];
                foreach ($parameters as $param) {
                    $selectedFields[] = 'param' . $param->id;
                }

                // Ambil data monitorings
                $query = DB::table('monitorings')
                    ->select($selectedFields)
                    ->where('titik_pengamatan_id', $titik->id)
                    ->where('diverifikasi', 1)
                    ->where('periode', $tanggal);

                if ($shift == 3 || $shift == 0) {
                    $query->where(function ($q) use ($range1, $range2) {
                        $q->whereBetween('jam', $range1)
                            ->orWhereBetween('jam', $range2);
                    });
                } else {
                    $query->whereBetween('jam', [$start, $end]);
                }

                $monitorings = $query->get();

                // Hitung agregasi per parameter
                $hasil = [];
                foreach ($parameters as $parameter) {
                    $field = 'param' . $parameter->id;
                    $agregasi = $parameter->metode_agregasi;

                    $values = $monitorings->pluck($field)->filter(fn($v) => $v !== null);

                    if ($agregasi === 'sum') {
                        $nilai = $values->sum();
                    } elseif ($agregasi === 'avg') {
                        $nilai = $values->avg();
                    } elseif ($agregasi === 'count') {
                        $nilai = $values->count();
                    } else {
                        $nilai = null;
                    }

                    $hasil[] = [
                        'parameter_id' => $parameter->id,
                        'simbol' => $parameter->simbol,
                        'nilai' => $nilai,
                        'agregasi' => $agregasi,
                    ];
                }

                $zonaData['titik_pengamatans'][] = [
                    'nama' => $titik->nama,
                    'hasil' => $hasil,
                ];
            }

            $result[] = $zonaData;
        }

        return response()->json($result);
    }
}
