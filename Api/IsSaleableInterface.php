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
interface IsSaleableInterface
{
    public function isSaleable(int $customerGroupId): bool;
}
