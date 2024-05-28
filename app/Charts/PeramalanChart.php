<?php

// app/Charts/PeramalanChart.php

namespace App\Charts;

use App\Models\TransaksiDetailModel;
use App\Services\Peramalan;
use ArielMejiaDev\LarapexCharts\LarapexChart;

class PeramalanChart
{
    protected $chart;

    public function __construct(LarapexChart $chart)
    {
        $this->chart = $chart;
    }

    public function build(): \ArielMejiaDev\LarapexCharts\LineChart
    {
        // Ambil data penjualan dari database
        $transaksi = TransaksiDetailModel::with('penjualan')->get();
        $salesData = $transaksi->pluck('jumlah', 'penjualan.penjualan_tanggal')->toArray();
        // $salesData = $transaksi->pluck('penjualan.penjualan_tanggal')->toArray();

        // Lakukan peramalan dengan metode rata-rata bergerak sederhana
        $forecast = Peramalan::simpleMovingAverage($salesData, 7);

        // Siapkan data untuk chart
        $dates = array_keys($forecast);
        $values = array_values($forecast);

        return $this->chart->lineChart()
            ->setTitle('Peramalan Penjualan')
            ->setSubtitle('Prediksi berdasarkan data historis')
            ->addData('Penjualan', $values)
            ->setXAxis($dates)
            ->setColors(['#FF5733'])
            ->setMarkers(['#FF5733'], 7, 10);
    }
}
