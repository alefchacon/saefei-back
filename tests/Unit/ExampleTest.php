<?php
use App\Models\Evento;
use Tests\TestCase;


test('example', function () {
    $response = $this->getJson('/api/eventos');
    $response->assertStatus(200);
    //expect(true)->toBeTrue();
});
