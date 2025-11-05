<?php

namespace Acme\Bundle\StockPdpBundle\Layout\DataProvider;

use Doctrine\Persistence\ManagerRegistry;
use Oro\Bundle\InventoryBundle\Entity\InventoryLevel;
use Oro\Bundle\InventoryBundle\Inventory\InventoryQuantityManager;
use Oro\Bundle\ProductBundle\Entity\Product;

class StockDataProvider
{
    private InventoryQuantityManager $inventoryQuantityManager;
    private ManagerRegistry $doctrine;

    public function __construct(
        InventoryQuantityManager $inventoryQuantityManager,
        ManagerRegistry $doctrine
    ) {
        $this->inventoryQuantityManager = $inventoryQuantityManager;
        $this->doctrine = $doctrine;
    }

    public function getStockInfo(Product $product): array
    {
        try {
            $precision = $product->getPrimaryUnitPrecision();
            if (!$precision) {
                return ['available' => false, 'quantity' => 0, 'formatted_quantity' => '0'];
            }

            $repo = $this->doctrine->getRepository(InventoryLevel::class);
            $inventoryLevel = $repo->findOneBy([
                'product' => $product,
                'productUnitPrecision' => $precision,
            ]);

            if (!$inventoryLevel) {
                return ['available' => false, 'quantity' => 0, 'formatted_quantity' => '0'];
            }

            // ✅ Pass InventoryLevel to the manager
            $quantity = $this->inventoryQuantityManager->getAvailableQuantity($inventoryLevel);

            return [
                'available' => $quantity > 0,
                'quantity' => $quantity,
                'formatted_quantity' => $this->formatQuantity((float)$quantity),
            ];
        } catch (\Throwable $e) {
            return ['available' => false, 'quantity' => 0, 'formatted_quantity' => '0'];
        }
    }

    private function formatQuantity(float $quantity): string
    {
        return floor($quantity) == $quantity ? (string) (int) $quantity : number_format($quantity, 2);
    }
}
