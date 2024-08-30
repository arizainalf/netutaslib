@extends('layouts.pdf')

@section('title', 'Peminjaman')

@push('style')
@endpush

@section('main')
    <div>
        <center>
            <u>
                <h3>Data Peminjaman</h3>
            </u>
        </center>
        <br>
        <table width="100%" border="1" cellpadding="2.5" cellspacing="0">
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th width="10%">Kode Pinjaman</th>
                    <th width="15%">Buku</th>
                    <th width="15%">Peminjam</th>
                    <th width="15%">Tanggal Pinjam</th>
                    <th width="15%">Tanggal Tanggal Selesai</th>
                    <th width="15%">Status Pinjaman</th>
                    <th width="10%">Admin</th>
                </tr>
            </thead>
            <tbody valign="top">
                @forelse ($loans as $loan)
                    <tr>
                        <td style="text-align: center;">{{ $loop->iteration }}</td>
                        <td>{{ $loan->kode }}</td>
                        <td>{{ $loan->book->judul }}</td>
                        <td>{{ $loan->member->nama }}</td>
                        <td>{{ $loan->tanggal_mulai }}</td>
                        <td>{{ $loan->tanggal_selesai }}</td>
                        <td> <?php if ($loan->status == '0') {
                            echo '>Dipinjam';
                        } else {
                            echo 'Dikembalikan';
                        } ?></td>
                        <td>{{ $loan->user->nama }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" align="center">Data @yield('title') Kosong</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection

@push('scripts')
@endpush
