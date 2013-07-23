<?php
	include_once("Action.php");
	include_once("Parser.php");
	
	// executes a unix command
	class CommandAction implements Action {
		public function execute($arguments) {
			if (count($arguments) != 1) throw new ActionException("Action 'command' requires 1 argument but " . count($arguments) . " were given.");

			$cmd = Pattern::replace_pattern_vars($arguments[0]);
			//$cmd .= ' 2>&1'; // no output on errors
			
			$output = null;
			$returnValue = -1;
			exec($cmd, $output, $returnValue);
		
			return ($returnValue == 0) ? true : false;
		}
	}
?>