<?php
class Fixture_ThreeApplications {
  public $fixtures = array();
  function __construct(){
    $this->fixtures = array(
      new Dialplan_Parser_Event('newline',array (  'newline' => 'exten => 1,1,Answer()',  'number' => 1,)),
      new Dialplan_Parser_Event('extension',array (  'extension' => '1',)),
      new Dialplan_Parser_Event('priority',array (  'priority' => '1',)),
      new Dialplan_Parser_Event('application',array (  'application' => 'Answer',)),
      new Dialplan_Parser_Event('newline',array (  'newline' => 'exten => 1,2,Playback(\'tt-weasels\') ; Always funny',  'number' => 2,)),
      new Dialplan_Parser_Event('extension',array (  'extension' => '1',)),
      new Dialplan_Parser_Event('priority',array (  'priority' => '2',)),
      new Dialplan_Parser_Event('application',array (  'application' => 'Playback',)),
      new Dialplan_Parser_Event('parameter',array (  'parameter' => '\'tt-weasels\'',)),
      new Dialplan_Parser_Event('comment',array (  'comment' => ' Always funny',  'context' => 'extension',)),
      new Dialplan_Parser_Event('newline',array (  'newline' => 'exten => 1,3,Hangup()',  'number' => 3,)),
      new Dialplan_Parser_Event('extension',array (  'extension' => '1',)),
      new Dialplan_Parser_Event('priority',array (  'priority' => '3',)),
      new Dialplan_Parser_Event('application',array (  'application' => 'Hangup',)),
      new Dialplan_Parser_Event('endfile',array (  'name' => '../examples/extensions1.conf',))
    );
  }
}
