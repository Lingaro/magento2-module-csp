<?php

namespace Orba\Csp\Model\Config;

use Magento\Framework\App\Config\ScopeConfigInterface;

/**
 * Class Config
 * @package Orba\Csp\Model\Config
 */
class Config
{
    const CONFIG_PATH_CSP_MODE_STOREFRONT_REPORT_ONLY = "csp/mode/storefront/report_only";
    const CONFIG_PATH_CSP_MODE_ADMIN_REPORT_ONLY = "csp/mode/admin/report_only";

    const CONFIG_PATH_CSP_MODE_ADMIN_REPORT_URI = "csp/mode/admin/report_uri";
    const CONFIG_PATH_CSP_MODE_STOREFRONT_REPORT_URI = "csp/mode/storefront/report_uri";
    const CONFIG_PATH_CSP_MODE_REPORT_URIS = [
        self::CONFIG_PATH_CSP_MODE_ADMIN_REPORT_URI,
        self::CONFIG_PATH_CSP_MODE_STOREFRONT_REPORT_URI
    ];

    const CONFIG_PATH_ORBA_CSP_GENERAL_STOREFRONT_MODE = "orba_csp/general/storefront_mode";
    const CONFIG_PATH_ORBA_CSP_GENERAL_ADMIN_MODE = "orba_csp/general/admin_mode";
    const CONFIG_PATH_ORBA_CSP_GENERAL_REPORT_URI = "orba_csp/general/report_uri";

    const REPORT_URI = "/csp/report/";

    /**
     * @var ScopeConfigInterface
     */
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
