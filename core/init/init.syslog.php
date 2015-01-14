<?php
/**
 * Created by JetBrains PhpStorm.
 * User: pilotuser
 * Date: 21.09.12
 * Time: 13:05
 */

openlog('proxy-gate['.rand(10000, 999999).']', LOG_PID, LOG_LOCAL0);
syslog(LOG_DEBUG, 'Start proxy-gate gateway');



