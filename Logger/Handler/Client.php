<?php

namespace Orba\Csp\Logger\Handler;

use Magento\Framework\Logger\Handler\Base;
use Monolog\Logger;

/**
 * Class Client
 * @package Orba\Csp\Logger\Handler
 */
class Client extends Base
{
    /**
     *
     */
    const FILENAME = '/var/log/csp.log';

    /**
     * @var string
     */
    protected $fileName = self::FILENAME;

    /**
     * @var int
     */
    protected $loggerType = Logger::INFO;
}
