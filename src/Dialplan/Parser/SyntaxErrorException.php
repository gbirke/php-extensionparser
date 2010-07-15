<?php
/* 
 */

/**
 * This exception is thrown when the parser encounters unknown input from the
 * extension file.
 *
 * @author birke
 */
class Dialplan_Parser_SyntaxErrorException extends Dialplan_Parser_Exception {

  /**
   * @var int
   */
  protected $_parsedLine;

  function  __construct($message, $parsedLine) {
    parent::__construct($message);
    $this->_parsedLine = $parsedLine;
  }

  /**
   * Get the line number in the extensiuon file where the error occured.
   * @return int
   */
  public function getParsedLine() {
    return $this->_parsedLine;
  }
}
?>
