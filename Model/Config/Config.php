<?php

namespace Orba\Csp\Model\Config;

use Magento\Framework\App\Config\ScopeConfigInterface;

/**
 * Class Config
 * @package Orba\Csp\Model\Config
 */
class Config
{
    const CONFIG_PATH_ORBA_CSP_GENERAL_REPORT_URI = "orba_csp/general/report_uri";

    /** @var ScopeConfigInterface */
    private $scopeConfig;

    /**
     * Config constructor.
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig
    ) {
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * @return int
     */
    public function isReportUri() : int
    {
        return (int) $this->scopeConfig->getValue(
            self::CONFIG_PATH_ORBA_CSP_GENERAL_REPORT_URI,
            ScopeConfigInterface::SCOPE_TYPE_DEFAULT
        );
    }
}
