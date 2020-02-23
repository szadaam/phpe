<?php

// 24 hours format

// when the application starts counting

define('DAY_START', '10');

// !!!: egyelőre nincs időmérés ezt itt be kell fejezni!

define('MAX_FILE_UPLOAD_SIZE', 50 * 100000); // megabytes
// MAINTENANCE

/*
 * If we set up our projects with cronjobs we don't need to use this method
 * Otherwise it will take care of our tables automatically
 * 0: tables are maintained by internal php scripts
 * 1: tables are maintained by external php scripts defined by crontab
 */

//define('CRONJOBS', 0);
// LAYOUT
//$card_colors = ['primary', 'success', 'info', 'warning', 'danger', 'secondary'];
//define('CARD_COLORS', serialize($card_colors));
// ERRORS
define('ERROR_MSG', '<h1>Sorry! Something went wrong!</h1>');
