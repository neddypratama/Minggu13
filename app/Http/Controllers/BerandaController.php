<?php

namespace App\Http\Controllers;

use App\Charts\CountUser;
use App\Charts\PeramalanChart;
use App\Charts\ProfitBarang;
use Illuminate\Http\Request;

class BerandaController extends Controller
{
    public function index(CountUser $countUser, ProfitBarang $profitBarang, PeramalanChart $peramalanChart)
    {
        $breadcrumb = (object) [
            'title' => 'Selamat Datang',
            'list' => ['Home', 'Welcome']
        ];

        $activeMenu = 'dashboard';

        return view('halamanDepan.beranda', [
            'breadcrumb' => $breadcrumb,
            'activeMenu' => $activeMenu,
            'countUser' => $countUser->build(),
            'profitBarang' => $profitBarang->build(),
            'peramalanChart' => $peramalanChart->build()
        ]);
    }
}

