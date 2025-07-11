@extends('template.master')

@section('content')
    <div class="container-fluid py-0 px-6">
        <h4 class="mb-4">Edit Role</h4>

        <div class="card shadow-sm bg-light">
            <div class="card-body">
                <form action="{{ route('role.update', $role->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="nama" class="form-label">Nama Role</label>
                        <input type="text" name="nama" id="nama" class="form-control"
                            value="{{ old('nama', $role->nama) }}" required>
                    </div>

                    {{-- Checkbox Izin Detail --}}
                    <div class="mb-3">
                        <label class="form-label d-block">Detail Izin Akses</label>

                        {{-- Check All --}}
                        <div class="form-check mb-3">
                            <input type="checkbox" class="form-check-input" id="checkAll">
                            <label class="form-check-label fw-bold" for="checkAll">Pilih Semua</label>
                        </div>

                        <div class="row">
                            @foreach ($semua_akses as $key => $label)
                                <div class="col-md-4">
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" name="{{ $key }}"
                                            id="{{ $key }}" value="1"
                                            {{ old($key, isset($role) && $role->{$key} ? 'checked' : '') }}>
                                        <label class="form-check-label"
                                            for="{{ $key }}">{{ $label }}</label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save"></i> Simpan Perubahan
                    </button>
                    <a href="{{ route('role.index') }}" class="btn btn-secondary">Kembali</a>
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
            $('#kategori_role_id, #satuan_id').select2({
                theme: 'bootstrap4',
                placeholder: '-- Pilih --',
                allowClear: true
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            $('#kategori_role_id, #satuan_id').select2({
                theme: 'bootstrap4',
                placeholder: '-- Pilih --',
                allowClear: true
            });

            // Fitur Check All
            $('#checkAll').on('change', function() {
                const checked = $(this).is(':checked');
                $('input[type=checkbox]').not('#checkAll').prop('checked', checked);
            });
        });
    </script>
@endsection
