<?php

/**
 * Copyright © 2023 Lingaro sp. z o.o. All rights reserved.
 * See LICENSE for license details.
 */

namespace Lingaro\Csp\Logger\Handler;

use Magento\Framework\Logger\Handler\Base;
use Monolog\Logger;

/**
 * Class Client
 * @package Lingaro\Csp\Logger\Handler
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
