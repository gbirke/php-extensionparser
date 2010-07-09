#!/usr/bin/php
<?php

if(!defined("TEST_DIR"))
	DEFINE("TEST_DIR", dirname(__FILE__));
require_once TEST_DIR.'/autoload.php';

if(empty($argv[1]) || empty($argv[2])) {
  die("You must give a file name to parse and a class name for output.\n");
}

$parser = new Extensionparser();
$fixtureGenerator = new EventFixtureGenerator();
$parser->addObserver($fixtureGenerator);
$parser->parse($argv[1]);
$filepath = TEST_DIR . '/Fixture';
if(!file_exists($filepath)) {
  mkdir($filepath);
}
$classname = ucfirst($argv[2]);
$filename = $filepath.'/'.$classname.'.php';
file_put_contents($filename, $fixtureGenerator->export("Fixture_$classname"));
?>
