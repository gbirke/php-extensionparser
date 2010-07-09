<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Application
 *
 * @author gbirke
 */
class Dialplan_Builder_Application extends Dialplan_Builder_Abstract {
    
  /**
   *
   * @var Dialplan_Application
   */
  protected $_currentApplication;

  public function getNotificationTypes() {
    return array('comment', 'application', 'parameter');
  }

  public function applicationAction(Parserevent $notification) {
    $this->_currentApplication = new Dialplan_Application();
    $this->_currentApplication->setName($notification->name);
  }

  public function parameterAction(Parserevent $notification) {
    $this->_currentApplication->addParam($notification->value);
  }

  public function commentAction(Parserevent $notification) {
    if($notification->context == 'extension')
      $this->_currentApplication->setComment($notification->text);
  }


  public function getObject() {
    $this->_addObject($this->_currentApplication);
    return parent::getObject();
  }

}
?>
