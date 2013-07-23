<?php
	include_once("SequenceAction.php");
	include_once("CommandAction.php");
	include_once("SetAction.php");
	
	class ActionExecutor {
		public $name;
		public $arguments;
		
		public function ActionExecutor($name, $arguments) {
			$this->name = $name;
			$this->arguments = $arguments;
		}
		
		public function execute_action() {
			$class = ucfirst($this->name) . "Action";
				
			$a = new $class;
			return $a->execute($this->arguments);
		}
		
		public function __toString () {
			$str = $this->name . "(";
			$str .= implode(",", $this->arguments);
			$str .= ")";
			return $str;
		} 
	}
?>