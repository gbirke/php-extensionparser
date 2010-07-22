<?php
/* 
 */

/**
 * This class represents an Astersik dialplan context that acts as a menu
 *
 * @author birke
 */
class Dialplan_Menu {

  protected $_name='';

  /**
   *
   * @var string
   */
  protected $_audiofile='';

  protected $_digitTimeout = 0;
  protected $_responseTimeout = 0;

  /**
   * An associative array in the format option_exten => array(context,exten,priority)
   * @var array
   */
  protected $_options = array();

  public function getName() {
    return $this->_name;
  }

  public function setName($name) {
    $this->_name = $name;
  }

  public function getDigitTimeout() {
    return $this->_digitTimeout;
  }

  public function setDigitTimeout($digitTimeout) {
    $this->_digitTimeout = $digitTimeout;
  }

  public function getResponseTimeout() {
    return $this->_responseTimeout;
  }

  public function setResponseTimeout($responseTimeout) {
    $this->_responseTimeout = $responseTimeout;
  }

  public function getOptions() {
    return $this->_options;
  }

  public function setOptions($options) {
    $this->_options = $options;
  }

  public function getAudiofile() {
    return $this->_audiofile;
  }

  public function setAudiofile($audiofile) {
    $this->_audiofile = $audiofile;
  }

  /**
   *
   * @param string $name
   * @param array $value array in the format array(context,exten,priority)
   */
  public function setOption($name, $value) {
    $this->_options[$name] = $value;
  }

  public function toArray() {
    return array(
      'name' => $this->_name,
      'audiofile' => $this->_audiofile,
      'digitTimeout' => $this->_digitTimeout,
      'responseTimeout' => $this->_responseTimeout,
      'options' => $this->_options
    );
  }

}
?>
