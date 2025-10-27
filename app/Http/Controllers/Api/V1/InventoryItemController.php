<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\InventoryItem;
use App\Services\Api\V1\InventoryItemService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\ApiController;


class InventoryItemController extends ApiController
{
    protected $inventoryItemService;

    public function __construct(InventoryItemService $inventoryItemService)
    {
        $this->inventoryItemService = $inventoryItemService;
    }

    public function index() {}
    public function show(InventoryItem $inventory_item) {}

    public function store(Request $request)
    {
        
        $validator = Validator::make($request->all(), [
            'inventory_id' => 'required|integer|exists:inventories,id',
            'hs_code' => 'required|string|max:20',
            'count' => 'required|integer|min:1',
            'weight' => 'required|numeric|gt:0',
            'description' => 'nullable|string',
        ]);
        if ($validator->fails()) {
            return $this->errorResponse($validator->messages(), 422);
        }

        $validated_data = $validator->validated();
        $item = $this->inventoryItemService->createItem($validated_data);
            return $this->successfulResponse($item, 201);
    }

    public function update(Request $request, InventoryItem $inventory_item)
    {
        $validator = Validator::make($request->all(), [
            'inventory_id' => 'required|integer|exists:inventories,id',
            'hs_code' => 'required|string|max:20',
            'count' => 'required|integer|min:1',
            'weight' => 'required|numeric|gt:0',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->messages(), 422);
        }
        $validated_data = $validator->validated();
        $item = $this->inventoryItemService->updateItem($inventory_item, $validated_data);

        return $this->successfulResponse($item, 200);
    }

    public function destroy(InventoryItem $inventory_item)
    {
        $item = $this->inventoryItemService->deleteItem($inventory_item);
        return $this->successfulResponse($item, 200);
    }
}
