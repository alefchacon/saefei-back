<?php
use Illuminate\Http\Response;
use Illuminate\Testing\Fluent\AssertableJson;
use App\Models\Evento;

test('Obtener el índice de eventos', function () {
    $response = $this->getJson('/api/eventos');
    $response->assertStatus(Response::HTTP_OK);
});
test('Obtener eventos de primer usuario, filtrados por el nombre', function () {
    $response = $this->getJson('/api/eventos?idUsuario[eq]=1&nombre=semina');
    $response->assertStatus(Response::HTTP_OK);

    $data = $response->json("data");

    expect(count($data))->toBe(2);
    /*
    
    foreach ($data as $evento){
        expect($evento)->toMatchArray([
            "id" => expect()->toBeInt()
        ]);
    }
*/
});
