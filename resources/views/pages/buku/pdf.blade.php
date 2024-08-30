@extends('layouts.pdf')

@section('title', 'Book')

@push('style')
@endpush

@section('main')
    <div>
        <center>
            <u>
                <h3>Data Buku</h3>
            </u>
        </center>
        <br>
        <table width="100%" border="1" cellpadding="2.5" cellspacing="0">
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th width="10%">Kategori</th>
                    <th width="25%">Judul Buku</th>
                    <th width="15%">Penulis</th>
                    <th width="15%">Penerbit</th>
                    <th width="15%">Tahun Terbit</th>
                    <th width="15%">Jumlah Buku</th>
                </tr>
            </thead>
            <tbody valign="top">
                @forelse ($books as $book)
                    <tr>
                        <td style="text-align: center;">{{ $loop->iteration }}</td>
                        <td>{{ $book->category->nama }}</td>
                        <td>{{ $book->judul }}</td>
                        <td>{{ $book->penulis }}</td>
                        <td>{{ $book->penerbit }}</td>
                        <td>{{ $book->tahun }}</td>
                        <td>{{ $book->stok }}</td>
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
