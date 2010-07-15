<?php
if(!defined("TEST_DIR"))
	DEFINE("TEST_DIR", realpath(dirname(__FILE__).'/../..'));
require_once TEST_DIR.'/autoload.php';

class Dialplan_Builder_FilterTest
  extends PHPUnit_Framework_TestCase
  implements Dialplan_Parser_IExtensionObserver
{

  protected $_notifications;

  /**
   *
   * @var Dialplan_Builder_Filter
   */
  protected $_filter;


  public function  setUp(){
    $this->_filter = new TestFilter();
    $this->_filter->addObserver($this);
    $this->_notifications = array();
  }

  public function testFilterAcceptsAllByDefault()
  {
    $events = $this->_getFixtureEvents(array('test', 'foo', 'bar'));
    $this->_sendEvents($events);
    $this->assertEquals($events, $this->_notifications);
  }

  public function testFilterDropsAllOnDropStatechange()
  {
    $events = $this->_getFixtureEvents(array('state_drop', 'test', 'foo', 'bar'));
    $this->_sendEvents($events);
    $this->assertEquals(array(), $this->_notifications);
  }

  public function testFilterDropsQueuedEventsOnDropStatechange()
  {
    $events = $this->_getFixtureEvents(array('state_queue', 'test', 'foo', 'state_drop', 'bar'));
    $this->_sendEvents($events);
    $this->assertEquals(array(), $this->_notifications);
  }

  public function testFilterAcceptsAllOnAcceptStatechange()
  {
    $events = $this->_getFixtureEvents(array('state_drop', 'test', 'foo', 'state_accept', 'bar'));
    $this->_sendEvents($events);
    $expected = $events = $this->_getFixtureEvents(array('state_accept', 'bar'));
    $this->assertEquals($expected, $this->_notifications);
  }

  public function testFilterSendsQueuedEventsOnAcceptStatechange()
  {
    $events = $this->_getFixtureEvents(array('state_queue', 'test', 'foo', 'state_accept', 'bar'));
    $this->_sendEvents($events);
    $this->assertEquals($events, $this->_notifications);
  }

  public function testGetNotoficationTypesFromObservers() {
    $observer1 = new TestObserver();
    $observer1->setNotificationTypes(array('foo', 'bar'));
    $observer2 = new TestObserver();
    $observer2->setNotificationTypes(array('bar', 'baz'));
    $this->_filter->addObserver($observer1)
            ->addObserver($observer2);
    $this->assertEquals(array('foo', 'bar', 'baz'), $this->_filter->getNotificationTypesFromObservers());
  }

  protected function _sendEvents($events) {
    foreach ($events as $evt) {
      $this->_filter->update($this, $evt);
    }
  }

  protected function _getFixtureEvents($eventNames) {
    $fixture = array();
    foreach($eventNames as $evt)
      $fixture[] = new Dialplan_Parser_Event($evt);
    return $fixture;
  }

  public function update($subject, $notification) {
     $this->_notifications[] = $notification;
  }
 
}
?>
