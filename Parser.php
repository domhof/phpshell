<?php
	include_once 'action/ActionExecutor.php';
	include_once 'Pattern.php';
	include_once 'ParseException.php';
	
	class Parser {
		static public function next_action($script) {
			$script = trim($script);
			$length = strlen($script);
			$pos = 0;
		
			$name = "";
			$arguments = array();
			
			while($pos < $length && $script[$pos] != "(") {
				$name .= $script[$pos];
				$pos++;
			}
			$name = trim($name);
			
			$buffer = "";
			$brackets = 1; // initial bracket
			while(++$pos < $length) {
				$c = $script[$pos];				
				switch ($c) {
					case "(":
						$brackets++;
						$buffer .= $c;
						break;
					case ")":
						$brackets--;
						if ($brackets == 0) { // initial bracket closed
							$buffer = trim($buffer);
							if (!empty($buffer)) {
								array_push($arguments, trim($buffer));
								$buffer = "";
								break 2;
							} elseif (!empty($arguments)) throw new ParseException("Syntax error: Empty argument."); // "," followed by ")"
						}
						$buffer .= $c;
						break;
					case ",":
						if ($brackets == 1) { // argument delimiter for the action found
							if (empty($buffer)) throw new ParseException();
							array_push($arguments, trim($buffer));
							$buffer = "";
						} else {
							$buffer .= $c;
						}
						break;
					case "\\": // escape character
						if (++$pos < $lenth) $buffer .= $script[$pos];
						break;
					default:
 						$buffer .= $c;
						break;
				}
			}
			if ($brackets != 0) throw new ParseException("Syntax error: Brackets mismatch. $brackets open bracket(s).");
			
			return new ActionExecutor($name,$arguments);
		}

		static public function next_pattern($script) {
			$script = trim($script);
			$length = strlen($script);
			$pos = 0;
		
			$pattern_string = "";
			$arguments = array();
			
			if ($script[$pos] != '"') throw new ParseException();
			 
			while(++$pos < $length && $script[$pos] != '"') {
				$pattern_string .= $script[$pos];
			}
			
			return new Pattern(trim($pattern_string));
		}
	}
	
?>
