<?php

use App\Models\Advertisement;
use App\Models\User;

it('een gebruiker kan een advertentie aanmaken', function () {
    $user = User::factory()->create([
        'user_type' => 1,
    ]);

    $advertisementData = [
        'title' => 'Test Advertentie',
        'price' => 100,
        'information' => 'Dit is een test advertentie.',
    ];

    $response = $this->actingAs($user)->post('/newAdvertisement', $advertisementData);
    $response->assertRedirect(route('getMyAdvertisements'));
    $this->assertDatabaseHas('advertisement', $advertisementData);
});

it('een gebruiker kan geen advertentie aanmaken met ongeldige gegevens', function () {
    $user = User::factory()->create([
        'user_type' => 1,
    ]);

    $advertisementData = [
        'title' => 'Test Advertentie',
        'price' => null,
        'information' => 'Dit is een test advertentie.',
    ];

    $response = $this->actingAs($user)->post('/newAdvertisement', $advertisementData);
    $response->assertSessionHasErrors('price');
    $this->assertDatabaseMissing('advertisement', $advertisementData);
});

it('een niet ingelogde gebruiker kan geen advertentie aanmaken', function () {
    $advertisementData = [
        'title' => 'Test Advertentie 2',
        'price' => 100,
        'information' => 'Dit is de 2e test advertentie.',
    ];

    $response = $this->post('/newAdvertisement', $advertisementData);
    $response->assertRedirect('/login');
    $this->assertDatabaseMissing('advertisement', $advertisementData);
});