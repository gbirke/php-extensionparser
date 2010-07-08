<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Abstract
 *
 * @author gbirke
 */
abstract class Dialplan_Builder_Abstract implements IExtensionObserver{

  abstract public function getNotificationTypes();

  protected $_objectStack = array();

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
    $this->_objectStack[] = $object;
  }

  public function getObject() {
    return array_shift($this->_objectStack);
    ;
  }

}
?>
