<?php
/**
 * Copyright © 2018 Deja. All rights reserved.
 * @author Tadjuddin
 * @email udinnoer@gmail.com
 *
 */

namespace Deja\Tools\Logger;

use Monolog\Logger;
/**
* 
*/
class Handler extends \Magento\Framework\Logger\Handler\Base
{
    /**
     * Logging level
     * @var int
     */
    protected $loggerType = Logger::INFO;

    /**
     * File name
     * @var string
     */
    protected $fileName = '/var/log/tools.log';
}