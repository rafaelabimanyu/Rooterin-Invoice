<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\ChronosEvent;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Livewire\Livewire;
use App\Livewire\ChronosCalendar;

class ChronosCalendarTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_access_chronos_page(): void
    {
        $user = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($user)->get(route('chronos.index'));

        $response->assertStatus(200);
        $response->assertSeeLivewire(ChronosCalendar::class);
    }

    public function test_user_can_create_operational_reminder_via_livewire(): void
    {
        $user = User::factory()->create(['role' => 'owner']);

        Livewire::actingAs($user)
            ->test(ChronosCalendar::class)
            ->set('selectedDate', '2026-05-25')
            ->set('reminderTitle', 'Dev Sprint meeting')
            ->set('reminderDescription', 'Discuss API integrations')
            ->set('reminderCategory', 'meeting')
            ->set('reminderColor', 'emerald')
            ->call('saveReminder')
            ->assertHasNoErrors()
            ->assertSet('showModal', false);

        $this->assertDatabaseHas('chronos_events', [
            'title' => 'Dev Sprint meeting',
            'status_type' => 'meeting',
            'color' => 'emerald',
            'start_date' => '2026-05-25 00:00:00',
        ]);
    }

    public function test_user_can_update_operational_reminder_via_livewire(): void
    {
        $user = User::factory()->create(['role' => 'owner']);
        $reminder = ChronosEvent::create([
            'title' => 'Old Title',
            'description' => 'Old description',
            'status_type' => 'internal',
            'color' => 'indigo',
            'start_date' => '2026-05-25',
            'responsible_staff_id' => $user->id,
        ]);

        Livewire::actingAs($user)
            ->test(ChronosCalendar::class)
            ->call('openEditModal', $reminder->id)
            ->assertSet('reminderTitle', 'Old Title')
            ->set('reminderTitle', 'New Sprint Title')
            ->set('reminderColor', 'rose')
            ->call('saveReminder')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('chronos_events', [
            'id' => $reminder->id,
            'title' => 'New Sprint Title',
            'color' => 'rose',
        ]);
    }

    public function test_user_can_delete_operational_reminder_via_livewire(): void
    {
        $user = User::factory()->create(['role' => 'owner']);
        $reminder = ChronosEvent::create([
            'title' => 'To be deleted',
            'status_type' => 'internal',
            'color' => 'slate',
            'start_date' => '2026-05-25',
            'responsible_staff_id' => $user->id,
        ]);

        Livewire::actingAs($user)
            ->test(ChronosCalendar::class)
            ->call('deleteReminder', $reminder->id)
            ->assertHasNoErrors();

        $this->assertDatabaseMissing('chronos_events', [
            'id' => $reminder->id,
        ]);
    }
}
