<?php
class Fixture_ThreeApplications {
  public $fixtures = array();
  function __construct(){
    $this->fixtures = array(
      new Parserevent('extension',array (  'value' => '1',)),
      new Parserevent('priority',array (  'value' => '1',)),
      new Parserevent('application',array (  'name' => 'Answer',)),
      new Parserevent('newline',array (  'text' => 'exten => 1,2,Wait(0.3)',  'number' => 4,)),
      new Parserevent('extension',array (  'value' => '1',)),
      new Parserevent('priority',array (  'value' => '2',)),
      new Parserevent('application',array (  'name' => 'Wait',)),
      new Parserevent('parameter',array (  'value' => '0.3',)),
      new Parserevent('newline',array (  'text' => 'exten => 1,3,Playback(\'tt-weasels\') ; Always funny',  'number' => 5,)),
      new Parserevent('extension',array (  'value' => '1',)),
      new Parserevent('priority',array (  'value' => '3',)),
      new Parserevent('application',array (  'name' => 'Playback',)),
      new Parserevent('parameter',array (  'value' => '\'tt-weasels\'',)),
      new Parserevent('endfile',array (  'name' => '../examples/extensions1.conf',))
    );
  }
}
