<?php
/**
 * Copyright © OpenGento, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */
declare(strict_types=1);

namespace Opengento\Saleable\Plugin\Saleable;

use Magento\Catalog\Model\Product;
use Magento\Customer\Model\Context as CustomerContext;
use Magento\Framework\App\Http\Context as HttpContext;
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
        return $canShowPrice
            ? $this->canShowPrice->canShowPrice((int) $this->httpContext->getValue(CustomerContext::CONTEXT_GROUP))
            : $canShowPrice;
    }
}
