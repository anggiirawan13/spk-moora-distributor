<?php

namespace App\Models;

use App\Models\Concerns\TracksImportBatchVisibility;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Distributor extends Model
{
    use HasFactory, TracksImportBatchVisibility;

    protected $table = 'distributors';

    protected $fillable = [
        'code',
        'name',
        'image_name',
        'npwp',
        'address',
        'phone',
        'email',
        'payment_term_id',
        'delivery_method_id',
        'business_scale_id',
        'description',
        'is_active',
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

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function getNpwpFormattedAttribute(): string
    {
        $digits = preg_replace('/\D+/', '', (string) $this->npwp);

        if (strlen($digits) !== 15) {
            return (string) $this->npwp;
        }

        return substr($digits, 0, 2) . '.'
            . substr($digits, 2, 3) . '.'
            . substr($digits, 5, 3) . '.'
            . substr($digits, 8, 1) . '-'
            . substr($digits, 9, 3) . '.'
            . substr($digits, 12, 3);
    }

    public function products()
    {
        $relation = $this->belongsToMany(Product::class, 'distributor_product')->withPivot('import_batch_id');
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

    public function alternative()
    {
        $relation = $this->hasOne(Alternative::class, 'distributor_id');
        $user = auth()->user();

        return $user ? $relation->visibleTo($user) : $relation;
    }

    public function paymentTerm()
    {
        $relation = $this->belongsTo(PaymentTerm::class, 'payment_term_id');
        $user = auth()->user();

        return $user ? $relation->visibleTo($user) : $relation;
    }

    public function deliveryMethod()
    {
        $relation = $this->belongsTo(DeliveryMethod::class, 'delivery_method_id');
        $user = auth()->user();

        return $user ? $relation->visibleTo($user) : $relation;
    }

    public function businessScale()
    {
        $relation = $this->belongsTo(BusinessScale::class, 'business_scale_id');
        $user = auth()->user();

        return $user ? $relation->visibleTo($user) : $relation;
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
