<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class LaporanController extends Controller
{
    private function getApiUrl() { return env('API_URL'); }
    private function getToken() { return session('api_token'); }

    public function index(Request $request)
    {
        $response = Http::withToken($this->getToken())->get($this->getApiUrl() . '/laporan', [
            'bulan' => $request->bulan,
            'tahun' => $request->tahun
        ]);

        if ($response->successful() && $response->json('success')) {
            $data = json_decode(json_encode($response->json('data')));
            
            $barangMasuks = collect($data->barangMasuks);
            $barangKeluars = collect($data->barangKeluars);
            $bulan = $data->bulan;
            $tahun = $data->tahun;

            return view('laporan.index', compact('barangMasuks', 'barangKeluars', 'bulan', 'tahun'));
        }

        return back()->with('error', 'Gagal mengambil laporan dari API.');
    }

    public function print(Request $request)
    {
        // For print, we can just fetch the data the same way and pass to print view
        $response = Http::withToken($this->getToken())->get($this->getApiUrl() . '/laporan', [
            'bulan' => $request->bulan,
            'tahun' => $request->tahun
        ]);

        if ($response->successful() && $response->json('success')) {
            $data = json_decode(json_encode($response->json('data')));
            
            $barangMasuks = collect($data->barangMasuks);
            $barangKeluars = collect($data->barangKeluars);
            $bulan = $data->bulan;
            $tahun = $data->tahun;

            return view('laporan.print', compact('barangMasuks', 'barangKeluars', 'bulan', 'tahun'));
        }

        return back()->with('error', 'Gagal memproses data cetak.');
    }
}
