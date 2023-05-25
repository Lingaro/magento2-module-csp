<?php

/**
 * Copyright © 2023 Lingaro sp. z o.o. All rights reserved.
 * See LICENSE for license details.
 */

namespace Lingaro\Csp\Model\Config\Frontend;

use Magento\Backend\Block\Template\Context;
use Magento\Config\Block\System\Config\Form\Field;
use Magento\Framework\Data\Form\Element\AbstractElement;
use Lingaro\Csp\Controller\Adminhtml\Report\Download;
use Lingaro\Csp\Controller\Report\Index;
use Lingaro\Csp\Logger\Handler\Client;

class ReportUri extends Field
{
//    public function __construct(
//        Context $context,
//        array $data = []
//    ) {
//        parent::__construct($context, $data);
//    }

    public function render(AbstractElement $element)
    {
        $element->unsScope()->unsCanUseWebsiteValue()->unsCanUseDefaultValue();
        return parent::render($element);
    }

//
    protected function _getElementHtml(AbstractElement $element)
    {
        return sprintf(
            'You can download logs from %s, or click  <a href ="%s">%s</a>',
            Client::FILENAME,
            $this->_urlBuilder->getUrl(Download::URL, ['filename' => Index::FILE_NAME]),
            __('here')
        );
    }
}
