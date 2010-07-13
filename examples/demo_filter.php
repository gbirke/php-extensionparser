<?php
/* 
 * This demo shows how to use filters.
 * It will chain ContextFilter and MacroFilter to return only speciafic macro
 * extensions from a specific context.
 */

require_once dirname(__FILE__).'/autoload.php';

$fn = empty($argv[1]) ? 'extensions2.include' : $argv[1];

$parser = new Extensionparser();
$contextFilter = new Dialplan_Builder_ContextFilter();
$macroFilter = new Dialplan_Builder_MacroFilter();
$abuilder = new Dialplan_Builder_Application();
$ebuilder = new Dialplan_Builder_Extension();
$ebuilder->setApplicationBuilder($abuilder);
$macroFilter->setAllowedMacros(array('tl-userextension'))
       ->addObserver($abuilder, $abuilder->getNotificationTypes())
       ->addObserver($ebuilder, $ebuilder->getNotificationTypes());
$contextFilter->setAllowedContexts(array('local-extensions'))
       ->addObserver($macroFilter, $macroFilter->getNotificationTypes());
$parser->addObserver($contextFilter, $contextFilter->getNotificationTypes());
$parser->parse($fn);

foreach($ebuilder as $exten) {
  echo $exten;
}