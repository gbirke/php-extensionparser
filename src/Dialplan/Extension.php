<?php
/* 
 */

/**
 * This class represents a single extension in an Asterisk dialplan.
 * One extension can have several appplications with assigned priorities.
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

  protected $_template = "exten => %s,%s,%s\n";

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

  /**
   * Return applications
   *
   * @todo Parameter to retrieve them in priority order
   * @return array
   */
  public function getApplications() {
    return $this->_applications;
  }

  /**
   * Get a specific application
   *
   * @throws Exception
   * @param int $index
   */
  public function getApplication($index) {
    if($index < count($this->_applications)) {
      return $this->_applications[$index];
    }
    else {
      throw new Exception("Application index out of bounds.");
    }
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

  public function toArray() {
    $return = array (
        'extension' => $this->_exten,
        'comment' => $this->_comment,
        'applications' => array()
    );
    $labels = array_flip($this->_labels);
    foreach($this->_applications as $idx => $app) {
      $return['applications'][] = array(
          $this->_priorities[$idx],
          empty($labels[$idx]) ? null : $labels[$idx],
          $app->toArray()
          );
    }
    return $return;
  }
}
?>
