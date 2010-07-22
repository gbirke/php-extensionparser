<?php
/* 
 * This example shows how to use the menu builder.
 */

require_once dirname(__FILE__).'/autoload.php';

$fn = empty($argv[1]) ? 'menu.include' : $argv[1];
$parser = new Dialplan_Parser();
$mbuilder = new Dialplan_Builder_Menu();
$parser->addObserver($mbuilder, $mbuilder->getNotificationTypes())->parse($fn);
foreach($mbuilder->getObjectQueue() as $menu) {
  var_export($menu->toArray());
  echo "\n";
}
