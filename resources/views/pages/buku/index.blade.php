@extends('layouts.app')

@section('title', 'Buku')

@push('style')
    <link rel="stylesheet" href="{{ asset('library/datatables/datatables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('library/datatables/DataTables-1.10.16/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('library/datatables/Select-1.2.4/css/select.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('library/dropify/css/dropify.css') }}">
    <link rel="stylesheet" href="{{ asset('library/select2/dist/css/select2.min.css') }}">
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>@yield('title')</h1>
            </div>
            <div class="section-body">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title font-weight-bolder">
                            Data @yield('title')
                        </div>
                        <div class="ml-auto">
                            <button class="btn btn-success" onclick="getModal('createModal')"><i
                                    class="fas fa-plus mr-2"></i>Tambah</button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <a href="{{ route('buku.show', 'pdf') }}" class="btn btn-sm px-3 btn-danger mr-1"
                                target="_blank"><i class="fas fa-file-pdf mr-2"></i>Pdf</a>
                            <a href="{{ route('buku.show', 'excel') }}" class="btn btn-sm px-3 btn-info" target="_blank"><i
                                    class="fas fa-file-excel mr-2"></i>Excel</a>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped" id="book-table" width="100%">
                                <thead>
                                    <tr>
                                        <th scope="col" width="5%">#</th>
                                        <th scope="col" width="10%">Gambar</th>
                                        <th scope="col">Judul</th>
                                        <th scope="col">Kategori</th>
                                        <th scope="col">Penulis</th>
                                        <th scope="col">Penerbit</th>
                                        <th scope="col">Tahun</th>
                                        <th scope="col">Stok</th>
                                        <th scope="col" width="20%">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    @include('pages.buku.modal')
@endsection

@push('scripts')
    <script src="{{ asset('library/sweetalert/dist/sweetalert.min.js') }}"></script>
    <script src="{{ asset('library/datatables/datatables.min.js') }}"></script>
    <script src="{{ asset('library/datatables/DataTables-1.10.16/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('library/datatables/Select-1.2.4/js/dataTables.select.min.js') }}"></script>
    <script src="{{ asset('library/dropify/js/dropify.js') }}"></script>
    <script src="{{ asset('library/select2/dist/js/select2.full.min.js') }}"></script>

    <script>
        $(document).ready(function() {
            $('.dropify').dropify();

            datatableCall('book-table', '{{ route('buku.index') }}', [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex'
                },
                {
                    data: 'image',
                    name: 'image'
                },
                {
                    data: 'judul',
                    name: 'judul'
                },
                {
                    data: 'category',
                    name: 'category'
                },
                {
                    data: 'penulis',
                    name: 'penulis'
                },
                {
                    data: 'penerbit',
                    name: 'penerbit'
                },
                {
                    data: 'tahun',
                    name: 'tahun'
                },
                {
                    data: 'stok',
                    name: 'stok'
                },
                {
                    data: 'action',
                    name: 'action'
                },
            ]);
            select2ToJson("#category_id", "{{ route('kategori.index') }}", "#createModal");

            $("#saveData").submit(function(e) {
                setButtonLoadingState("#saveData .btn.btn-success", true);
                e.preventDefault();
                const kode = $("#saveData #id").val();
                let url = "{{ route('buku.store') }}";
                const data = new FormData(this);

                if (kode !== "") {
                    data.append("_method", "PUT");
                    url = `/admin/book/${kode}`;
                }

                const successCallback = function(response) {
                    setButtonLoadingState("#saveData .btn.btn-success", false);
                    handleSuccess(response, "book-table", "createModal");
                };

                const errorCallback = function(error) {
                    setButtonLoadingState("#saveData .btn.btn-success", false);
                    handleValidationErrors(error, "saveData", ["category_id", "judul", "penulis",
                        "penerbit", "tahun", "stok", "image"
                    ]);
                };

                ajaxCall(url, "POST", data, successCallback, errorCallback);
            });
        });
    </script>
@endpush
