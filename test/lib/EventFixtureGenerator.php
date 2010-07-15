<?php
/* 
 * 
 */

/**
 * EventFixtureGenerator creates a PHP fixture class from an extension file.
 *
 *
 * @author gbirke
 */
class EventFixtureGenerator implements IExtensionObserver {

  protected $_notifications = array();
  
  public function update($emitter, $notification) {
    $this->_notifications[] = $notification;
  }
  
  public function export($classname) {
    $code = "<?php\n";
    $code .= "class $classname {\n\n";
    $code .= "  public \$fixtures = array();\n\n";
    $code .= "  function __construct(){\n";
    $code .= "    \$this->fixtures = array(\n";
    foreach($this->_notifications as $n) {
      $code .= "      new Dialplan_Parser_Event('{$n->type}',".str_replace("\n", "", var_export($n->getProperties(), true))."),\n";
    }
    $code .= "    );\n";
    $code .= "  }\n";
    $code .= "}\n";
    return $code;
  }

  public function getNotificationTypes() {
    return array('ALL');
  }
}
?>
