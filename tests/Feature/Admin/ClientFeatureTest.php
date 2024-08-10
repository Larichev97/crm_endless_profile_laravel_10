<?php

namespace Feature\Admin;

use App\Models\Client;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

class ClientFeatureTest extends AdminFeatureTest
{
    use RefreshDatabase, WithFaker;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
    }


    /**
     * @test
     */
    public function test_index_page_clients_contains_empty_table()
    {
        $response = $this->get('admin/clients');

        $response->assertStatus(200);
        $response->assertViewIs('client.index');
        $response->assertSee('Клиенты');
        $response->assertSee('Список клиентов');

        $this->assertDatabaseEmpty('clients');
    }

    /**
     * @test
     */
    public function test_index_page_clients_contains_non_empty_table()
    {
        $clientFieldsData = $this->getClientFieldsData();

        /** @var Client $clientModel */
        $clientModel = Client::query()->create($clientFieldsData);

        $response = $this->get('admin/clients');

        $response->assertStatus(200);
        $response->assertSee('Клиенты');
        $response->assertSee('Список клиентов');
        $response->assertSee('Testclientname');
        $response->assertSee('Testclientlastname');
        $response->assertSee('Testclientsurname');
        $response->assertSee('testclient@gmail.com');

        $this->assertDatabaseHas('clients', $clientFieldsData);

        $response->assertViewIs('client.index');
        $response->assertViewHas('clients');
        $response->assertViewHas('clients', function ($collection) use ($clientModel) {
            return $collection->contains($clientModel);
        });
    }
//
//    /**
//     * @test
//     */
//    public function test_index_page_paginated_products_table_doesnt_contain_first_record_order_by_desc_with_10_per_page()
//    {
//        $productsCollection = Product::factory()->count(11)->create();
//
//        $firstProduct = $productsCollection->first();
//
//        $response = $this->get('products');
//
//        $response->assertStatus(200);
//        $response->assertViewIs('admin.crud.product.index');
//        $response->assertViewHas('products', function ($collection) use ($firstProduct) {
//            return !$collection->contains($firstProduct);
//        });
//    }
//
//    /**
//     * @test
//     */
//    public function test_index_page_paginated_products_table_doesnt_contain_last_record_order_by_asc_with_10_per_page()
//    {
//        $productsCollection = Product::factory()->count(11)->create();
//
//        $lastProduct = $productsCollection->last();
//
//        $response = $this->get('products?sort_by=id&sort_way=asc');
//
//        $response->assertStatus(200);
//        $response->assertViewIs('admin.crud.product.index');
//        $response->assertViewHas('products', function ($collection) use ($lastProduct) {
//            return !$collection->contains($lastProduct);
//        });
//    }
//
//    /**
//     * @test
//     */
//    public function test_create_product_successful()
//    {
//        $productData = [
//            'title' => 'Test Product 1',
//            'price' => 99.99,
//            'currency_id' => 1
//        ];
//
//        $response = $this->from('products/create')->post('products', $productData);
//
//        $response->assertStatus(302);
//        $response->assertRedirect('products');
//
//        $this->assertDatabaseHas('products', $productData);
//
//        $lastAddedProduct = Product::query()->latest()->first();
//
//        /** @var Product $lastAddedProduct */
//
//        $this->assertEquals($productData['title'], $lastAddedProduct->title);
//        $this->assertEquals($productData['price'], $lastAddedProduct->price);
//        $this->assertEquals($productData['currency_id'], $lastAddedProduct->currency_id);
//    }
//
//    /**
//     * @test
//     */
//    public function test_create_product_with_wrong_price_validation()
//    {
//        $productData = [
//            'title' => 'Test Product 1',
//            'price' => -99.99,
//            'currency_id' => 1
//        ];
//
//        $response = $this->from('products/create')->post('products', $productData);
//
//        $response->assertStatus(302);
//        $response->assertRedirect('products/create');
//        $response->assertSessionHasErrors('price');
//    }
//
//    /**
//     * @test
//     */
//    public function test_create_product_with_empty_currency_id_validation()
//    {
//        $productData = [
//            'title' => 'Test Product 1',
//            'price' => 99.99,
//            'currency_id' => null
//        ];
//
//        $response = $this->from('products/create')->post('products', $productData);
//
//        $response->assertStatus(302);
//        $response->assertRedirect('products/create');
//        $response->assertSessionHasErrors('currency_id');
//    }
//
//    /**
//     * @test
//     */
//    public function test_create_product_with_empty_title_validation()
//    {
//        $productData = [
//            'title' => '',
//            'price' => 99.99,
//            'currency_id' => 1
//        ];
//
//        $response = $this->from('products/create')->post('products', $productData);
//
//        $response->assertStatus(302);
//        $response->assertRedirect('products/create');
//        $response->assertSessionHasErrors('title');
//    }
//
//    /**
//     * @test
//     */
//    public function test_create_product_with_same_title_of_existing_product_validation()
//    {
//        $firstProductData = [
//            'title' => 'Test Product 1',
//            'price' => 99.99,
//            'currency_id' => 1
//        ];
//
//        Product::query()->create($firstProductData);
//
//        $secondProductData = [
//            'title' => 'Test Product 1',
//            'price' => 333.03,
//            'currency_id' => 3
//        ];
//
//        $response = $this->from('products/create')->post('products', $secondProductData);
//
//        $response->assertStatus(302);
//        $response->assertRedirect('products/create');
//        $response->assertSessionHasErrors('title');
//    }
//
//    /**
//     * @test
//     */
//    public function test_show_existing_product()
//    {
//        $clientModel = Product::factory()->create();
//
//        $response = $this->get('products/'.$clientModel->id);
//
//        $response->assertStatus(200);
//        $response->assertViewIs('admin.crud.product.show');
//        $response->assertViewHas('product', $clientModel);
//        $response->assertSee(value: 'value="'.$clientModel->title.'"', escape: false);
//        $response->assertSee(value: 'value="'.$clientModel->price.'"', escape: false);
//        $response->assertSee(value: 'value="'.$clientModel->currency->iso_code.'"', escape: false);
//    }
//
//    /**
//     * @test
//     */
//    public function test_show_non_existing_product()
//    {
//        $response = $this->get('products/3333');
//
//        $response->assertStatus(500);
//    }
//
//    /**
//     * @test
//     */
//    public function test_edit_product_contains_correct_values()
//    {
//        $clientModel = Product::factory()->create();
//
//        $response = $this->get('products/'.$clientModel->id.'/edit');
//
//        $response->assertStatus(200);
//        $response->assertViewIs('admin.crud.product.edit');
//        $response->assertViewHas('product', $clientModel);
//        $response->assertSee(value: 'value="'.$clientModel->title.'"', escape: false);
//        $response->assertSee(value: 'value="'.$clientModel->price.'"', escape: false);
//        $response->assertSee(value: 'value="'.$clientModel->currency_id.'"', escape: false);
//    }
//
//    /**
//     * @test
//     */
//    public function test_update_product_with_empty_title_validation()
//    {
//        $clientModel = Product::factory()->create();
//
//        $response = $this->from('products/'.$clientModel->id.'/edit')->put('products/'.$clientModel->id, [
//            'title' => '',
//            'price' => 333.33,
//            'currency_id' => 3,
//        ]);
//
//        $response->assertStatus(302);
//        $response->assertRedirect('products/'.$clientModel->id.'/edit');
//        $response->assertSessionHasErrors('title');
//    }
//
//    /**
//     * @test
//     */
//    public function test_update_product_successful()
//    {
//        $clientModel = Product::factory()->create();
//
//        $newProductData = [
//            'title' => 'New product name 123',
//            'price' => 555.51,
//            'currency_id' => 2,
//        ];
//
//        $response = $this->from('products/'.$clientModel->id.'/edit')->put('products/'.$clientModel->id, $newProductData);
//
//        $response->assertStatus(302);
//        $response->assertRedirect('products');
//
//        $this->assertDatabaseHas('products', $newProductData);
//    }
//
//    /**
//     * @test
//     */
//    public function test_delete_product_successful()
//    {
//        $clientModel = Product::factory()->create();
//
//        $response = $this->from('products/'.$clientModel->id.'/edit')->delete('products/'.$clientModel->id);
//
//        $response->assertStatus(302);
//        $response->assertRedirect('products');
//
//        $productData = [
//            'title' => $clientModel->title,
//            'price' => $clientModel->price,
//            'currency_id' => $clientModel->currency_id,
//        ];
//
//        $this->assertDatabaseMissing('products', $productData);
//        $this->assertDatabaseCount('products', 0);
//    }

    /**
     * @return array
     */
    private function getClientFieldsData(): array
    {
        return [
            'id_status' => 1,
            'id_country' => 1,
            'id_city' => 1,
            'phone_number' => '+380687777777',
            'email' => 'testclient@gmail.com',
            'bdate' => '1997-01-05',
            'address' => '668 Durgan Curve Apt. 704 Weberhaven, ND 10656',
            'firstname' => 'Testclientname',
            'lastname' => 'Testclientlastname',
            'surname' => 'Testclientsurname',
            'id_user_add' => 1,
            'id_user_update' => 1,
        ];
    }
}
