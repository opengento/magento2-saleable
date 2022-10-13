<?php
/**
 * Copyright © OpenGento, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */
declare(strict_types=1);

namespace Opengento\Saleable\Test\Unit\Model;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Opengento\Saleable\Model\CanShowPrice;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

final class TestCanShowPrice extends TestCase
{
    private MockObject|ScopeConfigInterface|ScopeConfigInterface&MockObject $scopeConfig;

    private CanShowPrice $canShowPrice;

    protected function setUp(): void
    {
        $this->scopeConfig = $this->getMockForAbstractClass(ScopeConfigInterface::class);

        $this->canShowPrice = new CanShowPrice($this->scopeConfig);
    }

    /**
     * @dataProvider canShowPriceDataProvider
     */
    public function testCanShowPrice(bool $isEnabled, ?string $config, array $expectations): void
    {
        $this->scopeConfig->expects($this->once())
            ->method('isSetFlag')
            ->with('catalog/price/restrict_show_price', 'website', null)
            ->willReturn($isEnabled);
        $this->scopeConfig->expects($isEnabled ? $this->once() : $this->never())
            ->method('getValue')
            ->with('catalog/price/can_show_price_groups', 'website', null)
            ->willReturn($config);

        foreach ($expectations as $groupId => $assert) {
            $this->assertSame($assert, $this->canShowPrice->canShowPrice($groupId));
        }
    }

    public function canShowPriceDataProvider(): array
    {
        return [
            [true, '1,2,3,4', [0 => false, 1 => true, 2 => true, 3 => true, 4 => true]],
            [true, '1,3', [0 => false, 1 => true, 2 => false, 3 => true, 4 => false]],
            [true, '', [0 => false, 1 => false, 2 => false, 3 => false, 4 => false]],
            [false, null, [0 => true, 1 => true, 2 => true, 3 => true, 4 => true]],
        ];
    }
}
