<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Role;

class RoleAccessTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(); // Run seeders to get roles and default users
    }

    public function test_staff_can_access_verification_endpoint()
    {
        $staff = User::where('email', 'staff@exporthub.com')->first();
        
        $response = $this->actingAs($staff)->postJson('/api/staff/verify-ticket', [
            'qr_code' => 'invalid_code' // Just testing access, not logic
        ]);

        // Should be 404 (Ticket not found) or 200/400, but NOT 403 Forbidden
        $this->assertNotEquals(403, $response->status());
    }

    public function test_attendee_cannot_access_verification_endpoint()
    {
        $attendee = User::where('email', 'test@example.com')->first();
        
        $response = $this->actingAs($attendee)->postJson('/api/staff/verify-ticket', [
            'qr_code' => 'invalid_code'
        ]);

        $response->assertStatus(403);
    }

    public function test_admin_can_assign_role()
    {
        $admin = User::where('email', 'admin@exporthub.com')->first();
        $user = User::factory()->create();
        
        $response = $this->actingAs($admin)->postJson("/api/admin/users/{$user->id}/assign-role", [
            'role' => 'staff'
        ]);

        $response->assertStatus(200);
        $this->assertTrue($user->fresh()->hasRole('staff'));
    }

    public function test_non_admin_cannot_assign_role()
    {
        $staff = User::where('email', 'staff@exporthub.com')->first();
        $user = User::factory()->create();
        
        $response = $this->actingAs($staff)->postJson("/api/admin/users/{$user->id}/assign-role", [
            'role' => 'admin'
        ]);

        $response->assertStatus(403);
    }
}
