<?php
/**
 * Copyright © OpenGento, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */
declare(strict_types=1);

namespace Opengento\Saleable\Setup\Patch\Data;

use Magento\Catalog\Model\Product;
use Magento\Catalog\Model\ResourceModel\Product as ResourceProduct;
use Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface;
use Magento\Eav\Setup\EavSetup;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\Framework\DB\Sql\Expression;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Store\Model\Store;
use Zend_Validate_Exception;

final class ProductAttributes implements DataPatchInterface
{
    public function __construct(
        private ModuleDataSetupInterface $moduleDataSetup,
        private EavSetupFactory $eavSetupFactory,
        private ResourceProduct $productResource
    ) {}

    public function getAliases(): array
    {
        return [];
    }

    public static function getDependencies(): array
    {
        return [];
    }

    /**
     * @throws Zend_Validate_Exception
     * @throws LocalizedException
     */
    public function apply()
    {
        $this->moduleDataSetup->startSetup();

        /** @var EavSetup $eavSetup */
        $eavSetup = $this->eavSetupFactory->create(['setup' => $this->moduleDataSetup]);

        $this->addAttributes($eavSetup);
        $this->addDefaultValues($eavSetup);

        $this->moduleDataSetup->endSetup();
    }

    private function addAttributes(EavSetup $eavSetup): void
    {
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
    }

    private function addDefaultValues(EavSetup $eavSetup): void
    {
        foreach (['is_purchasable', 'can_show_price'] as $attributeCode) {
            $this->addDefaultValue($eavSetup, $eavSetup->getAttribute(Product::ENTITY, $attributeCode));
        }
    }
    
    private function addDefaultValue(EavSetup $eavSetup, array $attribute): void
    {
        $connection = $this->productResource->getConnection();
        $select = $connection
            ->select()
            ->from(
                $this->productResource->getEntityTable(),
                [
                    'attribute_id' => new Expression($attribute['attribute_id']),
                    'store_id' => new Expression(Store::DEFAULT_STORE_ID),
                    'value' => new Expression(1),
                    $this->productResource->getLinkField(),
                ]
            );

        $insert = $connection->insertFromSelect(
            $select,
            $connection->getTableName('catalog_product_entity_int'),
            [
                'attribute_id',
                'store_id',
                'value',
                $this->productResource->getLinkField()
            ],
            AdapterInterface::INSERT_IGNORE
        );

        $connection->query($insert);
    }
}
