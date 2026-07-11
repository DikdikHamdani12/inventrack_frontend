<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Pagination\LengthAwarePaginator;

class SaldoController extends Controller
{
    private function getApiUrl() { return env('API_URL'); }
    private function getToken() { return session('api_token'); }

    public function index(Request $request)
    {
        $response = Http::withToken($this->getToken())->get($this->getApiUrl() . '/saldo');

        if ($response->successful() && $response->json('success')) {
            $data = $response->json('data');
            
            $topups = $data['topups'];
            $runningSaldo = $data['runningSaldo'];
            $totalTopup = $data['totalTopup'];
            $totalMasuk = $data['totalMasuk'];
            $totalKeluar = $data['totalKeluar'];
            $ledger = $data['ledger'];

            return view('saldo.index', compact('topups', 'runningSaldo', 'totalTopup', 'totalMasuk', 'totalKeluar', 'ledger'));
        }

        if ($response->status() === 403) {
            abort(403, 'Akses ditolak.');
        }

        return back()->with('error', 'Gagal mengambil data dari API.');
    }

    public function store(Request $request)
    {
        $response = Http::withToken($this->getToken())->post($this->getApiUrl() . '/saldo', $request->all());

        if ($response->successful() && $response->json('success')) {
            return redirect()->route('saldo.index')->with('success', $response->json('message'));
        }

        if ($response->status() === 403) {
            abort(403, 'Akses ditolak.');
        }

        return back()->with('error', 'Gagal mendepositkan saldo.');
    }

    public function destroy($id)
    {
        $response = Http::withToken($this->getToken())->delete($this->getApiUrl() . '/saldo/' . $id);

        if ($response->successful() && $response->json('success')) {
            return redirect()->route('saldo.index')->with('success', $response->json('message'));
        }

        if ($response->status() === 403) {
            abort(403, 'Akses ditolak.');
        }

        return back()->with('error', 'Gagal menghapus top up saldo.');
    }
}
