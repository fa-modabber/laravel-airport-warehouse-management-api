<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Customer;
use App\Models\AirWaybill;
use App\Models\Inventory;
use App\Models\InventoryItem;
use Tests\TestCase;

class InventoryApiTest extends TestCase
{
    use RefreshDatabase;

    //test methods for CREATING INVENTORY
    public function test_it_can_create_an_inventory()
    {
        $customer = Customer::factory()->create();
        $airWaybill = AirWaybill::factory()->create();

        $inventory = [
            'code' => 'INV-2000',
            'customer_id' => $customer->id,
            'air_waybill_id' => $airWaybill->id,
            'items' => [
                ['hs_code' => '1111', 'count' => 10, 'weight' => 10],
                ['hs_code' => '2222', 'count' => 20, 'weight' => 15.5],
            ],
        ];

        $response = $this->postJson('/api/v1/inventories', $inventory);
        $response->dump();
    }


    public function test_it_fails_validation_without_customer_id()
    {

        $airWaybill = AirWaybill::factory()->create();

        $inventory = [
            'code' => 'INV-2000',
            'air_waybill_id' => $airWaybill->id,
            'items' => [
                ['hs_code' => '1111', 'count' => 10, 'weight' => 10],
                ['hs_code' => '2222', 'count' => 20, 'weight' => 15.5],
            ],
        ];
        $response = $this->postJson('/api/v1/inventories', $inventory);

        $response->assertStatus(422)
            ->assertJsonStructure(['status', 'message']);
    }

    public function test_it_fails_validation_with_invalid_customer_id()
    {

        $airWaybill = AirWaybill::factory()->create();

        $inventory = [
            'code' => 'INV-2000',
            'customer_id' => 2000000,
            'air_waybill_id' => $airWaybill->id,
            'items' => [
                ['hs_code' => '1111', 'count' => 10, 'weight' => 10],
                ['hs_code' => '2222', 'count' => 20, 'weight' => 15.5],
            ],
        ];
        $response = $this->postJson('/api/v1/inventories', $inventory);

        $response->assertStatus(422)
            ->assertJsonStructure(['status', 'message']);
    }




    //test methods for TRANSFER ONERSHIP
    public function test_it_can_transfer_ownership()
    {
        $inventory = Inventory::factory()->create();
        $new_customer = Customer::factory()->create();
        $response = $this->postJson("/api/v1/inventories/{$inventory->id}/transfer", ['new_customer_id' => $new_customer->id]);
        dump([
            'inventory' => $inventory->toArray(),
            'new_customer' => $new_customer->toArray(),
            'api_response' => $response->json(),
        ]);
    }



    //test methods for AGGREGATE HS CODES
    public function test_it_can_aggregate_hs_codes()
    {
        $inventory1 = Inventory::factory()->create(['id' => 1]);
        $inventory2 = Inventory::factory()->create(['id' => 2]);

        $inventory_item1 = InventoryItem::factory()->create(['inventory_id' => $inventory1->id]);
        $inventory_item1 = InventoryItem::factory()->create(['inventory_id' => $inventory2->id]);
    
        $query = http_build_query(['inventory_ids' => [$inventory1->id, $inventory2->id]]);
    
        $response = $this->getJson("/api/v1/reports/hs-codes?$query");
    
        $response->dump();
    }
    public function test_it_fails_with_invalid_inventories() {
        $query = http_build_query(['inventory_ids' => [20,30]]);
    
        $response = $this->getJson("/api/v1/reports/hs-codes?$query");
    
        $response->dump();
    }
}
