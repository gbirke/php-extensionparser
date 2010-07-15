<?php
class Fixture_SkippedExtensionNewlines {

  public $fixtures = array();

  function __construct(){
    $this->fixtures = array(
      new Dialplan_Parser_Event('newline',array (  'newline' => '',  'number' => 2,)),
      new Dialplan_Parser_Event('newline',array (  'newline' => 'exten => 1001,1,Macro(tl-userextension,SIP/1001,1001)',  'number' => 3,)),
      new Dialplan_Parser_Event('extension',array (  'extension' => '1001',)),
      new Dialplan_Parser_Event('priority',array (  'priority' => '1',)),
      new Dialplan_Parser_Event('application',array (  'application' => 'Macro',)),
      new Dialplan_Parser_Event('parameter',array (  'parameter' => 'tl-userextension',  'position' => 1,)),
      new Dialplan_Parser_Event('parameter',array (  'parameter' => 'SIP/1001',  'position' => 2,)),
      new Dialplan_Parser_Event('parameter',array (  'parameter' => '1001',  'position' => 3,)),
      new Dialplan_Parser_Event('newline',array (  'newline' => 'exten => 1001,hint,SIP/1001',  'number' => 4,)),
      new Dialplan_Parser_Event('newline',array (  'newline' => 'exten => 1002,1,Macro(tl-userextension,SIP/1002,1002)',  'number' => 5,)),
      new Dialplan_Parser_Event('extension',array (  'extension' => '1002',)),
      new Dialplan_Parser_Event('priority',array (  'priority' => '1',)),
      new Dialplan_Parser_Event('application',array (  'application' => 'Macro',)),
      new Dialplan_Parser_Event('parameter',array (  'parameter' => 'tl-userextension',  'position' => 1,)),
      new Dialplan_Parser_Event('parameter',array (  'parameter' => 'SIP/1002',  'position' => 2,)),
      new Dialplan_Parser_Event('parameter',array (  'parameter' => '1002',  'position' => 3,)),
      new Dialplan_Parser_Event('newline',array (  'newline' => 'exten => 1002,hint,SIP/1002',  'number' => 6,)),
      new Dialplan_Parser_Event('newline',array (  'newline' => 'exten => 1003,1,Macro(tl-userextension,SIP/1003,1003)',  'number' => 7,)),
      new Dialplan_Parser_Event('extension',array (  'extension' => '1003',)),
      new Dialplan_Parser_Event('priority',array (  'priority' => '1',)),
      new Dialplan_Parser_Event('application',array (  'application' => 'Macro',)),
      new Dialplan_Parser_Event('parameter',array (  'parameter' => 'tl-userextension',  'position' => 1,)),
      new Dialplan_Parser_Event('parameter',array (  'parameter' => 'SIP/1003',  'position' => 2,)),
      new Dialplan_Parser_Event('parameter',array (  'parameter' => '1003',  'position' => 3,)),
      new Dialplan_Parser_Event('newline',array (  'newline' => 'exten => 1003,hint,SIP/1003',  'number' => 8,)),
      new Dialplan_Parser_Event('newline',array (  'newline' => '',  'number' => 9,)),
      new Dialplan_Parser_Event('newline',array (  'newline' => '[feature-extensions]',  'number' => 10,)),
      new Dialplan_Parser_Event('endfile',array (  'endfile' => '..\\examples\\extensions2.include',)),
    );
  }
}
