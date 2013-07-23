<?php
	include_once("Action.php");
	include_once("Parser.php");
	
	// runs all arguments as actions
	class SequenceAction implements Action {
		public function execute($arguments) {
			foreach ($arguments as $arg) {
				if (!Parser::next_action($arg)->execute_action()) return false;
			}
			
			return true;
		}
	}
?>