<?php
class Fixture_NewlineExtensions {
  public $fixtures = array();
  function __construct(){
    $this->fixtures = array(
      new Parserevent('startfile',array (  'startfile' => '../examples/extensions1.conf',)),
      new Parserevent('newline',array (  'newline' => '',  'number' => 1,)),
      new Parserevent('newline',array (  'newline' => '',  'number' => 2,)),
      new Parserevent('newline',array (  'newline' => 'exten => *2,1,Answer()',  'number' => 3,)),
      new Parserevent('extension',array (  'extension' => '*2',)),
      new Parserevent('priority',array (  'priority' => '1',)),
      new Parserevent('application',array (  'application' => 'Answer',)),
      new Parserevent('newline',array (  'newline' => 'exten => 2,n,Wait(0.3)',  'number' => 4,)),
      new Parserevent('extension',array (  'extension' => '2',)),
      new Parserevent('priority',array (  'priority' => 'n',)),
      new Parserevent('application',array (  'application' => 'Wait',)),
      new Parserevent('parameter',array (  'parameter' => '0.3',)),
      new Parserevent('newline',array (  'newline' => '',  'number' => 5,)),
      new Parserevent('newline',array (  'newline' => '',  'number' => 6,)),
      new Parserevent('endfile',array (  'endfile' => '../examples/extensions1.conf',)),
    );
  }
}
