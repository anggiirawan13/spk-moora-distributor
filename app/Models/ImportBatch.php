<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImportBatch extends Model
{
    use HasFactory;

    protected $fillable = [
        'original_file_name',
        'imported_by',
        'stats',
        'admin_approved_at',
        'admin_approved_by',
        'director_approved_at',
        'director_approved_by',
    ];

    protected $casts = [
        'stats' => 'array',
        'admin_approved_at' => 'datetime',
        'director_approved_at' => 'datetime',
    ];

    public function importedBy()
    {
        return $this->belongsTo(User::class, 'imported_by');
    }

    public function adminApprovedBy()
    {
        return $this->belongsTo(User::class, 'admin_approved_by');
    }

    public function directorApprovedBy()
    {
        return $this->belongsTo(User::class, 'director_approved_by');
    }

    public function scopeVisibleToUser(Builder $query, User $user): Builder
    {
        if ((int) $user->is_admin === 1 || $user->role === 'staf') {
            return $query;
        }

        if ($user->role === 'direktur_utama') {
            return $query->whereNotNull('admin_approved_at');
        }

        if ($user->role === 'komisaris') {
            return $query->whereNotNull('director_approved_at');
        }

        return $query->whereRaw('1 = 0');
    }
}
