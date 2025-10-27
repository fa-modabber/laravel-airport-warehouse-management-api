<?php

namespace App\Services\Api\V1;
use App\Models\InventoryItem;

class InventoryItemService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function createItem(array $data)
    {
        $item = InventoryItem::create($data);
        return $item;
    }

    public function updateItem(InventoryItem $item, array $data)
    {
        $item->update($data);
        return $item;
    }

    public function deleteItem(InventoryItem $item)
    {
        $item->delete();
        return $item;
    }
}
