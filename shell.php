<?php
	include_once 'Parser.php';
	
	$script = file_get_contents($argv[1]);
		
	try {
		$result = Parser::next_action($script)->execute_action();
		if ($result) {
			print "Script successfully executed.\n"; 
		} else {
			print "Script execution failed.\n";
		}
	} catch (ParseException $e) {
		print $e->getMessage() . "\n";
	} catch (PatternException $e) {
		print $e->getMessage() . "\n";
	}
?>