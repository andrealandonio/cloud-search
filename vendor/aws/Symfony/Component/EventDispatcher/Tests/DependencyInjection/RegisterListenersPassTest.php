<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace WP_Cloud_Search\Symfony\Component\EventDispatcher\Tests\DependencyInjection;

use WP_Cloud_Search\Symfony\Component\DependencyInjection\ContainerBuilder;
use WP_Cloud_Search\Symfony\Component\EventDispatcher\DependencyInjection\RegisterListenersPass;
class RegisterListenersPassTest extends \WP_Cloud_Search\PHPUnit_Framework_TestCase
{
    /**
     * Tests that event subscribers not implementing EventSubscriberInterface
     * trigger an exception.
     *
     * @expectedException \InvalidArgumentException
     */
    public function testEventSubscriberWithoutInterface()
    {
        // one service, not implementing any interface
        $services = array('my_event_subscriber' => array(0 => array()));
        $definition = $this->getMock('WP_Cloud_Search\\Symfony\\Component\\DependencyInjection\\Definition');
        $definition->expects($this->atLeastOnce())->method('isPublic')->will($this->returnValue(\true));
        $definition->expects($this->atLeastOnce())->method('getClass')->will($this->returnValue('stdClass'));
        $builder = $this->getMock('WP_Cloud_Search\\Symfony\\Component\\DependencyInjection\\ContainerBuilder', array('hasDefinition', 'findTaggedServiceIds', 'getDefinition'));
        $builder->expects($this->any())->method('hasDefinition')->will($this->returnValue(\true));
        // We don't test kernel.event_listener here
        $builder->expects($this->atLeastOnce())->method('findTaggedServiceIds')->will($this->onConsecutiveCalls(array(), $services));
        $builder->expects($this->atLeastOnce())->method('getDefinition')->will($this->returnValue($definition));
        $registerListenersPass = new \WP_Cloud_Search\Symfony\Component\EventDispatcher\DependencyInjection\RegisterListenersPass();
        $registerListenersPass->process($builder);
    }
    public function testValidEventSubscriber()
    {
        $services = array('my_event_subscriber' => array(0 => array()));
        $definition = $this->getMock('WP_Cloud_Search\\Symfony\\Component\\DependencyInjection\\Definition');
        $definition->expects($this->atLeastOnce())->method('isPublic')->will($this->returnValue(\true));
        $definition->expects($this->atLeastOnce())->method('getClass')->will($this->returnValue('WP_Cloud_Search\\Symfony\\Component\\EventDispatcher\\Tests\\DependencyInjection\\SubscriberService'));
        $builder = $this->getMock('WP_Cloud_Search\\Symfony\\Component\\DependencyInjection\\ContainerBuilder', array('hasDefinition', 'findTaggedServiceIds', 'getDefinition', 'findDefinition'));
        $builder->expects($this->any())->method('hasDefinition')->will($this->returnValue(\true));
        // We don't test kernel.event_listener here
        $builder->expects($this->atLeastOnce())->method('findTaggedServiceIds')->will($this->onConsecutiveCalls(array(), $services));
        $builder->expects($this->atLeastOnce())->method('getDefinition')->will($this->returnValue($definition));
        $builder->expects($this->atLeastOnce())->method('findDefinition')->will($this->returnValue($definition));
        $registerListenersPass = new \WP_Cloud_Search\Symfony\Component\EventDispatcher\DependencyInjection\RegisterListenersPass();
        $registerListenersPass->process($builder);
    }
    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage The service "foo" must be public as event listeners are lazy-loaded.
     */
    public function testPrivateEventListener()
    {
        $container = new \WP_Cloud_Search\Symfony\Component\DependencyInjection\ContainerBuilder();
        $container->register('foo', 'stdClass')->setPublic(\false)->addTag('kernel.event_listener', array());
        $container->register('event_dispatcher', 'stdClass');
        $registerListenersPass = new \WP_Cloud_Search\Symfony\Component\EventDispatcher\DependencyInjection\RegisterListenersPass();
        $registerListenersPass->process($container);
    }
    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage The service "foo" must be public as event subscribers are lazy-loaded.
     */
    public function testPrivateEventSubscriber()
    {
        $container = new \WP_Cloud_Search\Symfony\Component\DependencyInjection\ContainerBuilder();
        $container->register('foo', 'stdClass')->setPublic(\false)->addTag('kernel.event_subscriber', array());
        $container->register('event_dispatcher', 'stdClass');
        $registerListenersPass = new \WP_Cloud_Search\Symfony\Component\EventDispatcher\DependencyInjection\RegisterListenersPass();
        $registerListenersPass->process($container);
    }
    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage The service "foo" must not be abstract as event listeners are lazy-loaded.
     */
    public function testAbstractEventListener()
    {
        $container = new \WP_Cloud_Search\Symfony\Component\DependencyInjection\ContainerBuilder();
        $container->register('foo', 'stdClass')->setAbstract(\true)->addTag('kernel.event_listener', array());
        $container->register('event_dispatcher', 'stdClass');
        $registerListenersPass = new \WP_Cloud_Search\Symfony\Component\EventDispatcher\DependencyInjection\RegisterListenersPass();
        $registerListenersPass->process($container);
    }
    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage The service "foo" must not be abstract as event subscribers are lazy-loaded.
     */
    public function testAbstractEventSubscriber()
    {
        $container = new \WP_Cloud_Search\Symfony\Component\DependencyInjection\ContainerBuilder();
        $container->register('foo', 'stdClass')->setAbstract(\true)->addTag('kernel.event_subscriber', array());
        $container->register('event_dispatcher', 'stdClass');
        $registerListenersPass = new \WP_Cloud_Search\Symfony\Component\EventDispatcher\DependencyInjection\RegisterListenersPass();
        $registerListenersPass->process($container);
    }
    public function testEventSubscriberResolvableClassName()
    {
        $container = new \WP_Cloud_Search\Symfony\Component\DependencyInjection\ContainerBuilder();
        $container->setParameter('subscriber.class', 'WP_Cloud_Search\\Symfony\\Component\\EventDispatcher\\Tests\\DependencyInjection\\SubscriberService');
        $container->register('foo', '%subscriber.class%')->addTag('kernel.event_subscriber', array());
        $container->register('event_dispatcher', 'stdClass');
        $registerListenersPass = new \WP_Cloud_Search\Symfony\Component\EventDispatcher\DependencyInjection\RegisterListenersPass();
        $registerListenersPass->process($container);
        $definition = $container->getDefinition('event_dispatcher');
        $expected_calls = array(array('addSubscriberService', array('foo', 'WP_Cloud_Search\\Symfony\\Component\\EventDispatcher\\Tests\\DependencyInjection\\SubscriberService')));
        $this->assertSame($expected_calls, $definition->getMethodCalls());
    }
    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage You have requested a non-existent parameter "subscriber.class"
     */
    public function testEventSubscriberUnresolvableClassName()
    {
        $container = new \WP_Cloud_Search\Symfony\Component\DependencyInjection\ContainerBuilder();
        $container->register('foo', '%subscriber.class%')->addTag('kernel.event_subscriber', array());
        $container->register('event_dispatcher', 'stdClass');
        $registerListenersPass = new \WP_Cloud_Search\Symfony\Component\EventDispatcher\DependencyInjection\RegisterListenersPass();
        $registerListenersPass->process($container);
    }
}
class SubscriberService implements \WP_Cloud_Search\Symfony\Component\EventDispatcher\EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
    }
}
