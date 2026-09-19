<?php

namespace App\Http\Controllers;

use App\Models\WatchItem;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WatchItemController extends Controller
{
    public function index(Request $request): View
    {
        $query = trim((string) $request->query('q', ''));

        $items = $request->user()->watchItems()
            ->when($query !== '', function ($builder) use ($query) {
                $builder->where(function ($sub) use ($query) {
                    $sub->where('title', 'like', "%{$query}%")
                        ->orWhere('url', 'like', "%{$query}%");
                });
            })
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('watch-items.index', [
            'items' => $items,
            'query' => $query,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'url' => ['required', 'url', 'max:2048'],
        ]);

        $request->user()->watchItems()->create($validated);

        return redirect()->route('watch-items.index')->with('status', 'item-saved');
    }

    public function destroy(Request $request, WatchItem $watchItem): RedirectResponse
    {
        abort_unless($watchItem->user_id === $request->user()->id, 404);

        $watchItem->delete();

        return redirect()->route('watch-items.index')->with('status', 'item-deleted');
    }
}
