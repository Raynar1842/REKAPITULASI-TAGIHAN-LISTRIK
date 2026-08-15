<?php

namespace App\Http\Controllers;

use App\Models\Warga;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TagihanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $wargas = Warga::orderBy('no', 'asc')->get();
        return response()->json([
            'status' => 'success',
            'data' => $wargas
        ]);
    }

    /**
     * Toggle payment status of a resident (Admin & Petugas Only).
     */
    public function toggle(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (!$user || !$user->canManagePayments()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Akses Ditolak. Hanya Petugas atau Admin yang dapat mengubah status pembayaran warga.'
            ], 403);
        }

        $validated = $request->validate([
            'no' => 'required|integer',
            'lunas' => 'required|boolean',
        ]);

        $warga = Warga::where('no', $validated['no'])->first();
        if (!$warga) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data warga tidak ditemukan.'
            ], 404);
        }

        $warga->lunas = $validated['lunas'];
        $warga->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Status pembayaran berhasil diperbarui.',
            'data' => $warga
        ]);
    }

    /**
     * Reset all payment status to false (Admin & Petugas Only).
     */
    public function reset()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (!$user || !$user->canManagePayments()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Akses Ditolak. Hanya Petugas atau Admin yang dapat mereset pembayaran.'
            ], 403);
        }

        Warga::query()->update(['lunas' => false]);

        return response()->json([
            'status' => 'success',
            'message' => 'Semua status pembayaran warga berhasil direset.'
        ]);
    }

    /**
     * Sync bulk data.
     */
    public function sync(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (!$user || !$user->canManagePayments()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Akses Ditolak.'
            ], 403);
        }

        $validated = $request->validate([
            'warga' => 'required|array',
            'warga.*.no' => 'required|integer',
            'warga.*.nama' => 'required|string',
            'warga.*.rek' => 'required|string',
            'warga.*.tagihan' => 'required|integer',
            'warga.*.lunas' => 'required|boolean',
        ]);

        foreach ($validated['warga'] as $item) {
            Warga::updateOrCreate(
                ['no' => $item['no']],
                [
                    'nama' => $item['nama'],
                    'rek' => $item['rek'],
                    'tagihan' => $item['tagihan'],
                    'lunas' => $item['lunas'],
                ]
            );
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Data berhasil disinkronkan.'
        ]);
    }

    /**
     * Store a newly created resource (Admin & Petugas Only).
     */
    public function store(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (!$user || !$user->canManagePayments()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Akses Ditolak. Hanya Petugas atau Admin yang dapat menambah data warga.'
            ], 403);
        }

        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'rek' => 'required|string|max:50',
            'tagihan' => 'required|integer|min:0',
            'lunas' => 'boolean',
        ]);

        $maxNo = Warga::max('no') ?? 0;
        $validated['no'] = $maxNo + 1;
        $validated['lunas'] = $request->boolean('lunas', false);

        $warga = Warga::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Warga baru berhasil ditambahkan.',
            'data' => $warga
        ]);
    }

    /**
     * Update specified resource in storage.
     */
    public function update(Request $request, $no)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (!$user || !$user->canManagePayments()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Akses Ditolak.'
            ], 403);
        }

        $warga = Warga::where('no', $no)->firstOrFail();

        $validated = $request->validate([
            'nama' => 'sometimes|required|string|max:255',
            'rek' => 'sometimes|required|string|max:50',
            'tagihan' => 'sometimes|required|integer|min:0',
            'lunas' => 'sometimes|boolean',
        ]);

        $warga->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Data warga berhasil diperbarui.',
            'data' => $warga
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($no)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (!$user || !$user->canManagePayments()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Akses Ditolak.'
            ], 403);
        }

        $warga = Warga::where('no', $no)->firstOrFail();
        $warga->delete();

        // Reorder remaining numbers
        $wargas = Warga::orderBy('no', 'asc')->get();
        foreach ($wargas as $index => $w) {
            $w->no = $index + 1;
            $w->save();
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Warga berhasil dihapus.'
        ]);
    }
}
