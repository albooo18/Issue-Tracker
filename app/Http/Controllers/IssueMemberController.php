<?php

namespace App\Http\Controllers;

use App\Models\Issue;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class IssueMemberController extends Controller
{
    public function store(Request $request, Issue $issue): JsonResponse
    {
        $validated = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
        ]);

        $issue->users()->syncWithoutDetaching([$validated['user_id']]);
        $issue->load('users');

        return response()->json([
            'members_html' => view('issues.partials.members', [
                'issue' => $issue,
            ])->render(),
        ]);
    }

    public function destroy(Issue $issue, User $user): JsonResponse
    {
        $issue->users()->detach($user->id);
        $issue->load('users');

        return response()->json([
            'members_html' => view('issues.partials.members', [
                'issue' => $issue,
            ])->render(),
        ]);
    }
}
