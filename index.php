<?php

///////////////////////////////////////////////////////////////////////////////
//MARKED BETWEEN THESE MUST BE CONFIGURED BEFORE RUNNING THE PROJECT
///////////////////////////////////////////////////////////////////////////////

/*
 * ****************************************
 * INSTALL TODO 
 * ****************************************
 * ---------------------------------------
 * Configure Paths
 * ---------------------------------------
 * 
 *  edit the following files:
 * 
 * .htaccess
 *  /config/paths-config.php
 * 
 * --------------------------------------
 * Set up Database
 * --------------------------------------
 * 
 * dump the database file to the mysql server:
 * 
 * /install/phpe_core.sql
 * 
 * edit the following files:
 * 
 * /config/mysql-config.php
 * 
 * --------------------------------------
 * Set up Mail Sending Service
 * --------------------------------------
 * 
 * edit the following files:
 * 
 * /config/mailer-config.php
 * 
 * --------------------------------------
 * Configure application
 * --------------------------------------
 * edit the following files:
 * 
 * /config/core-config.php
 * 
 * --------------------------------------
 * 
 * After successful installation the you can login with the
 * root user of the application
 * 
 * username: admin
 * password: password
 * --------------------------------------
 */

// loads the enviroment

require_once 'libraries/core/includes/loader-root.php';
require_once 'config/paths-config.php';

// loads the core library

require_once ABS_PATH . 'libraries/core/classes/autoload.php';

// initilaze

$system = new System();
$system->run();
