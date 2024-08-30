@extends('layouts.app')

@section('title', 'Profil')

@push('style')
    <link rel="stylesheet" href="{{ asset('library/dropify/css/dropify.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.css">
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>@yield('title')</h1>
            </div>
            <div class="section-body">
                <div class="row">
                    <div class="col-12 col-lg-6">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="text-dark">Data @yield('title')</h4>
                            </div>
                            <div class="card-body">
                                <form id="updateData">
                                    @method('PUT')
                                    <div class="form-group">
                                        <label for="image" class="form-label">Foto </label>
                                        <input type="file" name="image" id="image" class="dropify"
                                            data-height="200">
                                        <small class="invalid-feedback" id="errorimage"></small>
                                        <div id="image-crop-container" style="display:none;">
                                            <img id="image-crop" style="max-width: 100%;">
                                            <button type="button" id="crop-image-btn" class="btn btn-primary mt-2">Crop &
                                                Save</button>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="nama" class="form-label">Nama <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="nama" name="nama"
                                            value="{{ Auth::user()->nama }}">
                                        <small class="invalid-feedback" id="errornama"></small>
                                    </div>
                                    <div class="form-group">
                                        <label for="email" class="form-label">Email <span
                                                class="text-danger">*</span></label>
                                        <input type="email" class="form-control" id="email" name="email"
                                            value="{{ Auth::user()->email }}">
                                        <small class="invalid-feedback" id="erroremail"></small>
                                    </div>
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-success">Simpan</button>
                                    </div>
                                </form>
                            </div>
                        </div>

                    </div>
                    <div class="col-12 col-lg-6">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="text-dark">Ubah Password</h4>
                            </div>
                            <div class="card-body">
                                <form id="updatePassword">
                                    @method('PUT')
                                    <div class="form-group">
                                        <label for="password_lama" class="form-label">Password Lama <span
                                                class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <input type="password" class="form-control" id="password_lama"
                                                name="password_lama">
                                            <div class="input-group-append">
                                                <a class="btn bg-white d-flex justify-content-center align-items-center border"
                                                    onclick="togglePasswordVisibility('#password_lama', '#toggle-password-lama'); event.preventDefault();">
                                                    <i id="toggle-password-lama" class="fas fa-eye"></i>
                                                </a>
                                            </div>
                                        </div>
                                        <small class="text-danger" id="errorpassword_lama"></small>
                                    </div>
                                    <div class="form-group">
                                        <label for="password" class="form-label">Password Baru <span
                                                class="text-danger">*</span></label>
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
                                        <label for="password_confirmation" class="form-label">Konfirmasi Password <span
                                                class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <input type="password" class="form-control" id="password_confirmation"
                                                name="password_confirmation">
                                            <div class="input-group-append">
                                                <a class="btn bg-white d-flex justify-content-center align-items-center border"
                                                    onclick="togglePasswordVisibility('#password_confirmation', '#toggle-password-confirmation'); event.preventDefault();">
                                                    <i id="toggle-password-confirmation" class="fas fa-eye"></i>
                                                </a>
                                            </div>
                                        </div>
                                        <small class="text-danger" id="errorpassword_confirmation"></small>
                                    </div>
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-success d-none d-lg-block">Simpan</button>
                                        <button type="submit"
                                            class="btn btn-success d-block w-100 d-lg-none">Simpan</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('library/sweetalert/dist/sweetalert.min.js') }}"></script>
    <script src="{{ asset('library/dropify/js/dropify.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.dropify').dropify();

            $("#updateData").submit(function(e) {
                setButtonLoadingState("#updateData .btn.btn-success", true);
                e.preventDefault();
                const url = `{{ route('profil') }}`;
                const data = new FormData(this);

                const successCallback = function(response) {
                    $('#image').parent().find(".dropify-clear").trigger('click');
                    setButtonLoadingState("#updateData .btn.btn-success", false);
                    handleSuccess(response, null, null, "no");
                    $(".img-navbar").css("background-image",
                        `url('/storage/img/user/${response.data.image}')`);
                };

                const errorCallback = function(error) {
                    setButtonLoadingState("#updateData .btn.btn-success", false);
                    handleValidationErrors(error, "updateData", ["nama", "email", "no_hp",
                        "image"
                    ]);
                };

                ajaxCall(url, "POST", data, successCallback, errorCallback);
            });

            $("#updatePassword").submit(function(e) {
                setButtonLoadingState("#updatePassword .btn.btn-success", true);
                e.preventDefault();
                const url = `{{ route('profil.password') }}`;
                const data = new FormData(this);

                const successCallback = function(response) {
                    setButtonLoadingState("#updatePassword .btn.btn-success", false);
                    handleSuccess(response, null, null, "no");
                    $('#updatePassword .form-control').removeClass("is-invalid").val("");
                    $('#updatePassword .text-danger').html("");
                };

                const errorCallback = function(error) {
                    setButtonLoadingState("#updatePassword .btn.btn-success", false);
                    handleValidationErrors(error, "updatePassword", ["password_lama", "password"]);
                };

                ajaxCall(url, "POST", data, successCallback, errorCallback);
            });
        });
    </script>
@endpush
