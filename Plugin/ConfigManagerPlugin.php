<?php

namespace Orba\Csp\Plugin;

use Magento\Csp\Model\Mode\ConfigManager;
use Magento\Csp\Model\Mode\Data\ModeConfigured;
use \Orba\Csp\Model\Config\Config as CspConfig;

/**
 * Class ConfigManagerPlugin
 * @package Orba\Csp\Plugin
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
