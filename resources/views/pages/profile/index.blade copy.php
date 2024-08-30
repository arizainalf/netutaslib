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
        let cropper;

        $(document).ready(function() {
            $('.dropify').dropify();

            $("#image").on("change", function(e) {
                const files = e.target.files;
                if (files && files.length > 0) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        $("#image-crop-container").show();
                        $("#image-crop").attr("src", e.target.result);
                        cropper = new Cropper(document.getElementById("image-crop"), {
                            aspectRatio: 1,
                            viewMode: 1
                        });
                    };
                    reader.readAsDataURL(files[0]);
                }
            });

            $("#crop-image-btn").on("click", function() {
                const canvas = cropper.getCroppedCanvas({
                    width: 300,
                    height: 300
                });
                canvas.toBlob(function(blob) {
                    const formData = new FormData();
                    formData.append("image", blob);
                    formData.append("_method", "PUT");

                    // Append other form data
                    $("#updateData").serializeArray().forEach(field => {
                        formData.append(field.name, field.value);
                    });

                    // Send the data to the server
                    const url = `{{ route('profil') }}`;

                    const successCallback = function(response) {
                        $('#image').parent().find(".dropify-clear").trigger('click');
                        $("#image-crop-container").hide();
                        cropper.destroy();
                        setButtonLoadingState("#updateData .btn.btn-success", false);
                        handleSuccess(response, null, null, "no");
                        $(".img-navbar").css("background-image",
                            `url('/storage/img/karyawan/${response.data.image}')`);
                    };

                    const errorCallback = function(error) {
                        setButtonLoadingState("#updateData .btn.btn-success", false);
                        handleValidationErrors(error, "updateData", ["nama", "email", "image"]);
                    };

                    ajaxCall(url, "POST", formData, successCallback, errorCallback);
                });
            });

            $("#updateData").submit(function(e) {
                e.preventDefault();
                if (!cropper) {
                    alert("Please select and crop an image first.");
                    return;
                }
                $("#crop-image-btn").trigger("click");
            });
        });
    </script>
@endpush
