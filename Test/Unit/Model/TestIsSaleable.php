<?php
/**
 * Copyright © OpenGento, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */
declare(strict_types=1);

namespace Opengento\Saleable\Test\Unit\Model;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Opengento\Saleable\Model\IsSaleable;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

final class TestIsSaleable extends TestCase
{
    /**
     * @var MockObject|ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @var IsSaleable
     */
    private $isSaleable;

    protected function setUp(): void
    {
        $this->scopeConfig = $this->getMockForAbstractClass(ScopeConfigInterface::class);

        $this->isSaleable = new IsSaleable($this->scopeConfig);
    }

    /**
     * @dataProvider isSaleableDataProvider
     */
    public function testIsSaleable(bool $isEnabled, ?string $config, array $expectations): void
    {
        $this->scopeConfig->expects($this->once())
            ->method('isSetFlag')
            ->with('checkout/cart/restrict_saleable', 'website', null)
            ->willReturn($isEnabled);
        $this->scopeConfig->expects($isEnabled ? $this->once() : $this->never())
            ->method('getValue')
            ->with('checkout/cart/is_saleable_groups', 'website', null)
            ->willReturn($config);

        foreach ($expectations as $groupId => $assert) {
            $this->assertSame($assert, $this->isSaleable->isSaleable($groupId));
        }
    }

    public function isSaleableDataProvider(): array
    {
        return [
            [true, '1,2,3,4', [0 => false, 1 => true, 2 => true, 3 => true, 4 => true]],
            [true, '1,3', [0 => false, 1 => true, 2 => false, 3 => true, 4 => false]],
            [true, '', [0 => false, 1 => false, 2 => false, 3 => false, 4 => false]],
            [false, null, [0 => true, 1 => true, 2 => true, 3 => true, 4 => true]],
        ];
    }
}
