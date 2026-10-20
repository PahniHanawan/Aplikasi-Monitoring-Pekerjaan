<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'is_active',
        'phone',
        'address'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    // Scope untuk user aktif
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Method untuk mengecek role - HANYA admin dan staff
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isStaff()
    {
        return $this->role === 'staff';
    }

    // Method untuk mendapatkan label role
    public function getRoleLabel()
    {
        $roles = [
            'admin' => 'Administrator',
            'staff' => 'Staff'
        ];

        return $roles[$this->role] ?? 'Unknown';
    }

    // Method untuk mendapatkan badge color berdasarkan role
    public function getRoleBadgeColor()
    {
        $colors = [
            'admin' => 'danger',
            'staff' => 'info'
        ];

        return $colors[$this->role] ?? 'secondary';
    }

    // Method untuk mendapatkan icon berdasarkan role
    public function getRoleIcon()
    {
        $icons = [
            'admin' => 'bi-shield-check',
            'staff' => 'bi-person'
        ];

        return $icons[$this->role] ?? 'bi-person';
    }

    // Method untuk status aktif
    public function getStatusBadge()
    {
        return $this->is_active
            ? '<span class="badge bg-success">Aktif</span>'
            : '<span class="badge bg-danger">Nonaktif</span>';
    }
}
