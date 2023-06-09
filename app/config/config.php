<?php
//Database params
define('DB_HOST', 'localhost'); //Add your db host
define('DB_USER', 'root'); // Add your DB root
define('DB_PASS', ''); //Add your DB pass
define('DB_NAME', 'testdb'); //Add your DB Name

//APPROOT
define('APPROOT', dirname(dirname(__FILE__)));

//URLROOT (Dynamic links)
//here is where you specify your website url
define('URLROOT', 'http://www.phprox.com');

//Sitename
define('SITENAME', 'phprox');

//timezone
define('DTZ', new DateTimeZone('Europe/Amsterdam'));
