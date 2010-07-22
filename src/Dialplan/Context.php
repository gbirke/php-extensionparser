<?php
/* 
 */

/**
 * This class represents a Dialplan context
 *
 * @author birke
 */
class Dialplan_Context {

  /**
   * The name of the context
   * @var string
   */
  protected $_name;

  /**
   * Comment
   * @var string
   */
  protected $_comment = '';

  /**
   * The extensions. The array holds Dialplan_Extension objects
   * @var array
   */
  protected $_extensions;

  /**
   *
   * @return string
   */
  public function getName() {
    return $this->_name;
  }

  /**
   *
   * @param string $name
   * @return Dialplan_Context
   */
  public function setName($name) {
    $this->_name = $name;
    return $this;
  }

  /**
   *
   * @return string
   */
  public function getComment() {
    return $this->_comment;
  }

  /**
   *
   * @param string $comment
   * @return Dialplan_Context
   */
  public function setComment($comment) {
    $this->_comment = $comment;
    return $this;
  }
  /**
   *
   * @return array
   */
  public function getExtensions() {
    return $this->_extensions;
  }

  /**
   *
   * @param array $extensions
   * @return Dialplan_Context
   */
  public function setExtensions($extensions) {
    $this->_extensions = $extensions;
    return $this;
  }

  public function __toString() {
    $s = '['.$this->_name.']';
    if($this->_comment)
            $s .= ' ;'.$this->_comment;
    $s .= "\n";
    foreach($this->_extensions as $exten) {
      $s .= $exten->__toString();
    }
    return $s;
  }

  /**
   *
   * @return array
   */
  public function toArray() {
    $extensions = array();
    foreach($this->_extensions as $number => $exten) {
      $extensions[$number] = $exten->toArray();
    }
    return array(
      'name' => $this->_name,
      'extensions' => $extensions,
      'comment' =>  $this->_comment
    );
  }




}
?>
