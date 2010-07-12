<?php
class Fixture_StarExtensions {
  public $fixtures = array();
  function __construct(){
    $this->fixtures = array(
      new Parserevent('startfile',array (  'startfile' => '../examples/extensions1.conf',)),
      new Parserevent('newline',array (  'newline' => 'exten => *2,1,Answer()',  'number' => 8,)),
      new Parserevent('extension',array (  'extension' => '*2',)),
      new Parserevent('priority',array (  'priority' => '1',)),
      new Parserevent('application',array (  'application' => 'Answer',)),
      new Parserevent('newline',array (  'newline' => 'exten => *2,n,Wait(0.3)',  'number' => 9,)),
      new Parserevent('extension',array (  'extension' => '*2',)),
      new Parserevent('priority',array (  'priority' => 'n',)),
      new Parserevent('application',array (  'application' => 'Wait',)),
      new Parserevent('parameter',array (  'parameter' => '0.3',)),
      new Parserevent('newline',array (  'newline' => 'exten => *2,n,Playback(\'goodbye\')',  'number' => 10,)),
      new Parserevent('extension',array (  'extension' => '*2',)),
      new Parserevent('priority',array (  'priority' => 'n',)),
      new Parserevent('application',array (  'application' => 'Playback',)),
      new Parserevent('parameter',array (  'parameter' => '\'goodbye\'',)),
      new Parserevent('newline',array (  'newline' => 'exten => 2,1,Dial(SIP/1337)',  'number' => 11,)),
      new Parserevent('extension',array (  'extension' => '2',)),
      new Parserevent('priority',array (  'priority' => '1',)),
      new Parserevent('application',array (  'application' => 'Dial',)),
      new Parserevent('parameter',array (  'parameter' => 'SIP/1337',)),
      new Parserevent('newline',array (  'newline' => false,  'number' => 12,)),
      new Parserevent('endfile',array (  'endfile' => '../examples/extensions1.conf',)),
    );
  }
}
