<?php

namespace App\Http\Controllers;

use App\Http\Requests\IssueTagRequest;
use App\Models\Issue;
use App\Models\Tag;
use Illuminate\Http\JsonResponse;

class IssueTagController extends Controller
{
    public function store(IssueTagRequest $request, Issue $issue): JsonResponse
    {
        $validated = $request->validated();
        $issue->tags()->syncWithoutDetaching([$validated['tag_id']]);
        $issue->load('tags');

        return response()->json([
            'tags_html' => view('issues.partials.tags', [
                'issue' => $issue,
            ])->render(),
        ]);
    }

    public function destroy(Issue $issue, Tag $tag): JsonResponse
    {
        $issue->tags()->detach($tag->id);
        $issue->load('tags');

        return response()->json([
            'tags_html' => view('issues.partials.tags', [
                'issue' => $issue,
            ])->render(),
        ]);
    }
}
