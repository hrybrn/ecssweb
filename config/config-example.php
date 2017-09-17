<?php

//if the authentication files exist then you should authenticate
//otherwise go into a debug state
if(file_exists("/var/www/auth/lib/_autoload.php")){
	define("DEBUG", false);
} else {
	define("DEBUG", true);
}
