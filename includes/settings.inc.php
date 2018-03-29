<?php

define('MOE_DB_CONN', 'mysql:host=127.0.0.1;dbname=pomf');
define('MOE_DB_IP', '127.0.0.1');
define('MOE_DB_NAME', 'pomf');

/**
 * PDO database login credentials
 */

/** @param string POMF_DB_NAME Database username */
define('MOE_DB_USER', 'pomf');
/** @param string POMF_DB_PASS Database password */
define('MOE_DB_PASS', 'oYlRw5crmMLoFKxF');

/**
 * 'POMF_FILES_ROOT' - Where to store the files
 * 'LENGTH' - Invite key length
 * 'POMF_NAME' - Pomf instance name
 * 'POMF_ADDRESS' - Pomf address/[sub]domain
 * 'POMF_URL' - URL/[sub]domain to host files from
 * 'MOE_URL' - URL for moe
 * 'ID_CHARSET' - set of characters to use for file IDs
 */
define('POMF_FILES_ROOT', '/media/DISK2/www/pomfe.co/html/files/');
define('LENGTH', 32);
define('POMF_NAME', 'Pomfe.co');
define('POMF_ADDRESS', 'pomfe.co');
define('POMF_URL', 'https://a.pomfe.co/');
define('MOE_URL', 'https://pomfe.co/panel');
define('ID_CHARSET', '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ');

/** SMTP email settings */
define('SMTPD_HOST', '');
define('SMTPD_USERNAME', '');
define('SMTPD_PASSWORD', '');

/** Cloudflare creds for removing deleted files from their cache */
define('CF_EMAIL', 'votton@auroware.com');
define('CF_TOKEN', '34b01d8965162624e2906b80891348e961d50');
