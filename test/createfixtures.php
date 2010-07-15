#!/usr/bin/php
<?php

if(!defined("TEST_DIR"))
	DEFINE("TEST_DIR", dirname(__FILE__));
require_once TEST_DIR.'/autoload.php';

// Set up command line option parser
require_once 'Console/CommandLine.php';

$cmdparser = new Console_CommandLine();
$cmdparser->description = 'Generate event fixture files from dialplan extension files.';
$cmdparser->version = '1.0';
$cmdparser->addOption('filterchain', array(
    'short_name'  => '-f',
    'long_name'   => '--filterchain',
    'description' => 'Name of PHP file that creates filter instances',
    'help_name'   => 'FILE',
    'action'      => 'StoreString'
));
$cmdparser->addArgument('inputfile', array('description' => 'Extension file'));
$cmdparser->addArgument('classname', array('description' => 'Class name for the generated fixture class', 'optional' => true));
try {
    $cmdline = $cmdparser->parse();
} catch (Exception $exc) {
    $cmdparser->displayError($exc->getMessage());
    exit;
}

$parser = new Extensionparser();
$fixtureGenerator = new EventFixtureGenerator();
if(!empty($cmdline->options['filterchain'])) {
  $filter = include $cmdline->options['filterchain'];
  if(!$filter || !($filter instanceof Dialplan_Builder_Filter)) {
    throw new Exception("filterchain did not return a filter.");
  }
  $filter->addObserver($fixtureGenerator);
}
else {
  $parser->addObserver($fixtureGenerator);
}

$parser->parse($cmdline->args['inputfile']);
$filepath = TEST_DIR . '/Fixture';
if(!file_exists($filepath)) {
  mkdir($filepath);
}
if(empty($cmdline->args['classname']))
  $classname = ucfirst(preg_replace('/\..*/', '', basename($cmdline->args['inputfile'])));
else {
  $classname = $cmdline->args['classname'];
}
$filename = $filepath.'/'.$classname.'.php';
file_put_contents($filename, $fixtureGenerator->export("Fixture_$classname"));
?>
