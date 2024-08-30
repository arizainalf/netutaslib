@extends('layouts.app')

@section('title', 'Kunjungan')

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
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="tanggal_mulai" class="form-label">Tanggal Mulai</label>
                                    <input type="date" name="tanggal_mulai" id="tanggal_mulai"
                                        value="{{ date('Y-m-d') }}" class="form-control">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="tanggal_selesai" class="form-label">Tanggal Selesai</label>
                                    <input type="date" name="tanggal_selesai" id="tanggal_selesai"
                                        value="{{ date('Y-m-d') }}" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <a id="downloadPdf" class="btn btn-sm px-3 btn-danger mr-1" target="_blank"><i
                                    class="fas fa-file-pdf mr-2"></i>Pdf</a>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped" id="visit-table" width="100%">
                                <thead>
                                    <tr>
                                        <th scope="col" width="5%">#</th>
                                        <th scope="col">Tanggal</th>
                                        <th scope="col">NISN</th>
                                        <th scope="col">NIPD</th>
                                        <th scope="col">Nama Pengunjung</th>
                                        <th scope="col">Deskripsi</th>
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
            datatableCall('visit-table', '{{ route('kunjungan.index') }}', [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex'
                },
                {
                    data: 'tanggal',
                    name: 'tanggal'
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
                    data: 'member',
                    name: 'member'
                },
                {
                    data: 'deskripsi',
                    name: 'deskripsi'
                },
            ]);

            renderData();
            $("#tanggal_mulai,#tanggal_selesai").on("change", function() {
                $("#visit-table").DataTable().ajax.reload();
                renderData();
            });

        });

        const renderData = () => {
            const downloadPdf =
                `/visit?mode=pdf&tanggal_mulai=${$("#tanggal_mulai").val()}&tanggal_selesai=${$("#tanggal_selesai").val()}`;
            $("#downloadPdf").attr("href", downloadPdf);
        }
    </script>
@endpush
