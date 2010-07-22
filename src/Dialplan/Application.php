<?php
/* 
 */

/**
 * This class represents a call to a Dialplan application in an extension.
 *
 * @author gbirke
 */
class Dialplan_Application {
  
  /**
   *
   * @var string
   */
  protected $_name;

  protected $_paramSeparator = ',';

  protected $_comment = '';

  /**
   *
   * @var array
   */
  protected $_params = array();

  public function getName() {
    return $this->_name;
  }

  public function setName($name) {
    $this->_name = $name;
  }

  public function getParams() {
    return $this->_params;
  }

  public function setParams($params) {
    $this->_params = $params;
  }
  
  public function addParam($param) {
    $this->_params[] = $param;
  }

  public function getComment() {
    return $this->_comment;
  }

  public function setComment($comment) {
    $this->_comment = $comment;
  }

  public function __toString() {
    $comment = $this->_comment ? " ;{$this->_comment}" : "";
    return $this->_name.'('.  implode($this->_paramSeparator, $this->_params).")$comment";
  }

  public function toArray() {
    $ret = array(
        'application' => $this->_name,
        'params' => $this->_params
    );
    if($this->_comment)
            $ret['comment'] = $this->_comment;
    return $ret;
  }

}
?>
