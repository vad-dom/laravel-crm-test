<?php

namespace Tests\Feature;

use App\Models\Ticket;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class TicketApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_ticket_can_be_created(): void
    {
        $response = $this->postJson('/api/tickets', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'phone_e164' => '+15550000001',
            'subject' => 'Test',
            'message' => 'Hello',
        ]);

        $response->assertStatus(201);

        $this->assertDatabaseHas('tickets', [
            'subject' => 'Test',
        ]);
    }

    public function test_ticket_validation_error(): void
    {
        $response = $this->postJson('/api/tickets');

        $response->assertStatus(422);
    }

    public function test_only_one_ticket_per_day(): void
    {
        $data = [
            'name' => 'Test',
            'email' => 'test@example.com',
            'phone_e164' => '+15550000002',
            'subject' => 'Test',
            'message' => 'Hello',
        ];

        $this->postJson('/api/tickets', $data)->assertStatus(201);

        $this->postJson('/api/tickets', $data)->assertStatus(422);
    }

    public function test_ticket_can_be_created_with_attachment(): void
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->image('test.png');

        $response = $this->postJson('/api/tickets', [
            'name' => 'Test User',
            'email' => 'file@example.com',
            'phone_e164' => '+15550000003',
            'subject' => 'Attachment test',
            'message' => 'Hello',
            'attachments' => [$file],
        ]);

        $response->assertStatus(201);

        $ticket = Ticket::first();

        $this->assertCount(1, $ticket->getMedia('attachments'));
    }

    public function test_customer_is_reused_for_same_email_and_phone(): void
    {
        $data = [
            'name' => 'Test User',
            'email' => 'same@example.com',
            'phone_e164' => '+15550000004',
            'subject' => 'Test',
            'message' => 'Hello',
        ];

        Carbon::setTestNow('2026-03-01 10:00:00');

        $this->postJson('/api/tickets', $data)->assertStatus(201);

        Carbon::setTestNow('2026-03-02 10:00:00');

        $this->postJson('/api/tickets', $data)->assertStatus(201);

        $this->assertDatabaseCount('customers', 1);

        Carbon::setTestNow();
    }

    public function test_statistics_returns_correct_counts(): void
    {
        Carbon::setTestNow('2026-03-10 12:00:00');

        Ticket::factory()->count(2)->create([
            'created_at' => now()
        ]);

        Ticket::factory()->count(3)->create([
            'created_at' => now()->subDays(3)
        ]);

        $response = $this->getJson('/api/tickets/statistics');

        $response->assertStatus(200);

        $response->assertJson([
            'data' => [
                'day' => 2,
                'week' => 5,
                'month' => 5,
            ]
        ]);

        Carbon::setTestNow();
    }
}
