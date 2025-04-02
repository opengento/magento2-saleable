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

    /**
     * Plugin is necessary for :
     * \Magento\Catalog\Model\Product\Pricing\Renderer\SalableResolver::isSalable
     * \Magento\Catalog\Block\Product\Price::_toHtml
     * Such as $salableItem->getCanShowPrice() or $this->getProduct()->getCanShowPrice()
     * You can search for references of: ‘->getCanShowPrice()‘ (magic method from DataObject)
     */
    public function afterGetData(Product $product, $result, $key = null)
    {
        if ($key === 'can_show_price') {
            $result = ($result ?? true) && $this->canShowPrice($product);
        } elseif ($key === null) {
            $result['can_show_price'] = ($result['can_show_price'] ?? true) && $this->canShowPrice($product);
        }

        return $result;
    }

    private function canShowPrice(Product $product): bool
    {
        return $this->canShowPrice->canShowPrice(
            $this->currentCustomerGroupId->get(),
            $product->getStore()->getWebsiteId()
        );
    }
}
