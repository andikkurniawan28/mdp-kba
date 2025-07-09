@extends('template.master2')

@section('title', 'Login - MDP')

@section('content')
    <!-- Tambahkan Bootstrap Icons CDN jika belum -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <div class="d-flex justify-content-center align-items-center" style="min-height: 75vh;">
        <div class="card shadow-sm p-4 bg-dark text-light" style="max-width: 400px; width: 100%;">
            <div class="text-center mb-4">
                <h5 class="fw-bold"><i class="bi bi-graph-up"></i> MDP</h5>
                <small class="text-light">Monitoring Data Platform</small>
            </div>

            <form action="{{ route('login_process') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label for="email" class="form-label text-light">Email</label>
                    <input type="text" name="email" id="email"
                        class="form-control form-control-sm bg-secondary text-white @error('email') is-invalid @enderror"
                        value="{{ old('email') }}" required autofocus>
                    @error('email')
                        <div class="invalid-feedback bg-dark text-danger border-0">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3 position-relative">
                    <label for="password" class="form-label text-light">Kata Sandi</label>
                    <div class="input-group">
                        <input type="password" name="password" id="password"
                            class="form-control form-control-sm bg-secondary text-white @error('password') is-invalid @enderror"
                            required>
                        <span class="input-group-text bg-secondary border-0">
                            <i class="bi bi-eye text-white" id="togglePassword" style="cursor: pointer;"></i>
                        </span>
                    </div>
                    @error('password')
                        <div class="invalid-feedback bg-dark text-danger border-0">{{ $message }}</div>
                    @enderror
                </div>


                {{-- <div class="mb-3 form-check">
                <input type="checkbox" name="remember" class="form-check-input" id="remember">
                <label class="form-check-label small text-light" for="remember">Ingat Saya</label>
            </div> --}}

                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i class="bi bi-box-arrow-in-right me-1"></i> Masuk
                    </button>
                </div>
            </form>

            <div class="mt-3 text-center">
                <small class="text-light">Belum punya akun? Hubungi <a href="https://wa.me/6285733465399" target="_blank" class="text-light">Admin.</a></small>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const toggle = document.getElementById('togglePassword');
            const passwordInput = document.getElementById('password');

            toggle.addEventListener('click', function() {
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);
                this.classList.toggle('bi-eye');
                this.classList.toggle('bi-eye-slash');
            });
        });
    </script>

@endsection
