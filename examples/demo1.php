<?php

require_once dirname(__FILE__).'/autoload.php';

$fn = empty($argv[1]) ? 'extensions2.include' : $argv[1];

$parser = new Extensionparser();
$logger = new Eventlogger(array('NONE'));
$abuilder = new Dialplan_Builder_Application();
$ebuilder = new Dialplan_Builder_Extension();
$ebuilder->setApplicationBuilder($abuilder);
$parser->addObserver($logger)
       ->addObserver($abuilder, $abuilder->getNotificationTypes())
       ->addObserver($ebuilder, $ebuilder->getNotificationTypes());
$parser->parse($fn);


foreach($ebuilder as $exten) {
  echo $exten;
}
?>
