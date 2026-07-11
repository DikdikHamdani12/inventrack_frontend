<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Pagination\LengthAwarePaginator;

class BarangKeluarController extends Controller
{
    private function getApiUrl() { return env('API_URL'); }
    private function getToken() { return session('api_token'); }

    public function index(Request $request)
    {
        $response = Http::withToken($this->getToken())->get($this->getApiUrl() . '/barang-keluar', [
            'search' => $request->search,
            'page' => $request->page
        ]);

        if ($response->successful() && $response->json('success')) {
            $data = $response->json('data');
            
            $barangKeluarsData = $data['barangKeluars'];
            $barangKeluars = new LengthAwarePaginator(
                json_decode(json_encode($barangKeluarsData['data'])),
                $barangKeluarsData['total'],
                $barangKeluarsData['per_page'],
                $barangKeluarsData['current_page'],
                ['path' => $request->url(), 'query' => $request->query()]
            );

            $barangs = json_decode(json_encode($data['barangs']));

            return view('barang_keluar.index', compact('barangKeluars', 'barangs'));
        }

        return back()->with('error', 'Gagal mengambil data dari API.');
    }

    public function store(Request $request)
    {
        $response = Http::withToken($this->getToken())->post($this->getApiUrl() . '/barang-keluar', $request->all());

        if ($response->successful()) {
            if ($response->json('success')) {
                return redirect()->route('barang-keluar.index')->with('success', $response->json('message'));
            } else {
                return back()->withErrors(['jumlah' => $response->json('message')])->withInput();
            }
        }
        
        // Handle 400 Bad Request if stok not enough
        if ($response->status() === 400) {
             return back()->withErrors(['jumlah' => $response->json('message')])->withInput();
        }

        return back()->with('error', 'Gagal mencatat transaksi.');
    }

    public function destroy($id)
    {
        $response = Http::withToken($this->getToken())->delete($this->getApiUrl() . '/barang-keluar/' . $id);

        if ($response->successful() && $response->json('success')) {
            return redirect()->route('barang-keluar.index')->with('success', $response->json('message'));
        }

        return back()->with('error', 'Gagal membatalkan transaksi.');
    }
}
