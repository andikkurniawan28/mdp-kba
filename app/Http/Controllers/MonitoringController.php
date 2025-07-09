<?php

namespace App\Http\Controllers;

use App\Models\Parameter;
use App\Models\Monitoring;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\TitikPengamatan;
use Yajra\DataTables\DataTables;
use App\Models\InputMonitoringLog;
use Illuminate\Support\Facades\Auth;

class MonitoringController extends Controller
{
    public function index(Request $request)
    {
        if ($response = $this->checkIzin('akses_daftar_input_monitoring')) {
            return $response;
        }

        if ($request->ajax()) {
            // Eager load hubungan
            $data       = Monitoring::with('titik_pengamatan');
            $parameters = Parameter::with('satuan')->get();

            return DataTables::of($data)
                ->addIndexColumn()

                // Nama titik pengamatan
                ->addColumn('titik_pengamatan_nama', fn($row) =>
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

                // Aksi edit/hapus
                ->addColumn('aksi', function ($row) {
                    $editUrl   = route('monitoring.edit', $row->id);
                    $showUrl   = route('monitoring.show', $row->id);
                    $deleteUrl = route('monitoring.destroy', $row->id);
                    return '
                        <a href="' . $showUrl . '" class="btn btn-sm btn-info">Log</a>
                        <a href="' . $editUrl . '" class="btn btn-sm btn-warning">Edit</a>
                        <form action="' . $deleteUrl . '" method="POST" class="d-inline" onsubmit="return confirm(\'Hapus data ini?\')">
                            ' . csrf_field() . method_field('DELETE') . '
                            <button class="btn btn-sm btn-danger">Hapus</button>
                        </form>
                    ';
                })

                ->rawColumns(['parameter', 'aksi'])
                ->make(true);
        }

        return view('monitoring.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if ($response = $this->checkIzin('akses_tambah_input_monitoring')) {
            return $response;
        }

        return view('monitoring.create2', [
            'titik_pengamatans' => TitikPengamatan::orderBy('urutan', 'asc')->get(),
            'parameters' => Parameter::all(),
            // Flow Terakhir khusus untuk input flow
            'flow_terakhir' => Monitoring::where('titik_pengamatan_id', 1)->get()->last(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if ($response = $this->checkIzin('akses_tambah_input_monitoring')) {
            return $response;
        }

        $request->validate([
            'periode'               => 'required|date',
            'jam'                   => 'required|date_format:H:i',
            'titik_pengamatan_id'   => 'required|exists:titik_pengamatans,id',
        ]);

        // Ambil parameter dari form
        $allParamIds = collect($request->all())
            ->keys()
            ->filter(fn($k) => Str::startsWith($k, 'param'))
            ->map(fn($k) => (int) Str::after($k, 'param'))
            ->all();

        $parameters = Parameter::whereIn('id', $allParamIds)
            ->pluck('nama', 'id'); // ambil nama parameter, contoh ['1' => 'pH', '2' => 'Suhu']

        $conditions = [
            'periode'             => $request->periode,
            'jam'                 => $request->jam,
            'titik_pengamatan_id' => $request->titik_pengamatan_id,
        ];

        $dataToSave = [];

        foreach ($request->all() as $key => $value) {
            if (Str::startsWith($key, 'param')) {
                $paramId = (int) Str::after($key, 'param');

                $finalValue = $value !== null && $value !== ''
                    ? $value
                    : null;

                $dataToSave[$key] = $finalValue;
            }
        }

        // Simpan atau perbarui
        $monitoring = Monitoring::updateOrCreate($conditions, $dataToSave);

        // Simpan log untuk setiap parameter yang diinput (tidak null)
        $logs = [];

        foreach ($request->all() as $key => $value) {
            if (Str::startsWith($key, 'param') && $value !== null && $value !== '') {
                $paramId   = (int) Str::after($key, 'param');
                $namaParam = $parameters->get($paramId, 'Tidak diketahui');

                $logs[] = "Input $namaParam dengan nilai: $value";
            }
        }

        // Jika ada yang diinput, simpan ke log
        if (!empty($logs)) {
            InputMonitoringLog::create([
                'user_id'       => auth()->id(),
                'monitoring_id' => $monitoring->id,
                'keterangan'    => implode('; ', $logs),
            ]);
        }

        return redirect()
            ->route('monitoring.index')
            ->with('success', 'Data monitoring berhasil disimpan atau diperbarui.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Monitoring $monitoring)
    {
        $logs = $monitoring->input_monitoring_log()->latest()->get();
        return view('monitoring.show', compact('monitoring', 'logs'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Monitoring $monitoring)
    {
        if ($response = $this->checkIzin('akses_edit_input_monitoring')) {
            return $response;
        }

        return view('monitoring.edit', [
            'monitoring' => $monitoring,
            'titik_pengamatans' => TitikPengamatan::orderBy('urutan', 'asc')->get(),
            'parameters' => Parameter::all(),
            // Flow Terakhir khusus untuk input flow
            'flow_terakhir' => Monitoring::where('titik_pengamatan_id', 1)->get()->last(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Monitoring $monitoring)
    {
        if ($response = $this->checkIzin('akses_edit_input_monitoring')) {
            return $response;
        }

        // 1) Normalize jam (ambil HH:MM)
        $request->merge([
            'jam' => substr($request->jam, 0, 5),
        ]);

        // 2) Validasi dasar
        $request->validate([
            'periode'             => 'required|date',
            'jam'                 => 'required|date_format:H:i',
            'titik_pengamatan_id' => 'required|exists:titik_pengamatans,id',
        ]);

        // 3) Kumpulkan semua parameter IDs dari input
        $paramIds = collect($request->all())
            ->keys()
            ->filter(fn($k) => Str::startsWith($k, 'param'))
            ->map(fn($k) => (int) Str::after($k, 'param'))
            ->all();

        // 4) Ambil jenis tiap parameter: ['1'=>'kuantitatif', ...]
        $jenisMap = Parameter::whereIn('id', $paramIds)
            ->pluck('jenis', 'id');

        // 5) Siapkan data utama
        $data = [
            'periode'             => $request->periode,
            'jam'                 => $request->jam,
            'titik_pengamatan_id' => $request->titik_pengamatan_id,
        ];

        // 6) Loop semua field "param{ID}"
        foreach ($request->all() as $key => $value) {
            if (Str::startsWith($key, 'param')) {
                $id    = (int) Str::after($key, 'param');
                $jenis = $jenisMap->get($id);

                if ($jenis === 'kuantitatif') {
                    // cast ke float
                    $data[$key] = $value !== null && $value !== ''
                        ? floatval($value)
                        : null;
                } else {
                    // simpan string
                    $data[$key] = $value !== null && $value !== ''
                        ? $value
                        : null;
                }
            }
        }

        // Logging
        $logs = [];

        foreach ($request->all() as $key => $value) {
            if (Str::startsWith($key, 'param')) {
                $id    = (int) Str::after($key, 'param');
                $jenis = $jenisMap->get($id);
                $paramName = Parameter::find($id)?->nama ?? 'Unknown';

                $newValue = $jenis === 'kuantitatif'
                    ? ($value !== null && $value !== '' ? floatval($value) : null)
                    : ($value !== null && $value !== '' ? $value : null);

                // Ambil nilai lama
                $oldValue = $monitoring->{$key};

                // Masukkan ke data update
                $data[$key] = $newValue;

                // Jika nilai berubah, masukkan ke log
                if ($newValue !== $oldValue) {
                    $logs[] = "Update $paramName dari $oldValue ke $newValue";
                }
            }
        }

        // Simpan log jika ada perubahan
        if (!empty($logs)) {
            InputMonitoringLog::create([
                'user_id'       => auth()->id(),
                'monitoring_id' => $monitoring->id,
                'keterangan'    => implode('; ', $logs), // atau pakai newline jika ingin
            ]);
        }

        // 7) Update record
        $monitoring->update($data);

        return redirect()
            ->route('monitoring.index')
            ->with('success', 'Monitoring berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Monitoring $monitoring)
    {
        if ($response = $this->checkIzin('akses_hapus_input_monitoring')) {
            return $response;
        }

        $monitoring->delete();

        return redirect()->route('monitoring.index')->with('success', 'Monitoring berhasil dihapus.');
    }
}
