<?php
class Fixture_NewlineExtensions {
  public $fixtures = array();
  function __construct(){
    $this->fixtures = array(
      new Dialplan_Parser_Event('startfile',array (  'startfile' => '../examples/extensions1.conf',)),
      new Dialplan_Parser_Event('newline',array (  'newline' => '',  'number' => 1,)),
      new Dialplan_Parser_Event('newline',array (  'newline' => '',  'number' => 2,)),
      new Dialplan_Parser_Event('newline',array (  'newline' => 'exten => *2,1,Answer()',  'number' => 3,)),
      new Dialplan_Parser_Event('extension',array (  'extension' => '*2',)),
      new Dialplan_Parser_Event('priority',array (  'priority' => '1',)),
      new Dialplan_Parser_Event('application',array (  'application' => 'Answer',)),
      new Dialplan_Parser_Event('newline',array (  'newline' => 'exten => 2,n,Wait(0.3)',  'number' => 4,)),
      new Dialplan_Parser_Event('extension',array (  'extension' => '2',)),
      new Dialplan_Parser_Event('priority',array (  'priority' => 'n',)),
      new Dialplan_Parser_Event('application',array (  'application' => 'Wait',)),
      new Dialplan_Parser_Event('parameter',array (  'parameter' => '0.3',)),
      new Dialplan_Parser_Event('newline',array (  'newline' => '',  'number' => 5,)),
      new Dialplan_Parser_Event('newline',array (  'newline' => '',  'number' => 6,)),
      new Dialplan_Parser_Event('endfile',array (  'endfile' => '../examples/extensions1.conf',)),
    );
  }
}
