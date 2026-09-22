<?php

namespace Tests\Feature;

use App\Enums\ActiveStatus;
use App\Models\Category;
use App\Models\ExamStage;
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
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('UM RANK PREDICTOR');
        $response->assertSeeText('Know Your Expected Rank Before You Compete');
        $response->assertSeeText('Check Your Rank Now');
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
            ->assertSee('SSC Combined Graduate Level')
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
            ->set('step', 5)
            ->set('name', 'Rohan Sharma')
            ->set('category_id', $category->id)
            ->set('gender', 'Male')
            ->set('total_questions', 100)
            ->set('correct_answers', 75)
            ->set('incorrect_answers', 20)
            ->call('submitPrediction')
            ->assertSet('step', 6)
            ->assertSee('Your Rank Prediction')
            ->assertSee('Rohan Sharma');
    }
}
