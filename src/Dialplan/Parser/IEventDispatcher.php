<?php
/* 
 * 
 */

/**
 * This is the interface for classes that can dispatch parser events.
 *
 * @author gbirke
 */
interface Dialplan_Parser_IEventDispatcher {

    public function addObserver(Dialplan_Parser_IExtensionObserver $observer, $eventname = 'ALL');
    public function removeObserver(Dialplan_Parser_IExtensionObserver $observer, $eventname = 'ALL');
    public function notify($emitter, Dialplan_Parser_Event $notification);

}
?>
