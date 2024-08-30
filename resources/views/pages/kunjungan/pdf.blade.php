@extends('layouts.pdf')

@section('title', 'Kunjungan')

@push('style')
@endpush

@section('main')
    <div>
        <center>
            <u>
                <h3>Data Kunjungan Perpustakaan {{ formatTanggal($tanggalMulai, 'd/m/Y') }} -
                    {{ formatTanggal($tanggalSelesai, 'd/m/Y') }}</h3>
            </u>
        </center>
        <br>
        <table width="100%" border="1" cellpadding="2.5" cellspacing="0">
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th width="25%">Nisn</th>
                    <th width="25%">Nipd</th>
                    <th width="15%">Nama</th>
                    <th width="30%">Deskripsi Kunjungan</th>
                </tr>
            </thead>
            <tbody valign="top">
                @forelse ($visits as $visit)
                    <tr>
                        <td style="text-align: center;">{{ $loop->iteration }}</td>
                        <td>{{ $visit->member->nisn }}</td>
                        <td>{{ $visit->member->nipd }}</td>
                        <td>{{ $visit->member->nama }}</td>
                        <td>{{ $visit->deskripsi }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" align="center">Data @yield('title') Kosong</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection

@push('scripts')
@endpush
