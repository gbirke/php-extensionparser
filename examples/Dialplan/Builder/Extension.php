<?php
/* 
 * 
 */

/**
 * Description of Extension
 *
 * @author gbirke
 */
class Dialplan_Builder_Extension extends Dialplan_Builder_Abstract  {
    
  protected $_currentExtension;
  protected $_currentPriority;
  protected $_currentLabel;
  /**
   *
   * @var Dialplan_Extension
   */
  protected $_currentExtensionObj;

  /**
   *
   * @var Dialplan_Builder_Application
   */
  protected $_applicationBuilder;

  public function getNotificationTypes() {
    return array('extension', 'priority', 'label', 'endfile', 'newline');
  }

  public function commentAction(Parserevent $notification) {

  }

  public function extensionAction(Parserevent $notification) {
    if($notification->value != $this->_currentExtension) {
      $this->_currentExtension = $notification->value;
      $this->_currentExtensionObj = new Dialplan_Extension();
      $this->_currentExtensionObj->setExten($notification->value);
    }
    else {
      $this->_addExtension();
    }
  }

  public function priorityAction(Parserevent $notification) {
    $this->_currentPriority = $notification->value;
  }

  public function labelAction(Parserevent $notification) {
    $this->_currentLabel = $notification->value;
  }

  public function endfileAction(Parserevent $notification) {
    $this->_addExtension();
  }
  public function newlineAction(Parserevent $notification) {
    $this->_addExtension();
  }

  public function setApplicationBuilder(Dialplan_Builder_Application $builder) {
    $this->_applicationBuilder = $builder;
  }

  protected function _addExtension() {
    if($this->_currentExtensionObj) {
      $application = $this->_applicationBuilder->getObject();
      if($application) {
        $this->_currentExtensionObj->addApplication($application,
              $this->_currentPriority, $this->_currentLabel);
      }
      $this->_addObject($this->_currentExtensionObj);
      $this->_currentExtensionObj = null;
      $this->_currentLabel = null;
      $this->_currentPriority = '';
    }
  }

}
?>
