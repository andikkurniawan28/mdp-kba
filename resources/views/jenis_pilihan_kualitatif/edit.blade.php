@extends('template.master')

@section('content')
    <div class="container-fluid py-0 px-6">
        <h4 class="mb-4">Edit Jenis Pilihan Kualitatif</h4>

        <div class="card shadow-sm bg-light">
            <div class="card-body">
                <form action="{{ route('jenis_pilihan_kualitatif.update', $jenis_pilihan_kualitatif->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="keterangan" class="form-label">Jenis Pilihan Kualitatif</label>
                        <input type="text" name="keterangan" id="keterangan" class="form-control"
                            value="{{ $jenis_pilihan_kualitatif->keterangan }}" required autofocus>
                    </div>

                    {{-- <div class="mb-3">
                        <label for="kode" class="form-label">Kode</label>
                        <input type="text" name="kode" id="kode" class="form-control"
                            value="{{ $jenis_pilihan_kualitatif->kode }}" required>
                    </div> --}}

                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save"></i> Perbarui
                    </button>
                    <a href="{{ route('jenis_pilihan_kualitatif.index') }}" class="btn btn-secondary">Kembali</a>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    {{-- Select2 JS & CSS --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@1.5.2/dist/select2-bootstrap4.min.css"
        rel="stylesheet" />

    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#kategori_jenis_pilihan_kualitatif_id, #satuan_id').select2({
                theme: 'bootstrap4',
                placeholder: '-- Pilih --',
                allowClear: true
            });
        });
    </script>
@endsection
