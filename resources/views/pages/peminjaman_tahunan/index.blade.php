@extends('layouts.app')

@section('title', 'Peminjaman Buku Mata Pelajaran')

@push('style')
    <link rel="stylesheet" href="{{ asset('library/datatables/datatables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('library/datatables/DataTables-1.10.16/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('library/datatables/Select-1.2.4/css/select.bootstrap4.min.css') }}">
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
                            <button class="btn btn-primary" onclick="getModal('createModalBanyak')"><i
                                    class="fas fa-plus mr-2"></i>Tambah Massal</button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <a href="{{ route('peminjamanmapel.show', 'pdf') }}" class="btn btn-sm px-3 btn-danger mr-1"
                                target="_blank"><i class="fas fa-file-pdf mr-2"></i>Pdf</a>
                            <a href="{{ route('peminjamanmapel.show', 'excel') }}" class="btn btn-sm px-3 btn-info" target="_blank"><i
                                    class="fas fa-file-excel mr-2"></i>Excel</a>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped" id="peminjaman-table" width="100%">
                                <thead>
                                    <tr>
                                        <th scope="col" width="5%">#</th>
                                        <th scope="col">Kode</th>
                                        <th scope="col">Mapel</th>
                                        <th scope="col">Siswa</th>
                                        <th scope="col">Tanggal Pinjam</th>
                                        <th scope="col">Tanggal Selesai</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Pengelola</th>
s                                       <th scope="col">Aksi</th>
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
    @include('pages.peminjaman_tahunan.modal')
@endsection

@push('scripts')
    <script src="{{ asset('library/sweetalert/dist/sweetalert.min.js') }}"></script>
    <script src="{{ asset('library/datatables/datatables.min.js') }}"></script>
    <script src="{{ asset('library/datatables/DataTables-1.10.16/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('library/datatables/Select-1.2.4/js/dataTables.select.min.js') }}"></script>
    <script src="{{ asset('library/select2/dist/js/select2.full.min.js') }}"></script>

    <script>
        $(document).ready(function() {

            datatableCall('peminjaman-table', '{{ route('peminjamanmapel.index') }}', [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex'
                },
                {
                    data: 'kode',
                    name: 'kode'
                },
                {
                    data: 'mapel',
                    name: 'mapel',
                },
                {
                    data: 'siswa',
                    name: 'siswa'
                },
                {
                    data: 'tanggal_mulai',
                    name: 'tanggal_mulai'
                },
                {
                    data: 'tanggal_selesai',
                    name: 'tanggal_selesai'
                },
                {
                    data: 'status',
                    name: 'status'
                },
                {
                    data: 'user',
                    name: 'user'
                },
                {
                    data: 'action',
                    name: 'action'
                },
            ]);

            select2ToJson("#id_siswa", "{{ route('siswa.index') }}", "#createModal");
            select2ToJson("#id_kelas", "{{ route('kelas.index') }}", "#createModalBanyak");
            select2ToJson("#id_mapels", "{{ route('mapel.index') }}", "#createModalBanyak");
            select2ToJson("#id_mapel", "{{ route('mapel.index') }}", "#createModal");

            $("#saveData").submit(function(e) {
                setButtonLoadingState("#saveData .btn.btn-success", true);
                e.preventDefault();
                const kode = $("#saveData #id").val();
                let url = "{{ route('peminjamanmapel.store') }}";
                const data = new FormData(this);

                if (kode !== "") {
                    data.append("_method", "PUT");
                    url = `/peminjamanmapel/${kode}`;
                }

                const successCallback = function(response) {
                    setButtonLoadingState("#saveData .btn.btn-success", false);
                    handleSuccess(response, "peminjaman-table", "createModal");
                };

                const errorCallback = function(error) {
                    setButtonLoadingState("#saveData .btn.btn-success", false);
                    handleValidationErrors(error, "saveData", ['id_siswa', 'id_mapel',
                        "kode", "tanggal_mulai", "tanggal_selesai", "status"
                    ]);
                };

                ajaxCall(url, "POST", data, successCallback, errorCallback);
            });
            $("#saveMassData").submit(function(e) {
                setButtonLoadingState("#saveMassData .btn.btn-success", true);
                e.preventDefault();
                const kode = $("#saveMassData #id").val();
                let url = "{{ route('peminjamanmapel.mass-insert') }}";
                const data = new FormData(this);

                if (kode !== "") {
                    data.append("_method", "PUT");
                    url = `/peminjamanmapel/${kode}`;
                }

                const successCallback = function(response) {
                    setButtonLoadingState("#saveMassData .btn.btn-success", false);
                    handleSuccess(response, "peminjaman-table", "createModal");
                };

                const errorCallback = function(error) {
                    setButtonLoadingState("#saveMassData .btn.btn-success", false);
                    handleValidationErrors(error, "saveMassData", ['id_siswa', 'id_mapel',
                        "kode", "tanggal_mulai", "tanggal_selesai", "status"
                    ]);
                };

                ajaxCall(url, "POST", data, successCallback, errorCallback);
            });
        });
    </script>
@endpush
