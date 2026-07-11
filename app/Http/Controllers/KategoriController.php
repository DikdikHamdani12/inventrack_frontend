<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Pagination\LengthAwarePaginator;

class KategoriController extends Controller
{
    private function getApiUrl() { return env('API_URL'); }
    private function getToken() { return session('api_token'); }

    public function index(Request $request)
    {
        $response = Http::withToken($this->getToken())->get($this->getApiUrl() . '/kategori', [
            'search' => $request->search,
            'page' => $request->page
        ]);

        if ($response->successful() && $response->json('success')) {
            $data = $response->json('data');
            
            $kategorisData = $data['kategoris'];
            $kategoris = new LengthAwarePaginator(
                json_decode(json_encode($kategorisData['data'])),
                $kategorisData['total'],
                $kategorisData['per_page'],
                $kategorisData['current_page'],
                ['path' => $request->url(), 'query' => $request->query()]
            );

            return view('kategori.index', compact('kategoris'));
        }

        return back()->with('error', 'Gagal mengambil data dari API.');
    }

    public function store(Request $request)
    {
        $response = Http::withToken($this->getToken())->post($this->getApiUrl() . '/kategori', $request->all());

        if ($response->successful() && $response->json('success')) {
            return redirect()->route('kategori.index')->with('success', $response->json('message'));
        }

        return back()->with('error', 'Gagal menambah data.');
    }

    public function update(Request $request, $id)
    {
        $response = Http::withToken($this->getToken())->put($this->getApiUrl() . '/kategori/' . $id, $request->all());

        if ($response->successful() && $response->json('success')) {
            return redirect()->route('kategori.index')->with('success', $response->json('message'));
        }

        return back()->with('error', 'Gagal mengupdate data.');
    }

    public function destroy($id)
    {
        $response = Http::withToken($this->getToken())->delete($this->getApiUrl() . '/kategori/' . $id);

        if ($response->successful()) {
            if ($response->json('success')) {
                return redirect()->route('kategori.index')->with('success', $response->json('message'));
            } else {
                return redirect()->route('kategori.index')->with('error', $response->json('message'));
            }
        }

        return back()->with('error', 'Gagal menghapus data.');
    }
}
