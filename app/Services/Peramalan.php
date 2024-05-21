<?php

namespace App\Services;

class Peramalan
{
    public static function simpleMovingAverage($salesData, $windowSize)
    {
        $forecast = [];

        $totalSales = count($salesData);
        if ($totalSales < $windowSize) {
            return $forecast;
        }

        for ($i = $windowSize; $i <= $totalSales; $i++) {
            $windowSales = array_slice($salesData, $i - $windowSize, $windowSize);
            $average = array_sum($windowSales) / $windowSize;
            $forecast[date('Y-m-d', strtotime("+{$i} days"))] = $average;
        }

        return $forecast;
    }
}
