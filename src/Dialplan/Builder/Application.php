<?php
/* 
 */

/**
 * The application builder consumes application and parameter events to create
 * Dialplan_Application objects
 *
 * @author gbirke
 */
class Dialplan_Builder_Application extends Dialplan_Builder_Abstract {
    
  /**
   *
   * This builder creates a new application object whenever it encounters an
   * "application" event.
   *
   * @var Dialplan_Application
   */
  protected $_currentApplication;

  public function getNotificationTypes() {
    return array('comment', 'application', 'parameter');
  }

  public function applicationAction(Dialplan_Parser_Event $notification) {
    $this->_currentApplication = new Dialplan_Application();
    $this->_currentApplication->setName($notification->application);
  }

  public function parameterAction(Dialplan_Parser_Event $notification) {
    $this->_currentApplication->addParam($notification->parameter);
  }

  public function commentAction(Dialplan_Parser_Event $notification) {
    if($notification->context == 'extension')
      $this->_currentApplication->setComment($notification->comment);
  }

  /**
   * Put current application object in object queue.
   */
  public function flush() {
    $this->_addObject($this->_currentApplication);
    ;
  }
  

}
?>
