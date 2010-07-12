<?php
class Fixture_ThreeApplications {
  public $fixtures = array();
  function __construct(){
    $this->fixtures = array(
      new Parserevent('extension',array (  'extension' => '1',)),
      new Parserevent('priority',array (  'priority' => '1',)),
      new Parserevent('application',array (  'application' => 'Answer',)),
      new Parserevent('newline',array (  'newline' => 'exten => 1,2,Playback(\'tt-weasels\') ; Always funny',  'number' => 4,)),
      new Parserevent('extension',array (  'extension' => '1',)),
      new Parserevent('priority',array (  'priority' => '2',)),
      new Parserevent('application',array (  'application' => 'Playback',)),
      new Parserevent('parameter',array (  'parameter' => '\'tt-weasels\'',)),
      new Parserevent('comment',array (  'comment' => ' Always funny',  'context' => 'extension',)),
      new Parserevent('newline',array (  'newline' => 'exten => 1,3,Hangup()',  'number' => 5,)),
      new Parserevent('extension',array (  'extension' => '1',)),
      new Parserevent('priority',array (  'priority' => '3',)),
      new Parserevent('application',array (  'application' => 'Hangup',)),
      new Parserevent('endfile',array (  'name' => '../examples/extensions1.conf',))
    );
  }
}
