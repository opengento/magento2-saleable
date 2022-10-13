<?php
/**
 * Copyright © OpenGento, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */
declare(strict_types=1);

namespace Opengento\Saleable\Model;

use Magento\Customer\Model\Context as CustomerContext;
use Magento\Framework\App\Http\Context as HttpContext;

final class CurrentCustomerGroupId
{
    public function __construct(private HttpContext $httpContext) {}

    public function get(): int
    {
        return (int) $this->httpContext->getValue(CustomerContext::CONTEXT_GROUP);
    }
}
