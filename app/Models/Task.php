<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'category_id',
        'title',
        'description',
        'priority',
        'status',
        'due_date',
    ];

    protected function casts(): array
    {
        return [
            'due_date' => 'date',
        ];
    }

    /**
     * Relasi ke pemilik tugas (User).
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke Kategori tugas.
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Scope untuk memfilter berdasarkan status.
     */
    public function scopeFilterStatus($query, $status)
    {
        if (!empty($status)) {
            if ($status === 'overdue') {
                $query->overdue();
            } else {
                $query->where('status', $status);
            }
        }
    }

    /**
     * Scope untuk memfilter berdasarkan prioritas.
     */
    public function scopeFilterPriority($query, $priority)
    {
        if (!empty($priority)) {
            $query->where('priority', $priority);
        }
    }

    /**
     * Scope untuk memfilter berdasarkan kategori.
     */
    public function scopeFilterCategory($query, $categoryId)
    {
        if (!empty($categoryId)) {
            $query->where('category_id', $categoryId);
        }
    }

    /**
     * Scope untuk pencarian judul dan deskripsi.
     */
    public function scopeSearch($query, $search)
    {
        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }
    }

    /**
     * Scope untuk tugas yang melewati tenggat waktu.
     */
    public function scopeOverdue($query)
    {
        return $query->where('status', '!=', 'selesai')
                     ->whereNotNull('due_date')
                     ->whereDate('due_date', '<', Carbon::today());
    }

    /**
     * Scope untuk tugas yang mendekati tenggat waktu (dalam N hari ke depan).
     */
    public function scopeUpcoming($query, $days = 7)
    {
        return $query->where('status', '!=', 'selesai')
                     ->whereNotNull('due_date')
                     ->whereDate('due_date', '>=', Carbon::today())
                     ->whereDate('due_date', '<=', Carbon::today()->addDays($days))
                     ->orderBy('due_date', 'asc');
    }

    /**
     * Cek apakah tugas sudah melewati tenggat waktu.
     */
    public function getIsOverdueAttribute(): bool
    {
        if ($this->status === 'selesai' || !$this->due_date) {
            return false;
        }
        return $this->due_date->isPast() && !$this->due_date->isToday();
    }

    /**
     * Helper badge Bootstrap class untuk prioritas.
     */
    public function getPriorityBadgeClassAttribute(): string
    {
        return match ($this->priority) {
            'tinggi' => 'badge bg-danger-subtle text-danger border border-danger-subtle fs-12 px-2 py-1',
            'sedang' => 'badge bg-warning-subtle text-warning border border-warning-subtle fs-12 px-2 py-1',
            'rendah' => 'badge bg-info-subtle text-info border border-info-subtle fs-12 px-2 py-1',
            default => 'badge bg-secondary-subtle text-secondary fs-12 px-2 py-1',
        };
    }

    /**
     * Helper icon class untuk prioritas.
     */
    public function getPriorityIconAttribute(): string
    {
        return match ($this->priority) {
            'tinggi' => 'ri-arrow-up-circle-fill text-danger',
            'sedang' => 'ri-equal-circle-fill text-warning',
            'rendah' => 'ri-arrow-down-circle-fill text-info',
            default => 'ri-record-circle-line',
        };
    }

    /**
     * Helper badge Bootstrap class untuk status.
     */
    public function getStatusBadgeClassAttribute(): string
    {
        return match ($this->status) {
            'selesai' => 'badge bg-success-subtle text-success border border-success-subtle fs-12 px-2 py-1',
            'dikerjakan' => 'badge bg-primary-subtle text-primary border border-primary-subtle fs-12 px-2 py-1',
            'belum dimulai' => 'badge bg-secondary-subtle text-secondary border border-secondary-subtle fs-12 px-2 py-1',
            default => 'badge bg-light text-dark fs-12 px-2 py-1',
        };
    }

    /**
     * Helper icon class untuk status.
     */
    public function getStatusIconAttribute(): string
    {
        return match ($this->status) {
            'selesai' => 'ri-checkbox-circle-fill text-success',
            'dikerjakan' => 'ri-loader-2-fill text-primary',
            'belum dimulai' => 'ri-time-fill text-secondary',
            default => 'ri-checkbox-blank-circle-line',
        };
    }
}
