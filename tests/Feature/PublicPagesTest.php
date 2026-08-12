<?php

namespace Tests\Feature;

use App\Models\Branch;
use App\Models\HealthArticle;
use App\Models\Service;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicPagesTest extends TestCase
{
    use RefreshDatabase;

    public function test_all_public_pages_load_successfully(): void
    {
        $routes = [
            'home', 'about', 'contact', 'privacy-policy',
            'services.index', 'branches.index', 'team.index',
            'education.index', 'appointments.create', 'sitemap',
        ];

        foreach ($routes as $name) {
            $this->get(route($name))->assertOk();
        }
    }

    public function test_unknown_route_returns_404(): void
    {
        $this->get('/this-page-does-not-exist')->assertNotFound();
    }

    public function test_homepage_shows_active_services_and_visible_branches(): void
    {
        $service = Service::factory()->create(['name' => 'Wellness Consultation']);
        Service::factory()->inactive()->create(['name' => 'Retired Service']);
        $branch = Branch::factory()->create(['name' => 'Central Branch']);

        $response = $this->get(route('home'));

        $response->assertOk();
        $response->assertSee('Wellness Consultation');
        $response->assertDontSee('Retired Service');
        $response->assertSee('Central Branch');
    }

    public function test_services_page_can_filter_by_category(): void
    {
        Service::factory()->create(['name' => 'Alpha Service', 'category' => 'Men\'s Health']);
        Service::factory()->create(['name' => 'Beta Service', 'category' => 'Women\'s Health']);

        $response = $this->get(route('services.index', ['category' => "Men's Health"]));

        $response->assertOk();
        $response->assertSee('Alpha Service');
        $response->assertDontSee('Beta Service');
    }

    public function test_draft_article_is_not_publicly_visible(): void
    {
        $draft = HealthArticle::factory()->create([
            'title' => 'Secret Draft',
            'status' => HealthArticle::STATUS_DRAFT,
        ]);

        $this->get(route('education.index'))->assertDontSee('Secret Draft');
        $this->get(route('education.show', $draft))->assertNotFound();
    }

    public function test_published_article_is_publicly_visible(): void
    {
        $article = HealthArticle::factory()->create([
            'title' => 'Understanding Wellness',
            'status' => HealthArticle::STATUS_PUBLISHED,
            'published_at' => now()->subDay(),
        ]);

        $this->get(route('education.index'))->assertSee('Understanding Wellness');
        $this->get(route('education.show', $article))->assertOk()->assertSee('Understanding Wellness');
    }
}
