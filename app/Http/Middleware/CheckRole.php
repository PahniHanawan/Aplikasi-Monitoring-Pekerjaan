<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        if ($user->role !== $role) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda tidak memiliki akses ke halaman tersebut.'
                ], 403);
            }

            $errorMessage = match($request->route()->getName()) {
                'users.index', 'users.create', 'users.store' => '🔐 Akses Manajemen User Ditolak! Role Staff tidak diizinkan mengelola data pengguna.',
                'users.edit', 'users.update' => '✋ Tidak diizinkan! Anda tidak bisa mengedit data pengguna.',
                'users.destroy' => '❌ Dilarang! Anda tidak memiliki hak untuk menghapus pengguna.',
                'users.change-password', 'users.update-password' => '🔒 Tidak diizinkan! Anda tidak bisa mengubah password user lain.',
                default => '⚠️ Akses Ditolak! Anda tidak memiliki izin untuk mengakses halaman ini.'
            };

            return redirect()->route('dashboard')->with('error', $errorMessage);
        }

        return $next($request);
    }
}
