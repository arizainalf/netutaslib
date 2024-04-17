<?php

use Carbon\Carbon;

if (!function_exists('statusBadge')) {
    function statusBadge($status)
    {
        if ($status == '0') {
            return "<span class='badge badge-warning'><i class='far fa-clock mr-1'></i> Dipinjam</span>";
        } elseif ($status == '1') {
            return "<span class='badge badge-success'><i class='far fa-check-circle mr-1'></i>Dikembalikan</span>";
        }
    }
}

if (!function_exists('generateBase64Image')) {
    function generateBase64Image($imagePath)
    {
        if (file_exists($imagePath)) {
            $data = file_get_contents($imagePath);
            $type = pathinfo($imagePath, PATHINFO_EXTENSION);
            $base64Image = 'data:image/' . $type . ';base64,' . base64_encode($data);

            return $base64Image;
        } else {
            return '';
        }
    }
}

if (!function_exists('formatTanggal')) {
    function formatTanggal($tanggal = null, $format = 'l, j F Y')
    {
        $parsedDate = Carbon::parse($tanggal)->locale('id')->settings(['formatFunction' => 'translatedFormat']);
        return $parsedDate->format($format);
    }
}

if (!function_exists('getGreeting')) {
    function getGreeting()
    {
        $hour = now()->hour;

        if ($hour >= 5 && $hour < 12) {
            return 'Selamat Pagi';
        } elseif ($hour >= 12 && $hour < 17) {
            return 'Selamat Siang';
        } elseif ($hour >= 17 && $hour < 20) {
            return 'Selamat Sore';
        } else {
            return 'Selamat Malam';
        }
    }
}

if (!function_exists('bulan')) {
    function bulan()
    {
        return [
            'Januari',
            'Februari',
            'Maret',
            'April',
            'Mei',
            'Juni',
            'Juli',
            'Agustus',
            'September',
            'Oktober',
            'November',
            'Desember',
        ];
    }
}
