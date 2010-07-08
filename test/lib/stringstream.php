<?php
/**
 * String Stream Wrapper
 *
 * This file allows you to use a PHP string like
 * you would normally use a regular stream wrapper
 *
 * PHP5
 *
 * Created on Aug 7, 2008
 *
 * @package stringstream
 * @author Sam Moffatt <sam.moffatt@toowoombarc.qld.gov.au>
 * @author Toowoomba Regional Council Information Management Branch
 * @license GNU/GPL http://www.gnu.org/licenses/gpl.html
 * @copyright 2008 Toowoomba Regional Council/Sam Moffatt
 * @version SVN: $Id:$
 */

class StringStreamController {

	function &_getArray() {
		static $strings = Array();
		return $strings;
	}

	function createRef($reference, &$string) {
		$ref =& StringStreamController::_getArray();
		$ref[$reference] =& $string;
	}


	function &getRef($reference) {
		$ref =& StringStreamController::_getArray();
		if(isset($ref[$reference])) {
			return $ref[$reference];
		} else {
			$false = false;
			return $false;
		}
	}
}


class StringStream {
	var $_currentstring;

	var $_path;
	var $_mode;
	var $_options;
	var $_opened_path;
	var $_pos;
	var $_len;

	function stream_open($path, $mode, $options, &$opened_path) {
		$this->_currentstring = StringStreamController::getRef(str_replace('string://','',$path));
		if($this->_currentstring) {
			$this->_len = strlen($this->_currentstring);
			$this->_pos = 0;
			return true;
		} else {
			return false;
		}
	}

	function stream_stat() {
		return false;
	}

	function stream_read($count) {
		$result = substr($this->_currentstring, $this->_pos, $count);
		$this->_pos += $count;
		return $result;
	}

	function stream_write($data) {
		return strlen($data);
	}

	function stream_tell() {
		return $this->_pos;
	}

	function stream_eof() {
		if($this->_pos > $this->_len) return true;
		return false;
	}

	function stream_seek($offset, $whence) {
		//$whence: SEEK_SET, SEEK_CUR, SEEK_END
		if($offset > $this->_len) return false; // we can't seek beyond our len
		switch($whence) {
			case SEEK_SET:
				$this->_pos = $offset;
				break;
			case SEEK_CUR:
				if(($this->_pos + $offset) < $this->_len) {
					$this->_pos += $offset;
				} else return false;
				break;
			case SEEK_END:
				$this->_pos = $this->_len - $offset;
				break;
		}
		return true;
	}
}

stream_wrapper_register('string', 'StringStream') or die('Failed to register string stream');