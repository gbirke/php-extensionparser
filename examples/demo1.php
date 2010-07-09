<?php

require_once dirname(__FILE__).'/autoload.php';

$parser = new Extensionparser();
$logger = new Eventlogger(array('extension', 'application'));
$abuilder = new Dialplan_Builder_Application();
$ebuilder = new Dialplan_Builder_Extension();
$ebuilder->setApplicationBuilder($abuilder);
$parser->addObserver($logger)
       ->addObserver($abuilder, $abuilder->getNotificationTypes())
       ->addObserver($ebuilder, $ebuilder->getNotificationTypes());
$parser->parse('extensions.include');


while($exten = $ebuilder->getObject()) {
  echo $exten;
}
?>
