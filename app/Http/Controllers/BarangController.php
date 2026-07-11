<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Pagination\LengthAwarePaginator;

class BarangController extends Controller
{
    private function getApiUrl() { return env('API_URL'); }
    private function getToken() { return session('api_token'); }

    public function index(Request $request)
    {
        $response = Http::withToken($this->getToken())->get($this->getApiUrl() . '/barang', [
            'search' => $request->search,
            'page' => $request->page
        ]);

        if ($response->successful() && $response->json('success')) {
            $data = $response->json('data');
            
            $barangsData = $data['barangs'];
            $barangs = new LengthAwarePaginator(
                json_decode(json_encode($barangsData['data'])),
                $barangsData['total'],
                $barangsData['per_page'],
                $barangsData['current_page'],
                ['path' => $request->url(), 'query' => $request->query()]
            );

            $kategoris = json_decode(json_encode($data['kategoris']));

            return view('barang.index', compact('barangs', 'kategoris'));
        }

        return back()->with('error', 'Gagal mengambil data dari API.');
    }

    public function store(Request $request)
    {
        $response = Http::withToken($this->getToken())->post($this->getApiUrl() . '/barang', $request->all());

        if ($response->successful() && $response->json('success')) {
            return redirect()->route('barang.index')->with('success', $response->json('message'));
        }

        return back()->with('error', 'Gagal menambah data.');
    }

    public function update(Request $request, $id)
    {
        $response = Http::withToken($this->getToken())->put($this->getApiUrl() . '/barang/' . $id, $request->all());

        if ($response->successful() && $response->json('success')) {
            return redirect()->route('barang.index')->with('success', $response->json('message'));
        }

        return back()->with('error', 'Gagal mengupdate data.');
    }

    public function destroy($id)
    {
        $response = Http::withToken($this->getToken())->delete($this->getApiUrl() . '/barang/' . $id);

        if ($response->successful() && $response->json('success')) {
            return redirect()->route('barang.index')->with('success', $response->json('message'));
        }

        return back()->with('error', 'Gagal menghapus data.');
    }
}
