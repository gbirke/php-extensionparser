<?php
/* 
 */

/**
 * A simple event Event Dispatcher for Extension Parser events
 *
 * You can diecide which of the attached event handlers receive which event type.
 *
 * @author gbirke
 */
class EventDispatcher implements IEventDispatcher {

  /**
   * A list of observers, sorted by event name
   *
   * @var array
   */
  protected $_observers = array();

  /**
   * Add an observer to the list of observers.
   *
   * The special event name 'ALL' means that the attached observer will receive
   * all events. In case of 'ALL', the method does not check if the observer was
   * already registered for other events it may happen that you receive the same
   * event multiple times if you register for 'ALL' and for other events.
   *
   * @param IExtensionObserver $observer
   * @param mixed $eventname A string with a single event name or an array of event names.
   * @return Extensionparser
   */
  public function addObserver(IExtensionObserver $observer, $eventname = 'ALL') {
    if(is_string($eventname))
      $eventname = array($eventname);
    foreach($eventname as $evt) {
      if(!isset($this->_observers[$evt])) {
        $this->_observers[$evt] = new SplObjectStorage();
      }
      $this->_observers[$evt]->attach($observer);
    }
    return $this;
  }

  /**
   * Remove an observer from the list of observers.
   *
   * The special event name 'ALL' means that the attached observer will be
   * removed for all possible events.
   *
   * @param IExtensionObserver $observer
   * @param mixed $eventname A string with a single event name or an array of event names.
   * @return Extensionparser
   */
  public function removeObserver(IExtensionObserver $observer, $eventname = 'ALL') {
    if(is_string($eventname)) {
      if($eventname == 'ALL') {
        $eventname = array_keys($this->_observers);
      }
      else {
        $eventname = array($eventname);
      }
    }
    foreach($eventname as $evt) {
      if(!empty($this->_observers[$evt])) {
        $this->_observers[$evt]->detach($observer);
      }
    }
    return $this;
  }

  /**
   * Notify all observers of the event, depending on $notification->type.
   *
   * Notification happens in order of observer registration.
   * 
   * The notification is sent to all observers unless an observer calls
   * $notification->cancelNotification()
   *
   * @param object
   * @param Parserevent $notification
   */
  public function notify($emitter, Parserevent $notification) {
    $eventnames = array('ALL', $notification->type);
    $notified = array();
    foreach($eventnames as $evt) {
      if(!empty($this->_observers[$evt])) {
        foreach($this->_observers[$evt] as $observer) {
          if(!in_array($observer, $notified)) {
            $observer->update($emitter, $notification);
            $notified[] = $observer;
            if($notification->notificationIsCanceled())
                    break 2;
          }
        }
      }
    }
  }

  /**
   * Get all attached observers for an event.
   *
   * If $eventname is "ALL", all observers for all events will be returned, one
   * instance of each observer.
   *
   * @param string $eventname
   * @return array
   */
  public function getObservers($eventname = 'ALL') {
    $return = array();
    if($eventname == 'ALL') {
      $keys = array_keys($this->_observers);
    }
    else {
      $keys = array($eventname);
    }
    foreach($keys as $event) {
      if(!empty($this->_observers[$event])) {
        foreach($this->_observers[$event] as $observer) {
          if(!in_array($observer, $return, true))
            $return[] = $observer;
        }
      }
    }
    return $return;
  }
}
?>
