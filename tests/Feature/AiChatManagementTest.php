<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\AiChatHistory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AiChatManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_owner_user_can_access_ai_assistant_page(): void
    {
        $user = User::factory()->create(['role' => User::ROLE_ADMIN]);

        $response = $this->actingAs($user)->get(route('ai-assistant.index'));

        $response->assertStatus(200);
    }

    public function test_staff_user_cannot_access_ai_assistant_page(): void
    {
        $user = User::factory()->create(['role' => User::ROLE_STAFF]);

        $response = $this->actingAs($user)->get(route('ai-assistant.index'));

        $response->assertStatus(403);
    }

    public function test_user_can_rename_chat_session(): void
    {
        $user = User::factory()->create(['role' => User::ROLE_ADMIN]);
        $sessionId = 'test-session-123';

        AiChatHistory::create([
            'user_id' => $user->id,
            'session_id' => $sessionId,
            'message' => 'Hello AI',
            'response' => 'Hello User'
        ]);

        $response = $this->actingAs($user)->post(route('ai-assistant.session.rename', $sessionId), [
            'title' => 'New Chat Title'
        ]);

        $response->assertJson(['success' => true]);

        $this->assertDatabaseHas('ai_chat_histories', [
            'session_id' => $sessionId,
            'title' => 'New Chat Title'
        ]);
    }

    public function test_user_can_delete_chat_session(): void
    {
        $user = User::factory()->create(['role' => User::ROLE_ADMIN]);
        $sessionId = 'test-session-456';

        AiChatHistory::create([
            'user_id' => $user->id,
            'session_id' => $sessionId,
            'message' => 'Hello AI',
            'response' => 'Hello User'
        ]);

        $response = $this->actingAs($user)->delete(route('ai-assistant.session.delete', $sessionId));

        $response->assertJson(['success' => true]);

        $this->assertDatabaseMissing('ai_chat_histories', [
            'session_id' => $sessionId
        ]);
    }
}
