<?php
/* .
 */

/**
 * Eventrecorder stores events as array
 *
 * @author birke
 */
class Eventrecorder implements IExtensionObserver {

  protected $_notifications = array();

  public function update($emitter, $notification) {
    $this->_notifications[] = $notification;
  }

  public function getNotifications() {
    return $this->_notifications;
  }
}
?>
