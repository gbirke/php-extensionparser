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

  /**
   * Dispatch the netofication to a builder action.
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

}
?>
