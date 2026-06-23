<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommentRequest;
use App\Models\Issue;
use Illuminate\Http\JsonResponse;

class IssueCommentController extends Controller
{
    public function index(Issue $issue): JsonResponse
    {
        $comments = $issue->comments()->latest()->paginate(5);

        return response()->json([
            'html' => view('issues.partials.comment-list', [
                'comments' => $comments,
            ])->render(),
            'pagination' => [
                'current_page' => $comments->currentPage(),
                'last_page' => $comments->lastPage(),
                'prev_page_url' => $comments->previousPageUrl(),
                'next_page_url' => $comments->nextPageUrl(),
                'total' => $comments->total(),
            ],
        ]);
    }

    public function store(CommentRequest $request, Issue $issue): JsonResponse
    {
        $comment = $issue->comments()->create($request->validated());

        return response()->json([
            'comment_html' => view('issues.partials.comment', [
                'comment' => $comment,
            ])->render(),
        ], 201);
    }
}
