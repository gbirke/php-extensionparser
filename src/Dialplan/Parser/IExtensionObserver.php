<?php
/* 
 */

/**
 * This is the interface that each observer of parser events must implement
 * @author birke
 */
interface Dialplan_Parser_IExtensionObserver {
  /**
   *
   * @param object $emitter
   * @param Dialplan_Parser_Event $notification
   */
  public function update($emitter, $notification);
}
?>
