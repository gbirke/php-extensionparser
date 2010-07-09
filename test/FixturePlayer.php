<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of FixturePlayer
 *
 * @author gbirke
 */
class FixturePlayer extends EventDispatcher {

  public function replay($fixtureClassname) {
    $fixture = new $fixtureClassname;
    foreach($fixture->fixtures as $notification) {
      $this->notify($notification);
    }
  }

}
?>
