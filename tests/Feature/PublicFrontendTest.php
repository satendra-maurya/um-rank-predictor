<?php

namespace Tests\Feature;

use App\Enums\ActiveStatus;
use App\Models\Category;
use App\Models\ExamStage;
use App\Models\ImportantLink;
use App\Models\Notice;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class PublicFrontendTest extends TestCase
{
    use RefreshDatabase;

    public function test_homepage_loads_successfully_without_admin_login(): void
    {
        $this->seed(DatabaseSeeder::class);

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Rank Predictor');
        $response->assertSeeText('Know Your Expected Rank Before You Compete');
        $response->assertSeeText('Check Your Rank');
        $response->assertDontSeeText('Admin Login');
    }

    public function test_notices_index_and_detail_page(): void
    {
        $notice = Notice::factory()->create([
            'title' => 'SSC CGL Tier 1 Answer Key Released Notice',
            'slug' => 'ssc-cgl-tier-1-answer-key-released-notice',
            'content' => 'Official answer key content description.',
            'status' => ActiveStatus::ACTIVE,
        ]);

        $responseList = $this->get('/notices');
        $responseList->assertStatus(200);
        $responseList->assertSee('SSC CGL Tier 1 Answer Key Released Notice');

        $responseDetail = $this->get('/notices/'.$notice->slug);
        $responseDetail->assertStatus(200);
        $responseDetail->assertSee('Official answer key content description.');
    }

    public function test_rank_predictor_livewire_component_selection_flow(): void
    {
        $this->seed(DatabaseSeeder::class);

        Livewire::test('rank-predictor')
            ->assertSee('Select Your Exam Category')
            ->call('selectCategory', 'ssc')
            ->assertSet('step', 2)
            ->assertSee('SSC CGL')
            ->call('selectCategory', 'state-exams')
            ->assertSet('step', 2)
            ->assertSee('Select State');
    }

    public function test_rank_predictor_livewire_submission_creates_prediction(): void
    {
        $this->seed(DatabaseSeeder::class);

        $category = Category::first();
        $cglStage = ExamStage::where('slug', 'tier-1')->first();

        Livewire::test('rank-predictor')
            ->set('selectedCategorySlug', 'ssc')
            ->set('selectedExamId', $cglStage->examCycle->exam_id)
            ->set('selectedCycleId', $cglStage->exam_cycle_id)
            ->set('selectedStageId', $cglStage->id)
            ->set('name', 'Rahul Kumar')
            ->set('roll_number', '2201004589')
            ->set('dob', '2000-05-15')
            ->set('raw_score', 145.50)
            ->set('category_id', $category->id)
            ->set('gender', 'Male')
            ->set('consent', true)
            ->call('submitPrediction')
            ->assertSet('step', 6)
            ->assertSee('Your Estimated Rank')
            ->assertSee('2201004589');
    }

    public function test_home_page_displays_database_driven_exam_authorities(): void
    {
        $this->seed(DatabaseSeeder::class);

        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('Staff Selection Commission');
        $response->assertSee('/rank-predictor/ssc/available-exams');
    }

    public function test_available_exams_page_loads_exams_for_authority(): void
    {
        $this->seed(DatabaseSeeder::class);

        $response = $this->get('/rank-predictor/ssc/available-exams');
        $response->assertStatus(200);
        $response->assertSee('SSC Exams');
        $response->assertSee('SSC CGL');
    }

    public function test_available_exams_page_returns_404_for_invalid_authority(): void
    {
        $response = $this->get('/rank-predictor/non-existent-authority/available-exams');
        $response->assertStatus(404);
    }

    public function test_homepage_hides_notices_and_links_when_no_records_exist(): void
    {
        Notice::query()->delete();
        ImportantLink::query()->delete();

        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('Select Your Exam');
        $response->assertDontSee('Latest Updates & Notices');
        $response->assertDontSee('Important Direct Links');
        $response->assertDontSee('No official notices published yet.');
        $response->assertDontSee('No links configured.');
    }

    public function test_homepage_shows_only_notices_when_links_do_not_exist(): void
    {
        ImportantLink::query()->delete();
        Notice::factory()->create([
            'title' => 'Test Notice Entry',
            'status' => ActiveStatus::ACTIVE,
        ]);

        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('Select Your Exam');
        $response->assertSee('Latest Updates & Notices');
        $response->assertDontSee('Important Direct Links');
    }

    public function test_homepage_shows_only_links_when_notices_do_not_exist(): void
    {
        Notice::query()->delete();
        ImportantLink::factory()->create([
            'title' => 'Test Direct Link',
            'url' => 'https://example.com',
            'status' => ActiveStatus::ACTIVE,
        ]);

        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('Select Your Exam');
        $response->assertDontSee('Latest Updates & Notices');
        $response->assertSee('Important Direct Links');
    }

    public function test_homepage_shows_both_sections_when_both_exist(): void
    {
        Notice::factory()->create([
            'title' => 'Test Notice Entry',
            'status' => ActiveStatus::ACTIVE,
        ]);
        ImportantLink::factory()->create([
            'title' => 'Test Direct Link',
            'url' => 'https://example.com',
            'status' => ActiveStatus::ACTIVE,
        ]);

        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('Select Your Exam');
        $response->assertSee('Latest Updates & Notices');
        $response->assertSee('Important Direct Links');
    }

    public function test_start_over_redirects_to_current_authority_available_exams_page(): void
    {
        $this->seed(DatabaseSeeder::class);

        Livewire::test('rank-predictor', [
            'category' => 'upsssc',
            'exam' => 'upsssc-pet',
        ])
            ->assertSee('/rank-predictor/upsssc/available-exams')
            ->call('resetPredictor')
            ->assertRedirect(route('rank-predictor.authority.available-exams', ['authority' => 'upsssc']));
    }
}
