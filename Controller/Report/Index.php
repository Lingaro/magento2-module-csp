<?php

/**
 * Copyright © 2023 Lingaro sp. z o.o. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Lingaro\Csp\Controller\Report;

use Codeception\Util\HttpCode;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\CsrfAwareActionInterface;
use Magento\Framework\App\Request\InvalidRequestException;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\Convert\DataSize;
use Magento\Framework\Serialize\JsonValidator;
use Lingaro\Csp\Model\Config\Config;
use Psr\Log\LoggerInterface;

/**
 * Class Index
 * @package Lingaro\Csp\Controller\Report
 */
class Index extends Action implements CsrfAwareActionInterface
{

    /**
     *
     */
    const FILE_NAME = "csp.log";

    /**
     *
     */
    const CONTENT_LENGTH_MAX_SIZE = '5K';

    /**
     * Url to this controller
     */
    const URL = '/csp/report/index/';

    /**
     * @var PageFactory
     */
    protected $resultPageFactory;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var DataSize
     */
    protected $dataSize;

    /**
     * @var JsonValidator
     */
    protected $jsonValidator;

    /**
     * @var Config
     */
    protected $config;

    /**
     * Index constructor.
     * @param Context $context
     * @param PageFactory $resultPageFactory
     * @param DataSize $dataSize
     * @param JsonValidator $jsonValidator
     * @param Config $config
     * @param LoggerInterface $logger
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        DataSize $dataSize,
        JsonValidator $jsonValidator,
        Config $config,
        LoggerInterface $logger
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->logger = $logger;
        $this->dataSize = $dataSize;
        $this->jsonValidator = $jsonValidator;
        $this->config = $config;
        parent::__construct($context);
    }

    /**
     * @return ResponseInterface|ResultInterface
     */
    public function execute()
    {
        if (!$this->config->isReportUri()) {
            $this->_forward('noroute');
        }
        $controllerResult = $this->resultFactory->create(ResultFactory::TYPE_JSON);
        $maxSize = $this->dataSize->convertSizeToBytes(self::CONTENT_LENGTH_MAX_SIZE);
        if ($this->getRequest()->getHeader('content-length') > $maxSize) {
            return $this->getError413Response($controllerResult);
        }

        $jsonData = $this->getRequest()->getContent();
        if ($this->getRequest()->getMethod() !== 'POST' || !$this->jsonValidator->isValid($jsonData)) {
            return $controllerResult->setHttpResponseCode(HttpCode::BAD_REQUEST);
        }

        $response = [
            'message' => 'successful'
        ];
        $this->logger->info($jsonData);
        return $controllerResult->setData($response);
    }

    /**
     * @param ResultInterface $controllerResult
     * @return ResultInterface
     */
    private function getError413Response(ResultInterface $controllerResult)
    {
        $controllerResult->setHttpResponseCode(HttpCode::REQUEST_ENTITY_TOO_LARGE);
        $controllerResult->setData(
            ['message' => HttpCode::getDescription(HttpCode::REQUEST_ENTITY_TOO_LARGE)]
        );
        return $controllerResult;
    }

    /**
     * @inheritDoc
     */
    public function createCsrfValidationException(
        RequestInterface $request
    ): ?InvalidRequestException {
        return null;
    }

    /**
     * @inheritDoc
     */
    public function validateForCsrf(RequestInterface $request): ?bool
    {
        return true;
    }
}
