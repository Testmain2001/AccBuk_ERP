<?php

function CheckCreateMenu(){
	if(in_array(basename($_SERVER['REQUEST_URI'], '?' . $_SERVER['QUERY_STRING']), $_SESSION['Ck_User_create_array'] )) {
		return 1;
	}else{
		return 0;
	}
}
function CheckEditMenu(){
	if(in_array(basename($_SERVER['REQUEST_URI'], '?' . $_SERVER['QUERY_STRING']), $_SESSION['Ck_User_edit_array'])){
		return 1;
	}else{
		return 0;
	}
}

function CheckEditMenuforajax($uri)
{
	if(in_array(basename($uri, '?' . $_SERVER['QUERY_STRING']), $_SESSION['Ck_User_edit_array'])){
		return 1;
	}else{
		return 0;
	}
}

function CheckEditMenuforindex($uri)
{
	if(in_array(basename($uri, '?' . $_SERVER['QUERY_STRING']), $_SESSION['Ck_User_edit_array'])){
		return 1;
	}else{
		return 0;
	}
}

function CheckDeleteMenu(){
	if(in_array(basename($_SERVER['REQUEST_URI'], '?' . $_SERVER['QUERY_STRING']), $_SESSION['Ck_User_delete_array'])){
		return 1;
	}else{
		return 0;
	}
}

function CheckViewMenu(){
	if(in_array(basename($_SERVER['REQUEST_URI'], '?' . $_SERVER['QUERY_STRING']), $_SESSION['Ck_User_view_array'])){
		return 1;
	}else{
		return 0;
	}
}
?>