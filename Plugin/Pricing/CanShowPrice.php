<?php
/**
 * Copyright © OpenGento, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */
declare(strict_types=1);

namespace Opengento\Saleable\Plugin\Pricing;

use Magento\Framework\Pricing\SaleableInterface;
use Opengento\Saleable\Api\CanShowPriceInterface;
use Opengento\Saleable\Model\CurrentCustomerGroupId;

final class CanShowPrice
{
    public function __construct(
        private CanShowPriceInterface $canShowPrice,
        private CurrentCustomerGroupId $currentCustomerGroupId
    ) {}

    public function afterGetCanShowPrice(SaleableInterface $saleable, bool $canShowPrice): bool
    {
        return $canShowPrice && $this->canShowPrice->canShowPrice($this->currentCustomerGroupId->get());
    }
}
