<?php

use App\Models\Payment;
use App\Models\Registration;
use App\Models\Ticket;
use App\Models\User;
use App\Models\Workshop;

function createTestWorkshop(array $attributes = []): Workshop
{
    return Workshop::create(array_merge([
        'title' => 'Laravel Enterprise & DevOps Architecture',
        'slug' => 'laravel-enterprise-devops-architecture',
        'category' => 'Web Development',
        'instructor_name' => 'Fauzi Rahman, S.Kom.',
        'instructor_title' => 'Senior Backend Engineer',
        'description' => 'Workshop intensif implementasi arsitektur Laravel berskala enterprise.',
        'date' => '2026-12-10',
        'start_time' => '08:30',
        'end_time' => '15:30',
        'location_type' => 'offline',
        'location' => 'Lab Software Engineering Lt. 3, Kampus Pesat IT',
        'price' => 150000,
        'quota' => 30,
        'status' => 'available',
        'is_featured' => true,
    ], $attributes));
}

test('landing page loads successfully with featured workshops', function () {
    $workshop = createTestWorkshop();

    $response = $this->get('/');

    $response->assertStatus(200);
    $response->assertSee('WORKSHOP');
    $response->assertSee('Laravel Enterprise & DevOps Architecture');
});

test('workshop catalog loads and can filter by category', function () {
    createTestWorkshop(['title' => 'Web Development Workshop', 'category' => 'Web Development', 'slug' => 'web-dev']);
    createTestWorkshop(['title' => 'Cyber Security Workshop', 'category' => 'Cyber Security', 'slug' => 'cyber-sec']);

    $response = $this->get('/workshops?category=Cyber+Security');

    $response->assertStatus(200);
    $response->assertSee('Cyber Security Workshop');
});

test('workshop detail page displays schedule and booking button', function () {
    $workshop = createTestWorkshop();

    $response = $this->get('/workshops/'.$workshop->slug);

    $response->assertStatus(200);
    $response->assertSee($workshop->title);
    $response->assertSee($workshop->instructor_name);
    $response->assertSee('Rp 150.000');
});

test('guest user can register an account and is assigned participant role', function () {
    $response = $this->post('/register', [
        'name' => 'Budi Setiawan',
        'email' => 'budi@example.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
        'phone' => '081234567890',
        'institution' => 'SMK Pesat IT',
    ]);

    $response->assertRedirect('/workshops');
    $this->assertAuthenticated();

    $user = User::where('email', 'budi@example.com')->first();
    expect($user)->not->toBeNull();
    expect($user->role)->toBe('participant');
});

test('participant can register for a workshop and payment record is created', function () {
    $user = User::factory()->create(['role' => 'participant']);
    $workshop = createTestWorkshop();

    $response = $this->actingAs($user)->post('/workshops/'.$workshop->slug.'/register', [
        'full_name' => 'Peserta Uji',
        'email' => $user->email,
        'phone' => '081299998888',
        'institution' => 'SMK Pesat',
        'payment_method' => 'Midtrans',
    ]);

    $registration = Registration::where('user_id', $user->id)->where('workshop_id', $workshop->id)->first();
    expect($registration)->not->toBeNull();
    expect($registration->status)->toBe('pending_payment');

    $payment = Payment::where('registration_id', $registration->id)->first();
    expect($payment)->not->toBeNull();
    expect($payment->status)->toBe('pending');

    $response->assertRedirect(route('payments.show', $payment->payment_code));
});

test('system rejects duplicate registration for the same workshop (anti-duplicate check)', function () {
    $user = User::factory()->create(['role' => 'participant']);
    $workshop = createTestWorkshop();

    // First registration
    Registration::create([
        'registration_code' => 'REG-TEST-1',
        'user_id' => $user->id,
        'workshop_id' => $workshop->id,
        'full_name' => $user->name,
        'email' => $user->email,
        'phone' => '08123456789',
        'status' => 'pending_payment',
    ]);

    // Attempt second registration for the same workshop
    $response = $this->actingAs($user)->post('/workshops/'.$workshop->slug.'/register', [
        'full_name' => $user->name,
        'email' => $user->email,
        'phone' => '08123456789',
        'payment_method' => 'Midtrans',
    ]);

    $response->assertRedirect(route('workshops.show', $workshop->slug));
    $response->assertSessionHas('error', 'Pendaftaran ganda ditolak! Anda sudah terdaftar dalam workshop ini.');
});

test('user cannot access payment or ticket belonging to another participant', function () {
    $userA = User::factory()->create(['role' => 'participant']);
    $userB = User::factory()->create(['role' => 'participant']);
    $workshop = createTestWorkshop();

    $regA = Registration::create([
        'registration_code' => 'REG-AAA-1',
        'user_id' => $userA->id,
        'workshop_id' => $workshop->id,
        'full_name' => $userA->name,
        'email' => $userA->email,
        'phone' => '0811111111',
        'status' => 'paid',
    ]);

    $paymentA = Payment::create([
        'payment_code' => 'PAY-AAA-1',
        'registration_id' => $regA->id,
        'user_id' => $userA->id,
        'amount' => 150000,
        'payment_method' => 'Midtrans',
        'status' => 'verified',
    ]);

    $ticketA = Ticket::create([
        'ticket_code' => 'TIX-AAA-1',
        'registration_id' => $regA->id,
        'user_id' => $userA->id,
        'workshop_id' => $workshop->id,
        'qr_token' => 'token-aaa-1',
        'status' => 'valid',
    ]);

    // User B tries to view User A's payment
    $responsePay = $this->actingAs($userB)->get('/payments/'.$paymentA->payment_code);
    $responsePay->assertStatus(403);

    // User B tries to view User A's ticket
    $responseTix = $this->actingAs($userB)->get('/tickets/'.$ticketA->ticket_code);
    $responseTix->assertStatus(403);
});

test('participant cannot access admin routes, only admin can access', function () {
    $participant = User::factory()->create(['role' => 'participant']);
    $admin = User::factory()->create(['role' => 'admin']);

    // Participant blocked
    $this->actingAs($participant)->get('/admin/dashboard')->assertStatus(403);
    $this->actingAs($participant)->get('/admin/workshops')->assertStatus(403);

    // Admin allowed
    $this->actingAs($admin)->get('/admin/dashboard')->assertStatus(200);
    $this->actingAs($admin)->get('/admin/workshops')->assertStatus(200);
});

test('midtrans finish callback marks payment verified and issues ticket', function () {
    $user = User::factory()->create(['role' => 'participant']);
    $workshop = createTestWorkshop();

    $reg = Registration::create([
        'registration_code' => 'REG-MID-1',
        'user_id' => $user->id,
        'workshop_id' => $workshop->id,
        'full_name' => $user->name,
        'email' => $user->email,
        'phone' => '08123456789',
        'status' => 'pending_payment',
    ]);

    $payment = Payment::create([
        'payment_code' => 'PAY-MID-1',
        'registration_id' => $reg->id,
        'user_id' => $user->id,
        'amount' => 150000,
        'payment_method' => 'Midtrans',
        'status' => 'pending',
    ]);

    $response = $this->actingAs($user)->post('/payments/'.$payment->payment_code.'/midtrans-finish', [
        'result_data' => [
            'order_id' => $payment->payment_code,
            'transaction_status' => 'settlement',
            'payment_type' => 'qris',
        ],
    ]);

    $payment->refresh();
    $reg->refresh();

    expect($payment->status)->toBe('verified');
    expect($reg->status)->toBe('paid');

    $ticket = Ticket::where('registration_id', $reg->id)->first();
    expect($ticket)->not->toBeNull();
    expect($ticket->status)->toBe('valid');

    $response->assertRedirect(route('tickets.show', $ticket->ticket_code));
});

test('midtrans webhook notification verifies payment with signature validation', function () {
    config()->set('midtrans.server_key', 'test-server-key-1234');

    $user = User::factory()->create(['role' => 'participant']);
    $workshop = createTestWorkshop();

    $reg = Registration::create([
        'registration_code' => 'REG-NOTIF-1',
        'user_id' => $user->id,
        'workshop_id' => $workshop->id,
        'full_name' => $user->name,
        'email' => $user->email,
        'phone' => '08123456789',
        'status' => 'pending_payment',
    ]);

    $payment = Payment::create([
        'payment_code' => 'PAY-NOTIF-1',
        'registration_id' => $reg->id,
        'user_id' => $user->id,
        'amount' => 150000,
        'payment_method' => 'Midtrans',
        'status' => 'pending',
    ]);

    $orderId = $payment->payment_code;
    $statusCode = '200';
    $grossAmount = '150000.00';
    $serverKey = 'test-server-key-1234';
    $signature = hash('sha512', $orderId.$statusCode.$grossAmount.$serverKey);

    $response = $this->postJson('/midtrans/notification', [
        'order_id' => $orderId,
        'status_code' => $statusCode,
        'gross_amount' => $grossAmount,
        'signature_key' => $signature,
        'transaction_status' => 'settlement',
        'payment_type' => 'bank_transfer',
    ]);

    $response->assertStatus(200);

    $payment->refresh();
    expect($payment->status)->toBe('verified');

    $ticket = Ticket::where('registration_id', $reg->id)->first();
    expect($ticket)->not->toBeNull();
});

test('admin can verify payment and issue ticket', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $user = User::factory()->create(['role' => 'participant']);
    $workshop = createTestWorkshop();

    $reg = Registration::create([
        'registration_code' => 'REG-ADM-1',
        'user_id' => $user->id,
        'workshop_id' => $workshop->id,
        'full_name' => $user->name,
        'email' => $user->email,
        'phone' => '08123456789',
        'status' => 'pending_payment',
    ]);

    $payment = Payment::create([
        'payment_code' => 'PAY-ADM-1',
        'registration_id' => $reg->id,
        'user_id' => $user->id,
        'amount' => 150000,
        'payment_method' => 'Bank Transfer BCA',
        'status' => 'pending',
    ]);

    $response = $this->actingAs($admin)->post('/admin/payments/'.$payment->id.'/verify');

    $payment->refresh();
    expect($payment->status)->toBe('verified');
    expect($payment->verified_by)->toBe($admin->id);

    $ticket = Ticket::where('registration_id', $reg->id)->first();
    expect($ticket)->not->toBeNull();
    expect($ticket->status)->toBe('valid');
});

test('ticket verification endpoint displays valid ticket and gate scanner check-in works', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $user = User::factory()->create(['role' => 'participant']);
    $workshop = createTestWorkshop();

    $reg = Registration::create([
        'registration_code' => 'REG-TIX-1',
        'user_id' => $user->id,
        'workshop_id' => $workshop->id,
        'full_name' => 'Peserta Check-In',
        'email' => $user->email,
        'phone' => '08123456789',
        'status' => 'paid',
    ]);

    $ticket = Ticket::create([
        'ticket_code' => 'TIX-WTX-2026-VAL01',
        'registration_id' => $reg->id,
        'user_id' => $user->id,
        'workshop_id' => $workshop->id,
        'qr_token' => 'qr-token-val-01',
        'status' => 'valid',
    ]);

    // Public / Gate verification
    $verifyResponse = $this->get('/tickets/verify/'.$ticket->ticket_code);
    $verifyResponse->assertStatus(200);
    $verifyResponse->assertSee('Verifikasi Tiket Berhasil');

    // Gate scanner check-in by admin
    $scanResponse = $this->actingAs($admin)->post('/admin/scanner/scan', [
        'code' => $ticket->ticket_code,
    ]);

    $ticket->refresh();
    expect($ticket->status)->toBe('used');
    expect($ticket->checked_in_at)->not->toBeNull();

    // Second scan on used ticket warns
    $secondScan = $this->actingAs($admin)->post('/admin/scanner/scan', [
        'code' => $ticket->ticket_code,
    ]);
    $secondScan->assertSessionHas('warning');
});

test('participant registration generates WTX- order ID and pay route redirects to midtrans url', function () {
    $user = User::factory()->create(['role' => 'participant']);
    $workshop = createTestWorkshop(['price' => 50000]);

    $this->actingAs($user)->post('/workshops/'.$workshop->slug.'/register', [
        'full_name' => 'Peserta WTX Test',
        'email' => $user->email,
        'phone' => '08123456789',
        'institution' => 'SMK Pesat IT',
        'payment_method' => 'Midtrans',
    ]);

    $payment = Payment::where('user_id', $user->id)->latest()->first();
    expect($payment)->not->toBeNull();
    expect($payment->payment_code)->toStartWith('WTX-');

    // Attach mock snap_token
    $payment->update([
        'snap_token' => 'mock-snap-token-12345',
        'midtrans_response' => [
            'redirect_url' => 'https://app.sandbox.midtrans.com/snap/v4/redirection/mock-snap-token-12345',
        ],
    ]);

    $payResponse = $this->actingAs($user)->get('/payments/'.$payment->payment_code.'/pay');
    $payResponse->assertRedirect('https://app.sandbox.midtrans.com/snap/v4/redirection/mock-snap-token-12345');
});
