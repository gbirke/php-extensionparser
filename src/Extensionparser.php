<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Extensionparser
 *
 * @author birke
 */
class Extensionparser {
  
  /**
   * A list of observers, sorted by event name
   * 
   * @var array 
   */
  protected $_observers = array();
  protected $_line = 1;

  /**
   *
   * @var EventDispatcher
   */
  protected $_eventDispatcher;

  public function __construct($eventDispatcher = null) {
    if($eventDispatcher)
      $this->_eventDispatcher = $eventDispatcher;
    else
      $this->_eventDispatcher = new EventDispatcher();
  }

  function parse($resourceName) {
    $fh = fopen($resourceName, 'r');
    if(!$fh) {
      throw new Exception("Cound not open $resourceName");
    }
    $this->notify(new Parserevent('startfile', array('name' => $resourceName)));
    while(!feof($fh)) {
      $line = fgets($fh, 2048);
      $this->_parseLine($line);
      $this->_line++;
    }
    fclose($fh);
    $this->notify(new Parserevent('endfile', array('name' => $resourceName)));
  }

  protected function _parseLine($line) {
    $this->notify(new Parserevent('newline', array('text' => $line, 'number' => $this->_line)));
    $line = trim($line);
    if(!$line)
      return;
    // Match Contexts
    if(preg_match('/^\\[([a-z0-9A-Z_\-]+)\]\s*(.*)$/', $line, $matches)) {
      $this->notify(new Parserevent('context', array('name' => $matches[1])));
      if(!empty($matches[2])) {
        $this->_parseComment($matches[2], "context");
      }
    }
    // Match Extensions
    elseif (preg_match('/^exten\s*=>\s*(.+)/', $line, $matches)) {
      $this->_parseExtension($matches[1]);
    }
    // Match single-line-comments
    elseif ($line[0] == ';') {
      $this->notify(new Parserevent('comment', array('text' => substr($line, 1), 'context' => "line")));
    }

    // TODO: match global variables in default/ global context
    // TODO: Match #include and other directives
    // TODO: Throw Exception on unexpected input

  }

  protected function _parseExtension($line) {
    list($exten, $rest) = explode(',', $line, 2);
    $exten = trim($exten);
    if(preg_match('/^([a-zA-Z0-9#*]+|_[a-zA-Z0-9#*.\\[\\]!]+)$/', $exten)) {
      $this->notify(new Parserevent('extension', array('value' => $exten)));
      $this->_parsePriority($rest);
    }
    else {
      throw new ParserSyntaxErrorException("Invalid extension: $exten", $this->_line);
    }
  }

  protected function _parsePriority($line) {
    list($priority, $rest) = explode(',', $line, 2);
    $priority = trim($priority);
    if(preg_match('/^([0-9]+|n(?:\+[0-9]+)?|s|hint)$/', $priority)) {
      // TODO: Parse labels
      $this->notify(new Parserevent('priority', array('value' => $priority)));
      if($priority == 'hint') {
        $this->_parseHintChannel($rest);
      }
      else {
        $this->_parseApplication($rest);
      }
    }
    else {
      throw new ParserSyntaxErrorException("Invalid priority: $priority", $this->_line);
    }
  }

  protected function _parseHintChannel($channel) {
    if(preg_match('@([A-Za-z0-9]+/[^; ]+)\s*(.*)$@', trim($channel), $matches)) {
      $this->notify(new Parserevent('hintchannel', array('channel' => $matches[1])));
      if(!empty($matches[2])) {
        $this->_parseComment($matches[2], "hint");
      }
    }
    else {
      throw new ParserSyntaxErrorException("Invalid channel: $channel", $this->_line);
    }
  }

  protected function _parseApplication($line) {
    if(preg_match('/^([A-Za-z0-9]+)\((.+)/', trim($line), $matches )) {
      $this->notify(new Parserevent('application', array('name' => $matches[1])));
      $rest = $this->_parseParams($matches[2]);
      $this->_parseComment($rest, "extension");
    }
    else {
      throw new ParserSyntaxErrorException("Invalid Application: $line", $this->_line);
    }
  }

  protected function _parseComment($line, $context = "line") {
    $comment = trim($line);
    if(strlen($comment) > 0 && $comment[0] == ';') {
      $this->notify(new Parserevent('comment', array('text' => substr($comment, 1), "context" => $context)));
    }
  }

  protected function _parseParams($line) {
    $len = strlen($line);
    $pos = 0;
    $openBraces = 0;
    $inQuotes = false;
    $paramcount = 0;
    while($pos < $len) {
      $c = $line[$pos];
      switch($c) {
        case ")":
          if($inQuotes)
            break;
          elseif($openBraces == 0) {
            if($pos > 0 || $paramcount > 0) {
              $this->notify(new Parserevent('parameter', array('value' => substr($line, 0, $pos))));
            }
            return substr($line, $pos + 1);
          }
          else {
            $openBraces--;
          }
          break;
        case ",":
          if(!$inQuotes) {
            $paramcount++;
            $this->notify(new Parserevent('parameter', array('value' => substr($line, 0, $pos))));
            $line = substr($line, $pos + 1);
            $len = strlen($line);
            $pos = -1; // Must be -1 so $pos will be 0 after $pos++
          }
          break;
        case "(":
          if(!$inQuotes) {
            $openBraces++;
          }
          break;
      }
      $pos++;
    }
    throw new ParserSyntaxErrorException("Unclosed brace", $this->_line);
  }

  /**
   *
   * @param IExtensionObserver $observer
   * @param mixed $eventname A string of array of event names.
   * @return Extensionparser
   */
  public function addObserver(IExtensionObserver $observer, $eventname = 'ALL') {
    $this->_eventDispatcher->addObserver($observer, $eventname);
  }

  public function removeObserver(IExtensionObserver $observer, $eventname = 'ALL') {
    $this->_eventDispatcher->removeObserver($observer, $eventname);
  }

  public function notify(Parserevent $notification) {
    $this->_eventDispatcher->notify($notification);
  }



}
?>
