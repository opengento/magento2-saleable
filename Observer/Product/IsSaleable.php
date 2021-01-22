<?php
/**
 * Copyright © OpenGento, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */
declare(strict_types=1);

namespace Opengento\Saleable\Observer\Product;

use Magento\Customer\Model\Context as CustomerContext;
use Magento\Framework\App\Http\Context as HttpContext;
use Magento\Framework\DataObject;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Opengento\Saleable\Api\IsSaleableInterface;

final class IsSaleable implements ObserverInterface
{
    private HttpContext $httpContext;

    private IsSaleableInterface $isSaleable;

    public function __construct(
        HttpContext $httpContext,
        IsSaleableInterface $isSaleable
    ) {
        $this->httpContext = $httpContext;
        $this->isSaleable = $isSaleable;
    }

    public function execute(Observer $observer): void
    {
        $product = $observer->getData('product');
        $saleable = $observer->getData('salable');

        if ($product instanceof DataObject && $saleable instanceof DataObject) {
            $groupId = $this->httpContext->getValue(CustomerContext::CONTEXT_GROUP);
            if ($groupId !== null) {
                $saleable->setData(
                    'is_salable',
                    ($saleable->getData('is_salable') && $product->getData('can_show_price'))
                        ? $this->isSaleable->isSaleable((int) $groupId)
                        : false
                );
            }
        }
    }
}
