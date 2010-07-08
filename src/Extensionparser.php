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
    if(preg_match('/^\\s*\[([a-z0-9A-Z_\-]+)\]\s*(?:;(.*))?/', $line, $matches)) {
      $this->notify(new Parserevent('context', array('name' => $matches[1])));
      if(!empty($matches[2])) {
        $this->notify(new Parserevent('comment', array('text' => $matches[2])));
      }
    }
    elseif (preg_match('/^\s*exten\s*=>\s*(.+)/', $line, $matches)) {
      $this->_parseExtension($matches[1]);
    }
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
    if(preg_match('@([A-Za-z0-9]+/[^; ]+)(?:;(.*))?$@', trim($channel), $matches)) {
      $this->notify(new Parserevent('hintchannel', array('channel' => $matches[1])));
      if(!empty($matches[2])) {
        $this->notify(new Parserevent('comment', array('text' => $matches[2])));
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
    }
    else {
      throw new ParserSyntaxErrorException("Invalid Application: $line", $this->_line);
    }
  }

  protected function _parseParams($line) {
    $len = strlen($line);
    $pos = 0;
    $openBraces = 0;
    $openSquareBraces = 0;
    $openCurlyBraces = 0;
    $inQuotes = 0;
    while($pos < $len) {
      $c = $line[$pos];
      switch($c) {
        case ")":
          if($inQuotes)
            break;
          elseif($openBraces == 0) {
            if($pos > 0)
              $this->notify(new Parserevent('parameter', array('value' => substr($line, 0, $pos - 1))));
            return substr($line, $pos);
          }
          else {
            $openBraces--;
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
   */
  public function addObserver(IExtensionObserver $observer, $eventname = 'ALL') {
    if(is_string($eventname))
      $eventname = array($eventname);
    foreach($eventname as $evt) {
      if(empty($this->_observers[$evt])) {
        $this->_observers[$evt] = new SplObjectStorage();
      }
      $this->_observers[$evt]->attach($observer);
    }
  }

  public function removeObserver(IExtensionObserver $observer, $eventname = 'ALL') {
    if(is_string($eventname)) {
      if($eventname == 'ALL') {
        $eventname = array_keys($this->_observers);
      }
      else {
        $eventname = array($eventname);
      }
    }
    foreach($eventname as $evt) {
      if(!empty($this->_observers[$evt])) {
        $this->_observers[$evt]->detach($observer);
      }
    }
  }

  public function notify(Parserevent $notification) {
    $eventnames = array('ALL', $notification->type);
    foreach($eventnames as $evt) {
      if(!empty($this->_observers[$evt])) {
        foreach($this->_observers[$evt] as $observer) {
          $observer->update($this, $notification);
          if($notification->notificationIsCanceled())
                  break 2;
        }
      }
    }
  }



}
?>