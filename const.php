<?php

namespace Cerceau;

/**
 * Directories
 */
define( 'ROOT_WWW', ROOT .'www/' );

define( 'CONFIG_DIR', ROOT .'config/' );
define( 'LOGS_DIR', ROOT .'logs/' );
define( 'SCRIPTS_DIR', ROOT .'scripts/' );
define( 'TESTS_DIR', ROOT .'tests/' );

define( 'LIB_DIR', ROOT .'lib/' );
define( 'EXTERNAL_DIR', ROOT .'external/' );
define( 'LOCALES_DIR', ROOT .'i18n/' );
define( 'SQL_DIR', ROOT .'database/' );

define( 'CSS_DIR', 'css/' );
define( 'LESS_DIR', 'less/' );
define( 'JS_DIR', 'js/' );

define( 'LOCALES_JS_DIR', ROOT_WWW . JS_DIR .'i18n/' );

/**
 * ReCaptcha
 * https://www.google.com/recaptcha/admin/site?siteid=315780194
 */
define( 'RECAPTCHA_PUBLIC_KEY',  '6LdibNISAAAAAFO_9l2F6L1U4EPNC6a3TQTmrE4G' );
define( 'RECAPTCHA_PRIVATE_KEY', '6LdibNISAAAAAML5Guy8UNZi6eRbl1TaWFMOuUxM' );
define( 'RECAPTCHA_THEME', 'clean' );

/**
 * Constants
 */
const ALPHABET = 'абвгдеёжзийклмнопрстуфхцчшщъыьэюяАБВГДЕЁЖЗИЙКЛМНОПРСТУФХЦЧШЩЪЫЬЭЮЯ';
