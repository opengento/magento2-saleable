<?php
/**
 * Copyright © OpenGento, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */
declare(strict_types=1);

namespace Opengento\Saleable\Plugin\Saleable;

use Magento\Catalog\Model\Product;
use Opengento\Saleable\Api\CanShowPriceInterface;
use Opengento\Saleable\Model\CurrentCustomerGroupId;

final class CanShowPrice
{
    public function __construct(
        private CanShowPriceInterface $canShowPrice,
        private CurrentCustomerGroupId $currentCustomerGroupId
    ) {}

    public function afterGetData(Product $product, $result, $key = null)
    {
        if ($key === 'can_show_price') {
            $result = $this->canShowPrice((bool) $result);
        } elseif ($key === null && isset($result['can_show_price'])) {
            $result['can_show_price'] = $this->canShowPrice((bool) $result['can_show_price']);
        }

        return $result;
    }

    private function canShowPrice(bool $canShowPrice): bool
    {
        return $canShowPrice && $this->canShowPrice->canShowPrice($this->currentCustomerGroupId->get());
    }
}
