<?php

namespace WP_Cloud_Search\Guzzle\Http;

use WP_Cloud_Search\Guzzle\Common\Event;
use WP_Cloud_Search\Guzzle\Common\HasDispatcherInterface;
use WP_Cloud_Search\Symfony\Component\EventDispatcher\EventDispatcher;
use WP_Cloud_Search\Symfony\Component\EventDispatcher\EventSubscriberInterface;
use WP_Cloud_Search\Symfony\Component\EventDispatcher\EventDispatcherInterface;
/**
 * EntityBody decorator that emits events for read and write methods
 */
class IoEmittingEntityBody extends \WP_Cloud_Search\Guzzle\Http\AbstractEntityBodyDecorator implements \WP_Cloud_Search\Guzzle\Common\HasDispatcherInterface
{
    /** @var EventDispatcherInterface */
    protected $eventDispatcher;
    public static function getAllEvents()
    {
        return array('body.read', 'body.write');
    }
    /**
     * {@inheritdoc}
     * @codeCoverageIgnore
     */
    public function setEventDispatcher(\WP_Cloud_Search\Symfony\Component\EventDispatcher\EventDispatcherInterface $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
        return $this;
    }
    public function getEventDispatcher()
    {
        if (!$this->eventDispatcher) {
            $this->eventDispatcher = new \WP_Cloud_Search\Symfony\Component\EventDispatcher\EventDispatcher();
        }
        return $this->eventDispatcher;
    }
    public function dispatch($eventName, array $context = array())
    {
        return $this->getEventDispatcher()->dispatch($eventName, new \WP_Cloud_Search\Guzzle\Common\Event($context));
    }
    /**
     * {@inheritdoc}
     * @codeCoverageIgnore
     */
    public function addSubscriber(\WP_Cloud_Search\Symfony\Component\EventDispatcher\EventSubscriberInterface $subscriber)
    {
        $this->getEventDispatcher()->addSubscriber($subscriber);
        return $this;
    }
    public function read($length)
    {
        $event = array('body' => $this, 'length' => $length, 'read' => $this->body->read($length));
        $this->dispatch('body.read', $event);
        return $event['read'];
    }
    public function write($string)
    {
        $event = array('body' => $this, 'write' => $string, 'result' => $this->body->write($string));
        $this->dispatch('body.write', $event);
        return $event['result'];
    }
}
