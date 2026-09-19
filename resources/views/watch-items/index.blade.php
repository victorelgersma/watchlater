@extends('layouts.app')

@section('content')
    @if (session('status') === 'item-saved')
        <div class="status">Saved.</div>
    @elseif (session('status') === 'item-deleted')
        <div class="status">Deleted.</div>
    @endif

    <form method="POST" action="{{ route('watch-items.store') }}" class="save-form">
        @csrf
        <div class="save-form-row">
            <input type="text" name="title" placeholder="Title" value="{{ old('title') }}" required>
        </div>
        @error('title')
            <p class="error">{{ $message }}</p>
        @enderror

        <div class="save-form-row">
            <input type="url" name="url" placeholder="https://…" value="{{ old('url') }}" required>
            <button type="submit" class="btn btn-solid">Save</button>
        </div>
        @error('url')
            <p class="error">{{ $message }}</p>
        @enderror
    </form>

    <form method="GET" action="{{ route('watch-items.index') }}" class="search-row">
        <input type="text" name="q" value="{{ $query }}" placeholder="Search watch later…">
    </form>

    @forelse ($items as $item)
        @if ($loop->first)
            <div class="link-list">
        @endif

        <div class="link-item">
            <a class="url" href="{{ $item->url }}" target="_blank" rel="noopener noreferrer">
                {{ $item->title }}
            </a>
            <p class="description">{{ $item->displayUrl() }}</p>
            <div class="meta">
                <span class="date">{{ $item->created_at->format('j M Y') }}</span>
                <form method="POST" action="{{ route('watch-items.destroy', $item) }}"
                    onsubmit="return confirm('Delete this item?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-plain">Delete</button>
                </form>
            </div>
        </div>

        @if ($loop->last)
            </div>
        @endif
    @empty
        <div class="empty">
            {{ $query !== '' ? 'No items match that search.' : 'Nothing saved yet — add something to watch later above.' }}
        </div>
    @endforelse

    @if ($items->hasPages())
        <div class="pagination">
            {{ $items->links() }}
        </div>
    @endif
@endsection
