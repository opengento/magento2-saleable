<?php
/**
 * Copyright © OpenGento, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */
declare(strict_types=1);

namespace Opengento\Saleable\Setup\Patch\Data;

use Magento\Catalog\Model\Product;
use Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface;
use Magento\Eav\Setup\EavSetup;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Zend_Validate_Exception;

final class ProductAttributes implements DataPatchInterface
{
    public function __construct(
        private ModuleDataSetupInterface $moduleDataSetup,
        private EavSetupFactory $eavSetupFactory
    ) {}

    /**
     * @throws Zend_Validate_Exception
     * @throws LocalizedException
     */
    public function apply()
    {
        $this->moduleDataSetup->startSetup();

        /** @var EavSetup $eavSetup */
        $eavSetup = $this->eavSetupFactory->create(['setup' => $this->moduleDataSetup]);

        $eavSetup->addAttribute(Product::ENTITY, 'is_purchasable', [
            'type' => 'int',
            'backend' => '',
            'frontend' => '',
            'label' => 'Is Salable',
            'input' => 'boolean',
            'class' => '',
            'source' => '',
            'global' => ScopedAttributeInterface::SCOPE_STORE,
            'visible' => true,
            'required' => true,
            'user_defined' => false,
            'default' => true,
            'searchable' => false,
            'filterable' => false,
            'comparable' => false,
            'visible_on_front' => false,
            'used_in_product_listing' => true,
            'unique' => false,
            'apply_to' => '',
        ]);
        $eavSetup->addAttribute(Product::ENTITY, 'can_show_price', [
            'type' => 'int',
            'backend' => '',
            'frontend' => '',
            'label' => 'Show Price',
            'input' => 'boolean',
            'class' => '',
            'source' => '',
            'global' => ScopedAttributeInterface::SCOPE_STORE,
            'visible' => true,
            'required' => true,
            'user_defined' => false,
            'default' => true,
            'searchable' => false,
            'filterable' => false,
            'comparable' => false,
            'visible_on_front' => false,
            'used_in_product_listing' => true,
            'unique' => false,
            'apply_to' => '',
        ]);

        $this->moduleDataSetup->endSetup();
    }

    public function getAliases(): array
    {
        return [];
    }

    public static function getDependencies(): array
    {
        return [];
    }
}
