@extends('layouts.app')

@section('title', 'Siswa')

@push('style')
    <link rel="stylesheet" href="{{ asset('library/datatables/datatables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('library/datatables/DataTables-1.10.16/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('library/datatables/Select-1.2.4/css/select.bootstrap4.min.css') }}">
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
                            <a href="{{ route('siswa.show', 'pdf') }}" class="btn btn-sm px-3 btn-danger mr-1"
                                target="_blank"><i class="fas fa-file-pdf mr-2"></i>Pdf</a>
                            <a href="{{ route('siswa.show', 'excel') }}" class="btn btn-sm px-3 btn-info"
                                target="_blank"><i class="fas fa-file-excel mr-2"></i>Excel</a>

                            <button class="btn btn-sm px-3 btn-warning float-right" onclick="getModal('importModal')"><i
                                    class="fas fa-file-excel mr-2"></i>Import Excel</button>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped" id="siswa-table" width="100%">
                                <thead>
                                    <tr>
                                        <th scope="col" width="5%">#</th>
                                        <th scope="col">Kelas</th>
                                        <th scope="col">NISN</th>
                                        <th scope="col">NIPD</th>
                                        <th scope="col">Nama</th>
                                        <th scope="col">Jenis Kelamin</th>
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
    @include('pages.siswa.modal')
@endsection

@push('scripts')
    <script src="{{ asset('library/sweetalert/dist/sweetalert.min.js') }}"></script>
    <script src="{{ asset('library/datatables/datatables.min.js') }}"></script>
    <script src="{{ asset('library/datatables/DataTables-1.10.16/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('library/datatables/Select-1.2.4/js/dataTables.select.min.js') }}"></script>

    <script>
        $(document).ready(function() {
            datatableCall('siswa-table', '{{ route('siswa.index') }}', [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex'
                },
                {
                    data: 'kelas',
                    name: 'kelas'
                },
                {
                    data: 'nisn',
                    name: 'nisn'
                },
                {
                    data: 'nipd',
                    name: 'nipd'
                },
                {
                    data: 'nama',
                    name: 'nama'
                },
                {
                    data: 'jenis_kelamin',
                    name: 'jenis_kelamin'
                },
                {
                    data: 'action',
                    name: 'action'
                },
            ]);

            select2ToJson("#id_kelas", "{{ route('kelas.index') }}", "#createModal");

            $("#saveData").submit(function(e) {
                setButtonLoadingState("#saveData .btn.btn-success", true);
                e.preventDefault();
                const kode = $("#saveData #id").val();
                let url = "{{ route('siswa.store') }}";
                const data = new FormData(this);

                if (kode !== "") {
                    data.append("_method", "PUT");
                    url = `/admin/siswa/${kode}`;
                }

                const successCallback = function(response) {
                    setButtonLoadingState("#saveData .btn.btn-success", false);
                    handleSuccess(response, "siswa-table", "createModal");
                };

                const errorCallback = function(error) {
                    setButtonLoadingState("#saveData .btn.btn-success", false);
                    handleValidationErrors(error, "saveData", ["id_kelas","nama", "nisn", "nipd",
                        "jenis_kelamin"
                    ]);
                };

                ajaxCall(url, "POST", data, successCallback, errorCallback);
            });
            $("#uploadExcel").submit(function(e) {
                setButtonLoadingState("#uploadExcel .btn.btn-success", true);
                e.preventDefault();
                const kode = $("#uploadExcel #id").val();
                let url = "{{ route('siswa.import') }}";
                const data = new FormData(this);

                if (kode !== "") {
                    data.append("_method", "PUT");
                    url = `/admin/siswa/${kode}`;
                }

                const successCallback = function(response) {
                    setButtonLoadingState("#uploadExcel .btn.btn-success", false);
                    handleSuccess(response, "siswa-table", "importModal");
                };

                const errorCallback = function(error) {
                    setButtonLoadingState("#uploadExcel .btn.btn-success", false);
                    handleValidationErrors(error, "uploadExcel", ["nama", "nisn", "nipd",
                        "jenis_kelamin"
                    ]);
                };

                ajaxCall(url, "POST", data, successCallback, errorCallback);
            });
        });
    </script>
@endpush
