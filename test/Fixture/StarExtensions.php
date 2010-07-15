<?php
class Fixture_StarExtensions {
  public $fixtures = array();
  function __construct(){
    $this->fixtures = array(
      new Dialplan_Parser_Event('startfile',array (  'startfile' => '../examples/extensions1.conf',)),
      new Dialplan_Parser_Event('newline',array (  'newline' => 'exten => *2,1,Answer()',  'number' => 8,)),
      new Dialplan_Parser_Event('extension',array (  'extension' => '*2',)),
      new Dialplan_Parser_Event('priority',array (  'priority' => '1',)),
      new Dialplan_Parser_Event('application',array (  'application' => 'Answer',)),
      new Dialplan_Parser_Event('newline',array (  'newline' => 'exten => *2,n,Wait(0.3)',  'number' => 9,)),
      new Dialplan_Parser_Event('extension',array (  'extension' => '*2',)),
      new Dialplan_Parser_Event('priority',array (  'priority' => 'n',)),
      new Dialplan_Parser_Event('application',array (  'application' => 'Wait',)),
      new Dialplan_Parser_Event('parameter',array (  'parameter' => '0.3',)),
      new Dialplan_Parser_Event('newline',array (  'newline' => 'exten => *2,n,Playback(\'goodbye\')',  'number' => 10,)),
      new Dialplan_Parser_Event('extension',array (  'extension' => '*2',)),
      new Dialplan_Parser_Event('priority',array (  'priority' => 'n',)),
      new Dialplan_Parser_Event('application',array (  'application' => 'Playback',)),
      new Dialplan_Parser_Event('parameter',array (  'parameter' => '\'goodbye\'',)),
      new Dialplan_Parser_Event('newline',array (  'newline' => 'exten => 2,1,Dial(SIP/1337)',  'number' => 11,)),
      new Dialplan_Parser_Event('extension',array (  'extension' => '2',)),
      new Dialplan_Parser_Event('priority',array (  'priority' => '1',)),
      new Dialplan_Parser_Event('application',array (  'application' => 'Dial',)),
      new Dialplan_Parser_Event('parameter',array (  'parameter' => 'SIP/1337',)),
      new Dialplan_Parser_Event('newline',array (  'newline' => false,  'number' => 12,)),
      new Dialplan_Parser_Event('endfile',array (  'endfile' => '../examples/extensions1.conf',)),
    );
  }
}
