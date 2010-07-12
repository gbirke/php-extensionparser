<?php

if(!defined("TEST_DIR"))
	DEFINE("TEST_DIR", dirname(__FILE__));
require_once TEST_DIR.'/autoload.php';
require_once TEST_DIR.'/lib/stringstream.php';

class ExtensionParserTest extends PHPUnit_Framework_TestCase implements IExtensionObserver
{
  /**
   * Storage for Parserevents
   * @var array
   */
  protected $_notifications;

  /**
   * Storage for subjects
   * @var array;
   */
  protected $_subjects;

  /**
   * @var Extensionparser
   */
  protected $_parser;

  public function setUp() {
    $this->_notifications = array();
    $this->_subjects = array();
    $this->_parser = new Extensionparser();
  }

   public function testNotificationWorks() {
     $this->_parser->addObserver($this);
     $n = new Parserevent("Test");
     $this->_parser->notify($this, $n);
     $this->assertEquals(1, count($this->_notifications));
     $this->assertEquals($n, $this->_notifications[0]);
   }

   
   public function testMultipleObserversForTheSameEvent() {
      $recorder1 = new Eventrecorder();
      $recorder2 = new Eventrecorder();
      $this->_parser->addObserver($recorder1,array('EVT1', 'EVT2'));
      $this->_parser->addObserver($recorder2,array('EVT2'));
      $evt1 = new Parserevent("EVT1");
      $evt2 = new Parserevent("EVT2");
      $this->_parser->notify($this, $evt1);
      $this->_parser->notify($this, $evt2);
      $this->assertEquals(array($evt1, $evt2), $recorder1->getNotifications());
      $this->assertEquals(array($evt2), $recorder2->getNotifications());
   }

   public function testParserPassesItselfAsEmitter() {
      $this->_parseString(" ", array('startfile', 'endfile'));
      $this->assertEquals(2, count($this->_subjects));
      $this->assertSame($this->_parser, $this->_subjects[0]);
   }

   public function testFileNotification() {
     $this->_parseString(" ", array('startfile', 'endfile'));
     $this->assertEquals(2, count($this->_notifications));
   }

   public function testLineNotification() {
     $this->_parseString("\n ", array('newline'));
     $this->assertEquals(2, count($this->_notifications));
   }

   public function testSingleLineComment() {
    $this->_parseString("; Just a reminder", array('comment'));
     $expected = new Parserevent('comment', array('comment' => ' Just a reminder', 'context' => "line"));
     $this->assertEvent($expected);
   }

   public function testContext() {
     $this->_parseString("[test]", array('context'));
     $expected = new Parserevent('context', array('context' => 'test'));
     $this->assertEvent($expected);
   }

   public function testCommentAfterContext() {
     $this->_parseString("[test];first comment\n", array('context', 'comment'));
     $expected = array(
       new Parserevent('context', array('context' => 'test')),
       new Parserevent('comment', array('comment' => 'first comment', "context" => "context"))
     );
     $this->assertEvents($expected);
   }

   public function testNumericExtension() {
     $this->_parseString("exten => 1,1,NoOp()", array('extension'));
     $expected = new Parserevent('extension', array('extension' => '1'));
     $this->assertEvent($expected);
   }

   public function testAlphaNumericExtension() {
     $this->_parseString("exten => s,1,NoOp()", array('extension'));
     $expected = new Parserevent('extension', array('extension' => 's'));
     $this->assertEvent($expected);
   }

   public function testWildcardExtension() {
     $this->_parseString("exten => _XXXX,1,NoOp()", array('extension'));
     $expected = new Parserevent('extension', array('extension' => '_XXXX'));
     $this->assertEvent($expected);
   }

   public function testInvalidExtension() {
     try {
      $this->_parseString("exten => %%,1,NoOp()", array('extension'));
      $this->fail("ParserSyntaxErrorException expected.");
     }
     catch(ParserSyntaxErrorException $e) {
       $this->assertContains("extension", $e->getMessage());
     }
   }

   public function testNumericPriority() {
     $this->_parseString("exten => 1,1,NoOp()", array('priority'));
     $expected = new Parserevent('priority', array('priority' => '1'));
     $this->assertEvent($expected);
   }

   public function testNPriority() {
     $this->_parseString("exten => 1,n,NoOp()", array('priority'));
     $expected = new Parserevent('priority', array('priority' => 'n'));
     $this->assertEvent($expected);
   }

   public function testNPlusPriority() {
     $this->_parseString("exten => 1,n+1,NoOp()", array('priority'));
     $expected = new Parserevent('priority', array('priority' => 'n+1'));
     $this->assertEvent($expected);
   }

   public function testSPriority() {
     $this->_parseString("exten => 1,s,NoOp()", array('priority'));
     $expected = new Parserevent('priority', array('priority' => 's'));
     $this->assertEvent($expected);
   }

   public function testHintPriority() {
     $this->_parseString("exten => 1,hint,SIP/1234", array('priority'));
     $expected = new Parserevent('priority', array('priority' => 'hint'));
     $this->assertEvent($expected);
   }

   public function testInvalidPriority() {
     try {
      $this->_parseString("exten => 1,foo,NoOp()", array('extension'));
      $this->fail("ParserSyntaxErrorException expected.");
     }
     catch(ParserSyntaxErrorException $e) {
       $this->assertContains("priority", $e->getMessage());
     }
   }

   public function testPriorityWithLabel() {
     $this->_parseString("exten => 1,n(my-label),NoOp()", array('label'));
     $expected = new Parserevent('label', array('label' => 'my-label'));
     $this->assertEvent($expected);;
   }

   public function testSimpleApplication() {
     $this->_parseString("exten => 1,1,NoOp()", array('application', 'parameters'));
     $expected = new Parserevent('application', array('application' => 'NoOp'));
     $this->assertEvent($expected);
   }

   public function testMissingApplicationBraces() {
     try {
      $this->_parseString("exten => 1,1,Verbose(Foo", array('extension'));
      $this->fail("ParserSyntaxErrorException expected.");
     }
     catch(ParserSyntaxErrorException $e) {
       $this->assertContains("brace", $e->getMessage());
     }
   }

   public function testApplicationWithOneParam() {
     $this->_parseString("exten => 1,1,Verbose(First Param)", array('application', 'parameter'));
     $expected = array(
         new Parserevent('application', array('application' => 'Verbose')),
         new Parserevent('parameter', array('parameter' => 'First Param', 'position' => 1))
     );
     $this->assertEvents($expected);
   }

   public function testApplicationWithSeveralParams() {
     $this->_parseString("exten => 1,1,Macro(mymacro,foo,1337)", array('application', 'parameter'));
     $expected = array(
         new Parserevent('application', array('application' => 'Macro')),
         new Parserevent('parameter', array('parameter' => 'mymacro', 'position' => 1)),
         new Parserevent('parameter', array('parameter' => 'foo', 'position' => 2)),
         new Parserevent('parameter', array('parameter' => '1337', 'position' => 3))
     );
     $this->assertEvents($expected);
   }

   public function testApplicationWithEmptyParam() {
     $this->_parseString("exten => 1,1,Macro(mymacro,,1337)", array('application', 'parameter'));
     $expected = array(
         new Parserevent('application', array('application' => 'Macro')),
         new Parserevent('parameter', array('parameter' => 'mymacro', 'position' => 1)),
         new Parserevent('parameter', array('parameter' => '', 'position' => 2)),
         new Parserevent('parameter', array('parameter' => '1337', 'position' => 3))
     );
     $this->assertEvents($expected);
   }

   public function testApplicationAllParamsEmpty() {
     $this->_parseString("exten => 1,1,Macro(,)", array('application', 'parameter'));
     $expected = array(
         new Parserevent('application', array('application' => 'Macro')),
         new Parserevent('parameter', array('parameter' => '', 'position' => 1)),
         new Parserevent('parameter', array('parameter' => '', 'position' => 2))
     );
     $this->assertEvents($expected);
   }

   // TODO Test brace handling

   public function testCommentAfterApplication() {
     $this->_parseString("exten => 1,1,NoOp();Comments FTW!\n", array('application', 'comment'));
     $expected = array(
         new Parserevent('application', array('application' => 'NoOp')),
         new Parserevent('comment', array('comment' => 'Comments FTW!', "context" => "extension"))
     );
     $this->assertEvents($expected);
   }

   public function testHintExtension() {
     $this->_parseString("exten => 1,hint,SIP/1234", array('hintchannel'));
     $expected = new Parserevent('hintchannel', array('hintchannel' => 'SIP/1234'));
     $this->assertEvent($expected);
   }

   public function testInvalidHintExtension() {
     try {
      $this->_parseString("exten => 1,hint,Foo", array('hintchannel'));
      $this->fail("ParserSyntaxErrorException expected.");
     }
     catch(ParserSyntaxErrorException $e) {
       $this->assertContains("channel", $e->getMessage());
     }
   }

   public function testCommentAfterHintExtension() {
     $this->_parseString("exten => 1,hint,SIP/1234 ; Wink wink, nudge nudge", array('hintchannel', 'comment'));
     $expected = array(
         new Parserevent('hintchannel', array('hintchannel' => 'SIP/1234')),
         new Parserevent('comment', array('comment' => ' Wink wink, nudge nudge', "context" => "hint"))
     );
     $this->assertEvents($expected);
   }

   public function testGeneralContext() {
     $this->_parseString("[general]\nstatic=yes\nwriteprotect=no", array('generalsettings', 'context', 'setting'));
     $expected = array(
         new Parserevent('generalsettings', array('generalsettings' => 'general')),
         new Parserevent('setting', array('setting' => 'static', "value" => "yes")),
         new Parserevent('setting', array('setting' => 'writeprotect', "value" => "no"))
     );
     $this->assertEvents($expected);
   }

   public function testAssignmentsOutsideOfGeneralContextThrowException() {
     try {
      $this->_parseString("[foo]\nstatic=yes\nwriteprotect=no", array('generalsettings', 'context', 'setting'));
      $this->fail("ParserSyntaxErrorException expected.");
     }
     catch(ParserSyntaxErrorException $e) {
       $this->assertContains("Invalid", $e->getMessage());
     }
   }

   public function testGlobalVariablesContext() {
     $this->_parseString("[globals]\nINTERNATIONAL-PREFIX=011\nRINGTIME=15\n", array('globalvariables', 'context', 'global'));
     $expected = array(
         new Parserevent('globalvariables', array('globalvariables' => 'globals')),
         new Parserevent('global', array('global' => 'INTERNATIONAL-PREFIX', "value" => "011")),
         new Parserevent('global', array('global' => 'RINGTIME', "value" => "15"))
     );
     $this->assertEvents($expected);
   }


   public function assertEvent($expected) {
     if(count($this->_notifications) > 0) {
      $this->assertEquals($expected, $this->_notifications[0]);
     }
     else {
       $this->fail("No notifications received");
     }
   }

   public function assertEvents($expected) {
     $this->assertEquals($expected, $this->_notifications);
   }

   public function update($subject, $notification) {
     $this->_notifications[] = $notification;
     $this->_subjects[] = $subject;
   }

   protected function _parseString($str, $observeEvents="ALL") {
     StringStreamController::createRef('test', $str);
     $this->_parser->addObserver($this, $observeEvents);
     $this->_parser->parse('string://test');
   }
}
?>
