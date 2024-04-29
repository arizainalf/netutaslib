@extends('layouts.auth')

@section('title', 'Masuk')

@push('style')
@endpush

@section('main')
    <section class="section">
        <div class="d-flex align-items-stretch flex-wrap">
            <div
                class="col-lg-4 col-12 order-lg-1 min-vh-100 order-2 bg-white d-flex justify-content-center align-items-center">
                <div class="py-2">
                    <div class="text-center mb-4">
                        <img src="{{ asset('images/icons/Netutas72.png') }}" alt="logo">
                    </div>
                    <h4 class="text-dark text-center mb-2 font-weight-normal">Selamat Datang di</h4>
                    <h4 class="font-weight-bold text-dark text-center mb-2">{{ config('app.name') }}</h4>
                    <small class='text-center mb-3 d-block '>Perpustakaan SMP Negeri 7 Tasikmalaya</small>
                    <form id="login" autocomplete="off">
                        <div class="form-group">
                            <label for="email">Email <span class="text-danger">*</span></label>
                            <input id="email" type="email" class="form-control" name="email">
                            <small class="invalid-feedback" id="erroremail"></small>
                        </div>
                        <div class="form-group">
                            <label for="password" class="control-label">Password <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input id="password" type="password" class="form-control" name="password">
                                <div class="input-group-append">
                                    <a class="btn bg-white d-flex justify-content-center align-items-center border"
                                        onclick="togglePasswordVisibility('#password', '#toggle-password'); event.preventDefault();">
                                        <i id="toggle-password" class="fas fa-eye"></i>
                                    </a>
                                </div>
                            </div>
                            <small class="text-danger" id="errorpassword"></small>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-block btn-primary btn-lg btn-icon icon-right">
                                <i class="fas fa-sign-in mr-2"></i>Masuk
                            </button>
                        </div>
                        <div class="form-group">
                            <a class="btn btn-block btn-success btn-lg btn-icon icon-right" href="{{ url('/attend') }}"
                                role="button"><i class="fa-solid fa-bookmark mr-2"></i>Kunjungan</a>
                        </div>
                    </form>
                    <div class="text-center">
                        <a href="{{ route('password.request') }}" class="text-small font-weight-bold">Lupa Password ?</a>
                    </div>
                    <div class="text-small mt-5 text-center">
                        Copyright &copy; {{ date('Y') }} <div class="bullet"></div> Create By Ari Zainal Fauziah

                    </div>

                </div>
            </div>
            <div class="d-none d-lg-block col-lg-8 py-5 min-vh-100 background-walk-y position-relative overlay-gradient-bottom order-1"
                data-background="{{ asset('img/smpn7tasik.jpg') }}">
                <div class="absolute-bottom-left index-2">
                    <div class="text-light p-5 pb-2">
                        <div class="mb-5 pb-3">
                            <h5 class="font-weight-normal text-muted-transparent">SMP Negeri 7 Tasikmalaya</h5>
                            <h5 class="font-weight-normal text-muted-transparent">SEKOLAHKU NYAMAN
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script src="{{ asset('library/sweetalert/dist/sweetalert.min.js') }}"></script>

    <script>
        $(document).ready(function() {
            $("#login").submit(function(e) {
                setButtonLoadingState("#login .btn.btn-primary", true, "Masuk");
                e.preventDefault();
                const url = "{{ route('login') }}";
                const data = new FormData(this);

                const successCallback = function(response) {
                    setButtonLoadingState("#login .btn.btn-primary", false,
                        "<i class='fas fa-sign-in mr-2'></i>Masuk");
                    handleSuccess(response, null, null, "/");
                };

                const errorCallback = function(error) {
                    setButtonLoadingState("#login .btn.btn-primary", false,
                        "<i class='fas fa-sign-in mr-2'></i>Masuk");
                    handleValidationErrors(error, "login", ["email", "password"]);
                };

                ajaxCall(url, "POST", data, successCallback, errorCallback);
            });
        });
    </script>
@endpush
