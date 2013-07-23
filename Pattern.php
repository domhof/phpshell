<?php
	include_once 'PatternException.php';
	
    class Pattern {
    	static private $key_value_stack = array(); // current var_name => values assignment on top
		
    	private $pattern_string;
		private $cnt_keys = 0; // how much elements per file are being pushed to the stack
		private $cnt_filenames = 0; // number of filenames left
		
    	public function Pattern($pattern_string) {
    		$this->pattern_string = $pattern_string;
    	}
		
		public function eval_and_push_pattern() {
			$ls_pattern = preg_replace('/<[0-9a-zA-Z]+>/', '*', $this->pattern_string);
			exec('ls ' . $ls_pattern . ' 2>&1', $filenames, $returnValue);
			$this->cnt_filenames = count($filenames);
			
			preg_match_all('/<[0-9a-zA-Z]+>/', $this->pattern_string, $key_matches, PREG_PATTERN_ORDER);
			$this->cnt_keys = count($key_matches[0]);
			
			$file_pattern = str_replace('.','\.',$this->pattern_string);
			$file_pattern = preg_replace('/<[0-9a-zA-Z]+>/', '(.*)', $file_pattern);
			$file_pattern = '/^'.str_replace('/', '\\/', $file_pattern).'$/';
			$file_pattern .= 'U'; // non greedy
			
			$new_stack_elements = array(); // var_name => values
			foreach($filenames as $fn) {
				preg_match_all($file_pattern, $fn, $val_matches, PREG_PATTERN_ORDER);
				for ($i = 0; $i < $this->cnt_keys; $i++) {
					$key = $key_matches[0][$i];
					if ($new_stack_elements[$key] == null) $new_stack_elements[$key] = array();
					foreach($val_matches[$i+1] as $val) {
						array_push(self::$key_value_stack, array($key => $val));
					}
				}
			}
		}
		
		public function pop_file() {
			for ($i = 0; $i < $this->cnt_keys; $i++) {
				array_pop(self::$key_value_stack);
			}
			--$this->cnt_filenames;
		}
		
		public function has_next_file() {
			return ($this->cnt_filenames > 0) ? TRUE : FALSE;
		}
		
		// returns cartesian product of replacements
		static public function replace_pattern_vars($string) {
			$values = array();
			preg_match_all('/<[0-9a-zA-Z]+>/', $string, $key_matches, PREG_PATTERN_ORDER);
			foreach($key_matches[0] as $key) {
				$value = self::get_value_for_key($key);
				if ($value == null) throw new PatternException("Unmapped key '$key'!");
				$string = str_replace($key, $value, $string);
			}
			
			return $string;
		}
		
		static private function get_value_for_key($key) {
			for ($i=count(self::$key_value_stack); $i >= 0; $i--) {
				if (!empty(self::$key_value_stack[$i][$key])) return self::$key_value_stack[$i][$key];
			}
			return null;
		}
    }
?>