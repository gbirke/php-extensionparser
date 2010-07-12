<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * This is the base class for a stateful filter that collects events and either
 * drops them or passes them on the the registered observers.
 *
 * @author gbirke
 */
abstract class Dialplan_Builder_Filter implements IEventDispatcher, IExtensionObserver {

  /**
   *
   * @var EventDispatcher
   */
  protected $_eventDispatcher;

  protected $_state = self::STATE_ACCEPT;

  /**
   * Event storage when state is STATE_QUEUE
   * @var array
   */
  protected $_eventQueue = array();

  const STATE_ACCEPT = 1;
  const STATE_DROP   = 2;
  const STATE_QUEUE  = 3;

  /**
   * Constructs the Filter object.
   *
   * If $eventDispatcher is null, an instance of EventDispatcher will be used.
   *
   * @param EventDispatcher $eventDispatcher
   */
  public function __construct($eventDispatcher = null) {
    if($eventDispatcher)
      $this->_eventDispatcher = $eventDispatcher;
    else
      $this->_eventDispatcher = new EventDispatcher();
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
    $this->_filter($emitter, $notification);

    if($this->_state == self::STATE_ACCEPT) {
      // If state changes to "accept", notify the observers of all previous events
      if($oldstate == self::STATE_QUEUE) {
        while($event = array_shift($this->_eventQueue)) {
          $this->notify($this, $event);
        }
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
  }


  public function addObserver(IExtensionObserver $observer, $eventname = 'ALL') {
    $this->_eventDispatcher->addObserver($observer, $eventname);
    return $this;
  }

  public function removeObserver(IExtensionObserver $observer, $eventname = 'ALL') {
    $this->_eventDispatcher->removeObserver($observer, $eventname);
    return $this;
  }

  public function notify($emitter, Parserevent $notification) {
    $this->_eventDispatcher->notify($emitter, $notification);
    return $this;
  }

}
?>
