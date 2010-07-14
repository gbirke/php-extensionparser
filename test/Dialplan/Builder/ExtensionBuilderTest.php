<?php

if(!defined("TEST_DIR"))
	DEFINE("TEST_DIR", realpath(dirname(__FILE__).'/../..'));
require_once TEST_DIR.'/autoload.php';

/**
 * Integration Test of extension builder and application builder
 */
class ExtensionBuilderTest extends PHPUnit_Framework_TestCase
{

  /**
   *
   * @var Dialplan_Builder_Application
   */
  protected $_applicationBuilder;

  /**
   *
   * @var Dialplan_Builder_Extension
   */
  protected $_extensionBuilder;

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

  function testSameExtensionNumberWithStarprefixCreatesDifferentExtensions() {
    $fixturePlayer = new FixturePlayer();
    $builder = $this->_getBuilder($fixturePlayer);
    $fixturePlayer->replay("Fixture_StarExtensions");
    $this->assertEquals(2, count($builder->getObjectQueue()), "Two extension objects expected.");
    $extension = $builder->getObject();
    // Three star extensions
    $this->assertEquals(3, count($extension->getApplications()));
    $extension = $builder->getObject();
    // One extension without star
    $this->assertEquals(1, count($extension->getApplications()));
  }
   
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
    $this->_applicationBuilder = new Dialplan_Builder_Application();
    $this->_extensionbuilder = new Dialplan_Builder_Extension();
    $this->_extensionbuilder->setApplicationBuilder($this->_applicationBuilder);
    $fixturePlayer->addObserver($this->_applicationBuilder, $this->_applicationBuilder->getNotificationTypes());
    $fixturePlayer->addObserver($this->_extensionbuilder, $this->_extensionbuilder->getNotificationTypes());
    return $this->_extensionbuilder;
  }

  
}