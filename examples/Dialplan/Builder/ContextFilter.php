<?php
/* 
 */

/**
 * This filter only accepts events after the allowed contexts
 *
 * @author gbirke
 */
class Dialplan_Builder_ContextFilter extends Dialplan_Builder_Filter {

  protected $_allowedContexts = array();

  public function __construct($eventDispatcher = null, $allowedContexts = array()) {
    parent::__construct($eventDispatcher);
    $this->_allowedContexts = $allowedContexts;
    $this->_state = self::STATE_DROP;
  }

  protected function _filter($emitter, $notification) {
    if($notification->type == 'context') {
      if(in_array($notification->context, $this->_allowedContexts)) {
        $this->_state = self::STATE_ACCEPT;
      }
      else {
        $this->_state = self::STATE_DROP;
      }
    }
  }

  public function getAllowedContexts() {
    return $this->_allowedContexts;
  }

  public function setAllowedContexts($allowedContexts) {
    $this->_allowedContexts = $allowedContexts;
    return $this;
  }



}
?>
