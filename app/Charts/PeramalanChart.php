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
        $salesData = [];
        foreach ($transaksi as $detail) {
            $salesData[$detail->penjualan->penjualan_tanggal] = $detail->jumlah;
        }

        // Lakukan peramalan dengan metode rata-rata bergerak sederhana
        $forecast = Peramalan::simpleMovingAverage($salesData, 1);

        // Siapkan data untuk chart
        $dates = array_keys($forecast);
        $values = array_values($forecast);

        $formattedValues = array_map(function($value) {
            return number_format($value, 2, '.', '');
        }, $values);

        return $this->chart->lineChart()
            ->setTitle('Peramalan Penjualan')
            ->setSubtitle('Prediksi berdasarkan data historis')
            ->addData('Penjualan', $formattedValues)
            ->setXAxis($dates)
            ->setColors(['#FF5733'])
            ->setMarkers(['#FF5733'], 7, 10);
    }
}
