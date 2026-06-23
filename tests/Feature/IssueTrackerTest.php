<?php

namespace Tests\Feature;

use App\Models\Issue;
use App\Models\Project;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class IssueTrackerTest extends TestCase
{
    use RefreshDatabase;

    public function test_projects_and_issues_can_be_rendered(): void
    {
        $project = Project::factory()->create([
            'name' => 'Alpha Project',
        ]);

        $issue = Issue::factory()->create([
            'project_id' => $project->id,
            'title' => 'Fix dashboard bug',
        ]);

        $response = $this->get(route('projects.show', $project));

        $response->assertOk();
        $response->assertSee('Alpha Project');
        $response->assertSee('Fix dashboard bug');

        $response = $this->get(route('issues.show', $issue));

        $response->assertOk();
        $response->assertSee('Fix dashboard bug');
    }

    public function test_issue_comments_can_be_loaded_and_created_via_ajax(): void
    {
        $user = User::factory()->create();
        $issue = Issue::factory()->create();

        $this->actingAs($user)
            ->postJson(route('issues.comments.store', $issue), [
                'author_name' => 'Sam',
                'body' => 'Looks good to me.',
            ])
            ->assertCreated()
            ->assertJsonStructure(['comment_html']);

        $this->getJson(route('issues.comments.index', $issue))
            ->assertOk()
            ->assertJsonStructure(['html', 'pagination' => ['current_page', 'last_page', 'prev_page_url', 'next_page_url', 'total']]);
    }

    public function test_tags_can_attach_and_detach_from_issues_via_ajax(): void
    {
        $user = User::factory()->create();
        $issue = Issue::factory()->create();
        $tag = Tag::factory()->create();

        $this->actingAs($user)
            ->postJson(route('issues.tags.store', $issue), [
                'tag_id' => $tag->id,
            ])
            ->assertOk()
            ->assertJsonStructure(['tags_html']);

        $this->assertDatabaseHas('issue_tag', [
            'issue_id' => $issue->id,
            'tag_id' => $tag->id,
        ]);

        $this->actingAs($user)
            ->deleteJson(route('issues.tags.destroy', [$issue, $tag]))
            ->assertOk()
            ->assertJsonStructure(['tags_html']);

        $this->assertDatabaseMissing('issue_tag', [
            'issue_id' => $issue->id,
            'tag_id' => $tag->id,
        ]);
    }

    public function test_owners_can_manage_projects_and_search_issues_and_assign_members(): void
    {
        $owner = User::factory()->create();
        $member = User::factory()->create();
        $project = Project::factory()->for($owner, 'owner')->create([
            'name' => 'Owner Project',
        ]);
        $issue = Issue::factory()->create([
            'project_id' => $project->id,
            'title' => 'Searchable login issue',
            'description' => 'This should appear in search.',
        ]);

        $this->actingAs($owner)
            ->get(route('projects.edit', $project))
            ->assertOk();

        $this->actingAs($owner)
            ->postJson(route('issues.members.store', $issue), [
                'user_id' => $member->id,
            ])
            ->assertOk()
            ->assertJsonStructure(['members_html']);

        $this->assertDatabaseHas('issue_user', [
            'issue_id' => $issue->id,
            'user_id' => $member->id,
        ]);

        $this->getJson(route('issues.search', ['q' => 'Searchable']))
            ->assertOk()
            ->assertJsonStructure(['cards_html', 'pagination' => ['current_page', 'last_page', 'prev_page_url', 'next_page_url', 'total']]);
    }

    public function test_registered_user_can_create_edit_and_delete_projects(): void
    {
        $user = User::factory()->create();

        $createResponse = $this->actingAs($user)->post(route('projects.store'), [
            'name' => 'New Project',
            'description' => 'Created from the form.',
            'start_date' => '2026-06-01',
            'deadline' => '2026-06-30',
        ]);

        $createResponse->assertRedirect();

        $project = Project::query()->where('name', 'New Project')->firstOrFail();

        $this->assertSame($user->id, $project->owner_id);

        $this->actingAs($user)
            ->put(route('projects.update', $project), [
                'name' => 'Updated Project',
                'description' => 'Updated.',
                'start_date' => '2026-06-02',
                'deadline' => '2026-07-02',
            ])
            ->assertRedirect(route('projects.show', $project));

        $this->actingAs($user)
            ->delete(route('projects.destroy', $project))
            ->assertRedirect(route('projects.index'));

        $this->assertDatabaseMissing('projects', [
            'id' => $project->id,
        ]);
    }
}
