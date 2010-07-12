<?php
/* 
 */

/**
 * Class for the Dialplan_Bilader_Filter test.
 *
 * Changes state on 'state_accept', 'state_drop', 'state_queue' events
 *
 * @author gbirke
 */
class TestFilter extends Dialplan_Builder_Filter {

  protected $_filterAssociations = array(
      'state_accept' => self::STATE_ACCEPT,
      'state_drop'   => self::STATE_DROP,
      'state_queue'  => self::STATE_QUEUE
  );

  protected function _filter($emitter, $notification) {
    $type = $notification->type;
    if(!empty($this->_filterAssociations[$type])) {
      $this->_state = $this->_filterAssociations[$type];
    }
  }

}
?>
