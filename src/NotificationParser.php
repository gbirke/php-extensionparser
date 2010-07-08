<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Whenever a parseable event occurs, notify the attached observers
 *
 * @author birke
 */
abstract class NotificationParser implements SplSubject {
    
  /**
   *
   * @var SplObjectStorage
   */
  protected $_observers;

  public function __construct() {
    $this->_observers = new SplObjectStorage();
  }

  public function attach(SplObserver $SplObserver) {
    $this->_observers->attach($SplObserver);
  }

  public function detach(SplObserver $SplObserver) {
    $this->_observers->detach($SplObserver);
  }

}
?>
