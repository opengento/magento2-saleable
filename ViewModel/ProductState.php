<?php
/**
 * Copyright © OpenGento, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */
declare(strict_types=1);

namespace Opengento\Saleable\ViewModel;

use Magento\Catalog\Model\Product;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Opengento\Saleable\Api\IsSaleableInterface;
use Opengento\Saleable\Model\CurrentCustomerGroupId;

final class ProductState implements ArgumentInterface
{
    public function __construct(
        private IsSaleableInterface $isSaleable,
        private CurrentCustomerGroupId $currentCustomerGroupId
    ) {}

    /**
     * Check wether or not the product could be purchased.
     * Does not check if the product is actually salable (meaning it is enabled and in stock/backorder).
     */
    public function isPurchasable(Product $product): bool
    {
        return $product->getData('is_purchasable') && $this->isSaleable->isSaleable($this->currentCustomerGroupId->get());
    }
}
