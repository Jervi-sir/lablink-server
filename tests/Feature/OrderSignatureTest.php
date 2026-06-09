<?php

use App\Models\Lab;
use App\Models\LabCategory;
use App\Models\Order;
use App\Models\Student;
use App\Models\User;
use App\Models\Wilaya;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('student can sign contract and generate PDF', function () {
    // 0. Create prerequisite taxomony
    $wilaya = Wilaya::create([
        'number' => '16',
        'code' => '16000',
        'en' => 'Algiers',
        'fr' => 'Alger',
        'ar' => 'الجزائر',
    ]);

    $category = LabCategory::create([
        'code' => 'general',
        'en' => 'General',
    ]);

    // 1. Create Student User and Student Profile
    $studentUser = User::factory()->create();
    $student = Student::create([
        'user_id' => $studentUser->id,
        'wilaya_id' => $wilaya->id,
        'full_name' => 'John Student',
    ]);

    // 2. Create Lab User and Lab Profile
    $labUser = User::factory()->create();
    $lab = Lab::create([
        'user_id' => $labUser->id,
        'wilaya_id' => $wilaya->id,
        'lab_category_id' => $category->id,
        'brand_name' => 'Alpha Lab',
    ]);

    // 3. Create Order
    $order = Order::create([
        'student_id' => $studentUser->id,
        'lab_id' => $labUser->id,
        'status' => 'estimation_provided',
        'total_price' => 1500.00,
    ]);

    // 4. Send request to signature endpoint
    $response = $this->actingAs($studentUser)
        ->postJson("/api/orders/{$order->id}/signature", [
            'signature_paths' => [
                '/tmp/sig1.png',
            ],
        ]);

    // 5. Assert successful response and contract_pdf_url is set and file exists
    $response->assertStatus(200);
    $response->assertJsonPath('status', 'success');

    $order->refresh();
    expect($order->status)->toBe('confirmed');
    expect($order->contract_pdf_url)->not->toBeNull();

    // Assert the file was actually written to public contracts directory
    $fileName = "contracts/contract_{$order->id}.pdf";
    $fullPath = storage_path('app/public/'.$fileName);
    expect(file_exists($fullPath))->toBeTrue();

    // Clean up
    if (file_exists($fullPath)) {
        unlink($fullPath);
    }
});
