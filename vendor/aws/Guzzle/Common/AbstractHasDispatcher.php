<?php

namespace WP_Cloud_Search\Guzzle\Common;

use WP_Cloud_Search\Symfony\Component\EventDispatcher\EventDispatcher;
use WP_Cloud_Search\Symfony\Component\EventDispatcher\EventDispatcherInterface;
use WP_Cloud_Search\Symfony\Component\EventDispatcher\EventSubscriberInterface;
/**
 * Class that holds an event dispatcher
 */
class AbstractHasDispatcher implements \WP_Cloud_Search\Guzzle\Common\HasDispatcherInterface
{
    /** @var EventDispatcherInterface */
    protected $eventDispatcher;
    public static function getAllEvents()
    {
        return array();
    }
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
    public function addSubscriber(\WP_Cloud_Search\Symfony\Component\EventDispatcher\EventSubscriberInterface $subscriber)
    {
        $this->getEventDispatcher()->addSubscriber($subscriber);
        return $this;
    }
}
