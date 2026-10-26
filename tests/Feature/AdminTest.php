<?php

namespace Tests\Feature;

use App\Models\Certification;
use App\Models\Education;
use App\Models\Project;
use App\Models\SkillCategory;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        $user = User::factory()->create();
        $user->is_admin = true;
        $user->save();

        return $user;
    }

    public function test_guests_are_redirected_to_login(): void
    {
        foreach (['/admin', '/admin/projects', '/admin/settings', '/admin/messages'] as $url) {
            $this->get($url)->assertRedirect(route('admin.login'));
        }
        $this->post('/admin/projects', ['title' => 'X'])->assertRedirect(route('admin.login'));
    }

    public function test_non_admin_users_are_forbidden(): void
    {
        $this->actingAs(User::factory()->create())->get('/admin')->assertForbidden();
        $this->actingAs(User::factory()->create())->post('/admin/projects', ['title' => 'X', 'status' => 'draft'])->assertForbidden();
    }

    public function test_is_admin_cannot_be_mass_assigned(): void
    {
        $user = User::create(['name' => 'X', 'email' => 'x@example.com', 'password' => 'secret-password', 'is_admin' => true]);

        $this->assertFalse((bool) $user->fresh()->is_admin);
    }

    public function test_admin_can_sign_in_and_non_admin_cannot(): void
    {
        $admin = $this->admin();
        $this->post('/admin/login', ['email' => $admin->email, 'password' => 'password'])->assertRedirect(route('admin.dashboard'));
        $this->post('/admin/logout');

        $user = User::factory()->create();
        $this->post('/admin/login', ['email' => $user->email, 'password' => 'password'])->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_admin_pages_render(): void
    {
        $this->seed(DatabaseSeeder::class);
        $admin = $this->admin();
        $project = Project::first();

        foreach ([
            '/admin', '/admin/projects', '/admin/projects/create', "/admin/projects/{$project->id}/edit",
            '/admin/project-categories', '/admin/skill-categories', '/admin/skill-categories/create',
            '/admin/experiences', '/admin/experiences/create', '/admin/education', '/admin/education/create',
            '/admin/certifications', '/admin/certifications/create', '/admin/social-links', '/admin/messages', '/admin/settings',
        ] as $url) {
            $this->actingAs($admin)->get($url)->assertOk();
        }
    }

    public function test_admin_can_create_a_project_with_list_fields(): void
    {
        $this->actingAs($this->admin())->post('/admin/projects', [
            'title' => 'Sample Project',
            'short_description' => 'Short text',
            'technologies' => "Laravel\nMySQL\n\n",
            'key_features' => "Feature one\nFeature two",
            'status' => 'published',
            'is_featured' => '1',
            'live_url' => 'https://example.org',
        ])->assertRedirect();

        $project = Project::where('slug', 'sample-project')->firstOrFail();
        $this->assertSame(['Laravel', 'MySQL'], $project->technologies);
        $this->assertTrue($project->is_featured);
        $this->get('/projects/sample-project')->assertOk()->assertSee('Feature two');
    }

    public function test_project_urls_must_be_http(): void
    {
        $this->actingAs($this->admin())
            ->post('/admin/projects', ['title' => 'Bad', 'status' => 'draft', 'live_url' => 'javascript:alert(1)'])
            ->assertSessionHasErrors('live_url');
    }

    public function test_awarded_education_requires_explicit_confirmation(): void
    {
        $admin = $this->admin();
        $data = ['level' => 'bachelor', 'field_of_study' => 'Computer Science', 'status' => 'awarded', 'qualification_title' => 'BSc Computer Science'];

        $this->actingAs($admin)->post('/admin/education', $data)->assertSessionHasErrors('confirm_awarded');
        $this->assertDatabaseCount('education', 0);

        $this->actingAs($admin)->post('/admin/education', $data + ['confirm_awarded' => '1', 'is_published' => '1'])->assertSessionHasNoErrors();
        $this->assertTrue(Education::sole()->isAwarded());
    }

    public function test_admin_can_save_a_certification_description(): void
    {
        $this->actingAs($this->admin())->post('/admin/certifications', [
            'name' => 'Sample Certificate',
            'description' => 'A short summary of the course.',
            'is_published' => '1',
        ])->assertRedirect(route('admin.certifications.index'));

        $this->assertSame('A short summary of the course.', Certification::where('name', 'Sample Certificate')->value('description'));
        $this->get('/')->assertSee('A short summary of the course.');
    }

    public function test_experience_needs_a_position_before_publishing(): void
    {
        $this->actingAs($this->admin())
            ->post('/admin/experiences', ['type' => 'project', 'is_published' => '1'])
            ->assertSessionHasErrors('position');
    }

    public function test_skill_category_syncs_skills_from_lines(): void
    {
        $this->seed(DatabaseSeeder::class);
        $category = SkillCategory::where('slug', 'databases')->firstOrFail();

        $this->actingAs($this->admin())->put("/admin/skill-categories/{$category->id}", [
            'name' => 'Databases',
            'slug' => 'databases',
            'icon' => 'database',
            'skills' => "PostgreSQL\nMySQL\nRedis",
        ])->assertRedirect();

        $this->assertSame(['PostgreSQL', 'MySQL', 'Redis'], $category->fresh()->skills->pluck('name')->all());
    }

    public function test_settings_update_is_reflected_on_the_site(): void
    {
        $this->actingAs($this->admin())->put('/admin/settings', [
            'full_name' => 'Bakunzi Bake Fiston',
            'hero_heading' => 'A new heading for testing',
            'contact_email' => 'contact@example.test',
        ])->assertRedirect(route('admin.settings.edit'));

        $this->get('/')->assertSee('A new heading for testing')->assertSee('contact@example.test');
    }
}
