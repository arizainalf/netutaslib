<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title>@yield('title') - {{ config('app.name') }}</title>
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            line-height: 1.2;
            margin: 0;
            padding: 0;
        }

        h3 {
            margin: 2;
        }
    </style>
    @stack('style')
</head>

<body>
    <table width="100%" border="0" cellpadding="2.5" cellspacing="0">
        <tbody>
            <tr>
                <td>
                    {{-- <img width='120px' src="{{ generateBase64Image(public_path('images/logo-pemkot.png')) }}"
                        alt=""> --}}
                </td>
                <td>
                    <h3>PERPUSTAKAAN SMPN 7 TASIKMALAYA </h3>
                    <h3>KAMPUS MENGAJAR 7</h3>
                    <h3>SEKOLAHKU NYAMAN</h3>
                    <div>
                        <span>
                            Jl. Letnan Dadi Suryatman No.76 46131 Tasikmalaya Jawa Barat
                        </span>
                    </div>
                </td>
                {{-- <td width='20%' align="right"><img width='120px'
                        src="{{ generateBase64Image(public_path('images/logo-psc.png')) }}" alt=""> </td> --}}
            </tr>
        </tbody>
    </table>
    <hr style="height:1px;background-color:black;">
    @yield('main')

    @stack('scripts')

</body>

</html>
