<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ParserException
 *
 * @author birke
 */
class ParserSyntaxErrorException extends ParserException {

  protected $_parsedLine;

  function  __construct($message, $parsedLine) {
    parent::__construct($message);
    $this->_parsedLine = $parsedLine;
  }

  public function getParsedLine() {
    return $this->_parsedLine;
  }
}
?>
