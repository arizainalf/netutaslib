@extends('layouts.pdf')

@section('title', 'Category')

@push('style')
@endpush

@section('main')
    <div>
        <center>
            <u>
                <h3>Data Kategori</h3>
            </u>
        </center>
        <br>
        <table width="100%" border="1" cellpadding="2.5" cellspacing="0">
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th width="95%">Nama Kategori</th>
                </tr>
            </thead>
            <tbody valign="top">
                @forelse ($categories as $category)
                    <tr>
                        <td style="text-align: center;">{{ $loop->iteration }}</td>
                        <td>{{ $category->nama }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="2" align="center">Data @yield('title') kosong</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection

@push('scripts')
@endpush
