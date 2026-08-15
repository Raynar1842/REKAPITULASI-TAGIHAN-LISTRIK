<?php

namespace App\Http\Controllers;

use App\Models\AppSetting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class SettingController extends Controller
{
    /**
     * Get all app settings.
     */
    public function index()
    {
        $settings = [
            'app_title' => AppSetting::get('app_title', 'REKAPITULASI TAGIHAN LISTRIK'),
            'app_subtitle' => AppSetting::get('app_subtitle', 'FORMADIKA'),
            'app_address' => AppSetting::get('app_address', 'Sekretariat: Kentolan Lor, Guwosari, Pajangan, Bantul'),
            'app_periode' => AppSetting::get('app_periode', 'AGUSTUS 2026'),
            'google_sheets_url' => AppSetting::get('google_sheets_url', ''),
        ];

        return response()->json([
            'status' => 'success',
            'data' => $settings,
            'user' => [
                'name' => Auth::user()->name,
                'email' => Auth::user()->email,
                'role' => Auth::user()->role,
                'is_admin' => Auth::user()->isAdmin(),
            ]
        ]);
    }

    /**
     * Update application identity & period settings (Admin Only).
     */
    public function update(Request $request)
    {
        if (!Auth::user()->isAdmin()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Akses Ditolak. Hanya Admin yang dapat mengubah Pengaturan Aplikasi.'
            ], 403);
        }

        $validated = $request->validate([
            'app_title' => 'required|string|max:255',
            'app_subtitle' => 'required|string|max:255',
            'app_address' => 'required|string|max:255',
            'app_periode' => 'required|string|max:255',
            'google_sheets_url' => 'nullable|string',
        ]);

        foreach ($validated as $key => $value) {
            AppSetting::set($key, $value ?? '');
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Pengaturan aplikasi berhasil diperbarui.',
            'data' => $validated
        ]);
    }

    /**
     * Update admin user credentials (Username & Password) (Admin Only).
     */
    public function updateAccount(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();

        if (!$user->isAdmin()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Akses Ditolak. Hanya Admin yang memiliki akses.'
            ], 403);
        }

        $validated = $request->validate([
            'username' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'current_password' => 'required|string',
            'new_password' => 'nullable|string|min:4',
        ]);

        // Verify current password
        if (!Hash::check($validated['current_password'], $user->password)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Password saat ini yang Anda masukkan salah.'
            ], 422);
        }

        $user->name = $validated['username'];
        $user->email = $validated['email'];

        if (!empty($validated['new_password'])) {
            $user->password = Hash::make($validated['new_password']);
        }

        $user->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Kredensial Akun Admin berhasil diperbarui.',
            'user' => [
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
            ]
        ]);
    }
}
