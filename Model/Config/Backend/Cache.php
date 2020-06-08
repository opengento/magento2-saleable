<?php
/**
 * Copyright © OpenGento, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */
declare(strict_types=1);

namespace Opengento\Saleable\Model\Config\Backend;

use Magento\Framework\App\Cache\Type\Block;
use Magento\Framework\App\Config\Value;

class Cache extends Value
{
    public function afterSave(): Cache
    {
        if ($this->isValueChanged()) {
            $this->cacheTypeList->invalidate(Block::TYPE_IDENTIFIER);
        }

        return parent::afterSave();
    }

    public function afterDelete(): Cache
    {
        if ($this->isValueChanged()) {
            $this->cacheTypeList->invalidate(Block::TYPE_IDENTIFIER);
        }

        return parent::afterDelete();
    }
}
