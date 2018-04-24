<form method="post"
	  action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
    <table>
        <tr>
            <td> <label for="username"> User name: </label> </td>
            <td> <input type="text" name="username" id="username" 
            			class= "<?php
            			             if (isset($GLOBALS['FORM_ERRORS']['username']))
            			                 echo 'error';
            			?>"
            			value="<?php 
            			             if (isset($_POST['username']))
                                        echo htmlspecialchars($_POST['username']); 
            			        ?>"/></td></tr> 
        <tr>
            <td> <label for="password1"> Password: </label> </td>
            <td> <input type="password" name="password1" id="password1" 
                 	class= "<?php
            	               if (isset($GLOBALS['FORM_ERRORS']['password']))
            	                   echo 'error';
            			     ?>"  
            	    value=""/> </td></tr>
        <tr>
        	<td> <label for="password2"> Repeat Password: </label> </td>
        	<td> <input type="password" name="password2" id="password2" value=""/></td></tr>
        <tr>
        	<td><label for="email"> Email Address: </label></td>
        	<td><input type="text" name="email" id="email" 
             	class= "<?php
        	               if (isset($GLOBALS['FORM_ERRORS']['email']))
        	                   echo 'error';
        			     ?>" 
        		value="<?php if (isset($_POST['email']))
                    echo htmlspecialchars($_POST['email']);?>"/> </td></tr>
        <tr>        	
        	<td></td>
        	<td><img src="img/captcha.php?nocache=<?php echo time();?>"/></td><tr/>
        <tr>
        		<td><label for="captcha"> Enter image text: </label> </td>
        		<td><input type="text" name="captcha" id="captcha"
        		           class= "<?php
            	                       if (isset($GLOBALS['FORM_ERRORS']['captcha']))
            	                           echo 'error';
            			            ?>" 
            			   value = ""/></td></tr>
        <tr>
        	<td></td>
        	<td><input type="submit" value="Sign Up"/></td>
        	<td><input type="hidden" name="submitted" value="1"/></td></tr>
    </table>
</form >