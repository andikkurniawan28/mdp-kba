@extends('template.master')

@section('content')
    <div class="container-fluid py-0 px-6">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4>Daftar Role</h4>
            <a href="{{ route('role.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Tambah
            </a>
        </div>

        <div class="card shadow-sm bg-light">
            <div class="card-body">
                <div class="table-responsive">

                    <table id="roleTable" class="table table-bordered table-hover table-sm w-100">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>Izin</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    {{-- jQuery & DataTables --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

    <script>
        $(function() {
            $('#roleTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('role.index') }}",
                order: [
                    [1, 'asc']
                ],
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    // { data: 'kode', name: 'kode' },
                    {
                        data: 'nama',
                        name: 'nama'
                    },
                    {
                        data: 'izin',
                        name: 'izin',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'aksi',
                        name: 'aksi',
                        orderable: false,
                        searchable: false
                    }
                ]
            });
        });
    </script>
@endsection
