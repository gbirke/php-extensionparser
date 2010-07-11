<?php
/* 
 */

/**
 * The Abstract builder is the base class for builders that receive and process
 * Parservenet objects from the Extensionparser  to custruct data structures
 * from the event data.
 *
 *
 *
 * @author gbirke
 */
abstract class Dialplan_Builder_Abstract implements IExtensionObserver, Iterator {

  /**
   * Return the names of all events the subclass reacts to.
   *
   * Note, that for each event you return here, you MUST implement a method
   * "[eventname]Action".
   *
   * @return array
   */
  abstract public function getNotificationTypes();

  /**
   * For storing resulting data structures.
   * @var array
   */
  protected $_objectQueue = array();

  /**
   * Dispatch the notification to a builder action.
   *
   * No error checking is done at the moment.
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
