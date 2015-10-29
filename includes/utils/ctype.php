<?php
/******************************************************************************
 *
 *	 COMPANY: Intelliants LLC
 *	 PROJECT: eSyndiCat Directory Software
 *	 VERSION: 3.3.0
 *	 LISENSE: http://www.esyndicat.com/license.html
 *	 http://www.esyndicat.com/
 *
 *	 This program is a commercial software and any kind of using it must agree 
 *	 to eSyndiCat Directory Software license.
 *
 *	 Link to eSyndiCat.com may not be removed from the software pages without
 *	 permission of eSyndiCat respective owners. This copyright notice may not
 *	 be removed from source code in any case.
 *
 *	 Copyright 2007-2013 Intelliants LLC
 *	 http://www.intelliants.com/
 *
 ******************************************************************************/
	
/**
 * PHP ctype compatibility functions.
 * 
 *
 */
    function ctype_digit($c) {
		return (string)(int)$c === $c && (int)$c > -1;
    }
    function ctype_graph($c) {
    		$x = (bool)preg_match("/\s/", $c);
    		return !$x;
    }
    function ctype_print($c) {
		$x = (bool)preg_match("/\s/", $c);
    		return !$x;
    }
    function ctype_space($c) {
        return (bool)preg_match("/^\s+$/i", $c);
    }     
    function ctype_alnum($c) {
		return (bool)preg_match("/^[a-z0-9]$/i",$c);
    }
    function ctype_alpha($c) {
		return (bool)preg_match("/^[a-z]$/i",$c);
    }
    function ctype_cntrl($c) {

    }
    function ctype_lower($c) {

    }
    function ctype_punct($c) {

    }
    function ctype_upper($c) {

    }
    function ctype_xdigit($c) {

    }
