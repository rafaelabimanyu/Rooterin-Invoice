<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\BusinessUnit;
use App\Models\Client;
use App\Models\Invoice;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_owner_role_can_access_dashboard_with_ai_insights()
    {
        $user = User::factory()->create(['role' => 'owner']);
        
        $response = $this->actingAs($user)->get(route('dashboard'));

        $response->assertStatus(200);
        $response->assertSee('AI Predictive Recommendation');
    }

    public function test_staff_role_can_access_dashboard_without_ai_insights()
    {
        $user = User::factory()->create(['role' => 'staff']);
        
        $response = $this->actingAs($user)->get(route('dashboard'));

        $response->assertStatus(200);
        $response->assertDontSee('AI Predictive Recommendation');
    }

    public function test_financial_advisory_component_renders_and_can_be_refreshed()
    {
        $user = User::factory()->create(['role' => 'owner']);
        $locale = app()->getLocale();
        
        \Livewire\Livewire::actingAs($user)
            ->test(\App\Livewire\Dashboard\FinancialAdvisory::class)
            ->assertSet('locale', $locale)
            ->call('refreshAnalysis')
            ->assertSet('locale', $locale);
    }
}
