<table>
    <thead>
        <tr>
            <th height="20" colspan="6" style="border: 1px solid black; text-align: center; font-weight: bold;">
                Data Siswa Perpustakaan SMPN 7 Tasikmalaya</th>
        </tr>
        <tr>
            <th width="5" style="border: 1px solid black; text-align: center; font-weight: bold;">No</th>
            <th width="45" style="border: 1px solid black; text-align: center; font-weight: bold;">NISN</th>
            <th width="9" style="border: 1px solid black; text-align: center; font-weight: bold;">NIPD</th>
            <th width="10" style="border: 1px solid black; text-align: center; font-weight: bold;">Nama</th>
            <th width="15" style="border: 1px solid black; text-align: center; font-weight: bold;">Jenis Kelamin
            </th>

        </tr>
    </thead>
    <tbody>
        @forelse ($members as $member)
            <tr>
                <td style="border: 1px solid black; text-align: center;">{{ $loop->iteration }}</td>
                <td style="border: 1px solid black;">{{ $member->nisn }}</td>
                <td style="border: 1px solid black;">{{ $member->nipd }}</td>
                <td style="border: 1px solid black;">{{ $member->nama }}</td>
                <td style="border: 1px solid black;">{{ $member->jenis_kelamin }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="5" align="center">Data @yield('title') kosong</td>
            </tr>
        @endforelse
    </tbody>
</table>
