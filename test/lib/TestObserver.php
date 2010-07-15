<?php
/* 
 */

/**
 * The TestObserver is a dummy class for testing the event dispatcher and filter
 * classes.
 *
 * @author Gabriel Birke
 */
class TestObserver implements Dialplan_Parser_IExtensionObserver {

  protected $_notificationTypes = array();

  protected $_notifications = array();

  public function update($emitter, $notification) {
    $this->_notifications[] = $notification;
  }
  
  public function getNotificationTypes() {
    return $this->_notificationTypes;
  }

  public function setNotificationTypes($notificationTypes) {
    $this->_notificationTypes = $notificationTypes;
    return $this;
  }

  public function getNotifications() {
    return $this->_notifications;
  }



}
?>
