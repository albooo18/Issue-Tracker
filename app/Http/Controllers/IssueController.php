<?php

namespace App\Http\Controllers;

use App\Http\Requests\IssueRequest;
use App\Models\Issue;
use App\Models\Project;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class IssueController extends Controller
{
    public function index(Request $request): View
    {
        $issues = $this->issueQuery($request)
            ->paginate(12)
            ->withQueryString();

        $tags = Tag::query()->orderBy('name')->get();
        $projects = Project::query()->orderBy('name')->get();

        return view('issues.index', compact('issues', 'tags', 'projects'));
    }

    public function search(Request $request): JsonResponse
    {
        $issues = $this->issueQuery($request)
            ->paginate(12)
            ->withQueryString();

        return response()->json([
            'cards_html' => view('issues.partials.cards', [
                'issues' => $issues,
                'showProject' => true,
            ])->render(),
            'pagination' => [
                'current_page' => $issues->currentPage(),
                'last_page' => $issues->lastPage(),
                'prev_page_url' => $issues->previousPageUrl(),
                'next_page_url' => $issues->nextPageUrl(),
                'total' => $issues->total(),
            ],
        ]);
    }

    public function create(Request $request): View
    {
        return view('issues.create', [
            'issue' => new Issue([
                'project_id' => $request->integer('project_id'),
                'status' => 'open',
                'priority' => 'medium',
            ]),
            'projects' => Project::query()->orderBy('name')->get(),
            'tags' => Tag::query()->orderBy('name')->get(),
        ]);
    }

    public function store(IssueRequest $request): RedirectResponse
    {
        $issue = Issue::create($request->safe()->except('tag_ids'));
        $issue->tags()->sync($request->input('tag_ids', []));

        return redirect()
            ->route('issues.show', $issue)
            ->with('status', 'Issue created successfully.');
    }

    public function show(Issue $issue): View
    {
        $issue->load(['project', 'tags', 'users'])->loadCount('comments');

        return view('issues.show', [
            'issue' => $issue,
            'tags' => Tag::query()->orderBy('name')->get(),
            'users' => User::query()->orderBy('name')->get(),
        ]);
    }

    public function edit(Issue $issue): View
    {
        $issue->load('tags');

        return view('issues.edit', [
            'issue' => $issue,
            'projects' => Project::query()->orderBy('name')->get(),
            'tags' => Tag::query()->orderBy('name')->get(),
        ]);
    }

    public function update(IssueRequest $request, Issue $issue): RedirectResponse
    {
        $issue->update($request->safe()->except('tag_ids'));
        $issue->tags()->sync($request->input('tag_ids', []));

        return redirect()
            ->route('issues.show', $issue)
            ->with('status', 'Issue updated successfully.');
    }

    public function destroy(Issue $issue): RedirectResponse
    {
        $project = $issue->project()->first();
        $issue->delete();

        return redirect()
            ->route('projects.show', $project)
            ->with('status', 'Issue deleted.');
    }

    private function issueQuery(Request $request)
    {
        return Issue::query()
            ->with(['project', 'tags'])
            ->withCount('comments')
            ->when($request->filled('q'), fn ($query) => $query->where(function ($subQuery) use ($request) {
                $search = $request->string('q')->toString();

                $subQuery->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            }))
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->input('status')))
            ->when($request->filled('priority'), fn ($query) => $query->where('priority', $request->input('priority')))
            ->when($request->filled('tag_id'), fn ($query) => $query->whereHas('tags', fn ($tagQuery) => $tagQuery->whereKey($request->integer('tag_id'))))
            ->latest();
    }
}
