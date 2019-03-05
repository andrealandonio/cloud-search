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

use WP_Cloud_Search\Symfony\Component\EventDispatcher\GenericEvent;
/**
 * Test class for Event.
 */
class GenericEventTest extends \WP_Cloud_Search\PHPUnit_Framework_TestCase
{
    /**
     * @var GenericEvent
     */
    private $event;
    private $subject;
    /**
     * Prepares the environment before running a test.
     */
    protected function setUp()
    {
        parent::setUp();
        $this->subject = new \stdClass();
        $this->event = new \WP_Cloud_Search\Symfony\Component\EventDispatcher\GenericEvent($this->subject, array('name' => 'Event'));
    }
    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown()
    {
        $this->subject = null;
        $this->event = null;
        parent::tearDown();
    }
    public function testConstruct()
    {
        $this->assertEquals($this->event, new \WP_Cloud_Search\Symfony\Component\EventDispatcher\GenericEvent($this->subject, array('name' => 'Event')));
    }
    /**
     * Tests Event->getArgs().
     */
    public function testGetArguments()
    {
        // test getting all
        $this->assertSame(array('name' => 'Event'), $this->event->getArguments());
    }
    public function testSetArguments()
    {
        $result = $this->event->setArguments(array('foo' => 'bar'));
        $this->assertAttributeSame(array('foo' => 'bar'), 'arguments', $this->event);
        $this->assertSame($this->event, $result);
    }
    public function testSetArgument()
    {
        $result = $this->event->setArgument('foo2', 'bar2');
        $this->assertAttributeSame(array('name' => 'Event', 'foo2' => 'bar2'), 'arguments', $this->event);
        $this->assertEquals($this->event, $result);
    }
    public function testGetArgument()
    {
        // test getting key
        $this->assertEquals('Event', $this->event->getArgument('name'));
    }
    /**
     * @expectedException \InvalidArgumentException
     */
    public function testGetArgException()
    {
        $this->event->getArgument('nameNotExist');
    }
    public function testOffsetGet()
    {
        // test getting key
        $this->assertEquals('Event', $this->event['name']);
        // test getting invalid arg
        $this->setExpectedException('InvalidArgumentException');
        $this->assertFalse($this->event['nameNotExist']);
    }
    public function testOffsetSet()
    {
        $this->event['foo2'] = 'bar2';
        $this->assertAttributeSame(array('name' => 'Event', 'foo2' => 'bar2'), 'arguments', $this->event);
    }
    public function testOffsetUnset()
    {
        unset($this->event['name']);
        $this->assertAttributeSame(array(), 'arguments', $this->event);
    }
    public function testOffsetIsset()
    {
        $this->assertTrue(isset($this->event['name']));
        $this->assertFalse(isset($this->event['nameNotExist']));
    }
    public function testHasArgument()
    {
        $this->assertTrue($this->event->hasArgument('name'));
        $this->assertFalse($this->event->hasArgument('nameNotExist'));
    }
    public function testGetSubject()
    {
        $this->assertSame($this->subject, $this->event->getSubject());
    }
    public function testHasIterator()
    {
        $data = array();
        foreach ($this->event as $key => $value) {
            $data[$key] = $value;
        }
        $this->assertEquals(array('name' => 'Event'), $data);
    }
}
