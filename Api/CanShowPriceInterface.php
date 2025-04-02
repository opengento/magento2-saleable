<?php
/**
 * Copyright © OpenGento, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */
declare(strict_types=1);

namespace Opengento\Saleable\Api;

/**
 * @api
 */
interface CanShowPriceInterface
{
    public function canShowPrice(int $customerGroupId, int|string|null $websiteId = null): bool;
}
