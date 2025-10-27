<?php

namespace App\Services\Api\V1;

use App\Models\Inventory;
use Illuminate\Support\Facades\DB;
use App\Models\InventoryItem;


class InventoryService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function createInventoryWithItems(array $data)
    {
        DB::beginTransaction();
        $inventory = Inventory::create([
            'code' => $data['code'],
            'customer_id' => $data['customer_id'],
            'air_waybill_id' => $data['air_waybill_id'],
            'is_voided' => $data['is_voided'],
            'is_banned' => $data['is_banned'],
            'total_count' => $data['total_count'],
            'total_weight' => $data['total_weight']
        ]);


        foreach ($data['items'] as $item) {
            InventoryItem::create([
                'inventory_id' => $inventory->id,
                'hs_code' => $item['hs_code'],
                'count' => $item['count'],
                'weight' => $item['weight'],
                'description' => $item['description'] ?? null
            ]);
        }
        DB::commit();

        return [
            "id" => $inventory->id,
            "code" => $inventory->code,
            "total_count" => $inventory->total_count,
            "total_weight" => $inventory->total_weight
        ];
    }

    public function transferOwnership(Inventory $inventory, int $new_customer_id)
    {
        $inventory->update(['customer_id' => $new_customer_id]);
        return $inventory;
    }


    public function aggregateHSCodes(array $inventory_ids): array
    {
        $existing_ids = DB::table('inventories')
            ->whereIn('id', $inventory_ids)
            ->pluck('id')
            ->toArray();

        $missing_ids = array_diff($inventory_ids, $existing_ids);
        if (!empty($missing_ids)) {
            throw new \Exception('Inventories not found: ' . implode(', ', $missing_ids));
        }

        $inventories = Inventory::whereIn('id', $inventory_ids)->get();

        $aggregated_total_count = 0;
        $aggregated_total_weight = 0;
        $all_hs_codes = [];

        foreach ($inventories as $inventory) {
            $aggregated_total_count += $inventory->total_count;
            $aggregated_total_weight += $inventory->total_weight;
            $all_hs_codes = array_merge($all_hs_codes, $inventory->items()->pluck('hs_code')->toArray());
        }

        $aggregated_hs_code = implode('-', $all_hs_codes);

        return [
            'hs_code' => $aggregated_hs_code,
            'total_count' => $aggregated_total_count,
            'total_weight' => $aggregated_total_weight
        ];
    }
}

