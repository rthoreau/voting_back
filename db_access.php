<?php
if (true || !local()) {
	define('HOST', '');
	define('USERNAME', '');
	define('PASSWORD', '');
	define('DATABASE', '');
} else {
	define('HOST', '');
	define('USERNAME', '');
	define('PASSWORD', '');
	define('DATABASE', '');
}
define('CHECK', '');
define('SALT', '');
define('TEMP_TOKEN_NAME', '');
define('TEMP_TOKEN', '');

function headers() {
}

function password_encrypt($password) {
	return $password;
}
function token_gen($length) {
    return '';
}