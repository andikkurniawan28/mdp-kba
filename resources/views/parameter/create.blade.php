@extends('template.master')

@section('content')
    <div class="container-fluid py-4 px-6">
        <h4 class="mb-4">Tambah Parameter</h4>
        <div class="card shadow-sm bg-light">
            <div class="card-body">
                <form action="{{ route('parameter.store') }}" method="POST">
                    @csrf
                    <div class="row g-3">
                        <!-- Nama & Simbol -->
                        <div class="col-md-6">
                            <label class="form-label">Nama</label>
                            <input name="nama" class="form-control" required autofocus>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Simbol</label>
                            <input name="simbol" class="form-control" required>
                        </div>

                        <!-- Kategori & Jenis -->
                        <div class="col-md-4">
                            <label class="form-label">Kategori</label>
                            <select name="kategori_parameter_id" id="kategori_parameter_id" class="form-select" required>
                                <option disabled selected>-- Pilih Kategori --</option>
                                @foreach ($kategori_parameters as $k)
                                    <option value="{{ $k->id }}">{{ $k->nama }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- <div class="col-md-4 d-flex align-items-center">
                                <label class="me-2 mb-0">Jenis</label>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="jenisSwitch" name="jenis"
                                        value="kualitatif">
                                    <label class="form-check-label" for="jenisSwitch" id="jenisLabel">Kuantitatif</label>
                                </div>
                            </div> -->

                        <div class="col-md-4">
                            <!-- <label for="jenisSelect" class="me-2 mb-0">Jenis</label> -->
                            <label class="form-label">Jenis</label>
                            <select class="form-control" id="jenisSelect" name="jenis">
                                <option value="kuantitatif">Kuantitatif</option>
                                <option value="kualitatif_opsional">Kualitatif Opsional</option>
                                <option value="kualitatif_entry">Kualitatif Entry</option>
                            </select>
                        </div>

                        <!-- Satuan & Agregasi -->
                        <div class="col-md-4" id="satuan-group">
                            <label class="form-label">Satuan</label>
                            <select name="satuan_id" id="satuan_id" class="form-select" required>
                                <option disabled selected>-- Pilih Satuan --</option>
                                @foreach ($satuans as $s)
                                    <option value="{{ $s->id }}">{{ $s->nama }} ({{ $s->simbol }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4" id="agregasi-group">
                            <label class="form-label">Agregasi</label>
                            <select name="metode_agregasi" class="form-select" required>
                                <option disabled selected>-- Pilih Metode Agregasi --</option>
                                <option value="sum">Sum</option>
                                <option value="avg">Average</option>
                                <option value="count">Count</option>
                            </select>
                        </div>

                        <!-- Pilihan Kualitatif sebagai checkbox -->
                        <div class="col-md-12" id="kualitatif-group" style="display:none;">
                            <label class="form-label d-block">Jenis Pilihan Kualitatif</label>
                            <div class="row">
                                @foreach ($jenis_pilihan_kualitatifs as $p)
                                    <div class="col-md-4">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="pilihan_kualitatif[]"
                                                id="pilihan{{ $p->id }}" value="{{ $p->id }}">
                                            <label class="form-check-label" for="pilihan{{ $p->id }}">
                                                {{ $p->keterangan }}
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Keterangan -->
                        <div class="col-12">
                            <label class="form-label">Keterangan</label>
                            <textarea name="keterangan" class="form-control" rows="2"></textarea>
                        </div>

                        <!-- Toggle Disembunyikan -->
                        <div class="col-md-4">
                            <label class="form-label d-block">Disembunyikan</label>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="disembunyikanToggle">
                                <input type="hidden" name="disembunyikan" id="disembunyikanHidden" value="0">
                                <label class="form-check-label" for="disembunyikanToggle">Sembunyikan Parameter</label>
                            </div>
                        </div>

                        <!-- Tombol -->
                        <div class="col-12 text-end">
                            <button class="btn btn-primary"><i class="bi bi-save"></i> Simpan</button>
                            <a href="{{ route('parameter.index') }}" class="btn btn-secondary">Kembali</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <!-- jQuery & Select2 -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@1.5.2/dist/select2-bootstrap4.min.css"
        rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        $(function() {
            // Inisialisasi Select2
            $('#kategori_parameter_id, #satuan_id').select2({
                theme: 'bootstrap4',
                placeholder: '-- Pilih --',
                allowClear: true,
                width: '100%'
            });

            function toggleJenis() {
                const jenis = $('#jenisSelect').val();

                const isKuantitatif = jenis === 'kuantitatif';
                const isKualitatifOpsional = jenis === 'kualitatif_opsional';

                // Kualitatif Group hanya untuk opsional
                $('#kualitatif-group')
                    .toggle(isKualitatifOpsional)
                    .find('input[type=checkbox]')
                    .prop('disabled', !isKualitatifOpsional)
                    .prop('checked', false);

                // Satuan & Agregasi hanya untuk kuantitatif
                $('#satuan-group')
                    .toggle(isKuantitatif)
                    .find('select')
                    .prop('required', isKuantitatif);

                $('#agregasi-group')
                    .toggle(isKuantitatif)
                    .find('select')
                    .prop('required', isKuantitatif);
            }

            $('#jenisSelect').change(toggleJenis);

            toggleJenis(); // panggil saat awal load

            $('#disembunyikanToggle').on('change', function () {
                $('#disembunyikanHidden').val(this.checked ? '1' : '0');
            });
        });
    </script>
@endsection
