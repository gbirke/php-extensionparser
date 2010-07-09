<?php
/* 
 */

/**
 * Description of Abstract
 *
 * @author gbirke
 */
abstract class Dialplan_Builder_Abstract implements IExtensionObserver, Iterator {

  abstract public function getNotificationTypes();

  protected $_objectQueue = array();

  /**
   * Dispatch the notification to a builder action.
   *
   * No error checking at the moment.
   *
   * @param object $emitter
   * @param Parserevent $notification
   */
  public function update($emitter, $notification) {
    $method = $notification->type.'Action';
    $this->$method($notification);
  }

  protected function _addObject($object) {
    if($object)
      $this->_objectQueue[] = $object;
  }

  public function getObject() {
    return array_shift($this->_objectQueue);
  }

  public function getObjectQueue() {
    return $this->_objectQueue;
  }

  public function current() {
    return current($this->_objectQueue);
  }

  public function key() {
    return key($this->_objectQueue);
  }

  public function next() {
    return next($this->_objectQueue);
  }

  public function rewind() {
    return reset($this->_objectQueue);
  }

  public function valid() {
    return (bool) $this->current();
  }

}
?>
