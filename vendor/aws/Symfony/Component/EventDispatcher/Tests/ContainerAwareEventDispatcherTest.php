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

use WP_Cloud_Search\Symfony\Component\DependencyInjection\Container;
use WP_Cloud_Search\Symfony\Component\DependencyInjection\Scope;
use WP_Cloud_Search\Symfony\Component\EventDispatcher\ContainerAwareEventDispatcher;
use WP_Cloud_Search\Symfony\Component\EventDispatcher\Event;
use WP_Cloud_Search\Symfony\Component\EventDispatcher\EventSubscriberInterface;
class ContainerAwareEventDispatcherTest extends \WP_Cloud_Search\Symfony\Component\EventDispatcher\Tests\AbstractEventDispatcherTest
{
    protected function createEventDispatcher()
    {
        $container = new \WP_Cloud_Search\Symfony\Component\DependencyInjection\Container();
        return new \WP_Cloud_Search\Symfony\Component\EventDispatcher\ContainerAwareEventDispatcher($container);
    }
    public function testAddAListenerService()
    {
        $event = new \WP_Cloud_Search\Symfony\Component\EventDispatcher\Event();
        $service = $this->getMock('WP_Cloud_Search\\Symfony\\Component\\EventDispatcher\\Tests\\Service');
        $service->expects($this->once())->method('onEvent')->with($event);
        $container = new \WP_Cloud_Search\Symfony\Component\DependencyInjection\Container();
        $container->set('service.listener', $service);
        $dispatcher = new \WP_Cloud_Search\Symfony\Component\EventDispatcher\ContainerAwareEventDispatcher($container);
        $dispatcher->addListenerService('onEvent', array('service.listener', 'onEvent'));
        $dispatcher->dispatch('onEvent', $event);
    }
    public function testAddASubscriberService()
    {
        $event = new \WP_Cloud_Search\Symfony\Component\EventDispatcher\Event();
        $service = $this->getMock('WP_Cloud_Search\\Symfony\\Component\\EventDispatcher\\Tests\\SubscriberService');
        $service->expects($this->once())->method('onEvent')->with($event);
        $service->expects($this->once())->method('onEventWithPriority')->with($event);
        $service->expects($this->once())->method('onEventNested')->with($event);
        $container = new \WP_Cloud_Search\Symfony\Component\DependencyInjection\Container();
        $container->set('service.subscriber', $service);
        $dispatcher = new \WP_Cloud_Search\Symfony\Component\EventDispatcher\ContainerAwareEventDispatcher($container);
        $dispatcher->addSubscriberService('service.subscriber', 'WP_Cloud_Search\\Symfony\\Component\\EventDispatcher\\Tests\\SubscriberService');
        $dispatcher->dispatch('onEvent', $event);
        $dispatcher->dispatch('onEventWithPriority', $event);
        $dispatcher->dispatch('onEventNested', $event);
    }
    public function testPreventDuplicateListenerService()
    {
        $event = new \WP_Cloud_Search\Symfony\Component\EventDispatcher\Event();
        $service = $this->getMock('WP_Cloud_Search\\Symfony\\Component\\EventDispatcher\\Tests\\Service');
        $service->expects($this->once())->method('onEvent')->with($event);
        $container = new \WP_Cloud_Search\Symfony\Component\DependencyInjection\Container();
        $container->set('service.listener', $service);
        $dispatcher = new \WP_Cloud_Search\Symfony\Component\EventDispatcher\ContainerAwareEventDispatcher($container);
        $dispatcher->addListenerService('onEvent', array('service.listener', 'onEvent'), 5);
        $dispatcher->addListenerService('onEvent', array('service.listener', 'onEvent'), 10);
        $dispatcher->dispatch('onEvent', $event);
    }
    /**
     * @expectedException \InvalidArgumentException
     * @group legacy
     */
    public function testTriggerAListenerServiceOutOfScope()
    {
        $service = $this->getMock('WP_Cloud_Search\\Symfony\\Component\\EventDispatcher\\Tests\\Service');
        $scope = new \WP_Cloud_Search\Symfony\Component\DependencyInjection\Scope('scope');
        $container = new \WP_Cloud_Search\Symfony\Component\DependencyInjection\Container();
        $container->addScope($scope);
        $container->enterScope('scope');
        $container->set('service.listener', $service, 'scope');
        $dispatcher = new \WP_Cloud_Search\Symfony\Component\EventDispatcher\ContainerAwareEventDispatcher($container);
        $dispatcher->addListenerService('onEvent', array('service.listener', 'onEvent'));
        $container->leaveScope('scope');
        $dispatcher->dispatch('onEvent');
    }
    /**
     * @group legacy
     */
    public function testReEnteringAScope()
    {
        $event = new \WP_Cloud_Search\Symfony\Component\EventDispatcher\Event();
        $service1 = $this->getMock('WP_Cloud_Search\\Symfony\\Component\\EventDispatcher\\Tests\\Service');
        $service1->expects($this->exactly(2))->method('onEvent')->with($event);
        $scope = new \WP_Cloud_Search\Symfony\Component\DependencyInjection\Scope('scope');
        $container = new \WP_Cloud_Search\Symfony\Component\DependencyInjection\Container();
        $container->addScope($scope);
        $container->enterScope('scope');
        $container->set('service.listener', $service1, 'scope');
        $dispatcher = new \WP_Cloud_Search\Symfony\Component\EventDispatcher\ContainerAwareEventDispatcher($container);
        $dispatcher->addListenerService('onEvent', array('service.listener', 'onEvent'));
        $dispatcher->dispatch('onEvent', $event);
        $service2 = $this->getMock('WP_Cloud_Search\\Symfony\\Component\\EventDispatcher\\Tests\\Service');
        $service2->expects($this->once())->method('onEvent')->with($event);
        $container->enterScope('scope');
        $container->set('service.listener', $service2, 'scope');
        $dispatcher->dispatch('onEvent', $event);
        $container->leaveScope('scope');
        $dispatcher->dispatch('onEvent');
    }
    public function testHasListenersOnLazyLoad()
    {
        $event = new \WP_Cloud_Search\Symfony\Component\EventDispatcher\Event();
        $service = $this->getMock('WP_Cloud_Search\\Symfony\\Component\\EventDispatcher\\Tests\\Service');
        $container = new \WP_Cloud_Search\Symfony\Component\DependencyInjection\Container();
        $container->set('service.listener', $service);
        $dispatcher = new \WP_Cloud_Search\Symfony\Component\EventDispatcher\ContainerAwareEventDispatcher($container);
        $dispatcher->addListenerService('onEvent', array('service.listener', 'onEvent'));
        $event->setDispatcher($dispatcher);
        $event->setName('onEvent');
        $service->expects($this->once())->method('onEvent')->with($event);
        $this->assertTrue($dispatcher->hasListeners());
        if ($dispatcher->hasListeners('onEvent')) {
            $dispatcher->dispatch('onEvent');
        }
    }
    public function testGetListenersOnLazyLoad()
    {
        $service = $this->getMock('WP_Cloud_Search\\Symfony\\Component\\EventDispatcher\\Tests\\Service');
        $container = new \WP_Cloud_Search\Symfony\Component\DependencyInjection\Container();
        $container->set('service.listener', $service);
        $dispatcher = new \WP_Cloud_Search\Symfony\Component\EventDispatcher\ContainerAwareEventDispatcher($container);
        $dispatcher->addListenerService('onEvent', array('service.listener', 'onEvent'));
        $listeners = $dispatcher->getListeners();
        $this->assertTrue(isset($listeners['onEvent']));
        $this->assertCount(1, $dispatcher->getListeners('onEvent'));
    }
    public function testRemoveAfterDispatch()
    {
        $service = $this->getMock('WP_Cloud_Search\\Symfony\\Component\\EventDispatcher\\Tests\\Service');
        $container = new \WP_Cloud_Search\Symfony\Component\DependencyInjection\Container();
        $container->set('service.listener', $service);
        $dispatcher = new \WP_Cloud_Search\Symfony\Component\EventDispatcher\ContainerAwareEventDispatcher($container);
        $dispatcher->addListenerService('onEvent', array('service.listener', 'onEvent'));
        $dispatcher->dispatch('onEvent', new \WP_Cloud_Search\Symfony\Component\EventDispatcher\Event());
        $dispatcher->removeListener('onEvent', array($container->get('service.listener'), 'onEvent'));
        $this->assertFalse($dispatcher->hasListeners('onEvent'));
    }
    public function testRemoveBeforeDispatch()
    {
        $service = $this->getMock('WP_Cloud_Search\\Symfony\\Component\\EventDispatcher\\Tests\\Service');
        $container = new \WP_Cloud_Search\Symfony\Component\DependencyInjection\Container();
        $container->set('service.listener', $service);
        $dispatcher = new \WP_Cloud_Search\Symfony\Component\EventDispatcher\ContainerAwareEventDispatcher($container);
        $dispatcher->addListenerService('onEvent', array('service.listener', 'onEvent'));
        $dispatcher->removeListener('onEvent', array($container->get('service.listener'), 'onEvent'));
        $this->assertFalse($dispatcher->hasListeners('onEvent'));
    }
}
class Service
{
    public function onEvent(\WP_Cloud_Search\Symfony\Component\EventDispatcher\Event $e)
    {
    }
}
class SubscriberService implements \WP_Cloud_Search\Symfony\Component\EventDispatcher\EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return array('onEvent' => 'onEvent', 'onEventWithPriority' => array('onEventWithPriority', 10), 'onEventNested' => array(array('onEventNested')));
    }
    public function onEvent(\WP_Cloud_Search\Symfony\Component\EventDispatcher\Event $e)
    {
    }
    public function onEventWithPriority(\WP_Cloud_Search\Symfony\Component\EventDispatcher\Event $e)
    {
    }
    public function onEventNested(\WP_Cloud_Search\Symfony\Component\EventDispatcher\Event $e)
    {
    }
}
