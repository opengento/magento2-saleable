<?php
/**
 * Copyright © OpenGento, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */
declare(strict_types=1);

namespace Opengento\Saleable\Observer\Product;

use Magento\Catalog\Model\Product;
use Magento\Framework\DataObject;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Opengento\Saleable\Api\IsSaleableInterface;
use Opengento\Saleable\Model\CurrentCustomerGroupId;

final class IsSaleable implements ObserverInterface
{
    public function __construct(
        private IsSaleableInterface $isSaleable,
        private CurrentCustomerGroupId $currentCustomerGroupId
    ) {}

    public function execute(Observer $observer): void
    {
        $product = $observer->getData('product');
        $saleable = $observer->getData('salable');

        if ($product instanceof Product && $saleable instanceof DataObject) {
            $saleable->setData(
                'is_salable',
                $saleable->getData('is_salable') &&
                ($product->getData('can_show_price') ?? true) &&
                ($product->getData('is_purchasable') ?? true) &&
                $this->isSaleable->isSaleable(
                    $this->currentCustomerGroupId->get(),
                    $product->getStore()->getWebsiteId()
                )
            );
        }
    }
}
