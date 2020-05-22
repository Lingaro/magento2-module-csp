<?php declare(strict_types=1);
/**
 * Copyright (c) 2019 Orba Sp. z o.o.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */

namespace Orba\Csp\Controller\Report;

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
use Orba\Csp\Model\Config\Config;
use Psr\Log\LoggerInterface;

/**
 * Class Index
 * @package Orba\Csp\Controller\Report
 */
class Index extends Action implements CsrfAwareActionInterface
{
    /** Allowed report length */
    const CONTENT_LENGTH_MAX_SIZE = '5K';

    /** Url to this controller */
    const URL = '/csp/report/';

    /** @var PageFactory */
    protected $resultPageFactory;

    /** @var LoggerInterface */
    protected $logger;

    /** @var DataSize */
    protected $dataSize;

    /** @var JsonValidator */
    protected $jsonValidator;

    /** @var Config */
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
        $postData = $this->getRequest()->getContent() ?? '';
        if ($this->getRequest()->getMethod() !== 'POST' || empty($postData)) {
            return $controllerResult->setHttpResponseCode(HttpCode::BAD_REQUEST);
        }
        $this->logger->info($this->prepareLogContent($postData));

        return $controllerResult->setData(['success' => true]);
    }

    /**
     * @param string $postData
     * @return string
     */
    protected function prepareLogContent(string $postData) : string
    {
        $maxSize = $this->dataSize->convertSizeToBytes(self::CONTENT_LENGTH_MAX_SIZE);
        if (strlen($postData) > $maxSize) {
            $postData = mb_strcut($postData, 0, $maxSize) . '...';
        }

        return $postData;
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
