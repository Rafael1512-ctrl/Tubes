<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed', // Laravel 10+ otomatis hash password
    ];

    /**
     * Relasi ke Pegawai (jika user adalah admin/dokter/pegawai)
     * Note: foreign key di tabel pegawai adalah 'user_id'
     */
    public function pegawai()
    {
        return $this->hasOne(Pegawai::class, 'user_id');
    }

    /**
     * Relasi ke Pasien (jika user adalah pasien)
     * Note: foreign key di tabel pasien adalah 'user_id'
     */
    public function pasien()
    {
        return $this->hasOne(Pasien::class, 'user_id');
    }

    /**
     * Helper method untuk mengecek role
     */
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isDokter()
    {
        return $this->role === 'dokter';
    }

    public function isPegawai()
    {
        return $this->role === 'pegawai';
    }

    public function isPasien()
    {
        return $this->role === 'pasien';
    }

    /**
     * Scope untuk filter user berdasarkan role
     */
    public function scopeByRole($query, $role)
    {
        return $query->where('role', $role);
    }

    /**
     * Get the user's full data based on role
     */
    public function getFullDataAttribute()
    {
        if ($this->isAdmin() || $this->isDokter() || $this->isPegawai()) {
            return $this->pegawai;
        } elseif ($this->isPasien()) {
            return $this->pasien;
        }
        
        return null;
    }

    /**
     * Get the user's phone number based on role
     */
    public function getPhoneNumberAttribute()
    {
        if ($this->isAdmin() || $this->isDokter() || $this->isPegawai()) {
            return optional($this->pegawai)->notelp;
        } elseif ($this->isPasien()) {
            return optional($this->pasien)->notelp;
        }
        
        return null;
    }
}