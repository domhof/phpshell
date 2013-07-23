<?php
	include_once("Action.php");
	include_once("Parser.php");
	
	// runs all arguments as actions
	class SetAction implements Action {
		public function execute($arguments) {
			if (count($arguments) != 2) throw new ActionException("Action 'set' requires 2 arguments but " . count($arguments) . " were/was given.");
			
			$pattern = Parser::next_pattern($arguments[0]);
			$pattern->eval_and_push_pattern();
			
			while ($pattern->has_next_file()) {
				if (!Parser::next_action($arguments[1])->execute_action()) {
					return FALSE;
				}
				$pattern->pop_file();
			}
			
			return TRUE;
		}
	}
?>