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
class Dialplan_Extension {
  
  /**
   * Extension number
   * @var string
   */
  protected $_exten;

  protected $_applications = array();
  protected $_priorities = array();
  protected $_labels = array();
  
  /**
   *
   * @var string
   */
  protected $_comment = "";

  protected $_template = "exten => %s,%s%s\n";

  /**
   * Get extension number
   * @return string
   */
  public function getExten() {
    return $this->_exten;
  }

  /**
   * Set extension number
   * @param string $exten
   * @return Dialplan_Extension
   */
  public function setExten($exten) {
    $this->_exten = $exten;
    return $this;
  }

  /**
   *
   * @param Dialplan_Application $application
   * @param string $priority
   * @param string $label
   * @return Dialplan_Extension
   */
  public function addApplication(Dialplan_Application $application, $priority, $label=null) {
    $this->_applications[] = $application;
    $this->_priorities[] = $priority;
    if($label) {
      $this->_labels[$label] = count($this->_priorities) - 1;
    }
    return $this;
  }

  public function getComment() {
    return $this->_comment;
  }

  public function setComment($comment) {
    $this->_comment = $comment;
  }

  public function __toString() {
    $str = $this->_comment ? ";{$this->_comment}\n" : "";
    $labels = array_flip($this->_labels);
    foreach($this->_priorities as $idx => $prio) {
      if(!empty($labels[$idx]))
        $prio .= "({$labels[$idx]})";
      $str .= sprintf($this->_template, $this->_exten, $prio, $this->_applications[$idx]);
    }
    return $str;
  }
}
?>