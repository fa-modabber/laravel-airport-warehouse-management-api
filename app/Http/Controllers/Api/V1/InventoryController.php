<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\ApiController;
use App\Http\Resources\V1\InventoryResource;
use App\Models\Inventory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Services\Api\V1\InventoryService;

class InventoryController extends ApiController
{
    protected $inventoryService;

    public function __construct(InventoryService $inventoryService)
    {
        $this->inventoryService = $inventoryService;
    }

    public function store(Request $request)
    {
        //validating the request
        $validator = Validator::make($request->all(), [
            'code' => 'required|string|unique:inventories',
            'customer_id' => 'required|integer|exists:customers,id',
            'air_waybill_id' => 'required|integer|exists:air_waybills,id',
            'is_voided' => 'nullable|boolean',
            'is_banned' => 'nullable|boolean',
            'total_count' => 'nullable|integer|min:1',
            'total_weight' => 'nullable|numeric|gt:0',
            'items' => 'required|array|min:1',
            'items.*.hs_code' => 'required|string|max:20',
            'items.*.count' => 'required|integer|min:1',
            'items.*.weight' => 'required|numeric|gt:0',
            'items.*.description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->messages(), 422);
        }

        //creating data array
        $total_count = array_sum(array_column($request->items, 'count'));
        $total_weight = array_sum(array_column($request->items, 'weight'));

        $inventory_data = [
            'code' => $request->code,
            'customer_id' => $request->customer_id,
            'air_waybill_id' => $request->air_waybill_id,
            'is_voided' => isset($request->is_voided) ?: false,
            'is_banned' => isset($request->is_banned) ?: false,
            'total_count' => $total_count,
            'total_weight' => $total_weight,
            'items' => $request->items
        ];

        $inventory = $this->inventoryService->createInventoryWithItems($inventory_data);
        return $this->successfulResponse(new InventoryResource($inventory), 201);
    }

    public function transferOwnership(Request $request, Inventory $inventory)
    {
        $validator = Validator::make($request->all(), [
            'new_customer_id' => 'required|integer|exists:customers,id',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->messages(), 422);
        }

        if (
            $inventory->is_banned ||
            $inventory->is_voided ||
            $inventory->total_count <= 0 ||
            $inventory->total_weight <= 0
        ) {
            return $this->errorResponse(message: "Transfer is not allowed!", code: 422);
        }

        $new_customer_id = $request->new_customer_id;
        $this->inventoryService->transferOwnership($inventory, $new_customer_id);
        return $this->successfulResponse(data: null, code: 200, message: 'Transferred!!!');
    }

    public function aggregateHSCodes(Request $request)
    {
        $inventory_ids = $request->query('inventory_ids', []);
        $result = $this->inventoryService->aggregateHSCodes($inventory_ids);
        return $this->successfulResponse(data: $result, code: 200);
    }
}
