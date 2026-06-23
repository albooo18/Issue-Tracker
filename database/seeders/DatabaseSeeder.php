<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Issue;
use App\Models\Project;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $owner = User::factory()->create([
            'name' => 'Project Owner',
            'email' => 'owner@example.com',
        ]);

        $members = User::factory()->count(3)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $tags = Tag::factory()->count(6)->create();
        $projects = Project::factory()->count(3)->for($owner, 'owner')->create();

        $projects->each(function (Project $project) use ($tags): void {
            $issues = Issue::factory()->count(4)->create([
                'project_id' => $project->id,
            ]);

            $issues->each(function (Issue $issue) use ($tags, $members): void {
                $attachedTags = collect($tags->random(rand(1, 3)))->pluck('id')->all();
                $issue->tags()->attach($attachedTags);

                $issue->users()->attach($members->random(rand(1, 3))->pluck('id')->all());

                Comment::factory()->count(3)->create([
                    'issue_id' => $issue->id,
                ]);
            });
        });
    }
}
