<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
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
   * @var Dialplan_Extension
   */
  protected $_prevExtension;

  protected $_applicationBuilder;

  public function getNotificationTypes() {
    return array('extension', 'priority', 'label');
  }

  public function commentAction(Parserevent $notification) {

  }

  public function extensionAction(Parserevent $notification) {
    if($notification->value != $this->_currentExtension) {
      $this->_prevExtension = $this->_currentExtensionObj;
      $this->_prevExtension->addApplication($this->_applicationBuilder->getLastApplication(),
              $this->_currentPriority, $this->_currentLabel);
      $this->_currentExtensionObj = new Dialplan_Extension();
      $this->_currentExtensionObj->setExten($notification->value);
    }
    $this->_currentLabel = null;
    $this->_currentPriority = '';
  }

  public function priorityAction(Parserevent $notification) {
    $this->_currentPriority = $notification->value;
  }

  public function labelAction(Parserevent $notification) {
    $this->_currentLabel = $notification->value;
  }

}
?>
