<?php
class Fixture_StarExtensions {
  public $fixtures = array();
  function __construct(){
    $this->fixtures = array(
      new Parserevent('startfile',array (  'name' => '../examples/extensions1.conf',)),
      new Parserevent('extension',array (  'value' => '*2',)),
      new Parserevent('priority',array (  'value' => '1',)),
      new Parserevent('application',array (  'name' => 'Answer',)),
      new Parserevent('newline',array (  'text' => 'exten => *2,n,Wait(0.3)',  'number' => 9,)),
      new Parserevent('extension',array (  'value' => '*2',)),
      new Parserevent('priority',array (  'value' => 'n',)),
      new Parserevent('application',array (  'name' => 'Wait',)),
      new Parserevent('parameter',array (  'value' => '0.3',)),
      new Parserevent('newline',array (  'text' => 'exten => *2,n,Playback(\'goodbye\')',  'number' => 10,)),
      new Parserevent('extension',array (  'value' => '*2',)),
      new Parserevent('priority',array (  'value' => 'n',)),
      new Parserevent('application',array (  'name' => 'Playback',)),
      new Parserevent('parameter',array (  'value' => '\'goodbye\'',)),
      new Parserevent('newline',array (  'text' => 'exten => 2,1,Dial(SIP/1337)',  'number' => 11,)),
      new Parserevent('extension',array (  'value' => '2',)),
      new Parserevent('priority',array (  'value' => '1',)),
      new Parserevent('application',array (  'name' => 'Dial',)),
      new Parserevent('parameter',array (  'value' => 'SIP/1337',)),
      new Parserevent('newline',array (  'text' => false,  'number' => 12,)),
      new Parserevent('endfile',array (  'name' => '../examples/extensions1.conf',)),
    );
  }
}
