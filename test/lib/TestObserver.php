<?php
/* 
 */

/**
 * The TestObserver is a dummy class for testing the event dispatcher and filter
 * classes.
 *
 * @author Gabriel Birke
 */
class TestObserver implements IExtensionObserver {

  protected $_notificationTypes = array();

  public function update($emitter, $notification) {
    // do nothing
  }
  
  public function getNotificationTypes() {
    return $this->_notificationTypes;
  }

  public function setNotificationTypes($notificationTypes) {
    $this->_notificationTypes = $notificationTypes;
    return $this;
  }



}
?>
