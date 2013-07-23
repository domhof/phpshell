<?php
    interface Action {		
    	// returns true on success, false on failure.
    	function execute($arguments);
    }
?>