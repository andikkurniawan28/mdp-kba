@extends('template.master')

@section('content')
    <form action="{{ route('verifikasi_mandor.proses') }}" method="POST" id="verifikasiForm">
    @csrf

    <div class="container-fluid py-0 px-6">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4>Verifikasi Mandor</h4>
            <div class="btn-group">
                <button type="submit" class="btn btn-success">
                    <i class="bi bi-check-circle"></i> Verifikasi Terpilih
                </button>
                {{-- <a href="{{ route('monitoring.index') }}" class="btn btn-primary">
                    <i class="bi bi-arrow-left"></i> Kembali
                </a> --}}
            </div>
        </div>

        <div class="card shadow-sm bg-light">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="monitoringTable" class="table table-bordered table-hover table-sm w-100">
                        <thead class="table-light">
                            <tr>
                                <th>
                                    <input type="checkbox" id="selectAll" class="form-check-input">
                                </th>
                                <th>Periode</th>
                                <th>Jam</th>
                                <th>Kode</th>
                                <th>Titik</th>
                                <th>Nilai</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</form>

@endsection

@section('scripts')
    {{-- jQuery & DataTables --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script>
        $(function () {
            const table = $('#monitoringTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('verifikasi_mandor.index') }}",
                order: [[0, 'desc']],
                columns: [
                    {
                        data: 'aksi',
                        name: 'aksi',
                        orderable: false,
                        searchable: false
                    },
                    { data: 'periode', name: 'periode' },
                    { data: 'jam', name: 'jam' },
                    { data: 'titik_pengamatan.kode', name: 'titik_pengamatan.kode' },
                    { data: 'titik_pengamatan.nama', name: 'titik_pengamatan.nama' },
                    { data: 'parameter', name: 'parameter' }
                ]
            });

            // Pilih semua checkbox
            $('#selectAll').on('click', function () {
                $('.row-checkbox').prop('checked', this.checked);
            });

            // Jika salah satu dicentang/di-uncheck manual
            $(document).on('change', '.row-checkbox', function () {
                if (!this.checked) {
                    $('#selectAll').prop('checked', false);
                } else if ($('.row-checkbox:checked').length === $('.row-checkbox').length) {
                    $('#selectAll').prop('checked', true);
                }
            });
        });
    </script>

@endsection
