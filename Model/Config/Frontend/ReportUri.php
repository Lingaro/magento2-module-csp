<?php

namespace Orba\Csp\Model\Config\Frontend;

use Magento\Config\Block\System\Config\Form\Field;
use Magento\Framework\Data\Form\Element\AbstractElement;
use Orba\Csp\Controller\Adminhtml\Report\Download;
use Orba\Csp\Logger\Handler\Csp;

/**
 * Class ReportUri
 * @package Orba\Csp\Model\Config\Frontend
 */
class ReportUri extends Field
{
    /**
     * @param AbstractElement $element
     * @return string
     */
    public function render(AbstractElement $element)
    {
        $element->unsScope()->unsCanUseWebsiteValue()->unsCanUseDefaultValue();

        return parent::render($element);
    }

    /**
     * @param AbstractElement $element
     * @return string
     */
    protected function _getElementHtml(AbstractElement $element)
    {
        return sprintf(
            '%s %s (<a href ="%s">%s</a>)',
            __('Logs are saved in file:'),
            Csp::FILEPATH,
            $this->_urlBuilder->getUrl(Download::URL),
            __('download')
        );
    }
}
