<?php
/**
 * Copyright © OpenGento, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */
declare(strict_types=1);

namespace Opengento\Saleable\Model;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;
use Opengento\Saleable\Api\CanShowPriceInterface;
use Opengento\Saleable\Api\IsSaleableInterface;
use function array_filter;
use function array_map;
use function explode;
use function in_array;

final class IsSaleable implements IsSaleableInterface
{
    private const CONFIG_PATH_RESTRICT_SALEABLE = 'checkout/cart/restrict_saleable';
    private const CONFIG_PATH_IS_SALEABLE_GROUPS = 'checkout/cart/is_saleable_groups';

    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @var CanShowPriceInterface
     */
    private $canShowPrice;

    /**
     * @var array|null
     */
    private $allowedGroups;

    /**
     * @var bool|null
     */
    private $isEnabled;

    public function __construct(
        ScopeConfigInterface $scopeConfig,
        CanShowPriceInterface $canShowPrice
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->canShowPrice = $canShowPrice;
    }

    public function isSaleable(int $customerGroupId): bool
    {
        return $this->canShowPrice->canShowPrice($customerGroupId)
            && (!$this->isEnabled() || in_array($customerGroupId, $this->resolveAllowedGroups(), true));
    }

    private function isEnabled(): bool
    {
        return $this->isEnabled ?? $this->isEnabled = $this->scopeConfig->isSetFlag(
            self::CONFIG_PATH_RESTRICT_SALEABLE,
            ScopeInterface::SCOPE_WEBSITE
        );
    }

    private function resolveAllowedGroups(): array
    {
        return $this->allowedGroups
            ?? $this->allowedGroups = array_map('\intval', array_filter(
                explode(',', (string) $this->scopeConfig->getValue(
                    self::CONFIG_PATH_IS_SALEABLE_GROUPS,
                    ScopeInterface::SCOPE_WEBSITE
                ))
            ));
    }
}
