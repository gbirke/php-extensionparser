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
    return array('extension', 'priority', 'label', 'endfile');
  }

  public function commentAction(Parserevent $notification) {
    // Handle comment lines in front of extensions
  }

  public function extensionAction(Parserevent $notification) {
    // if extension is the same as previous, add application
    // else push current extension on stack and create new extension object
    if($notification->value == $this->_currentExtension) {
      $this->_addApplication();
    }
    else {
      $this->_addExtension();
    }
    // always reset values for priority and label on new extension
    $this->_currentLabel = null;
    $this->_currentPriority = '';
  }

  public function priorityAction(Parserevent $notification) {
    if($notification->value == 'hint') {
      $this->_addExtension();
    }
    $this->_currentPriority = $notification->value;
  }

  public function labelAction(Parserevent $notification) {
    $this->_currentLabel = $notification->value;
  }

  public function endfileAction(Parserevent $notification) {
    // Push the last extension on the stack when the file ends
    $this->_addApplication();
    $this->_addObject($this->_currentExtensionObj);
  }

  public function setApplicationBuilder(Dialplan_Builder_Application $builder) {
    $this->_applicationBuilder = $builder;
  }

  protected function _addApplication() {
    $application = $this->_applicationBuilder->getObject();
    if($application) {
        $this->_currentExtensionObj->addApplication($application,
              $this->_currentPriority, $this->_currentLabel);
    }
    else {
      throw new Exception("No new Application found in application builder. ".
              "Maybe you called __addApplication too often?");
    }
  }

  public function _addExtension() {
    if($this->_currentExtensionObj) {
        $this->_addObject($this->_currentExtensionObj);
      }
      $this->_currentExtension = $notification->value;
      $this->_currentExtensionObj = new Dialplan_Extension();
      $this->_currentExtensionObj->setExten($notification->value);
  }

}
?>
