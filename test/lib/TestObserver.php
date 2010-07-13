<?php
/* 
 */

/**
 * The TestObserver is a dummy class for testing the event dispatcher.
 *
 * @author Gabriel Birke
 */
class TestObserver implements IExtensionObserver {

  public function update($emitter, $notification) {
    // do nothing
  }
}
?>
