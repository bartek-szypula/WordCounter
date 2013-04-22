<?php

function __autoload($className)
{
	require 'class/' . $className . '.class.php';
}

//----------------------------------------------------------------
// Funkcja zwracająca wartości pól z poprzednio wysłanego formularza
//----------------------------------------------------------------
function old($key, $default = '')
{
	if( !empty($_REQUEST[$key]) )
	{
		return htmlspecialchars($_REQUEST[$key]);
	}

	return $default;
}