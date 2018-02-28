<?php
/**
* TestLink Open Source Project - http://testlink.sourceforge.net/ 
* $Id: mantis.cfg.php,v 1.7 2007/03/05 18:22:04 franciscom Exp $ 
* 
* Constants used throughout TestLink are defined within this file
* they should be changed for your environment
* 
* 20051229 - scs - added DEFINE for the DB-Type
*/

//Set the bug tracking system Interface to MANTIS 0.19.1
//also tested with MANTIS 1.0.0.a3

/** The DB host to use when connecting to the mantis db */
define('BUG_TRACK_DB_HOST', '116.193.163.131');

/** The name of the database that contains the mantis tables */
define('BUG_TRACK_DB_NAME', 'gablian_bugtracker');

/** The DB type being used by mantis 
values: mysql,mssql,postgres
*/
define('BUG_TRACK_DB_TYPE', 'mysql');

/** The DB password to use for connecting to the mantis db */
define('BUG_TRACK_DB_USER', 'root');
define('BUG_TRACK_DB_PASS', 'Password');


/* link of the web server for mantis*/
/* anonymous login into mantis has to be turned on, and a mantis user has to created with viewer rights to all public projects
/* Change the following in your mantis config_inc.php (replace dummy with your created user)
 	# --- anonymous login -----------
	# Allow anonymous login
	$g_allow_anonymous_login	= ON;
	$g_anonymous_account		= 'dummy';
*/
define('BUG_TRACK_HREF', "http://116.193.163.130/gabilanmantis/view.php?id="); 

/** link to the bugtracking system, for entering new bugs */
define('BUG_TRACK_ENTER_BUG_HREF',"http://116.193.163.130/gabilanmantis");
?>
