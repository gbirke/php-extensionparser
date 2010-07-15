<?php
/* 
 */

/**
 * This is the base class for a stateful filter that collects events and either
 * drops them, passes them on the the registered observers or stores them in a
 * queue that is dropped or flushed.
 *
 * @author gbirke
 */
abstract class Dialplan_Builder_Filter 
  implements Dialplan_Parser_IEventDispatcher, Dialplan_Parser_IExtensionObserver
{

  /**
   *
   * @var Dialplan_Parser_EventDispatcher
   */
  protected $_eventDispatcher;

  protected $_state = self::STATE_ACCEPT;

  /**
   * Event storage when state is STATE_QUEUE
   * @var array
   */
  protected $_eventQueue = array();

  protected $_debugOutput = false;

  protected $_stateNames = array(1 => 'ACCEPT', 2=> 'DROP', 3 => 'QUEUE');

  const STATE_ACCEPT = 1;
  const STATE_DROP   = 2;
  const STATE_QUEUE  = 3;

  /**
   * Constructs the Filter object.
   *
   * If $eventDispatcher is null, an instance of EventDispatcher will be created.
   *
   * @param Dialplan_Parser_EventDispatcher $eventDispatcher
   */
  public function __construct($eventDispatcher = null) {
    if($eventDispatcher)
      $this->_eventDispatcher = $eventDispatcher;
    else
      $this->_eventDispatcher = new Dialplan_Parser_EventDispatcher();
  }

  /**
   * This function will change or keep the filter state depending on the provided data.
   * It can also change the values of the notification.
   */
  abstract protected function _filter($emitter, $notification);

  /**
   * First call the _filter function. Then, depending on the state, pass the
   * notification to the observers, drop it or queue it.
   * 
   * @param <type> $emitter
   * @param <type> $notification
   */
  public function update($emitter, $notification) {
    $oldstate = $this->_state;
    $debugOutput =  "{$notification->type} ".$this->_stateNames[$this->_state].' -> ';
    $this->_filter($emitter, $notification);
    
    if($this->_state == self::STATE_ACCEPT) {
      // If state changes to "accept", notify the observers of all previous events
      if($oldstate == self::STATE_QUEUE) {
        $this->flush();
      }
      $this->notify($this, $notification);
    }
    elseif ($this->_state == self::STATE_QUEUE) {
      $this->_eventQueue[] = $notification;
    }
    // If state changes to "drop", drop all previous events
    elseif($this->_state == self::STATE_DROP && $oldstate == self::STATE_QUEUE) {
      $this->_eventQueue = array();
    }
    if($this->_debugOutput) {
      echo $debugOutput . $this->_stateNames[$this->_state]."\n";
    }
  }

  /**
   * Return the notification types from all observers that have the method
   * 'getNotificationTypes'.
   *
   * @return array
   */
  public function getNotificationTypesFromObservers() {
    $types = array();
    foreach($this->_eventDispatcher->getObservers() as $observer) {
      if(method_exists($observer, 'getNotificationTypes')) {
        $types = array_merge($types, $observer->getNotificationTypes());
      }
    }
    return array_keys(array_flip($types));
  }

  /**
   * Send content of notification queue to all observers
   */
  public function flush() {
    while($event = array_shift($this->_eventQueue)) {
      $this->notify($this, $event);
    }
  }

  public function getNotificationTypes() {
    return $this->getNotificationTypesFromObservers();
  }

  public function addObserver(Dialplan_Parser_IExtensionObserver $observer, $eventname = 'ALL') {
    $this->_eventDispatcher->addObserver($observer, $eventname);
    return $this;
  }

  public function removeObserver(Dialplan_Parser_IExtensionObserver $observer, $eventname = 'ALL') {
    $this->_eventDispatcher->removeObserver($observer, $eventname);
    return $this;
  }

  public function notify($emitter, Dialplan_Parser_Event $notification) {
    $this->_eventDispatcher->notify($emitter, $notification);
    return $this;
  }

  public function debugOutputOn() {
    $this->_debugOutput = true;
    return $this;
  }

  public function debugOutputOff() {
    $this->_debugOutput = false;
    return $this;
  }
}
?>
