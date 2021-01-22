<?php
/**
 * Copyright © OpenGento, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */
declare(strict_types=1);

namespace Opengento\Saleable\Plugin\Pricing;

use Magento\Customer\Model\Context as CustomerContext;
use Magento\Framework\App\Http\Context as HttpContext;
use Magento\Framework\Pricing\SaleableInterface;
use Opengento\Saleable\Api\CanShowPriceInterface;

final class CanShowPrice
{
    private HttpContext $httpContext;

    private CanShowPriceInterface $canShowPrice;

    public function __construct(
        HttpContext $httpContext,
        CanShowPriceInterface $canShowPrice
    ) {
        $this->httpContext = $httpContext;
        $this->canShowPrice = $canShowPrice;
    }

    public function afterGetCanShowPrice(SaleableInterface $saleable, bool $canShowPrice): bool
    {
        return $canShowPrice
            ? $this->canShowPrice->canShowPrice((int) $this->httpContext->getValue(CustomerContext::CONTEXT_GROUP))
            : false;
    }
}
