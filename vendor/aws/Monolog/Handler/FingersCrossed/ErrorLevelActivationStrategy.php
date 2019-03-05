<?php

/*
 * This file is part of the Monolog package.
 *
 * (c) Jordi Boggiano <j.boggiano@seld.be>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace WP_Cloud_Search\Monolog\Handler\FingersCrossed;

use WP_Cloud_Search\Monolog\Logger;
/**
 * Error level based activation strategy.
 *
 * @author Johannes M. Schmitt <schmittjoh@gmail.com>
 */
class ErrorLevelActivationStrategy implements \WP_Cloud_Search\Monolog\Handler\FingersCrossed\ActivationStrategyInterface
{
    private $actionLevel;
    public function __construct($actionLevel)
    {
        $this->actionLevel = \WP_Cloud_Search\Monolog\Logger::toMonologLevel($actionLevel);
    }
    public function isHandlerActivated(array $record)
    {
        return $record['level'] >= $this->actionLevel;
    }
}
