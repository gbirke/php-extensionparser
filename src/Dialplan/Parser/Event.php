<?php
/* 
 */

/**
 * Each parser event has a type and additional properties
 *
 * @author birke
 */
class Dialplan_Parser_Event {

  protected $_properties = array();

  protected $_continueNotification = true;

  /**
   *
   * @var string
   */
  protected $_type;

  public function __construct($type, $properties=array()) {
    $this->_type = $type;
    $this->_properties = $properties;
  }

  public function  __set($name,  $value) {
    if($name == 'type') {
      throw new Exception("Type is read only");
    }
    $this->_properties[$name] = $value;
  }

  public function  __get($name) {
    if($name == 'type')
      return $this->_type;
    return $this->_properties[$name];
  }

  public function __isset($name) {
    return isset($this->_properties[$name]);
  }

  public function cancelNotification() {
    $this->_continueNotification = false;
  }

  public function notificationIsCanceled() {
    return !$this->_continueNotification;
  }

  /**
   * Get all properties of this event
   * @return array
   */
  public function getProperties() {
    return $this->_properties;
  }
}
?>
