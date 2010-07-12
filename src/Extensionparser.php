<?php
/* 
 * This file contains the Extensionparser class
 */

/**
 * Extensionparser is a class that parses an Asterisk extension file.
 *
 * Other classes can attach themselves als listeners to the parser. For each
 * "component" of the extension file (e.g. extension number, priority, application),
 * the parser creates a Parserevent object and sends that object to all attached
 * listeners.
 *
 * @author birke
 */
class Extensionparser implements IEventDispatcher {
  
  /**
   * The current line number
   * @var integer
   */
  protected $_line = 1;

  /**
   *
   * @var EventDispatcher
   */
  protected $_eventDispatcher;

  /**
   * Type of current parsed context (The context names "general" and "globals"
   * are handled differently than other contexts)
   * 
   * @var integer
   */
  protected $_ctx_type = self::CTX_DEFAULT;

  const CTX_DEFAULT = 1;
  const CTX_GENERAL = 2;
  const CTX_GLOBALS = 3;

  /**
   * Constructs the Parser object.
   *
   * If $eventDispatcher is null, an instance of EventDispatcher will be used.
   *
   * @param EventDispatcher $eventDispatcher
   */
  public function __construct($eventDispatcher = null) {
    if($eventDispatcher)
      $this->_eventDispatcher = $eventDispatcher;
    else
      $this->_eventDispatcher = new EventDispatcher();
  }

  /**
   * Read the selected resource line by line and notify all listeners with
   * Parserevent objects.
   *
   * @param string $resourceName A file name or a PHP stream URL
   * @return Extensionparser
   */
  function parse($resourceName) {
    $fh = fopen($resourceName, 'r');
    if(!$fh) {
      throw new ParserException("Cound not open $resourceName");
    }
    $this->notify($this, new Parserevent('startfile', array('startfile' => $resourceName)));
    while(!feof($fh)) {
      $line = fgets($fh, 2048);
      $this->_parseLine($line);
      $this->_line++;
    }
    fclose($fh);
    $this->notify($this, new Parserevent('endfile', array('endfile' => $resourceName)));
    return $this;
  }

  /**
   * Parse a single line.
   * @param string $line
   */
  protected function _parseLine($line) {
    $this->notify($this, new Parserevent('newline', array('newline' => $line, 'number' => $this->_line)));
    $line = trim($line);
    if(!$line)
      return;
    // Match Contexts
    if(preg_match('/^\\[([a-z0-9A-Z_\-]+)\]\s*(.*)$/', $line, $matches)) {
      if($matches[1] == 'general') {
        $this->_ctx_type = self::CTX_GENERAL;
        $this->notify($this, new Parserevent('generalsettings', array('generalsettings' => $matches[1])));
      }
      elseif ($matches['1'] == 'globals') {
        $this->_ctx_type = self::CTX_GLOBALS;
        $this->notify($this, new Parserevent('globalvariables', array('globalvariables' => $matches[1])));
      }
      else {
        $this->_ctx_type = self::CTX_DEFAULT;
        $this->notify($this, new Parserevent('context', array('context' => $matches[1])));
      }
      if(!empty($matches[2])) {
        $this->_parseComment($matches[2], "context");
      }
    }
    // Match Extensions
    elseif (preg_match('/^exten\s*=>\s*(.+)/', $line, $matches) && $this->_ctx_type = self::CTX_DEFAULT) {
      $this->_parseExtension($matches[1]);
    }
    // Match single-line-comments
    elseif ($line[0] == ';') {
      $this->notify($this, new Parserevent('comment', array('comment' => substr($line, 1), 'context' => "line")));
    }
    // Match including of other contexts
    elseif (preg_match('/^include\s*=>\s*([^;]+)(.*)/', $line, $matches)) {
      $this->notify($this, new Parserevent('include_context', array('include_context' => trim($matches[1]))));
      if(!empty($matches[2])) {
        $this->_parseComment($matches[2], "context");
      }
    }
    // Match including of other files
    elseif (preg_match('/^#include\s*([^;]+)(.*)/', $line, $matches)) {
      $this->notify($this, new Parserevent('include_file', array('include_file' => trim($matches[1]))));
      if(!empty($matches[2])) {
        $this->_parseComment($matches[2], "context");
      }
    }
    // Match assignments in [general] and [globals] context
    elseif(preg_match('/^([a-z0-9A-Z\-_]+)\s*=\s*([^;]*)(.*)/', $line, $matches)) {
      if($this->_ctx_type == self::CTX_GENERAL) {
        $this->notify($this, new ParserEvent('setting', array('setting' => $matches[1], 'value' => trim($matches[2]))));
      }
      elseif ($this->_ctx_type == self::CTX_GLOBALS) {
        $this->notify($this, new ParserEvent('global', array('global' => $matches[1], 'value' => trim($matches[2]))));
      }
      else {
        throw new ParserSyntaxErrorException("Invalid statement: $line", $this->_line);
      }
      if(!empty($matches[3])) {
        $this->_parseComment($matches[3], "setting");
      }
    }
    else {
      throw new ParserSyntaxErrorException("Invalid statement: $line", $this->_line);
    }

  }

  /**
   * Check if $line begins with a valid extension number pattern.
   * Send the rest of the line to _parsePriority
   * @param string $line
   */
  protected function _parseExtension($line) {
    list($exten, $rest) = explode(',', $line, 2);
    $exten = trim($exten);
    if(preg_match('/^([a-zA-Z0-9#*]+|_[a-zA-Z0-9#*.\\[\\]!]+)$/', $exten)) {
      $this->notify($this, new Parserevent('extension', array('extension' => $exten)));
      $this->_parsePriority($rest);
    }
    else {
      throw new ParserSyntaxErrorException("Invalid extension: $exten", $this->_line);
    }
  }

  /**
   * Check if $line begins with a valid priority pattern.
   * If the priority is "hint", send the rest of the line to _parseHintChannel.
   * Otherwise send it to _parseApplication
   * @param string $line
   */
  protected function _parsePriority($line) {
    list($priority, $rest) = explode(',', $line, 2);
    $priority = trim($priority);
    if(preg_match('/^(?:[0-9]+|n(?:\+[0-9]+)?|s|hint)(?:\(([a-zA-Z][a-zA-Z0-9\-_.]+)\))?$/', $priority, $matches)) {
      $this->notify($this, new Parserevent('priority', array('priority' => $priority)));
      if(!empty($matches[1])) {
        $this->notify($this, new Parserevent('label', array('label' => $matches[1])));
      }
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

  /**
   * Check $channel matches a channel pattern.
   * @param string $channel
   */
  protected function _parseHintChannel($channel) {
    if(preg_match('@([A-Za-z0-9]+/[^; ]+)\s*(.*)$@', trim($channel), $matches)) {
      $this->notify($this, new Parserevent('hintchannel', array('hintchannel' => $matches[1])));
      if(!empty($matches[2])) {
        $this->_parseComment($matches[2], "hint");
      }
    }
    else {
      throw new ParserSyntaxErrorException("Invalid channel: $channel", $this->_line);
    }
  }

  /**
   * Check if the beginning of $line matches a valid application pattern.
   * Send the rest of the line to _parseParams
   * @param string $line
   */
  protected function _parseApplication($line) {
    if(preg_match('/^([A-Za-z0-9]+)\((.+)/', trim($line), $matches )) {
      $this->notify($this, new Parserevent('application', array('application' => $matches[1])));
      $rest = $this->_parseParams($matches[2]);
      $this->_parseComment($rest, "extension");
    }
    else {
      throw new ParserSyntaxErrorException("Invalid Application: $line", $this->_line);
    }
  }

  /**
   * Check if $line begins with a semicolon.
   *
   * Several other parse methods use this. They can specify what the context
   * was for the comment.
   *
   * @param string $line
   * @param string $context
   */
  protected function _parseComment($line, $context = "line") {
    $comment = trim($line);
    if(strlen($comment) > 0 && $comment[0] == ';') {
      $this->notify($this, new Parserevent('comment', array('comment' => substr($comment, 1), "context" => $context)));
    }
  }

  /**
   * Parse application params separated by commas.
   *
   *
   * @todo Check for quotes
   * @param string $line string with params
   * @return Rest of the line after the closing brace of the param list
   */
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
              $this->notify($this, new Parserevent('parameter', array('parameter' => substr($line, 0, $pos), 'position' => $paramcount + 1)));
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
            $this->notify($this, new Parserevent('parameter', array('parameter' => substr($line, 0, $pos), 'position' => $paramcount)));
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
    return $this;
  }

  public function removeObserver(IExtensionObserver $observer, $eventname = 'ALL') {
    $this->_eventDispatcher->removeObserver($observer, $eventname);
    return $this;
  }

  public function notify($emitter, Parserevent $notification) {
    $this->_eventDispatcher->notify($emitter, $notification);
    return $this;
  }



}
?>
