<?php
/* 
 */

/**
 * The fixture player can replay a series of perser events from a fixture class
 *
 * @author gbirke
 */
class FixturePlayer extends Dialplan_Parser_EventDispatcher {

  public function replay($fixtureClassname) {
    $fixture = new $fixtureClassname;
    foreach($fixture->fixtures as $notification) {
      $this->notify($this, $notification);
    }
  }

}
?>
