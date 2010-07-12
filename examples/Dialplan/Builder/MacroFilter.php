<?php
/* 
 */

/**
 * This filter only accepts context events and extensions with the "Macro"
 * application and specific macro names.
 *
 * @author gbirke
 */
class Dialplan_Builder_MacroFilter extends Dialplan_Builder_Filter {

  protected $_allowedMacros = array();

  public function __construct($eventDispatcher = null, $allowedMacros = array()) {
    parent::__construct($eventDispatcher);
    $this->_allowedMacros = $allowedMacros;
  }
  
  protected function _filter($emitter, $notification) {
    switch($notification->type) {
      case 'context':
        $this->_state = self::STATE_ACCEPT;
        break;
      case 'extension':
        $this->_state = self::STATE_QUEUE;
        $this->_currentApplication = '';
        break;
      case 'application':
        if($notification->application != 'Macro') {
          $this->_state = self::STATE_DROP;
        }
        break;
      case 'parameter':
        if($notification->position != 1)
          return;
        if(in_array($notification->parameter, $this->_allowedMacros)) {
          $this->_state = self::STATE_ACCEPT;
        }
        else {
          $this->_state = self::STATE_DROP;
        }
        break;
    }
  }

  public function getAllowedMacros() {
    return $this->_allowedMacros;
  }

  public function setAllowedMacros($allowedMacros) {
    $this->_allowedMacros = $allowedMacros;
  }


}
?>
