<?php

use App\Models\DeliveryAssignment;
use App\Models\DeliveryPartnerProfile;
use App\Models\User;
use Laravel\Sanctum\Sanctum;


it('prevents another delivery partner from updating the delivery', function () {
    $partnerA = User::factory()->create();
    $partnerB = User::factory()->create();

    $partnerA->assignRole('delivery_partner');
    $partnerB->assignRole('delivery_partner');

    $profileA = DeliveryPartnerProfile::factory()->create([
        'user_id' => $partnerA->id,
    ]);

    $delivery = DeliveryAssignment::factory()->create([
        'delivery_partner_profile_id' => $profileA->id,
    ]);

    Sanctum::actingAs($partnerB, ['*']);

    expect($partnerB->can('update', $delivery))->toBeFalse();
});