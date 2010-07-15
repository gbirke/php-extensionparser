<?php

if(!defined("TEST_DIR"))
	DEFINE("TEST_DIR", dirname(__FILE__));
require_once TEST_DIR.'/autoload.php';

class EventDispatcherTest extends PHPUnit_Framework_TestCase
{

  /**
   * @var EventDispatcher
   */
  protected $_dispatcher;

  public function setUp() {
    $this->_dispatcher = new EventDispatcher();
  }

  public function testGetObserversReturnsAllObservers() {
    $observer1 = new TestObserver();
    $observer2 = new TestObserver();
    $this->_dispatcher->addObserver($observer1);
    $this->_dispatcher->addObserver($observer2);
    $this->assertEquals(array($observer1, $observer2), $this->_dispatcher->getObservers());
  }

  public function testGetObserversReturnsAllObserversFromDifferentEvents() {
    $observer1 = new TestObserver();
    $observer2 = new TestObserver();
    $this->_dispatcher->addObserver($observer1, array('EVT1'));
    $this->_dispatcher->addObserver($observer2, array('EVT2'));
    $this->assertEquals(array($observer1), $this->_dispatcher->getObservers('EVT1'));
    $this->assertEquals(array($observer2), $this->_dispatcher->getObservers('EVT2'));
    $this->assertEquals(array($observer1, $observer2), $this->_dispatcher->getObservers('ALL'));
  }

  public function testGetObserversReturnsOnlyOneInstanceOfEachObserver() {
    $observer1 = new TestObserver();
    $observer2 = new TestObserver();
    $this->_dispatcher->addObserver($observer1, array('EVT1'));
    $this->_dispatcher->addObserver($observer2, array('EVT1','EVT2'));
    $this->assertEquals(array($observer1, $observer2), $this->_dispatcher->getObservers('ALL'));
  }

  public function testNotifyAllOnlyNotfiesOncePerObserver() {
    $observer = new TestObserver();
    $this->_dispatcher->addObserver($observer, array('ALL', 'EVT1'));
    $this->_dispatcher->notify($this, new ParserEvent('EVT1'));
    $this->assertEquals(1, count($observer->getNotifications()));
  }
   
}
?>
