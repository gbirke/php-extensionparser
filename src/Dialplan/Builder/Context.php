<?php
/* 
 */

/**
 * The context builder consumes context events to create
 * Dialplan_Context objects
 *
 * @author gbirke
 */
class Dialplan_Builder_Context extends Dialplan_Builder_Abstract {
    
  /**
   * @var Dialplan_Context
   */
  protected $_currentContext;

  /**
   *
   * @var Dialplan_Builder_Extension
   */
  protected $_extensionBuilder;

  public function getNotificationTypes() {
    return array('comment', 'context', 'endfile');
  }

  public function contextAction(Dialplan_Parser_Event $notification) {
    if($this->_currentContext) {
      $this->_currentContext->setExtensions($this->_extensionBuilder->getObjectQueue());
      $this->_addObject($this->_currentContext);
    }
    $this->_currentContext = new Dialplan_Context();
    $this->_currentContext->setName($notification->context);
  }

  public function commentAction(Dialplan_Parser_Event $notification) {
    if($notification->context == 'context')
      $this->_currentContext->setComment($notification->comment);
  }

  public function endfileAction(Dialplan_Parser_Event $notification) {
    if($this->_currentContext) {
      $this->_currentContext->setExtensions($this->_extensionBuilder->getObjectQueue());
      $this->_addObject($this->_currentContext);
    }
  }

  public function getExtensionBuilder() {
    return $this->_extensionBuilder;
  }

  public function setExtensionBuilder($extensionBuilder) {
    $this->_extensionBuilder = $extensionBuilder;
    return $this;
  }


}
?>
