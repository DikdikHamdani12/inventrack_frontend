<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Pagination\LengthAwarePaginator;

class BarangMasukController extends Controller
{
    private function getApiUrl() { return env('API_URL'); }
    private function getToken() { return session('api_token'); }

    public function index(Request $request)
    {
        $response = Http::withToken($this->getToken())->get($this->getApiUrl() . '/barang-masuk', [
            'search' => $request->search,
            'page' => $request->page
        ]);

        if ($response->successful() && $response->json('success')) {
            $data = $response->json('data');
            
            $barangMasuksData = $data['barangMasuks'];
            $barangMasuks = new LengthAwarePaginator(
                json_decode(json_encode($barangMasuksData['data'])),
                $barangMasuksData['total'],
                $barangMasuksData['per_page'],
                $barangMasuksData['current_page'],
                ['path' => $request->url(), 'query' => $request->query()]
            );

            $barangs = json_decode(json_encode($data['barangs']));
            $kategoris = json_decode(json_encode($data['kategoris']));

            return view('barang_masuk.index', compact('barangMasuks', 'barangs', 'kategoris'));
        }

        return back()->with('error', 'Gagal mengambil data dari API.');
    }

    public function store(Request $request)
    {
        $response = Http::withToken($this->getToken())->post($this->getApiUrl() . '/barang-masuk', $request->all());

        if ($response->successful() && $response->json('success')) {
            return redirect()->route('barang-masuk.index')->with('success', $response->json('message'));
        }

        return back()->with('error', 'Gagal menambah data transaksi.');
    }

    public function destroy($id)
    {
        $response = Http::withToken($this->getToken())->delete($this->getApiUrl() . '/barang-masuk/' . $id);

        if ($response->successful() && $response->json('success')) {
            return redirect()->route('barang-masuk.index')->with('success', $response->json('message'));
        }

        return back()->with('error', 'Gagal membatalkan transaksi.');
    }
}
