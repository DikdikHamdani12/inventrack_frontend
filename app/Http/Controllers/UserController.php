<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Pagination\LengthAwarePaginator;

class UserController extends Controller
{
    private function getApiUrl() { return env('API_URL'); }
    private function getToken() { return session('api_token'); }

    public function index(Request $request)
    {
        $response = Http::withToken($this->getToken())->get($this->getApiUrl() . '/user', [
            'search' => $request->search,
            'page' => $request->page
        ]);

        if ($response->successful() && $response->json('success')) {
            $data = $response->json('data');
            
            $usersData = $data['users'];
            $users = new LengthAwarePaginator(
                json_decode(json_encode($usersData['data'])),
                $usersData['total'],
                $usersData['per_page'],
                $usersData['current_page'],
                ['path' => $request->url(), 'query' => $request->query()]
            );

            return view('users.index', compact('users'));
        }

        // Catch 403 Forbidden from API
        if ($response->status() === 403) {
            abort(403, 'Akses ditolak.');
        }

        return back()->with('error', 'Gagal mengambil data dari API.');
    }

    public function store(Request $request)
    {
        $response = Http::withToken($this->getToken())->post($this->getApiUrl() . '/user', $request->all());

        if ($response->successful() && $response->json('success')) {
            return redirect()->route('user.index')->with('success', $response->json('message'));
        }

        return back()->with('error', 'Gagal menambah data.');
    }

    public function update(Request $request, $id)
    {
        $response = Http::withToken($this->getToken())->put($this->getApiUrl() . '/user/' . $id, $request->all());

        if ($response->successful() && $response->json('success')) {
            return redirect()->route('user.index')->with('success', $response->json('message'));
        }

        return back()->with('error', 'Gagal mengupdate data.');
    }

    public function destroy($id)
    {
        $response = Http::withToken($this->getToken())->delete($this->getApiUrl() . '/user/' . $id);

        if ($response->successful()) {
            if ($response->json('success')) {
                return redirect()->route('user.index')->with('success', $response->json('message'));
            } else {
                return redirect()->route('user.index')->with('error', $response->json('message'));
            }
        }

        return back()->with('error', 'Gagal menghapus data.');
    }
}
