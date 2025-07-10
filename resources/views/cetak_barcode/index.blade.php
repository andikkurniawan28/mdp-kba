@extends('template.master')

@section('content')
    <div class="container-fluid py-0 px-6 text-light">
        <h4 class="mb-3">Cetak Barcode {{ $zona->nama }}</h4>
    </div>

    <div class="row">
        @foreach ($titik_pengamatans as $titik)
            <div class="col-lg-3 md-3 mb-4">
                <div class="card bg-dark text-white text-xs shadow">
                    <div class="card-body">
                        <div class="font-weight-bold text-light text-uppercase mb-1">
                            {{ $titik->nama }}
                        </div>
                        <form action="{{ route('cetak_barcode.proses') }}" method="POST" class="form-prevent" id="formID" target="_blank">
                            @csrf @method('POST')
                            <input type="hidden" name="titik_pengamatan_id" value="{{ $titik->id }}" readonly>
                            <button type="submit" class="btn btn-warning btn-sm text-dark">
                                Cetak <i class="fas fa-print"></i>
                            </button>
                        </form>

                        <br>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const forms = document.querySelectorAll('.form-prevent');

        forms.forEach(function(form) {
            form.addEventListener('submit', function(e) {
                // Cegah submit default
                e.preventDefault();

                // Cari tombol submit di dalam form
                const submitButton = form.querySelector('button[type="submit"]');

                // Disable tombol dan ubah teks jika perlu
                if (submitButton) {
                    submitButton.disabled = true;
                    submitButton.innerHTML = 'Mencetak... <i class="fas fa-spinner fa-spin"></i>';
                }

                // Submit form secara manual
                form.submit();
            });
        });
    });
</script>
@endsection
