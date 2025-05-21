<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class HomePageTest extends TestCase
{
    /** @test */
    public function it_should_return_the_welcome_page()
    {
        $response = $this->get('/');

        $response->assertStatus(200); // Verifica se a resposta é 200
        $response->assertViewIs('welcome'); // Verifica se a view acionada foi welcome ou qualquer outra passada como arg
        $response->assertSee('Laravel'); // verica se tem uma string especifica
        $response->assertDontSee('Erro 404'); // Verificar se a resposta NÃO contém um texto específico

        // // Testar um redirecionamento
        // $response = $this->get('/dashboard');
        // $response->assertRedirect('/login');

        // // Verificar resposta JSON
        // $response = $this->getJson('/api/user');
        // $response->assertJson([
        //     'name' => 'Victor',
        //     'email' => 'victor@example.com',
        // ]);

        // // Testar banco de dados
        // $this->assertDatabaseHas('users', [
        //     'email' => 'victor@example.com'
        // ]);

        // // Verifica se um registro existe no banco de dados.
        // $this->assertDatabaseMissing('users', [
        //     'email' => 'naoexiste@example.com'
        // ]);

    }
}
