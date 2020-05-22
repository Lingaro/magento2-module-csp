<?php

namespace Orba\Csp\Logger\Handler;

use Magento\Framework\Logger\Handler\Base;
use Monolog\Logger;

/**
 * Class Csp
 * @package Orba\Csp\Logger\Handler
 */
class Csp extends Base
{
    /** Log filename */
    const FILENAME = 'csp.log';

    /** Log filename */
    const FILEPATH = '/var/log/' . self::FILENAME;

    /** @var string */
    protected $fileName = self::FILEPATH;

    /** @var int */
    protected $loggerType = Logger::INFO;
}
