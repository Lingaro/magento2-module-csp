<?php

namespace Orba\Csp\Model\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;

/**
 * Class ReportUri
 * @package Orba\Csp\Model\Config\Source
 */
class ReportUri implements OptionSourceInterface
{
    const DEFAULT_VALUE = 'default';
    const ENDPOINT_VALUE = 'endpoint';
    /**
     * @inheritDoc
     */
    public function toOptionArray()
    {
        return [
            ['value' => self::DEFAULT_VALUE, 'label' => __('Default (from configs)')],
            ['value' => self::ENDPOINT_VALUE, 'label' => __('Report to an build-in endpoint')]
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
