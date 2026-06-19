<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'warung_id',
        'email_verified_at',
        'verification_code',
        'reset_password_code',
        'google_id',
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
            'deleted_at' => 'datetime',
        ];
    }

    // =========================================================
    // RELATIONSHIPS
    // =========================================================

    public function warung(): BelongsTo
    {
        return $this->belongsTo(Warung::class);
    }

    /**
     * Transaksi yang dibuat oleh user ini (sebagai kasir).
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class, 'user_id');
    }

    /**
     * Pergerakan stok yang dicatat oleh user ini.
     */
    public function stockMovements(): HasMany
    {
        return $this->hasMany(StockMovement::class, 'user_id');
    }

    // =========================================================
    // HELPER METHODS — dipakai di Middleware dan Blade @if
    // =========================================================

    public function isAuthenticated(): bool
    {
        return !!$this->role;
    }

    public function isOwner(): bool
    {
        return $this->role === 'owner';
    }

    public function isKasir(): bool
    {
        return $this->role === 'kasir';
    }
    public function isPelanggan(): bool
    {
        return $this->role === 'pelanggan';
    }
    public function isSuperAdmin(): bool
    {
        return $this->role === 'super_admin';
    }

    /**
     * Cek apakah user sudah menyelesaikan setup warung.
     * Dipakai oleh middleware EnsureWarungSetup.
     */
    public function hasWarung(): bool
    {
        return (bool) $this->warung_id;
    }

    /**
     * Owner dan kasir sama-sama bisa akses fitur operasional.
     */
    public function canAccessPOS(): bool
    {
        return in_array($this->role, ['owner', 'kasir', 'pelanggan']);
    }
}
