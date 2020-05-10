<?php
/**
 * Copyright © OpenGento, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */
declare(strict_types=1);

namespace Opengento\Saleable\Test\Unit\Model;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Opengento\Saleable\Api\CanShowPriceInterface;
use Opengento\Saleable\Model\IsSaleable;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

final class TestIsSaleable extends TestCase
{
    private const CONFIG_SALEABLE_ENABLED_ALL = 1;
    private const CONFIG_SALEABLE_ENABLED_SOME = 2;
    private const CONFIG_SALEABLE_ENABLED_NONE = 3;
    private const CONFIG_SALEABLE_DISABLED = 4;

    private const CONFIG_SALEABLE_GROUPS = [
        self::CONFIG_SALEABLE_ENABLED_ALL => '1,2,3,4',
        self::CONFIG_SALEABLE_ENABLED_SOME => '2,3',
        self::CONFIG_SALEABLE_ENABLED_NONE => '',
        self::CONFIG_SALEABLE_DISABLED => null
    ];

    private const CONFIG_SALEABLE_EXPECTS = [
        self::CONFIG_SALEABLE_ENABLED_ALL => [
            self::CONFIG_SHOW_PRICE_ENABLED_ALL => [
                0 => false,
                1 => true,
                2 => true,
                3 => true,
                4 => true,
            ],
            self::CONFIG_SHOW_PRICE_ENABLED_SOME => [
                0 => false,
                1 => false,
                2 => true,
                3 => false,
                4 => true,
            ],
            self::CONFIG_SHOW_PRICE_ENABLED_NONE => [
                0 => false,
                1 => false,
                2 => false,
                3 => false,
                4 => false,
            ],
            self::CONFIG_SHOW_PRICE_DISABLED => [
                0 => false,
                1 => true,
                2 => true,
                3 => true,
                4 => true,
            ],
        ],
        self::CONFIG_SALEABLE_ENABLED_SOME => [
            self::CONFIG_SHOW_PRICE_ENABLED_ALL => [
                0 => false,
                1 => false,
                2 => true,
                3 => true,
                4 => false,
            ],
            self::CONFIG_SHOW_PRICE_ENABLED_SOME => [
                0 => false,
                1 => false,
                2 => true,
                3 => false,
                4 => false,
            ],
            self::CONFIG_SHOW_PRICE_ENABLED_NONE => [
                0 => false,
                1 => false,
                2 => false,
                3 => false,
                4 => false,
            ],
            self::CONFIG_SHOW_PRICE_DISABLED => [
                0 => false,
                1 => false,
                2 => true,
                3 => true,
                4 => false,
            ],
        ],
        self::CONFIG_SALEABLE_ENABLED_NONE => [
            self::CONFIG_SHOW_PRICE_ENABLED_ALL => [
                0 => false,
                1 => false,
                2 => false,
                3 => false,
                4 => false,
            ],
            self::CONFIG_SHOW_PRICE_ENABLED_SOME => [
                0 => false,
                1 => false,
                2 => false,
                3 => false,
                4 => false,
            ],
            self::CONFIG_SHOW_PRICE_ENABLED_NONE => [
                0 => false,
                1 => false,
                2 => false,
                3 => false,
                4 => false,
            ],
            self::CONFIG_SHOW_PRICE_DISABLED => [
                0 => false,
                1 => false,
                2 => false,
                3 => false,
                4 => false,
            ],
        ],
        self::CONFIG_SALEABLE_DISABLED => [
            self::CONFIG_SHOW_PRICE_ENABLED_ALL => [
                0 => false,
                1 => true,
                2 => true,
                3 => true,
                4 => true,
            ],
            self::CONFIG_SHOW_PRICE_ENABLED_SOME => [
                0 => false,
                1 => false,
                2 => true,
                3 => false,
                4 => true,
            ],
            self::CONFIG_SHOW_PRICE_ENABLED_NONE => [
                0 => false,
                1 => false,
                2 => false,
                3 => false,
                4 => false,
            ],
            self::CONFIG_SHOW_PRICE_DISABLED => [
                0 => true,
                1 => true,
                2 => true,
                3 => true,
                4 => true,
            ],
        ]
    ];

    private const CONFIG_SHOW_PRICE_ENABLED_ALL = 1;
    private const CONFIG_SHOW_PRICE_ENABLED_SOME = 2;
    private const CONFIG_SHOW_PRICE_ENABLED_NONE = 3;
    private const CONFIG_SHOW_PRICE_DISABLED = 4;

    private const CONFIG_SHOW_PRICE_MAP = [
        self::CONFIG_SHOW_PRICE_ENABLED_ALL => [
            [0, false],
            [1, true],
            [2, true],
            [3, true],
            [4, true],
        ],
        self::CONFIG_SHOW_PRICE_ENABLED_SOME => [
            [0, false],
            [1, false],
            [2, true],
            [3, false],
            [4, true],
        ],
        self::CONFIG_SHOW_PRICE_ENABLED_NONE => [
            [0, false],
            [1, false],
            [2, false],
            [3, false],
            [4, false],
        ],
        self::CONFIG_SHOW_PRICE_DISABLED => [
            [0, true],
            [1, true],
            [2, true],
            [3, true],
            [4, true],
        ],
    ];

    /**
     * @var MockObject|ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @var MockObject|CanShowPriceInterface
     */
    private $canShowPrice;

    /**
     * @var IsSaleable
     */
    private $isSaleable;

    protected function setUp()
    {
        $this->scopeConfig = $this->getMockForAbstractClass(ScopeConfigInterface::class);
        $this->canShowPrice = $this->getMockForAbstractClass(CanShowPriceInterface::class);

        $this->isSaleable = new IsSaleable($this->scopeConfig, $this->canShowPrice);
    }

    /**
     * @dataProvider isSaleableDataProvider
     */
    public function testIsSaleable(bool $isSaleableEnabled, int $saleableConfigStatus, int $showPriceMapStatus): void
    {
        $isSaleableExpected = $showPriceMapStatus === self::CONFIG_SHOW_PRICE_ENABLED_NONE
            ? $this->never()
            : $this->once();
        $this->scopeConfig->expects($isSaleableExpected)
            ->method('isSetFlag')
            ->with('checkout/cart/restrict_saleable', 'website', null)
            ->willReturn($isSaleableEnabled);
        $this->scopeConfig->expects($isSaleableEnabled ? $this->any() : $this->never())
            ->method('getValue')
            ->with('checkout/cart/is_saleable_groups', 'website', null)
            ->willReturn(self::CONFIG_SALEABLE_GROUPS[$saleableConfigStatus]);
        $this->canShowPrice->method('canShowPrice')
            ->willReturnMap(self::CONFIG_SHOW_PRICE_MAP[$showPriceMapStatus]);

        foreach (self::CONFIG_SALEABLE_EXPECTS[$saleableConfigStatus][$showPriceMapStatus] as $groupId => $assert) {
            $this->assertSame($assert, $this->isSaleable->isSaleable($groupId));
        }
    }

    public function isSaleableDataProvider(): array
    {
        return [
            [true, self::CONFIG_SALEABLE_ENABLED_ALL, self::CONFIG_SHOW_PRICE_ENABLED_ALL],
            [true, self::CONFIG_SALEABLE_ENABLED_ALL, self::CONFIG_SHOW_PRICE_ENABLED_SOME],
            [true, self::CONFIG_SALEABLE_ENABLED_ALL, self::CONFIG_SHOW_PRICE_ENABLED_NONE],
            [true, self::CONFIG_SALEABLE_ENABLED_ALL, self::CONFIG_SHOW_PRICE_DISABLED],
            [true, self::CONFIG_SALEABLE_ENABLED_SOME, self::CONFIG_SHOW_PRICE_ENABLED_ALL],
            [true, self::CONFIG_SALEABLE_ENABLED_SOME, self::CONFIG_SHOW_PRICE_ENABLED_SOME],
            [true, self::CONFIG_SALEABLE_ENABLED_SOME, self::CONFIG_SHOW_PRICE_ENABLED_NONE],
            [true, self::CONFIG_SALEABLE_ENABLED_SOME, self::CONFIG_SHOW_PRICE_DISABLED],
            [true, self::CONFIG_SALEABLE_ENABLED_NONE, self::CONFIG_SHOW_PRICE_ENABLED_ALL],
            [true, self::CONFIG_SALEABLE_ENABLED_NONE, self::CONFIG_SHOW_PRICE_ENABLED_SOME],
            [true, self::CONFIG_SALEABLE_ENABLED_NONE, self::CONFIG_SHOW_PRICE_ENABLED_NONE],
            [true, self::CONFIG_SALEABLE_ENABLED_NONE, self::CONFIG_SHOW_PRICE_DISABLED],
            [false, self::CONFIG_SALEABLE_DISABLED, self::CONFIG_SHOW_PRICE_ENABLED_ALL],
            [false, self::CONFIG_SALEABLE_DISABLED, self::CONFIG_SHOW_PRICE_ENABLED_SOME],
            [false, self::CONFIG_SALEABLE_DISABLED, self::CONFIG_SHOW_PRICE_ENABLED_NONE],
            [false, self::CONFIG_SALEABLE_DISABLED, self::CONFIG_SHOW_PRICE_DISABLED],
        ];
    }
}
