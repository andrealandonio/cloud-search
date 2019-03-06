<?php

namespace WP_Cloud_Search\Psr\Log\Test;

use WP_Cloud_Search\Psr\Log\LogLevel;
/**
 * Provides a base test class for ensuring compliance with the LoggerInterface
 *
 * Implementors can extend the class and implement abstract methods to run this as part of their test suite
 */
abstract class LoggerInterfaceTest extends \WP_Cloud_Search\PHPUnit_Framework_TestCase
{
    /**
     * @return LoggerInterface
     */
    abstract function getLogger();
    /**
     * This must return the log messages in order with a simple formatting: "<LOG LEVEL> <MESSAGE>"
     *
     * Example ->error('Foo') would yield "error Foo"
     *
     * @return string[]
     */
    abstract function getLogs();
    public function testImplements()
    {
        $this->assertInstanceOf('WP_Cloud_Search\\Psr\\Log\\LoggerInterface', $this->getLogger());
    }
    /**
     * @dataProvider provideLevelsAndMessages
     */
    public function testLogsAtAllLevels($level, $message)
    {
        $logger = $this->getLogger();
        $logger->{$level}($message, array('user' => 'Bob'));
        $logger->log($level, $message, array('user' => 'Bob'));
        $expected = array($level . ' message of level ' . $level . ' with context: Bob', $level . ' message of level ' . $level . ' with context: Bob');
        $this->assertEquals($expected, $this->getLogs());
    }
    public function provideLevelsAndMessages()
    {
        return array(\WP_Cloud_Search\Psr\Log\LogLevel::EMERGENCY => array(\WP_Cloud_Search\Psr\Log\LogLevel::EMERGENCY, 'message of level emergency with context: {user}'), \WP_Cloud_Search\Psr\Log\LogLevel::ALERT => array(\WP_Cloud_Search\Psr\Log\LogLevel::ALERT, 'message of level alert with context: {user}'), \WP_Cloud_Search\Psr\Log\LogLevel::CRITICAL => array(\WP_Cloud_Search\Psr\Log\LogLevel::CRITICAL, 'message of level critical with context: {user}'), \WP_Cloud_Search\Psr\Log\LogLevel::ERROR => array(\WP_Cloud_Search\Psr\Log\LogLevel::ERROR, 'message of level error with context: {user}'), \WP_Cloud_Search\Psr\Log\LogLevel::WARNING => array(\WP_Cloud_Search\Psr\Log\LogLevel::WARNING, 'message of level warning with context: {user}'), \WP_Cloud_Search\Psr\Log\LogLevel::NOTICE => array(\WP_Cloud_Search\Psr\Log\LogLevel::NOTICE, 'message of level notice with context: {user}'), \WP_Cloud_Search\Psr\Log\LogLevel::INFO => array(\WP_Cloud_Search\Psr\Log\LogLevel::INFO, 'message of level info with context: {user}'), \WP_Cloud_Search\Psr\Log\LogLevel::DEBUG => array(\WP_Cloud_Search\Psr\Log\LogLevel::DEBUG, 'message of level debug with context: {user}'));
    }
    /**
     * @expectedException Psr\Log\InvalidArgumentException
     */
    public function testThrowsOnInvalidLevel()
    {
        $logger = $this->getLogger();
        $logger->log('invalid level', 'Foo');
    }
    public function testContextReplacement()
    {
        $logger = $this->getLogger();
        $logger->info('{Message {nothing} {user} {foo.bar} a}', array('user' => 'Bob', 'foo.bar' => 'Bar'));
        $expected = array('info {Message {nothing} Bob Bar a}');
        $this->assertEquals($expected, $this->getLogs());
    }
    public function testObjectCastToString()
    {
        $dummy = $this->getMock('WP_Cloud_Search\\Psr\\Log\\Test\\DummyTest', array('__toString'));
        $dummy->expects($this->once())->method('__toString')->will($this->returnValue('DUMMY'));
        $this->getLogger()->warning($dummy);
    }
    public function testContextCanContainAnything()
    {
        $context = array('bool' => \true, 'null' => null, 'string' => 'Foo', 'int' => 0, 'float' => 0.5, 'nested' => array('with object' => new \WP_Cloud_Search\Psr\Log\Test\DummyTest()), 'object' => new \DateTime(), 'resource' => \fopen('php://memory', 'r'));
        $this->getLogger()->warning('Crazy context data', $context);
    }
    public function testContextExceptionKeyCanBeExceptionOrOtherValues()
    {
        $this->getLogger()->warning('Random message', array('exception' => 'oops'));
        $this->getLogger()->critical('Uncaught Exception!', array('exception' => new \LogicException('Fail')));
    }
}
class DummyTest
{
}
