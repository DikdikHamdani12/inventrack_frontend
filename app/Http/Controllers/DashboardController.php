<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class DashboardController extends Controller
{
    private function getApiUrl() { return env('API_URL'); }
    private function getToken() { return session('api_token'); }

    public function index()
    {
        $response = Http::withToken($this->getToken())->get($this->getApiUrl() . '/dashboard');
        
        if ($response->successful() && $response->json('success')) {
            $data = json_decode(json_encode($response->json('data')));
            
            $totalBarang = $data->totalBarang;
            $stokMenipis = $data->stokMenipis;
            $totalMasuk = $data->totalMasuk;
            $totalKeluar = $data->totalKeluar;
            $totalMasukNominal = $data->totalMasukNominal;
            $totalKeluarNominal = $data->totalKeluarNominal;
            $barangsMenipis = $data->barangsMenipis;
            $runningSaldo = $data->runningSaldo;
            
            // Blade needs chart data as arrays or collections
            $chartMasuk = collect($data->chartMasuk);
            $chartKeluar = collect($data->chartKeluar);
            
            return view('dashboard', compact(
                'totalBarang', 'stokMenipis', 'totalMasuk', 'totalKeluar',
                'totalMasukNominal', 'totalKeluarNominal',
                'barangsMenipis', 'chartMasuk', 'chartKeluar', 'runningSaldo'
            ));
        }

        return redirect('/login')->withErrors(['error' => 'Gagal mengambil data dashboard dari API.']);
    }
}
