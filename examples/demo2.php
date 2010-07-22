<?php
/**
 * This example file reads a dial plan and echoes it back after parsing.
 */
require_once dirname(__FILE__).'/autoload.php';

$fn = empty($argv[1]) ? 'extensions2.include' : $argv[1];

$parser = new Dialplan_Parser();
$logger = new Eventlogger(array('NONE'));
$abuilder = new Dialplan_Builder_Application();
$ebuilder = new Dialplan_Builder_Extension();
$cbuilder = new Dialplan_Builder_Context();
$ebuilder->setApplicationBuilder($abuilder);
$cbuilder->setExtensionBuilder($ebuilder);
//$parser->addObserver($logger)
$parser->addObserver($abuilder, $abuilder->getNotificationTypes())
       ->addObserver($ebuilder, $ebuilder->getNotificationTypes())
       ->addObserver($cbuilder, $cbuilder->getNotificationTypes())
;
$parser->parse($fn);

foreach($cbuilder as $context) {
  echo $context;
}

?>
