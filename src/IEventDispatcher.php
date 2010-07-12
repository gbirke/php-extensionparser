<?php
/* 
 * 
 */

/**
 * This is the interface for classes that can dispatch parser events.
 *
 * @author gbirke
 */
interface IEventDispatcher {

    public function addObserver(IExtensionObserver $observer, $eventname = 'ALL');
    public function removeObserver(IExtensionObserver $observer, $eventname = 'ALL');
    public function notify($emitter, Parserevent $notification);

}
?>
