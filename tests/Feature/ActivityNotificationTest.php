<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Client;
use App\Models\Invoice;
use App\Models\Receipt;
use App\Models\BusinessUnit;
use App\Notifications\SystemActivityNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class ActivityNotificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_invoice_creation_logs_activity_and_notifies_other_users(): void
    {
        $user1 = User::factory()->create(['role' => 'admin', 'name' => 'Admin User']);
        $user2 = User::factory()->create(['role' => 'owner', 'name' => 'Owner User']);

        $businessUnit = BusinessUnit::create([
            'name' => 'Jaya-Website',
            'slug' => 'jaya-website',
        ]);
        $client = Client::create([
            'kode_client' => 'CL-001',
            'nama_client' => 'Test Client',
            'status' => 'aktif',
        ]);

        $response = $this->actingAs($user1)->post(route('invoices.store'), [
            'business_unit_id' => $businessUnit->id,
            'client_id' => $client->id,
            'due_date' => now()->addDays(7)->format('Y-m-d'),
            'status' => 'draft',
            'items' => [
                [
                    'deskripsi' => 'Testing service',
                    'qty' => 1,
                    'harga' => 100000,
                ]
            ],
        ]);

        $response->assertRedirect(route('invoices.index'));

        // Assert Activity Log is created
        $this->assertDatabaseHas('activity_logs', [
            'user_id' => $user1->id,
            'action' => 'created_invoice',
        ]);

        // Assert Notification is sent to other user (user2)
        $this->assertEquals(1, $user2->notifications()->count());
        $notificationData = $user2->notifications()->first()->data;
        $this->assertEquals('Invoice Created', $notificationData['title']);
        $this->assertStringContainsString('Admin User', $notificationData['message']);
    }

    public function test_receipt_creation_logs_activity_and_notifies_other_users(): void
    {
        $user1 = User::factory()->create(['role' => 'admin', 'name' => 'Admin User']);
        $user2 = User::factory()->create(['role' => 'owner', 'name' => 'Owner User']);

        $businessUnit = BusinessUnit::create([
            'name' => 'Jaya-Website',
            'slug' => 'jaya-website',
        ]);
        $client = Client::create([
            'kode_client' => 'CL-001',
            'nama_client' => 'Test Client',
            'status' => 'aktif',
        ]);

        $invoice = Invoice::create([
            'invoice_number' => 'INV-7777-' . date('Y'),
            'business_unit_id' => $businessUnit->id,
            'client_id' => $client->id,
            'status' => 'draft',
            'subtotal' => 100000,
            'discount' => 0,
            'ppn' => 0,
            'pph' => 0,
            'total' => 100000,
        ]);

        $response = $this->actingAs($user1)->put(route('invoices.update', $invoice->id), [
            'business_unit_id' => $businessUnit->id,
            'client_id' => $client->id,
            'due_date' => now()->addDays(7)->format('Y-m-d'),
            'status' => 'paid',
            'items' => [
                [
                    'deskripsi' => 'Testing service',
                    'qty' => 1,
                    'harga' => 100000,
                ]
            ],
        ]);

        $response->assertRedirect(route('invoices.index'));

        // Assert Receipt is created in database
        $this->assertDatabaseHas('receipts', [
            'invoice_id' => $invoice->id,
        ]);

        // Assert Activity Log for receipt creation is present
        $this->assertDatabaseHas('activity_logs', [
            'user_id' => $user1->id,
            'action' => 'created_receipt',
        ]);

        // Assert Notification is sent to user2 for receipt creation
        $this->assertTrue(
            $user2->notifications()
                ->where('data->type', 'finance')
                ->where('data->title', 'Receipt Created')
                ->exists()
        );
    }
}
