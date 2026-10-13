<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Obat;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Database\QueryException;

class ObatController extends Controller
{
    public function index(): View
    {
        $obats = Obat::orderByDesc('id')->paginate(10);

        return view('admin.obat.index', compact('obats'));
    }

    public function create(): View
    {
        return view('admin.obat.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate(
            [
                'nama_obat' => 'required|string|max:255',
                'kemasan' => 'required|string|max:255',
                'harga' => 'required|numeric|min:0',
            ],
            [
                'nama_obat.required' => 'Nama obat wajib diisi.',
                'kemasan.required' => 'Kemasan wajib diisi.',
                'harga.required' => 'Harga obat wajib diisi.',
                'harga.numeric' => 'Harga harus berupa angka.',
                'harga.min' => 'Harga tidak boleh negatif.',
            ]
        );

        Obat::create($validated);

        return redirect()
            ->route('obat.index')
            ->with('success', 'Data Berhasil disimpan');
    }

    public function edit(Obat $obat): View
    {
        return view('admin.obat.edit', compact('obat'));
    }

    public function update(
        Request $request,
        Obat $obat
    ): RedirectResponse {
        $validated = $request->validate(
            [
                'nama_obat' => 'required|string|max:255',
                'kemasan' => 'required|string|max:255',
                'harga' => 'required|numeric|min:0',
            ],
            [
                'nama_obat.required' => 'Nama obat wajib diisi.',
                'kemasan.required' => 'Kemasan wajib diisi.',
                'harga.required' => 'Harga obat wajib diisi.',
                'harga.numeric' => 'Harga harus berupa angka.',
                'harga.min' => 'Harga tidak boleh negatif.',
            ]
        );

        $obat->update($validated);

        return redirect()
            ->route('obat.index')
            ->with('success', 'Data Berhasil diperbarui');
    }

    public function destroy(Obat $obat): RedirectResponse
    {
        try {
            $obat->delete();

            return redirect()
                ->route('obat.index')
                ->with('success', 'Data Berhasil dihapus');

        } catch (QueryException $e) {
            // Jika gagal karena foreign key, tampilkan pesan.
            if ($e->getCode() !== '23000') {
                throw $e;
            }

            return redirect()
                ->route('obat.index')
                ->with(
                    'error',
                    'Obat tidak dapat dihapus karena sedang digunakan.'
                );
        }
    }
}
