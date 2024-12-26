@extends('layouts.inpdf')

@section('title', 'Siswa')

@push('style')
@endpush

@section('main')
    <div>
        <center>
            <u>
                <h3>Data Siswa</h3>
            </u>
        </center>
        <br>
        <table width="100%" border="1" cellpadding="2.5" cellspacing="0">
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th width="20%">NISN</th>
                    <th width="20%">NIPD</th>
                    <th width="40%">Nama Lengkap</th>
                    <th width="15%">Jenis Kelamin</th>
                </tr>
            </thead>
            <tbody valign="top">
                @forelse ($members as $member)
                    <tr>
                        <td style="text-align: center;">{{ $loop->iteration }}</td>
                        <td>{{ $member->nisn }}</td>
                        <td>{{ $member->nipd }}</td>
                        <td>{{ $member->nama }}</td>
                        <td>{{ $member->jenis_kelamin }}</td>
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
