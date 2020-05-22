<?php
declare(strict_types=1);

namespace Orba\Csp\Controller\Adminhtml\Report;

use Exception;
use Magento\Backend\App\Action;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\App\Response\Http\FileFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Filesystem;
use Orba\Csp\Logger\Handler\Csp;
use Psr\Log\LoggerInterface;

/**
 * Class Download
 * @package Orba\Csp\Controller\Adminhtml\Report
 */
class Download extends Action implements HttpGetActionInterface
{
    /** ACL resource */
    const ADMIN_RESOURCE = 'Orba_Csp::config';

    /** Url to this controller */
    const URL = 'csp/report/download/';

    /** @var FileFactory */
    private $fileFactory;

    /** @var Filesystem */
    private $filesystem;

    /** @var LoggerInterface */
    private $logger;

    /**
     * Download constructor.
     * @param Action\Context $context
     * @param FileFactory $fileFactory
     * @param Filesystem $filesystem
     * @param LoggerInterface $logger
     */
    public function __construct(
        Action\Context $context,
        FileFactory $fileFactory,
        Filesystem $filesystem,
        LoggerInterface $logger
    ) {
        $this->fileFactory = $fileFactory;
        $this->filesystem = $filesystem;
        $this->logger = $logger;

        parent::__construct($context);
    }

    /**
     * @return ResponseInterface|Redirect|ResultInterface
     */
    public function execute()
    {
        $directory = $this->filesystem->getDirectoryRead(DirectoryList::LOG);
        if (!$directory->isFile(Csp::FILENAME)) {
            $redirectResult = $this->resultRedirectFactory->create();
            $this->messageManager->addErrorMessage(
                __('File does not exist: %1. It is possible that no report has been logged yet.', Csp::FILENAME)
            );
            $redirectResult->setUrl($this->_redirect->getRefererUrl());

            return $redirectResult;
        }

        try {
            return $this->fileFactory->create(
                Csp::FILENAME,
                $content = $directory->readFile(Csp::FILENAME),
                DirectoryList::VAR_DIR
            );
        } catch (Exception $e) {
            $this->logger->error($e->getMessage());
            $redirectResult = $this->resultRedirectFactory->create();
            $this->messageManager->addErrorMessage(
                __('Could not read file: %1', Csp::FILENAME)
            );
            $redirectResult->setUrl($this->_redirect->getRefererUrl());

            return $redirectResult;
        }
    }
}
