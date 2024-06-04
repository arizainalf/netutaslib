@extends('layouts.app')

@section('title', 'General Dashboard')

@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('library/jqvmap/dist/jqvmap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('library/summernote/dist/summernote-bs4.min.css') }}">
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Dashboard {{ getGreeting() }}</h1>
            </div>
            <div class="row">
                <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-primary">
                            <i class="fas fa-columns"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Kategori</h4>
                            </div>
                            <div class="card-body">
                                {{ $category }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-danger">
                            <i class="fas fa-book"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Buku</h4>
                            </div>
                            <div class="card-body">
                                {{ $books }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-warning">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Member</h4>
                            </div>
                            <div class="card-body">
                                {{ $members }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-warning">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Peminjaman</h4>
                            </div>
                            <div class="card-body">
                                {{ $loans }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-warning">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Pengembalian</h4>
                            </div>
                            <div class="card-body">
                                {{ $pengembalian }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-warning">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Kunjungan</h4>
                            </div>
                            <div class="card-body">
                                {{ $berkunjung }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Grafik Pendatang</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="bulan_filter" class="form-label">Bulan</label>
                                        <select name="bulan_filter" id="bulan_filter" class="form-control">
                                            @foreach (bulan() as $key => $value)
                                                <option value="{{ $key + 1 }}"
                                                    {{ $key + 1 == date('m') ? 'selected' : '' }}>
                                                    {{ $value }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="tahun_filter" class="form-label">Tahun</label>
                                        <select name="tahun_filter" id="tahun_filter" class="form-control">
                                            @for ($i = now()->year; $i >= now()->year - 4; $i--)
                                                <option value="{{ $i }}"
                                                    {{ $i == date('Y') ? 'selected' : '' }}>
                                                    {{ $i }}
                                                </option>
                                            @endfor
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <canvas id="myChart" height="182"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@push('scripts')
    <!-- JS Libraries -->
    <script src="{{ asset('library/chart.js/dist/Chart.min.js') }}"></script>

    <!-- Page Specific JS File -->
    <script src="{{ asset('js/page/index-0.js') }}"></script>
    <script>
        $(document).ready(function() {
            renderData();

            $("#bulan_filter, #tahun_filter").on("change", function() {
                renderData();
            });
        });

        const renderData = () => {
            const successCallback = function(response) {
                createChart(response.data.labels, response.data.berkunjung, response.data.peminjaman, response.data
                    .pengembalian);
            };

            const errorCallback = function(error) {
                console.error(error);
            };

            const url = `/admin?bulan=${$("#bulan_filter").val()}&tahun=${$("#tahun_filter").val()}`;

            ajaxCall(url, "GET", null, successCallback, errorCallback);
        };
    </script>
@endpush
