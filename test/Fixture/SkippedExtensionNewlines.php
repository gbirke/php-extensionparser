<?php
class Fixture_SkippedExtensionNewlines {

  public $fixtures = array();

  function __construct(){
    $this->fixtures = array(
      new Parserevent('newline',array (  'newline' => '',  'number' => 2,)),
      new Parserevent('newline',array (  'newline' => 'exten => 1001,1,Macro(tl-userextension,SIP/1001,1001)',  'number' => 3,)),
      new Parserevent('extension',array (  'extension' => '1001',)),
      new Parserevent('priority',array (  'priority' => '1',)),
      new Parserevent('application',array (  'application' => 'Macro',)),
      new Parserevent('parameter',array (  'parameter' => 'tl-userextension',  'position' => 1,)),
      new Parserevent('parameter',array (  'parameter' => 'SIP/1001',  'position' => 2,)),
      new Parserevent('parameter',array (  'parameter' => '1001',  'position' => 3,)),
      new Parserevent('newline',array (  'newline' => 'exten => 1001,hint,SIP/1001',  'number' => 4,)),
      new Parserevent('newline',array (  'newline' => 'exten => 1002,1,Macro(tl-userextension,SIP/1002,1002)',  'number' => 5,)),
      new Parserevent('extension',array (  'extension' => '1002',)),
      new Parserevent('priority',array (  'priority' => '1',)),
      new Parserevent('application',array (  'application' => 'Macro',)),
      new Parserevent('parameter',array (  'parameter' => 'tl-userextension',  'position' => 1,)),
      new Parserevent('parameter',array (  'parameter' => 'SIP/1002',  'position' => 2,)),
      new Parserevent('parameter',array (  'parameter' => '1002',  'position' => 3,)),
      new Parserevent('newline',array (  'newline' => 'exten => 1002,hint,SIP/1002',  'number' => 6,)),
      new Parserevent('newline',array (  'newline' => 'exten => 1003,1,Macro(tl-userextension,SIP/1003,1003)',  'number' => 7,)),
      new Parserevent('extension',array (  'extension' => '1003',)),
      new Parserevent('priority',array (  'priority' => '1',)),
      new Parserevent('application',array (  'application' => 'Macro',)),
      new Parserevent('parameter',array (  'parameter' => 'tl-userextension',  'position' => 1,)),
      new Parserevent('parameter',array (  'parameter' => 'SIP/1003',  'position' => 2,)),
      new Parserevent('parameter',array (  'parameter' => '1003',  'position' => 3,)),
      new Parserevent('newline',array (  'newline' => 'exten => 1003,hint,SIP/1003',  'number' => 8,)),
      new Parserevent('newline',array (  'newline' => '',  'number' => 9,)),
      new Parserevent('newline',array (  'newline' => '[feature-extensions]',  'number' => 10,)),
      new Parserevent('endfile',array (  'endfile' => '..\\examples\\extensions2.include',)),
    );
  }
}
