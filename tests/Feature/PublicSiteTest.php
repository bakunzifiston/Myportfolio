<?php

namespace Tests\Feature;

use App\Models\Certification;
use App\Models\Education;
use App\Models\Experience;
use App\Models\Project;
use App\Models\SiteSetting;
use Database\Seeders\CertificationSeeder;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Tests\TestCase;

class PublicSiteTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    public function test_home_page_renders_profile_and_sections(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('Hi, I&#039;m <span class="text-accent-700 dark:text-accent-400">Bakunzi Bake Fiston</span>.', false)
            ->assertSee('Explore My Projects')
            ->assertSee('Download My CV')
            ->assertSee("Let's Connect", false)
            ->assertSee('id="about"', false)
            ->assertSee('id="projects"', false)
            ->assertSee('id="skills"', false)
            ->assertSee('id="experience"', false)
            ->assertSee('id="education"', false)
            ->assertSee('id="contact"', false)
            ->assertSee('<meta property="og:title"', false)
            ->assertSee('Laravel')
            ->assertSee('PostgreSQL');
    }

    public function test_pages_open_in_dark_mode_by_default(): void
    {
        foreach (['/', '/projects', '/projects/ihuzo', '/resume'] as $url) {
            $this->get($url)->assertSee('class="dark scroll-smooth"', false);
        }
    }

    public function test_education_is_presented_as_studies_not_awarded_degrees(): void
    {
        $this->get('/')
            ->assertSee('Bachelor&#039;s-level studies in Computer Science', false)
            ->assertSee('Bachelor&#039;s-level studies in Software Engineering', false)
            ->assertSee('Master&#039;s-level studies', false)
            ->assertSee('China')
            ->assertSee('Turkish Republic of Northern Cyprus')
            ->assertDontSee('Qualification awarded')
            ->assertDontSee('Bachelor of');
    }

    public function test_awarded_qualification_title_is_shown_only_when_awarded(): void
    {
        $entry = Education::where('field_of_study', 'Computer Science')->first();
        $entry->update(['qualification_title' => 'BSc Computer Science']);

        $this->get('/')->assertDontSee('BSc Computer Science');

        $entry->update(['status' => 'awarded']);

        $this->get('/')->assertSee('BSc Computer Science')->assertSee('Qualification awarded');
    }

    public function test_draft_projects_are_never_public(): void
    {
        $draft = Project::where('slug', 'data-access-and-relation-programme')->firstOrFail();
        $this->assertSame(Project::STATUS_DRAFT, $draft->status);

        $this->get('/')->assertDontSee($draft->title);
        $this->get('/projects')->assertDontSee($draft->title);
        $this->get('/resume')->assertDontSee($draft->title);
        $this->get('/sitemap.xml')->assertDontSee($draft->slug);
        $this->get('/projects/'.$draft->slug)->assertNotFound();
    }

    public function test_project_detail_page_hides_links_that_are_not_configured(): void
    {
        $this->get('/projects/ihuzo')
            ->assertOk()
            ->assertSee('IHUZO')
            ->assertSee('Digital Transformation')
            ->assertDontSee('Visit live site')
            ->assertDontSee('View repository');

        Project::where('slug', 'ihuzo')->update(['live_url' => 'https://example.org/ihuzo']);

        $this->get('/projects/ihuzo')->assertSee('Visit live site')->assertSee('https://example.org/ihuzo');
    }

    public function test_project_gallery_lists_published_projects_and_filters(): void
    {
        $response = $this->get('/projects')->assertOk();
        foreach (['IHUZO', 'MSMEs Go Digital', 'KoraLink', 'RDEP Phase II', 'DBI', 'RwandaMart', 'VIBE', 'NileLingua'] as $title) {
            $response->assertSee($title);
        }

        $this->get('/projects?category=e-learning')
            ->assertOk()
            ->assertSee('aria-pressed="true"', false);
    }

    public function test_nilelingua_is_the_first_project_with_its_case_study(): void
    {
        $this->assertSame('nilelingua', Project::query()->published()->ordered()->value('slug'));

        $this->get('/projects/nilelingua')
            ->assertOk()
            ->assertSee('Language Technology')
            ->assertSee('Nile Lingua Lab (NLL)')
            ->assertSee('Human-in-the-loop review for quality assurance')
            ->assertDontSee('A detailed case study for this project is being prepared.');
    }

    public function test_hero_ships_a_static_fallback_for_the_3d_visual(): void
    {
        $this->get('/')
            ->assertSee('data-hero-visual', false)
            ->assertSee('hero-fallback', false);
    }

    public function test_large_project_preview_only_appears_with_a_real_image(): void
    {
        $this->get('/projects/nilelingua')->assertDontSee('size="hero"', false)->assertDontSee('aspect-[16/7]', false);

        Storage::fake('public');
        $path = UploadedFile::fake()->image('cover.png', 1200, 750)->store('projects', 'public');
        Project::where('slug', 'nilelingua')->update(['thumbnail' => $path, 'thumbnail_alt' => 'NileLingua home page']);

        $this->get('/projects/nilelingua')->assertSee('aspect-[16/7]', false)->assertSee('alt="NileLingua home page"', false);
    }

    public function test_unknown_project_returns_404_page(): void
    {
        $this->get('/projects/does-not-exist')->assertNotFound()->assertSee('This page could not be found.');
    }

    public function test_unpublished_or_incomplete_experience_is_hidden(): void
    {
        Experience::create(['type' => 'employment', 'organization' => 'Hidden Org', 'position' => 'Developer', 'is_published' => false]);
        Experience::create(['type' => 'employment', 'organization' => null, 'position' => null, 'summary' => 'Incomplete entry', 'is_published' => true]);
        Experience::create(['type' => 'employment', 'organization' => 'Visible Org', 'position' => 'Software Developer', 'is_published' => true]);

        $this->get('/')
            ->assertDontSee('Hidden Org')
            ->assertDontSee('Incomplete entry')
            ->assertSee('Visible Org');
    }

    public function test_resume_page_renders(): void
    {
        $this->get('/resume')
            ->assertOk()
            ->assertSee('Professional Summary')
            ->assertSee('Technical Skills')
            ->assertSee('Selected Projects')
            ->assertSee('Education')
            ->assertSee('Download CV as PDF')
            ->assertSee('Certifications');
    }

    public function test_certifications_are_listed_on_the_site_and_cv(): void
    {
        foreach (['/', '/resume'] as $url) {
            $this->get($url)
                ->assertSee('Logic Models')
                ->assertSee('Monitoring, Evaluation, and Learning for Development Professionals (MEAL DPro)')
                ->assertSee('What is Project Management?')
                ->assertSee('Teaching English as a Foreign Language (TEFL), 120 Hours, with Distinction')
                ->assertSee('TR2397942172');
        }

        // The home page shows each certificate's name and short description, without the issuer; the CV keeps issuer and date.
        $this->get('/')
            ->assertDontSee('Humentum')
            ->assertSee('Online course on building logic models that connect project activities to outputs, outcomes and long-term impact.')
            ->assertSee('completed with Distinction.');
        $this->get('/resume')
            ->assertSee('Humentum')
            ->assertSee('Online course on building logic models that connect project activities to outputs, outcomes and long-term impact.');
    }

    public function test_certification_seeder_fills_missing_descriptions_without_overwriting_edits(): void
    {
        Certification::where('name', 'Logic Models')->update(['description' => null]);
        Certification::where('name', 'What is Project Management?')->update(['description' => 'Edited by the owner']);

        $this->seed(CertificationSeeder::class);

        $this->assertStringStartsWith('Online course on building logic models', Certification::where('name', 'Logic Models')->value('description'));
        $this->assertSame('Edited by the owner', Certification::where('name', 'What is Project Management?')->value('description'));
        $this->assertSame(4, Certification::count());
    }

    public function test_resume_downloads_as_generated_pdf(): void
    {
        $response = $this->get('/resume/download')->assertOk();

        $this->assertSame('application/pdf', $response->headers->get('Content-Type'));
        $this->assertStringContainsString('Bakunzi-Bake-Fiston-CV.pdf', $response->headers->get('Content-Disposition'));
        $this->assertStringStartsWith('%PDF', $response->getContent());
    }

    public function test_uploaded_resume_file_takes_precedence(): void
    {
        Storage::fake('public');
        $path = UploadedFile::fake()->create('cv.pdf', 20, 'application/pdf')->store('settings', 'public');
        SiteSetting::create(['key' => 'resume_file', 'value' => $path]);

        $response = $this->get('/resume/download')->assertOk();

        $this->assertInstanceOf(StreamedResponse::class, $response->baseResponse);
    }

    public function test_settings_drive_public_content(): void
    {
        SiteSetting::create(['key' => 'contact_email', 'value' => 'hello@example.test']);
        SiteSetting::create(['key' => 'location', 'value' => 'Kigali, Rwanda']);

        $this->get('/')->assertSee('mailto:hello@example.test', false)->assertSee('Kigali, Rwanda');
    }

    public function test_default_contact_details_are_shown(): void
    {
        $this->get('/')
            ->assertSee('mailto:bakunzifiston@gmail.com', false)
            ->assertSee('tel:+250783092757', false);
        $this->get('/resume')->assertSee('+250 783 092 757');
    }

    public function test_social_links_without_urls_are_not_rendered(): void
    {
        $this->get('/')->assertDontSee('rel="noopener noreferrer me"', false);
    }

    public function test_sitemap_lists_public_pages(): void
    {
        $this->get('/sitemap.xml')
            ->assertOk()
            ->assertHeader('Content-Type', 'application/xml')
            ->assertSee(route('projects.show', 'koralink'));
    }

    public function test_seeders_do_not_overwrite_edited_content(): void
    {
        Project::where('slug', 'ihuzo')->update(['short_description' => 'Edited by the owner']);

        $this->seed(DatabaseSeeder::class);

        $this->assertSame('Edited by the owner', Project::where('slug', 'ihuzo')->value('short_description'));
        $this->assertSame(1, Project::where('slug', 'ihuzo')->count());
    }
}
