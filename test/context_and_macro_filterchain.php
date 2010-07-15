<?php
// Example for a filterchain file
$contextFilter = new Dialplan_Builder_ContextFilter();
$macroFilter = new Dialplan_Builder_MacroFilter();
$macroFilter->setAllowedMacros(array('tl-userextension'));
$contextFilter->setAllowedContexts(array('local-extensions'))
       ->addObserver($macroFilter, $macroFilter->getNotificationTypes());
// Set first filter in chain as parser observer
$parser->addObserver($contextFilter, $contextFilter->getNotificationTypes());
// Return last filter in chain to be observed
return $macroFilter;
?>
