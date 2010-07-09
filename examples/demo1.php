<?php

require_once dirname(__FILE__).'/autoload.php';

$parser = new Extensionparser();
$abuilder = new Dialplan_Builder_Application();
$ebuilder = new Dialplan_Builder_Extension();
$ebuilder->setApplicationBuilder($abuilder);
$parser->addObserver($abuilder, $abuilder->getNotificationTypes())
       ->addObserver($ebuilder, $ebuilder->getNotificationTypes());
$parser->parse('extensions1.conf');

while($exten = $ebuilder->getObject()) {
  echo $exten;
}
?>
