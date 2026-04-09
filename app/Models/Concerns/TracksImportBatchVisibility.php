<?php

namespace App\Models\Concerns;

use App\Models\User;
use App\Support\ImportBatchState;
use Illuminate\Database\Eloquent\Builder;

trait TracksImportBatchVisibility
{
    private const APPROVAL_COLUMNS = [
        'admin_approval_status',
        'admin_approval_note',
        'admin_approved_at',
        'admin_approved_by',
        'director_approval_status',
        'director_approval_note',
        'director_approved_at',
        'director_approved_by',
        'updated_at',
        'updated_by',
    ];

    public static function bootTracksImportBatchVisibility(): void
    {
        static::creating(function ($model) {
            if (empty($model->import_batch_id) && ImportBatchState::currentBatchId()) {
                $model->import_batch_id = ImportBatchState::currentBatchId();
            }

            if (!empty($model->import_batch_id)) {
                if (auth()->check() && (int) auth()->user()->is_admin === 1) {
                    $model->admin_approval_status = 'approved';
                    $model->admin_approved_at = now();
                    $model->admin_approved_by = auth()->id();
                    $model->director_approval_status = 'pending';
                } else {
                    $model->admin_approval_status = 'pending';
                    $model->director_approval_status = 'pending';
                }
            }
        });

        static::updating(function ($model) {
            if (empty($model->import_batch_id) || !auth()->check()) {
                return;
            }

            $changes = array_diff(array_keys($model->getDirty()), self::APPROVAL_COLUMNS);
            if ($changes === []) {
                return;
            }

            if ((int) auth()->user()->is_admin === 1) {
                $model->admin_approval_status = 'approved';
                $model->admin_approval_note = null;
                $model->admin_approved_at = now();
                $model->admin_approved_by = auth()->id();
            } else {
                $model->admin_approval_status = 'pending';
                $model->admin_approval_note = null;
                $model->admin_approved_at = null;
                $model->admin_approved_by = null;
            }

            $model->director_approval_status = 'pending';
            $model->director_approval_note = null;
            $model->director_approved_at = null;
            $model->director_approved_by = null;
        });
    }

    public function importBatch()
    {
        return $this->belongsTo(ImportBatch::class, 'import_batch_id');
    }

    public function scopeVisibleTo(Builder $query, ?User $user = null): Builder
    {
        $user = $user ?: auth()->user();

        if (!$user) {
            return $query;
        }

        $column = $this->qualifyColumn('import_batch_id');
        $directorStatusColumn = $this->qualifyColumn('director_approval_status');

        return $query->where(function (Builder $builder) use ($column, $directorStatusColumn) {
            $builder->whereNull($column)
                ->orWhere(function (Builder $nested) use ($column, $directorStatusColumn) {
                    $nested->whereNotNull($column)
                        ->where($directorStatusColumn, 'approved');
                });
        });
    }

    public function scopeManageableBy(Builder $query, ?User $user = null): Builder
    {
        $user = $user ?: auth()->user();

        if (!$user) {
            return $query->whereRaw('1 = 0');
        }

        if ((int) $user->is_admin === 1) {
            return $query;
        }

        if ($user->role !== 'staf') {
            return $this->scopeVisibleTo($query, $user);
        }

        $column = $this->qualifyColumn('import_batch_id');
        $createdByColumn = $this->qualifyColumn('created_by');

        return $query->where(function (Builder $builder) use ($user, $column, $createdByColumn) {
            $this->scopeVisibleTo($builder, $user)
                ->orWhere(function (Builder $nested) use ($user, $column, $createdByColumn) {
                    $nested->whereNotNull($column)
                        ->where($createdByColumn, $user->id);
                });
        });
    }

    public function getApprovalStatusLabelAttribute(): string
    {
        if (!$this->import_batch_id) {
            return 'Data Manual';
        }

        if ($this->director_approval_status === 'rejected') {
            return 'Ditolak Direktur Utama';
        }

        if ($this->director_approval_status === 'approved') {
            return 'Disetujui Direktur Utama';
        }

        if ($this->admin_approval_status === 'rejected') {
            return 'Ditolak Admin';
        }

        if ($this->admin_approval_status === 'approved') {
            return 'Menunggu Direktur Utama';
        }

        return 'Menunggu Admin';
    }

    public function getApprovalReasonAttribute(): ?string
    {
        return $this->director_approval_note ?: $this->admin_approval_note;
    }

    public function getCanBeEditedByCurrentUserAttribute(): bool
    {
        $user = auth()->user();

        if (!$user) {
            return false;
        }

        if ((int) $user->is_admin === 1) {
            return true;
        }

        return $user->role === 'staf'
            && $this->import_batch_id
            && (int) $this->created_by === (int) $user->id;
    }

    public function getCanBeDeletedByCurrentUserAttribute(): bool
    {
        $user = auth()->user();

        if (!$user) {
            return false;
        }

        if ((int) $user->is_admin === 1) {
            return true;
        }

        return $user->role === 'staf'
            && $this->import_batch_id
            && (int) $this->created_by === (int) $user->id
            && ($this->admin_approval_status === 'rejected' || $this->director_approval_status === 'rejected');
    }

    public function approveByAdmin(int $userId): void
    {
        $this->forceFill([
            'admin_approval_status' => 'approved',
            'admin_approval_note' => null,
            'admin_approved_at' => now(),
            'admin_approved_by' => $userId,
        ])->save();
    }

    public function rejectByAdmin(int $userId, string $reason): void
    {
        $this->forceFill([
            'admin_approval_status' => 'rejected',
            'admin_approval_note' => $reason,
            'admin_approved_at' => now(),
            'admin_approved_by' => $userId,
            'director_approval_status' => 'pending',
            'director_approval_note' => null,
            'director_approved_at' => null,
            'director_approved_by' => null,
        ])->save();
    }

    public function approveByDirector(int $userId): void
    {
        $this->forceFill([
            'director_approval_status' => 'approved',
            'director_approval_note' => null,
            'director_approved_at' => now(),
            'director_approved_by' => $userId,
        ])->save();
    }

    public function rejectByDirector(int $userId, string $reason): void
    {
        $this->forceFill([
            'director_approval_status' => 'rejected',
            'director_approval_note' => $reason,
            'director_approved_at' => now(),
            'director_approved_by' => $userId,
        ])->save();
    }
}
