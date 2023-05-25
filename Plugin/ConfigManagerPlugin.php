<?php

/**
 * Copyright © 2023 Lingaro sp. z o.o. All rights reserved.
 * See LICENSE for license details.
 */

namespace Lingaro\Csp\Plugin;

use Magento\Csp\Model\Mode\ConfigManager;
use Magento\Csp\Model\Mode\Data\ModeConfigured;
use \Lingaro\Csp\Model\Config\Config as CspConfig;

/**
 * Class ConfigManagerPlugin
 * @package Lingaro\Csp\Plugin
 */
class ConfigManagerPlugin
{

    /**
     * @var CspConfig
     */
    private $config;

    /**
     * ConfigManagerPlugin constructor.
     * @param CspConfig $config
     */
    public function __construct(CspConfig $config)
    {
        $this->config = $config;
    }

    /**
     * @param ConfigManager $subject
     * @param $result
     * @return ModeConfigured
     */
    public function afterGetConfigured(ConfigManager $subject, $result)
    {
        $reportUri = $result->getReportUri();
        if ($reportUri === null && $this->config->isReportUri()) {
            return new ModeConfigured($result->isReportOnly(), CspConfig::REPORT_URI);
        }
        return $result;
    }
}
