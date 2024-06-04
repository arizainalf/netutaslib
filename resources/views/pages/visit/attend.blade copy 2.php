@extends('layouts.auth')

@section('title', 'Kunjungan')

@push('style')
@endpush

@section('main')
<section class="section">
  <div class="d-flex align-items-stretch flex-wrap">
    <div
      class="d-none d-lg-block col-lg-8 py-5 min-vh-100 background-walk-y position-relative overlay-gradient-bottom order-1"
      data-background="{{ asset('img/unsplash/login-bg.jpg') }}">
      <div class="absolute-bottom-left index-2">
        <div class="text-light p-5 pb-2">
          <div class="mb-5 pb-3">
            <h5 class="font-weight-normal text-muted-transparent">SMP Negeri 7 Tasikmalaya</h5>
            <h5 class="font-weight-normal text-muted-transparent">SEKOLAHKU NYAMAN
          </div>
        </div>
      </div>
    </div>
    <div
      class="col-lg-4 col-12 order-lg-1 min-vh-100 order-2 bg-white d-flex justify-content-center align-items-center">
      <div class="py-2">
        <div class="text-center mb-4">
          <img src="{{ asset('images/icons/Netutas72.png') }}" alt="logo">
        </div>
        <h4 class="text-dark text-center mb-2 font-weight-normal">Selamat Datang di</h4>
        <h4 class="font-weight-bold text-dark text-center mb-2">{{ config('app.name') }}</h4>
        <small class='text-center mb-3 d-block '>Perpustakaan SMP Negeri 7 Tasikmalaya</small>
        <form id="search" class="mb-3">
          <div class="input-group">
            <input id="keyword" placeholder="Masukan NISN atau NIPD" type="number" class="form-control" name="keyword">

            <div class="input-group-append">
              <button type="submit" class="btn btn-success">
                <i class="fas fa-search"></i>
              </button>
            </div>
          </div>
        </form>
        <form id="saveAttend">
          <div class="form-group">
            <input type="hidden" name="member_id" id="member_id">
            <label for="nama" class="form-label">Nama <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="nama" name="nama" readonly>
            <small class="invalid-feedback" id="errornama"></small>
          </div>
          <div class="form-group">
            <label>Kepentingan Kunjungan</label>
            <textarea id="deskripsi" name="deskripsi" class="form-control" data-height="100"></textarea>
          </div>
          <div class="form-group">
            <button type="submit" class="btn btn-block btn-primary btn-lg btn-icon icon-right">
              <i class="fas fa-sign-in mr-2"></i>Simpan
            </button>
          </div>
        </form>

        <div class="text-small mt-5 text-center">
          Copyright &copy; {{ date('Y') }} <div class="bullet"></div> Create By Ari Zainal Fauziah
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
  $("#search").submit(function(e) {
    setButtonLoadingState("#search .btn.btn-primary", true, "");
    e.preventDefault();
    const url = "{{ route('search') }}";
    const data = new FormData(this);

    select2ToJson("#id_member", "{{ route('search') }}");


    const successCallback = function(response) {
      setButtonLoadingState("#search .btn.btn-primary", false,
        '<i class="fas fa-search"></i>');
      handleSuccess(response, null, null, "no");

      $('#nama').val(response.data.nama);
      $('#member_id').val(response.data.id);
    };

    const errorCallback = function(error) {
      setButtonLoadingState("#search .btn.btn-primary", false,
        '<i class="fas fa-search"></i>');
      handleValidationErrors(error, "search");
      $('#nama').val('');
      $('#member_id').val('');
    };

    ajaxCall(url, "POST", data, successCallback, errorCallback);
  });
  $("#saveAttend").submit(function(e) {
    setButtonLoadingState("#saveAttend .btn.btn-success", true);
    e.preventDefault();
    const kode = $("#saveAttend #id").val();
    let url = "{{ route('saveattend') }}";
    const data = new FormData(this);

    const successCallback = function(response) {
      setButtonLoadingState("#saveAttend .btn.btn-success", false);
      handleSuccess(response, null, null, "no");
      $('#keyword').val('');
      $('#nama').val('');
      $('#member_id').val('');
      $('#deskripsi').val('');
    };

    const errorCallback = function(error) {
      setButtonLoadingState("#saveAttend .btn.btn-success", false);
      handleValidationErrors(error, "saveAttend");
      $('#keyword').val('');
      $('#nama').val('');
      $('#member_id').val('');
      $('#deskripsi').val('');
    };

    ajaxCall(url, "POST", data, successCallback, errorCallback);
  });
});
</script>
@endpush