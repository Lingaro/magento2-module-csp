<?php

/**
 * Copyright © 2023 Lingaro sp. z o.o. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Lingaro\Csp\Controller\Adminhtml\Report;

use Magento\Backend\App\Action;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\Response\Http\FileFactory;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Filesystem;

/**
 * Class Download
 * @package Lingaro\Csp\Controller\Adminhtml\Report
 */
class Download extends Action implements HttpGetActionInterface
{

    /**
     *
     */
    const ADMIN_RESOURCE = 'Lingaro_Csp::config';
    /**
     * Url to this controller
     */
    const URL = 'csp/report/download/';

    /**
     * @var FileFactory
     */
    private $fileFactory;

    /**
     * @var Filesystem
     */
    private $filesystem;


    /**
     * Download constructor.
     * @param Action\Context $context
     * @param FileFactory $fileFactory
     * @param Filesystem $filesystem
     */
    public function __construct(
        Action\Context $context,
        FileFactory $fileFactory,
        Filesystem $filesystem
    ) {
        $this->fileFactory = $fileFactory;
        $this->filesystem = $filesystem;
        parent::__construct($context);
    }


    /**
     * @return ResponseInterface|Redirect|ResultInterface
     */
    public function execute()
    {
        try {
            $fileName = $this->getRequest()->getParam('filename');
            if (empty($fileName) || preg_match('/\.\.(\\\|\/)/', $fileName) !== 0) {
                throw new LocalizedException(__('Please provide valid file name'));
            }
            $path = $fileName;
            $directory = $this->filesystem->getDirectoryRead(DirectoryList::LOG);
            if ($directory->isFile($path)) {
                return $this->fileFactory->create(
                    $path,
                    $directory->readFile($path),
                    DirectoryList::VAR_DIR
                );
            } else {
                throw new LocalizedException(__("This file doesn't exist %1", $fileName));
            }
        } catch (LocalizedException | \Exception $e) {
            $redirectResult = $this->resultRedirectFactory->create();
            $this->messageManager->addErrorMessage($e->getMessage());
            $redirectResult->setUrl($this->_redirect->getRefererUrl());
            return $redirectResult;
        }
    }
}
