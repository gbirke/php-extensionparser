<?php
/* 
 */

/**
 * This class logs events and prints them.
 *
 * @author birke
 */
class Eventlogger implements Dialplan_Parser_IExtensionObserver {

  protected $_filter;
  
  /**
   *
   * @param array $filter Event types that should be logged (empty array means all events)
   */
  public function __construct($filter=array()) {
    $this->_filter = $filter;
  }

  public function update($emitter, $notification) {
    if(!$this->_filter || in_array($notification->type, $this->_filter)) {
      $class = get_class($emitter);
      echo "[EVENT] {$notification->type} from {$class}\n";
      foreach($notification->getProperties() as $name => $prop) {
        printf("   %-10s: %s\n", $name, $prop);
      }
    }
  }

}
?>
