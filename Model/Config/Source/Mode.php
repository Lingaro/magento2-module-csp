<?php

namespace Orba\Csp\Model\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;

/**
 * Class Mode
 * @package Orba\Csp\Model\Config\Source
 */
class Mode implements OptionSourceInterface
{

    const DEFAULT_VALUE = 2;
    const REPORT_VALUE = 1;
    const BLOCK_VALUE = 0;

    /**
     * @inheritDoc
     */
    public function toOptionArray()
    {
        return [
            ['value' => self::DEFAULT_VALUE, 'label' => __('Default (from configs)')],
            ['value' => self::REPORT_VALUE, 'label' => __('Report Only')],
            ['value' => self::BLOCK_VALUE, 'label' => __('Block')],
        ];
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $options = $this->toOptionArray();
        $return = [];

        foreach ($options as $option) {
            $return[$option['value']] = $option['label'];
        }

        return $return;
    }
}
