<div class="form-errors">
	<h3>Data entry errors: </h3>
	<ul>
		<?php  
		// username error
		if ( isset($GLOBALS['FORM_ERRORS']['username']) && (strlen($GLOBALS['FORM_ERRORS']['username']) > 0) ) {
		    echo "<li>". $GLOBALS['FORM_ERRORS']['username'] ."</li>";
		}
		// password error
		if ( isset($GLOBALS['FORM_ERRORS']['password']) && (strlen($GLOBALS['FORM_ERRORS']['password']) > 0) ) {
		    echo "<li>". $GLOBALS['FORM_ERRORS']['password'] ."</li>";
		}
		// email address error
		if ( isset($GLOBALS['FORM_ERRORS']['email']) && (strlen($GLOBALS['FORM_ERRORS']['email']) > 0) ) {
		    echo "<li>". $GLOBALS['FORM_ERRORS']['email'] ."</li>";
		}
		
		// captcha error
		if ( isset($GLOBALS['FORM_ERRORS']['captcha']) && (strlen($GLOBALS['FORM_ERRORS']['captcha']) > 0) ) {
		    echo "<li>". $GLOBALS['FORM_ERRORS']['captcha'] ."</li>";
		}
		?>	
	</ul>	
</div>