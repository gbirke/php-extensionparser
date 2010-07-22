<?php
/* 
 */

/**
 * This builder builds a series of Dialplan_Menu objects.
 *
 * Most Dial plan actions are ignored, only Goto statements are allowed for each
 * context extension.
 *
 * @author birke
 */
class Dialplan_Builder_Menu extends Dialplan_Builder_Abstract {
  
  /**
   *
   * @var Dialplan_Menu
   */
  protected $_currentMenu;
  
  /**
   *
   * @var string
   */
  protected $_currentExten;
  
  /**
   *
   * @var array
   */
  protected $_currentOption;
  
  /**
   *
   * @var string
   */
  protected $_currentApplication;

  public function getNotificationTypes() {
    return array('context', 'extension', 'application', 'parameter', 'endfile');
  }

  public function contextAction(Dialplan_Parser_Event $notification) {
    if($this->_currentMenu) {
      $this->_addObject($this->_currentMenu);
    }
    $this->_currentMenu = new Dialplan_Menu();
    $this->_currentMenu->setName($notification->context);
  }

  public function endfileAction(Dialplan_Parser_Event $notification) {
    if($this->_currentMenu) {
      $this->_addObject($this->_currentMenu);
    }
  }

  public function extensionAction(Dialplan_Parser_Event $notification) {
    $this->_currentExten = $notification->extension;
  }

  public function applicationAction(Dialplan_Parser_Event $notification) {
    $this->_currentApplication = $notification->application;
    if($notification->application == 'Goto') {
      $this->_currentOption = array();
    }
  }

  public function parameterAction(Dialplan_Parser_Event $notification) {
    if($this->_currentExten == 's') {
      if($this->_currentApplication == 'Set'
              && preg_match('/TIMEOUT\((digit|response)\)=(\d+)/', $notification->parameter, $matches)) {
        $method = "set".ucfirst($matches[1])."Timeout";
        $this->_currentMenu->$method($matches[2]);
      }
      elseif ($this->_currentApplication == 'Background') {
        $this->_currentMenu->setAudiofile($notification->parameter);
      }
    }
    elseif ($this->_currentApplication == 'Goto') {
      $this->_currentOption[] = $notification->parameter;
      if($notification->position == 3) {
        $this->_currentMenu->setOption($this->_currentExten, $this->_currentOption);
      }
    }
  }
}
?>
