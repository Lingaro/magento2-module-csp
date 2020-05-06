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
 * Class ReportUri
 * @package Orba\Csp\Model\Config\Backend
 */
class ReportUri extends Value
{
    /**
     * @var WriterInterface
     */
    protected $writer;

    /**
     * ReportUri constructor.
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
        if ($this->getPath() == Config::CONFIG_PATH_ORBA_CSP_GENERAL_REPORT_URI) {
            $this->processMode();
        }
        return parent::beforeSave();
    }


    protected function processMode()
    {
        foreach (Config::CONFIG_PATH_CSP_MODE_REPORT_URIS as $path) {
            switch ($this->getValue()) {
                case SourceMode::DEFAULT_VALUE:
                    $this->writer->delete($path);
                    break;
                default:
                    $this->writer->save($path, Config::REPORT_URI);
            }
        }
    }
}
