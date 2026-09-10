<div class="card mb-2 kanban-card-item shadow-none border" draggable="true" data-task-id="{{ $task->id }}" style="cursor: grab;">
    <div class="card-body p-3">
        
        <span class="float-end badge {{ $task->priority_badge_class }} rounded-pill fs-11 px-2 py-0">
            <i class="{{ $task->priority_icon }} me-1"></i>{{ ucfirst($task->priority) }}
        </span>
        <small class="text-muted">
            <i class="ri-calendar-todo-line me-1"></i>{{ $task->due_date ? $task->due_date->format('d M Y') : 'Tanpa batas' }}
        </small>

        <h5 class="my-2 fs-14">
            <a href="{{ route('tasks.show', $task) }}" class="text-body fw-semibold text-decoration-none">
                {{ $task->title }}
            </a>
        </h5>

        @if($task->description)
            <p class="text-muted fs-12 mb-2" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; line-height: 1.45;">
                {{ $task->description }}
            </p>
        @endif

        @if($task->total_checklists_count > 0)
            <div class="mb-2">
                <div class="d-flex justify-content-between fs-11 text-muted mb-1">
                    <span><i class="ri-checkbox-line me-1"></i>Sub-tugas</span>
                    <span><b>{{ $task->completed_checklists_count }}/{{ $task->total_checklists_count }}</b> ({{ $task->checklist_progress_percentage }}%)</span>
                </div>
                <div class="progress progress-sm" style="height: 5px;">
                    <div class="progress-bar {{ $task->checklist_progress_percentage == 100 ? 'bg-success' : 'bg-primary' }}" 
                         role="progressbar" 
                         style="width: {{ $task->checklist_progress_percentage }}%;" 
                         aria-valuenow="{{ $task->checklist_progress_percentage }}" 
                         aria-valuemin="0" 
                         aria-valuemax="100"></div>
                </div>
            </div>
        @endif

        <p class="mb-0 fs-12 text-muted">
            @if($task->category)
                <span class="pe-2 text-nowrap mb-1 d-inline-block">
                    <span class="badge bg-{{ $task->category->color }}-subtle text-{{ $task->category->color }} rounded-pill fs-10">
                        {{ $task->category->name }}
                    </span>
                </span>
            @endif
            @if($task->comments->count() > 0)
                <span class="pe-2 text-nowrap mb-1 d-inline-block">
                    <i class="ri-discuss-line text-muted"></i>
                    <b>{{ $task->comments->count() }}</b> Catatan
                </span>
            @endif
            @if($task->has_attachment)
                <span class="text-nowrap mb-1 d-inline-block">
                    <i class="ri-attachment-2 text-muted"></i>
                    <b>Lampiran</b>
                </span>
            @endif
        </p>

        <div class="dropdown float-end mt-2">
            <a href="#" class="dropdown-toggle text-muted arrow-none" data-bs-toggle="dropdown" aria-expanded="false" title="Menu Opsi">
                <i class="ri-more-2-fill fs-16"></i>
            </a>
            <div class="dropdown-menu dropdown-menu-end shadow-sm border py-1 fs-12">
                <h6 class="dropdown-header text-uppercase fs-10 text-muted fw-bold">Pindahkan Status</h6>
                @if($task->status !== 'belum dimulai')
                <form action="{{ route('tasks.status', $task) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="status" value="belum dimulai">
                    <button type="submit" class="dropdown-item py-1">
                        <i class="ri-time-line me-1 text-secondary"></i> Ke Belum Dimulai
                    </button>
                </form>
                @endif
                @if($task->status !== 'dikerjakan')
                <form action="{{ route('tasks.status', $task) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="status" value="dikerjakan">
                    <button type="submit" class="dropdown-item py-1">
                        <i class="ri-loader-2-line me-1 text-primary"></i> Ke Sedang Dikerjakan
                    </button>
                </form>
                @endif
                @if($task->status !== 'selesai')
                <form action="{{ route('tasks.status', $task) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="status" value="selesai">
                    <button type="submit" class="dropdown-item py-1">
                        <i class="ri-checkbox-circle-line me-1 text-success"></i> Ke Selesai
                    </button>
                </form>
                @endif
                <div class="dropdown-divider my-1"></div>
                <a href="{{ route('tasks.show', $task) }}" class="dropdown-item py-1">
                    <i class="ri-eye-line me-1 text-info"></i> Detail Tugas
                </a>
                <a href="{{ route('tasks.edit', $task) }}" class="dropdown-item py-1">
                    <i class="ri-edit-box-line me-1 text-warning"></i> Edit Tugas
                </a>
            </div>
        </div>

        <div class="d-flex align-items-center mt-2">
            @if($task->user)
                <div class="avatar-xs me-1" title="{{ $task->user->name }}" style="width: 22px; height: 22px;">
                    <span class="avatar-title rounded-circle bg-primary-subtle text-primary fs-10 fw-bold" style="width: 22px; height: 22px; line-height: 22px;">
                        {{ strtoupper(substr($task->user->name, 0, 2)) }}
                    </span>
                </div>
                <small class="text-muted text-truncate fs-11" style="max-width: 120px;">{{ $task->user->name }}</small>
            @endif
        </div>
    </div>
</div>
