<?php

namespace App\Models;

use App\Models\Concerns\TracksImportBatchVisibility;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Product extends Model
{
    use HasFactory, TracksImportBatchVisibility;

    protected $table = 'products';

    protected $fillable = [
        'code',
        'name',
        'description',
        'import_batch_id',
        'created_by',
        'updated_by',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (auth()->check()) {
                $model->created_by = auth()->id();
                $model->updated_by = auth()->id();
            }
        });

        static::updating(function ($model) {
            if (auth()->check()) {
                $model->updated_by = auth()->id();
            }
        });
    }

    public function distributors()
    {
        $relation = $this->belongsToMany(Distributor::class, 'distributor_product')->withPivot('import_batch_id');
        $user = auth()->user();

        if (!$user || (int) $user->is_admin === 1 || $user->role === 'staf') {
            return $relation;
        }

        return $relation
            ->visibleTo($user)
            ->where(function ($query) use ($user) {
                $query->whereNull('distributor_product.import_batch_id')
                    ->orWhere(function ($nested) use ($user) {
                        if ($user->role === 'direktur_utama') {
                            $nested->where('distributor_product.admin_approval_status', 'approved');
                            return;
                        }

                        $nested->where('distributor_product.director_approval_status', 'approved');
                    });
            });
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
