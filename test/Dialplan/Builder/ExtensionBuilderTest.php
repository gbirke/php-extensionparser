<?php

if(!defined("TEST_DIR"))
	DEFINE("TEST_DIR", realpath(dirname(__FILE__).'/../..'));
require_once TEST_DIR.'/autoload.php';

/**
 * Integration Test of extension builder and application builder
 */
class ExtensionBuilderTest extends PHPUnit_Framework_TestCase
{

  function testApplicationsAreAssembled() {
    $fixturePlayer = new FixturePlayer();
    $builder = $this->_getBuilder($fixturePlayer);
    $fixturePlayer->replay("Fixture_ThreeApplications");
    $extension = $builder->getObject();
    $this->assertEquals(3, count($extension->getApplications()));
  }
  
  function testSameExtensionNumberCreatesOnlyOneExtensionObject() {
    $fixturePlayer = new FixturePlayer();
    $builder = $this->_getBuilder($fixturePlayer);
    $fixturePlayer->replay("Fixture_ThreeApplications");
    $this->assertEquals(1, count($builder->getObjectQueue()));
  }

  // Not working yet
  /*
  function testSameExtensionNumberWithStarprefixCreatesDifferentExtensions() {
    $fixturePlayer = new FixturePlayer();
    $fixturePlayer->addObserver(new Eventlogger());
    $builder = $this->_getBuilder($fixturePlayer);
    $fixturePlayer->replay("Fixture_StarExtensions");
    $this->assertEquals(2, count($builder->getObjectQueue()));
    $extension = $builder->getObject();
    // Three Star Extension
    $this->assertEquals(3, count($extension->getApplications()));
    $extension = $builder->getObject();
    // One Extension without star
    $this->assertEquals(1, count($extension->getApplications()));
  }
   */
   
  function testSeveralNewlinesDoNotAffectExtensionBuilding() {
    $fixturePlayer = new FixturePlayer();
    $builder = $this->_getBuilder($fixturePlayer);
    $fixturePlayer->replay("Fixture_NewlineExtensions");
  }

  /**
   * @param FixturePlayer $fixturePlayer
   * @return Dialplan_Builder_Extension
   */
  protected function _getBuilder($fixturePlayer) {
    $applicationBuilder = new Dialplan_Builder_Application();
    $extensionbuilder = new Dialplan_Builder_Extension();
    $extensionbuilder->setApplicationBuilder($applicationBuilder);
    $fixturePlayer->addObserver($applicationBuilder, $applicationBuilder->getNotificationTypes());
    $fixturePlayer->addObserver($extensionbuilder, $extensionbuilder->getNotificationTypes());
    return $extensionbuilder;
  }

  
}