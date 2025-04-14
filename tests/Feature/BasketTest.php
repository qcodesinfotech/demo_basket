<?php
namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use App\Models\Basket;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BasketTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function authenticated_user_can_add_product_to_basket()
    {
        $user = User::factory()->create();
        $product = Product::factory()->create(['sku' => 'ABC123']);

        $response = $this->actingAs($user)->post(route('basket.store'), [
            'sku' => 'ABC123'
        ]);

        $response->assertRedirect(route('basket.index'));
        $this->assertDatabaseHas('baskets', [
            'user_id' => $user->id,
            'sku' => 'ABC123'
        ]);
    }

    /** @test */
    public function guest_cannot_add_product_to_basket()
    {
        $product = Product::factory()->create(['sku' => 'XYZ']);

        $response = $this->post(route('basket.store'), [
            'sku' => 'XYZ'
        ]);

        $response->assertRedirect('/login');
    }

    /** @test */
    public function it_fails_if_product_does_not_exist()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('basket.store'), [
            'sku' => 'INVALIDSKU'
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error', 'Could not add product to basket. Please try again.');
    }

    /** @test */
    public function basket_index_displays_products()
    {
        $user = User::factory()->create();
        $product = Product::factory()->create(['sku' => 'ABC123', 'price' => 50]);
        Basket::create([
            'user_id' => $user->id,
            'sku' => $product->sku,
            'product_name' => $product->name,
            'product_price' => $product->price,
            'quantity' => 2,
        ]);

        $response = $this->actingAs($user)->get(route('basket.index'));
        $response->assertStatus(200);
        $response->assertSee($product->name);
        $response->assertSee('$'); // Should see price format
    }
}
