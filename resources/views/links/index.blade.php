@extends('layouts.app')
@section('title', 'Manage Links')

@section('content')

<div class="topbar animate-fade-up">
    <div class="topbar-title">
        <h1>My <span class="text-gradient">Links</span></h1>
        <p>Manage, reorder, and toggle visibility for your links.</p>
    </div>
    <div class="topbar-actions">
        <a href="{{ route('links.create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus"></i> Add New Link
        </a>
    </div>
</div>

<div class="card animate-fade-up" style="animation-delay:.05s;">
    @if($links->isEmpty())
        <div class="empty-state">
            <div class="empty-state-icon">🔗</div>
            <h3>No links added yet</h3>
            <p>Add your social profiles, websites, or portfolio pages to start sharing.</p>
            <a href="{{ route('links.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Add First Link
            </a>
        </div>
    @else
        <div style="margin-bottom: 1rem; display: flex; justify-content: space-between; align-items: center;">
            <p style="font-size: .85rem; color: var(--text-muted); margin: 0;">
                <i class="fas fa-arrows-up-down"></i> Drag and drop links to reorder them on your public profile.
            </p>
            <span id="reorderStatus" class="badge badge-purple" style="display:none;">Saved!</span>
        </div>

        <div id="linksList" style="display:flex; flex-direction:column; gap:0.75rem;">
            @foreach($links as $link)
                <div class="link-card" data-id="{{ $link->id }}" draggable="true">
                    <div class="link-card-drag" title="Drag to reorder">
                        <i class="fas fa-grip-vertical"></i>
                    </div>

                    <div class="link-card-icon" style="background: {{ $link->brandColor() }}22; color: {{ $link->brandColor() }};">
                        <i class="{{ $link->iconClass() }}"></i>
                    </div>

                    <div class="link-card-info">
                        <div class="link-card-label">{{ $link->label }}</div>
                        <div class="link-card-url">
                            <a href="{{ $link->url }}" target="_blank" rel="noopener" style="color:inherit; text-decoration:none;">
                                {{ $link->url }} <i class="fas fa-external-link-alt" style="font-size:0.7rem; margin-left:3px;"></i>
                            </a>
                        </div>
                    </div>

                    <div class="link-card-actions">
                        <span class="badge badge-gray" title="Total clicks">
                            <i class="fas fa-chart-simple"></i> {{ number_format($link->click_count) }}
                        </span>

                        <label class="toggle" title="Toggle visibility">
                            <input type="checkbox" onchange="toggleLink({{ $link->id }}, this)" {{ $link->is_active ? 'checked' : '' }}>
                            <span class="toggle-slider"></span>
                        </label>

                        <a href="{{ route('links.edit', $link) }}" class="btn btn-secondary btn-icon" title="Edit">
                            <i class="fas fa-pen"></i>
                        </a>

                        <form action="{{ route('links.destroy', $link) }}" method="POST" onsubmit="return confirm('Delete this link?');" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-icon" title="Delete">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

@endsection

@push('scripts')
<script>
// Toggle link active/inactive via AJAX
function toggleLink(id, checkbox) {
    fetch(`{{ url('/links') }}/${id}/toggle`, {
        method: 'PATCH',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        }
    })
    .then(res => res.json())
    .then(data => {
        if (!data.success) {
            checkbox.checked = !checkbox.checked;
        }
    })
    .catch(() => {
        checkbox.checked = !checkbox.checked;
    });
}

// Drag & Drop HTML5 Sort
const container = document.getElementById('linksList');
let draggedItem = null;

if (container) {
    container.addEventListener('dragstart', (e) => {
        draggedItem = e.target.closest('.link-card');
        if (draggedItem) {
            draggedItem.classList.add('dragging');
            e.dataTransfer.effectAllowed = 'move';
        }
    });

    container.addEventListener('dragend', (e) => {
        if (draggedItem) {
            draggedItem.classList.remove('dragging');
            draggedItem = null;
            saveOrder();
        }
    });

    container.addEventListener('dragover', (e) => {
        e.preventDefault();
        const afterElement = getDragAfterElement(container, e.clientY);
        if (draggedItem) {
            if (afterElement == null) {
                container.appendChild(draggedItem);
            } else {
                container.insertBefore(draggedItem, afterElement);
            }
        }
    });
}

function getDragAfterElement(container, y) {
    const draggableElements = [...container.querySelectorAll('.link-card:not(.dragging)')];
    return draggableElements.reduce((closest, child) => {
        const box = child.getBoundingClientRect();
        const offset = y - box.top - box.height / 2;
        if (offset < 0 && offset > closest.offset) {
            return { offset: offset, element: child };
        } else {
            return closest;
        }
    }, { offset: Number.NEGATIVE_INFINITY }).element;
}

function saveOrder() {
    const cards = [...container.querySelectorAll('.link-card')];
    const order = cards.map(c => parseInt(c.dataset.id));

    fetch('{{ route("links.reorder") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ order })
    })
    .then(res => res.json())
    .then(data => {
        const badge = document.getElementById('reorderStatus');
        if (badge) {
            badge.style.display = 'inline-block';
            setTimeout(() => { badge.style.display = 'none'; }, 2000);
        }
    });
}
</script>
@endpush
