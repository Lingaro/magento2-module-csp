<?php

namespace Orba\Csp\Model\Config\Backend;

use Magento\Framework\App\Cache\TypeListInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Config\Value;
use Magento\Framework\Data\Collection\AbstractDb;
use Magento\Framework\Model\Context;
use Magento\Framework\Model\ResourceModel\AbstractResource;
use \Magento\Framework\Registry;
use \Magento\Framework\App\Config\Storage\WriterInterface;
use Orba\Csp\Model\Config\Config;
use \Orba\Csp\Model\Config\Source\Mode as SourceMode;

/**
 * Class Mode
 * @package Orba\Csp\Model\Config\Backend
 */
class Mode extends Value
{

    /**
     * @var WriterInterface
     */
    protected $writer;


    /**
     * Mode constructor.
     * @param Context $context
     * @param Registry $registry
     * @param ScopeConfigInterface $config
     * @param TypeListInterface $cacheTypeList
     * @param WriterInterface $writer
     * @param AbstractResource|null $resource
     * @param AbstractDb|null $resourceCollection
     * @param array $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        ScopeConfigInterface $config,
        TypeListInterface $cacheTypeList,
        WriterInterface $writer,
        AbstractResource $resource = null,
        AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        $this->writer = $writer;
        parent::__construct($context, $registry, $config, $cacheTypeList, $resource, $resourceCollection, $data);
    }


    /**
     * @inheritDoc
     */
    public function beforeSave()
    {
        switch ($this->getPath()) {
            case Config::CONFIG_PATH_ORBA_CSP_GENERAL_ADMIN_MODE:
                $config = Config::CONFIG_PATH_CSP_MODE_ADMIN_REPORT_ONLY;
                break;
            case Config::CONFIG_PATH_ORBA_CSP_GENERAL_STOREFRONT_MODE:
                $config = Config::CONFIG_PATH_CSP_MODE_STOREFRONT_REPORT_ONLY;
                break;
        }
        if (isset($config)) {
            $this->processMode($config);
        }
        return parent::beforeSave();
    }

    /**
     * @param string $config
     */
    protected function processMode(string $config)
    {
        switch ($this->getValue()) {
            case SourceMode::DEFAULT_VALUE:
                $this->writer->delete($config);
                break;
            default:
                $this->writer->save($config, $this->getValue());
        }
    }
}
