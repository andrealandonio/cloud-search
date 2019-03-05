<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace WP_Cloud_Search\Symfony\Component\EventDispatcher\Tests;

use WP_Cloud_Search\Symfony\Component\EventDispatcher\EventDispatcher;
class EventDispatcherTest extends \WP_Cloud_Search\Symfony\Component\EventDispatcher\Tests\AbstractEventDispatcherTest
{
    protected function createEventDispatcher()
    {
        return new \WP_Cloud_Search\Symfony\Component\EventDispatcher\EventDispatcher();
    }
}
