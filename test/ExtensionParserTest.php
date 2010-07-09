<?php

if(!defined("TEST_DIR"))
	DEFINE("TEST_DIR", dirname(__FILE__));
require_once TEST_DIR.'/autoload.php';
require_once TEST_DIR.'/lib/stringstream.php';

class ExtensionParserTest extends PHPUnit_Framework_TestCase implements IExtensionObserver
{
  protected $_notifications;

  public function setUp() {
    $this->_notifications = array();
  }

   public function testNotificationWorks() {
     $parser = new Extensionparser();
     $parser->addObserver($this);
     $n = new Parserevent("Test");
     $parser->notify($n);
     $this->assertEquals(1, count($this->_notifications));
     $this->assertEquals($n, $this->_notifications[0]);
   }

   // TODO
   public function testMultipleObserversForTheSameEvent() {
    ;
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
     $expected = new Parserevent('comment', array('text' => ' Just a reminder', 'type' => "line"));
     $this->assertEvent($expected);
   }

   public function testContext() {
     $this->_parseString("[test]", array('context'));
     $expected = new Parserevent('context', array('name' => 'test'));
     $this->assertEvent($expected);
   }

   public function testCommentAfterContext() {
     $this->_parseString("[test];first comment\n", array('context', 'comment'));
     $expected = array(
       new Parserevent('context', array('name' => 'test')),
       new Parserevent('comment', array('text' => 'first comment', "type" => "context"))
     );
     $this->assertEvents($expected);
   }

   public function testNumericExtension() {
     $this->_parseString("exten => 1,1,NoOp()", array('extension'));
     $expected = new Parserevent('extension', array('value' => '1'));
     $this->assertEvent($expected);
   }

   public function testAlphaNumericExtension() {
     $this->_parseString("exten => s,1,NoOp()", array('extension'));
     $expected = new Parserevent('extension', array('value' => 's'));
     $this->assertEvent($expected);
   }

   public function testWildcardExtension() {
     $this->_parseString("exten => _XXXX,1,NoOp()", array('extension'));
     $expected = new Parserevent('extension', array('value' => '_XXXX'));
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
     $expected = new Parserevent('priority', array('value' => '1'));
     $this->assertEvent($expected);
   }

   public function testNPriority() {
     $this->_parseString("exten => 1,n,NoOp()", array('priority'));
     $expected = new Parserevent('priority', array('value' => 'n'));
     $this->assertEvent($expected);
   }

   public function testNPlusPriority() {
     $this->_parseString("exten => 1,n+1,NoOp()", array('priority'));
     $expected = new Parserevent('priority', array('value' => 'n+1'));
     $this->assertEvent($expected);
   }

   public function testSPriority() {
     $this->_parseString("exten => 1,s,NoOp()", array('priority'));
     $expected = new Parserevent('priority', array('value' => 's'));
     $this->assertEvent($expected);
   }

   public function testHintPriority() {
     $this->_parseString("exten => 1,hint,SIP/1234", array('priority'));
     $expected = new Parserevent('priority', array('value' => 'hint'));
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

   public function testSimpleApplication() {
     $this->_parseString("exten => 1,1,NoOp()", array('application', 'parameters'));
     $expected = new Parserevent('application', array('name' => 'NoOp'));
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
         new Parserevent('application', array('name' => 'Verbose')),
         new Parserevent('parameter', array('value' => 'First Param'))
     );
     $this->assertEvents($expected);
   }

   public function testApplicationWithSeveralParams() {
     $this->_parseString("exten => 1,1,Macro(mymacro,foo,1337)", array('application', 'parameter'));
     $expected = array(
         new Parserevent('application', array('name' => 'Macro')),
         new Parserevent('parameter', array('value' => 'mymacro')),
         new Parserevent('parameter', array('value' => 'foo')),
         new Parserevent('parameter', array('value' => '1337'))
     );
     $this->assertEvents($expected);
   }

   public function testApplicationWithEmptyParam() {
     $this->_parseString("exten => 1,1,Macro(mymacro,,1337)", array('application', 'parameter'));
     $expected = array(
         new Parserevent('application', array('name' => 'Macro')),
         new Parserevent('parameter', array('value' => 'mymacro')),
         new Parserevent('parameter', array('value' => '')),
         new Parserevent('parameter', array('value' => '1337'))
     );
     $this->assertEvents($expected);
   }

   public function testApplicationAllParamsEmpty() {
     $this->_parseString("exten => 1,1,Macro(,)", array('application', 'parameter'));
     $expected = array(
         new Parserevent('application', array('name' => 'Macro')),
         new Parserevent('parameter', array('value' => '')),
         new Parserevent('parameter', array('value' => ''))
     );
     $this->assertEvents($expected);
   }

   // TODO Test brace handling

   public function testCommentAfterApplication() {
     $this->_parseString("exten => 1,1,NoOp();Comments FTW!\n", array('application', 'comment'));
     $expected = array(
         new Parserevent('application', array('name' => 'NoOp')),
         new Parserevent('comment', array('text' => 'Comments FTW!', "type" => "extension"))
     );
     $this->assertEvents($expected);
   }

   public function testHintExtension() {
     $this->_parseString("exten => 1,hint,SIP/1234", array('hintchannel'));
     $expected = new Parserevent('hintchannel', array('channel' => 'SIP/1234'));
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
         new Parserevent('hintchannel', array('channel' => 'SIP/1234')),
         new Parserevent('comment', array('text' => ' Wink wink, nudge nudge', "type" => "hint"))
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
   }

   protected function _parseString($str, $observeEvents="ALL") {
     StringStreamController::createRef('test', $str);
     $parser = new Extensionparser();
     $parser->addObserver($this, $observeEvents);
     $parser->parse('string://test');
   }
}
?>
