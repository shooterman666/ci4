<?php

if (!function_exists('hitung_biaya_admin')) {
    function hitung_biaya_admin($total_harga)
    {
        $tarif = $total_harga > 20000000 ? 0.0075 : 0.005;
        return $total_harga * $tarif;
    }
}

if (!function_exists('hitung_diskon_kupon')) {
    function hitung_diskon_kupon($total_harga, $kupon_code)
    {
        $kuponList = [
            'HEMAT' => 0.15,
            'SUPER' => 0.20,
        ];

        $kode = strtoupper(trim($kupon_code));
        $persen = $kuponList[$kode] ?? 0;

        return [
            'kode' => $persen > 0 ? $kode : null,
            'diskon' => $total_harga * $persen,
            'persen' => $persen,
        ];
    }
}

if (!function_exists('hitung_cashback')) {
    function hitung_cashback($total_harga)
    {
        if ($total_harga > 10000000) {
            return $total_harga * 0.02;
        }
        return 0;
    }
}
