<?php
function formatRupiah($angka){
    $rupiah = number_format($angka, 0, ',', '.');
    return "Rp. $rupiah";
}
    function formatId($date, $format = 'd M Y')
    {
        setlocale(LC_TIME, 'id_ID.utf8');
        return \Carbon\Carbon::parse($date)->translatedFormat($format);
    }
function dateId($tanggal)
{
    // Pastikan tanggal dalam format yang diinginkan untuk diolah oleh Carbon
    $tanggal = \Carbon\Carbon::parse($tanggal);

    // Definisikan nama-nama hari dan bulan dalam bahasa Indonesia
    $namaHari = [
        'Sunday' => 'Minggu',
        'Monday' => 'Senin',
        'Tuesday' => 'Selasa',
        'Wednesday' => 'Rabu',
        'Thursday' => 'Kamis',
        'Friday' => 'Jumat',
        'Saturday' => 'Sabtu'
    ];

    $namaBulan = [
        'January' => 'Januari',
        'February' => 'Februari',
        'March' => 'Maret',
        'April' => 'April',
        'May' => 'Mei',
        'June' => 'Juni',
        'July' => 'Juli',
        'August' => 'Agustus',
        'September' => 'September',
        'October' => 'Oktober',
        'November' => 'November',
        'December' => 'Desember'
    ];

    $hari = $namaHari[$tanggal->format('l')];
    $bulan = $namaBulan[$tanggal->format('F')];
    $tanggalFormat = $tanggal->format('d');
    $tahun = $tanggal->format('Y');

    return "$hari, $tanggalFormat $bulan $tahun";
}
?>
