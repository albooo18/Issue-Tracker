<?php

namespace App\Http\Controllers;

use App\Http\Requests\TagRequest;
use App\Models\Tag;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class TagController extends Controller
{
    public function index(): View
    {
        $tags = Tag::query()->withCount('issues')->orderBy('name')->paginate(20);

        return view('tags.index', compact('tags'));
    }

    public function store(TagRequest $request): RedirectResponse
    {
        Tag::create($request->validated());

        return redirect()
            ->route('tags.index')
            ->with('status', 'Tag created successfully.');
    }
}
