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
        'attachment_path',
        'attachment_name',
        'attachment_size',
    ];

    protected function casts(): array
    {
        return [
            'due_date' => 'date',
            'attachment_size' => 'integer',
        ];
    }

    
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    
    public function checklists()
    {
        return $this->hasMany(TaskChecklist::class)->orderBy('id', 'asc');
    }

    
    public function comments()
    {
        return $this->hasMany(TaskComment::class)->with('user')->orderBy('created_at', 'desc');
    }

    
    public function getTotalChecklistsCountAttribute(): int
    {
        return $this->checklists->count();
    }

    
    public function getCompletedChecklistsCountAttribute(): int
    {
        return $this->checklists->where('is_completed', true)->count();
    }

    
    public function getChecklistProgressPercentageAttribute(): int
    {
        $total = $this->total_checklists_count;
        if ($total === 0) {
            return $this->status === 'selesai' ? 100 : ($this->status === 'dikerjakan' ? 50 : 0);
        }
        return (int) round(($this->completed_checklists_count / $total) * 100);
    }

    
    public function getIsDueSoonAttribute(): bool
    {
        if ($this->status === 'selesai' || !$this->due_date) {
            return false;
        }
        $diff = Carbon::today()->diffInDays($this->due_date, false);
        return $diff >= 0 && $diff <= 1;
    }

    
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

    
    public function scopeFilterPriority($query, $priority)
    {
        if (!empty($priority)) {
            $query->where('priority', $priority);
        }
    }

    
    public function scopeFilterCategory($query, $categoryId)
    {
        if (!empty($categoryId)) {
            $query->where('category_id', $categoryId);
        }
    }

    
    public function scopeSearch($query, $search)
    {
        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }
    }

    
    public function scopeOverdue($query)
    {
        return $query->where('status', '!=', 'selesai')
                     ->whereNotNull('due_date')
                     ->whereDate('due_date', '<', Carbon::today());
    }

    
    public function scopeUpcoming($query, $days = 7)
    {
        return $query->where('status', '!=', 'selesai')
                     ->whereNotNull('due_date')
                     ->whereDate('due_date', '>=', Carbon::today())
                     ->whereDate('due_date', '<=', Carbon::today()->addDays($days))
                     ->orderBy('due_date', 'asc');
    }

    
    public function getIsOverdueAttribute(): bool
    {
        if ($this->status === 'selesai' || !$this->due_date) {
            return false;
        }
        return $this->due_date->isPast() && !$this->due_date->isToday();
    }

    
    public function getPriorityBadgeClassAttribute(): string
    {
        return match ($this->priority) {
            'tinggi' => 'badge bg-danger-subtle text-danger border border-danger-subtle fs-12 px-2 py-1',
            'sedang' => 'badge bg-warning-subtle text-warning border border-warning-subtle fs-12 px-2 py-1',
            'rendah' => 'badge bg-info-subtle text-info border border-info-subtle fs-12 px-2 py-1',
            default => 'badge bg-secondary-subtle text-secondary fs-12 px-2 py-1',
        };
    }

    
    public function getPriorityIconAttribute(): string
    {
        return match ($this->priority) {
            'tinggi' => 'ri-arrow-up-circle-fill text-danger',
            'sedang' => 'ri-equal-circle-fill text-warning',
            'rendah' => 'ri-arrow-down-circle-fill text-info',
            default => 'ri-record-circle-line',
        };
    }

    
    public function getStatusBadgeClassAttribute(): string
    {
        return match ($this->status) {
            'selesai' => 'badge bg-success-subtle text-success border border-success-subtle fs-12 px-2 py-1',
            'dikerjakan' => 'badge bg-primary-subtle text-primary border border-primary-subtle fs-12 px-2 py-1',
            'belum dimulai' => 'badge bg-secondary-subtle text-secondary border border-secondary-subtle fs-12 px-2 py-1',
            default => 'badge bg-light text-dark fs-12 px-2 py-1',
        };
    }

    
    public function getDueDateLabelAttribute(): ?string
    {
        if (!$this->due_date) {
            return null;
        }

        $today = Carbon::today();
        $diff = $today->diffInDays($this->due_date, false);

        if ($this->status === 'selesai') {
            return 'Selesai';
        }

        if ($diff < 0) {
            return 'Terlewat ' . abs($diff) . ' hari';
        } elseif ($diff === 0) {
            return 'Hari Ini';
        } elseif ($diff === 1) {
            return 'Besok';
        } else {
            return $diff . ' hari lagi';
        }
    }

    
    public function getDueDateBadgeAttribute(): string
    {
        if (!$this->due_date) {
            return 'badge bg-light text-muted border fs-12 px-2 py-1';
        }

        if ($this->status === 'selesai') {
            return 'badge bg-success-subtle text-success border border-success-subtle fs-12 px-2 py-1';
        }

        $diff = Carbon::today()->diffInDays($this->due_date, false);

        if ($diff < 0) {
            return 'badge bg-danger text-white fs-12 px-2 py-1';
        } elseif ($diff === 0) {
            return 'badge bg-danger-subtle text-danger border border-danger-subtle fs-12 px-2 py-1';
        } elseif ($diff <= 2) {
            return 'badge bg-warning-subtle text-warning border border-warning-subtle fs-12 px-2 py-1';
        } else {
            return 'badge bg-light text-body border fs-12 px-2 py-1';
        }
    }

    
    public function getStatusIconAttribute(): string
    {
        return match ($this->status) {
            'selesai' => 'ri-checkbox-circle-fill text-success',
            'dikerjakan' => 'ri-loader-2-fill text-primary',
            'belum dimulai' => 'ri-time-fill text-secondary',
            default => 'ri-checkbox-blank-circle-line',
        };
    }

    
    public function getHasAttachmentAttribute(): bool
    {
        return !empty($this->attachment_path);
    }

    
    public function getAttachmentUrlAttribute(): ?string
    {
        if (!$this->attachment_path) {
            return null;
        }
        return asset('storage/' . $this->attachment_path);
    }

    
    public function getAttachmentSizeFormattedAttribute(): ?string
    {
        if (!$this->attachment_size) {
            return null;
        }

        $bytes = $this->attachment_size;
        if ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 1) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 0) . ' KB';
        }
        return $bytes . ' B';
    }

    
    public function getAttachmentExtensionAttribute(): ?string
    {
        if (!$this->attachment_name && !$this->attachment_path) {
            return null;
        }
        return strtolower(pathinfo($this->attachment_name ?: $this->attachment_path, PATHINFO_EXTENSION));
    }

    
    public function getIsImageAttachmentAttribute(): bool
    {
        return in_array($this->attachment_extension, ['jpg', 'jpeg', 'png', 'webp', 'gif', 'svg']);
    }

    
    public function getAttachmentIconClassAttribute(): string
    {
        return match ($this->attachment_extension) {
            'pdf' => 'ri-file-pdf-fill text-danger',
            'doc', 'docx' => 'ri-file-word-fill text-primary',
            'xls', 'xlsx', 'csv' => 'ri-file-excel-fill text-success',
            'ppt', 'pptx' => 'ri-file-ppt-fill text-warning',
            'zip', 'rar', '7z', 'tar', 'gz' => 'ri-file-zip-fill text-secondary',
            'jpg', 'jpeg', 'png', 'webp', 'gif', 'svg' => 'ri-image-fill text-info',
            'txt', 'md' => 'ri-file-text-fill text-muted',
            default => 'ri-file-3-fill text-primary',
        };
    }
}
