<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace WP_Cloud_Search\Symfony\Component\EventDispatcher\Tests\Debug;

use WP_Cloud_Search\Symfony\Component\EventDispatcher\Debug\TraceableEventDispatcher;
use WP_Cloud_Search\Symfony\Component\EventDispatcher\EventDispatcherInterface;
use WP_Cloud_Search\Symfony\Component\EventDispatcher\EventSubscriberInterface;
use WP_Cloud_Search\Symfony\Component\EventDispatcher\EventDispatcher;
use WP_Cloud_Search\Symfony\Component\EventDispatcher\Event;
use WP_Cloud_Search\Symfony\Component\Stopwatch\Stopwatch;
class TraceableEventDispatcherTest extends \WP_Cloud_Search\PHPUnit_Framework_TestCase
{
    public function testAddRemoveListener()
    {
        $dispatcher = new \WP_Cloud_Search\Symfony\Component\EventDispatcher\EventDispatcher();
        $tdispatcher = new \WP_Cloud_Search\Symfony\Component\EventDispatcher\Debug\TraceableEventDispatcher($dispatcher, new \WP_Cloud_Search\Symfony\Component\Stopwatch\Stopwatch());
        $tdispatcher->addListener('foo', $listener = function () {
        });
        $listeners = $dispatcher->getListeners('foo');
        $this->assertCount(1, $listeners);
        $this->assertSame($listener, $listeners[0]);
        $tdispatcher->removeListener('foo', $listener);
        $this->assertCount(0, $dispatcher->getListeners('foo'));
    }
    public function testGetListeners()
    {
        $dispatcher = new \WP_Cloud_Search\Symfony\Component\EventDispatcher\EventDispatcher();
        $tdispatcher = new \WP_Cloud_Search\Symfony\Component\EventDispatcher\Debug\TraceableEventDispatcher($dispatcher, new \WP_Cloud_Search\Symfony\Component\Stopwatch\Stopwatch());
        $tdispatcher->addListener('foo', $listener = function () {
        });
        $this->assertSame($dispatcher->getListeners('foo'), $tdispatcher->getListeners('foo'));
    }
    public function testHasListeners()
    {
        $dispatcher = new \WP_Cloud_Search\Symfony\Component\EventDispatcher\EventDispatcher();
        $tdispatcher = new \WP_Cloud_Search\Symfony\Component\EventDispatcher\Debug\TraceableEventDispatcher($dispatcher, new \WP_Cloud_Search\Symfony\Component\Stopwatch\Stopwatch());
        $this->assertFalse($dispatcher->hasListeners('foo'));
        $this->assertFalse($tdispatcher->hasListeners('foo'));
        $tdispatcher->addListener('foo', $listener = function () {
        });
        $this->assertTrue($dispatcher->hasListeners('foo'));
        $this->assertTrue($tdispatcher->hasListeners('foo'));
    }
    public function testGetListenerPriority()
    {
        $dispatcher = new \WP_Cloud_Search\Symfony\Component\EventDispatcher\EventDispatcher();
        $tdispatcher = new \WP_Cloud_Search\Symfony\Component\EventDispatcher\Debug\TraceableEventDispatcher($dispatcher, new \WP_Cloud_Search\Symfony\Component\Stopwatch\Stopwatch());
        $tdispatcher->addListener('foo', function () {
        }, 123);
        $listeners = $dispatcher->getListeners('foo');
        $this->assertSame(123, $tdispatcher->getListenerPriority('foo', $listeners[0]));
        // Verify that priority is preserved when listener is removed and re-added
        // in preProcess() and postProcess().
        $tdispatcher->dispatch('foo', new \WP_Cloud_Search\Symfony\Component\EventDispatcher\Event());
        $listeners = $dispatcher->getListeners('foo');
        $this->assertSame(123, $tdispatcher->getListenerPriority('foo', $listeners[0]));
    }
    public function testGetListenerPriorityReturnsZeroWhenWrappedMethodDoesNotExist()
    {
        $dispatcher = $this->getMock('WP_Cloud_Search\\Symfony\\Component\\EventDispatcher\\EventDispatcherInterface');
        $traceableEventDispatcher = new \WP_Cloud_Search\Symfony\Component\EventDispatcher\Debug\TraceableEventDispatcher($dispatcher, new \WP_Cloud_Search\Symfony\Component\Stopwatch\Stopwatch());
        $traceableEventDispatcher->addListener('foo', function () {
        }, 123);
        $listeners = $traceableEventDispatcher->getListeners('foo');
        $this->assertSame(0, $traceableEventDispatcher->getListenerPriority('foo', $listeners[0]));
    }
    public function testAddRemoveSubscriber()
    {
        $dispatcher = new \WP_Cloud_Search\Symfony\Component\EventDispatcher\EventDispatcher();
        $tdispatcher = new \WP_Cloud_Search\Symfony\Component\EventDispatcher\Debug\TraceableEventDispatcher($dispatcher, new \WP_Cloud_Search\Symfony\Component\Stopwatch\Stopwatch());
        $subscriber = new \WP_Cloud_Search\Symfony\Component\EventDispatcher\Tests\Debug\EventSubscriber();
        $tdispatcher->addSubscriber($subscriber);
        $listeners = $dispatcher->getListeners('foo');
        $this->assertCount(1, $listeners);
        $this->assertSame(array($subscriber, 'call'), $listeners[0]);
        $tdispatcher->removeSubscriber($subscriber);
        $this->assertCount(0, $dispatcher->getListeners('foo'));
    }
    public function testGetCalledListeners()
    {
        $dispatcher = new \WP_Cloud_Search\Symfony\Component\EventDispatcher\EventDispatcher();
        $tdispatcher = new \WP_Cloud_Search\Symfony\Component\EventDispatcher\Debug\TraceableEventDispatcher($dispatcher, new \WP_Cloud_Search\Symfony\Component\Stopwatch\Stopwatch());
        $tdispatcher->addListener('foo', $listener = function () {
        });
        $this->assertEquals(array(), $tdispatcher->getCalledListeners());
        $this->assertEquals(array('foo.closure' => array('event' => 'foo', 'type' => 'Closure', 'pretty' => 'closure', 'priority' => 0)), $tdispatcher->getNotCalledListeners());
        $tdispatcher->dispatch('foo');
        $this->assertEquals(array('foo.closure' => array('event' => 'foo', 'type' => 'Closure', 'pretty' => 'closure', 'priority' => null)), $tdispatcher->getCalledListeners());
        $this->assertEquals(array(), $tdispatcher->getNotCalledListeners());
    }
    public function testGetCalledListenersNested()
    {
        $tdispatcher = null;
        $dispatcher = new \WP_Cloud_Search\Symfony\Component\EventDispatcher\Debug\TraceableEventDispatcher(new \WP_Cloud_Search\Symfony\Component\EventDispatcher\EventDispatcher(), new \WP_Cloud_Search\Symfony\Component\Stopwatch\Stopwatch());
        $dispatcher->addListener('foo', function (\WP_Cloud_Search\Symfony\Component\EventDispatcher\Event $event, $eventName, $dispatcher) use(&$tdispatcher) {
            $tdispatcher = $dispatcher;
            $dispatcher->dispatch('bar');
        });
        $dispatcher->addListener('bar', function (\WP_Cloud_Search\Symfony\Component\EventDispatcher\Event $event) {
        });
        $dispatcher->dispatch('foo');
        $this->assertSame($dispatcher, $tdispatcher);
        $this->assertCount(2, $dispatcher->getCalledListeners());
    }
    public function testLogger()
    {
        $logger = $this->getMock('WP_Cloud_Search\\Psr\\Log\\LoggerInterface');
        $dispatcher = new \WP_Cloud_Search\Symfony\Component\EventDispatcher\EventDispatcher();
        $tdispatcher = new \WP_Cloud_Search\Symfony\Component\EventDispatcher\Debug\TraceableEventDispatcher($dispatcher, new \WP_Cloud_Search\Symfony\Component\Stopwatch\Stopwatch(), $logger);
        $tdispatcher->addListener('foo', $listener1 = function () {
        });
        $tdispatcher->addListener('foo', $listener2 = function () {
        });
        $logger->expects($this->at(0))->method('debug')->with('Notified event "foo" to listener "closure".');
        $logger->expects($this->at(1))->method('debug')->with('Notified event "foo" to listener "closure".');
        $tdispatcher->dispatch('foo');
    }
    public function testLoggerWithStoppedEvent()
    {
        $logger = $this->getMock('WP_Cloud_Search\\Psr\\Log\\LoggerInterface');
        $dispatcher = new \WP_Cloud_Search\Symfony\Component\EventDispatcher\EventDispatcher();
        $tdispatcher = new \WP_Cloud_Search\Symfony\Component\EventDispatcher\Debug\TraceableEventDispatcher($dispatcher, new \WP_Cloud_Search\Symfony\Component\Stopwatch\Stopwatch(), $logger);
        $tdispatcher->addListener('foo', $listener1 = function (\WP_Cloud_Search\Symfony\Component\EventDispatcher\Event $event) {
            $event->stopPropagation();
        });
        $tdispatcher->addListener('foo', $listener2 = function () {
        });
        $logger->expects($this->at(0))->method('debug')->with('Notified event "foo" to listener "closure".');
        $logger->expects($this->at(1))->method('debug')->with('Listener "closure" stopped propagation of the event "foo".');
        $logger->expects($this->at(2))->method('debug')->with('Listener "closure" was not called for event "foo".');
        $tdispatcher->dispatch('foo');
    }
    public function testDispatchCallListeners()
    {
        $called = array();
        $dispatcher = new \WP_Cloud_Search\Symfony\Component\EventDispatcher\EventDispatcher();
        $tdispatcher = new \WP_Cloud_Search\Symfony\Component\EventDispatcher\Debug\TraceableEventDispatcher($dispatcher, new \WP_Cloud_Search\Symfony\Component\Stopwatch\Stopwatch());
        $tdispatcher->addListener('foo', function () use(&$called) {
            $called[] = 'foo1';
        }, 10);
        $tdispatcher->addListener('foo', function () use(&$called) {
            $called[] = 'foo2';
        }, 20);
        $tdispatcher->dispatch('foo');
        $this->assertSame(array('foo2', 'foo1'), $called);
    }
    public function testDispatchNested()
    {
        $dispatcher = new \WP_Cloud_Search\Symfony\Component\EventDispatcher\Debug\TraceableEventDispatcher(new \WP_Cloud_Search\Symfony\Component\EventDispatcher\EventDispatcher(), new \WP_Cloud_Search\Symfony\Component\Stopwatch\Stopwatch());
        $loop = 1;
        $dispatcher->addListener('foo', $listener1 = function () use($dispatcher, &$loop) {
            ++$loop;
            if (2 == $loop) {
                $dispatcher->dispatch('foo');
            }
        });
        $dispatcher->dispatch('foo');
    }
    public function testDispatchReusedEventNested()
    {
        $nestedCall = \false;
        $dispatcher = new \WP_Cloud_Search\Symfony\Component\EventDispatcher\Debug\TraceableEventDispatcher(new \WP_Cloud_Search\Symfony\Component\EventDispatcher\EventDispatcher(), new \WP_Cloud_Search\Symfony\Component\Stopwatch\Stopwatch());
        $dispatcher->addListener('foo', function (\WP_Cloud_Search\Symfony\Component\EventDispatcher\Event $e) use($dispatcher) {
            $dispatcher->dispatch('bar', $e);
        });
        $dispatcher->addListener('bar', function (\WP_Cloud_Search\Symfony\Component\EventDispatcher\Event $e) use(&$nestedCall) {
            $nestedCall = \true;
        });
        $this->assertFalse($nestedCall);
        $dispatcher->dispatch('foo');
        $this->assertTrue($nestedCall);
    }
    public function testListenerCanRemoveItselfWhenExecuted()
    {
        $eventDispatcher = new \WP_Cloud_Search\Symfony\Component\EventDispatcher\Debug\TraceableEventDispatcher(new \WP_Cloud_Search\Symfony\Component\EventDispatcher\EventDispatcher(), new \WP_Cloud_Search\Symfony\Component\Stopwatch\Stopwatch());
        $listener1 = function ($event, $eventName, \WP_Cloud_Search\Symfony\Component\EventDispatcher\EventDispatcherInterface $dispatcher) use(&$listener1) {
            $dispatcher->removeListener('foo', $listener1);
        };
        $eventDispatcher->addListener('foo', $listener1);
        $eventDispatcher->addListener('foo', function () {
        });
        $eventDispatcher->dispatch('foo');
        $this->assertCount(1, $eventDispatcher->getListeners('foo'), 'expected listener1 to be removed');
    }
}
class EventSubscriber implements \WP_Cloud_Search\Symfony\Component\EventDispatcher\EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return array('foo' => 'call');
    }
}
