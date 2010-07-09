<?php
/* 
 */

/**
 * A simple event Event Dispatcher for Extension Parser events
 *
 * @author gbirke
 */
class EventDispatcher {
  /**
   *
   * @param IExtensionObserver $observer
   * @param mixed $eventname A string of array of event names.
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

  public function notify(Parserevent $notification) {
    $eventnames = array('ALL', $notification->type);
    foreach($eventnames as $evt) {
      if(!empty($this->_observers[$evt])) {
        foreach($this->_observers[$evt] as $observer) {
          $observer->update($this, $notification);
          if($notification->notificationIsCanceled())
                  break 2;
        }
      }
    }
  }
}
?>
