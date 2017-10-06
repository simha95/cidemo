<?php
/**
 * Description of Installation Loader
 *
 * @category Installer
 *
 * @package installer
 *
 * @module Installer
 *
 * @class installer.php
 *
 * @path installer.php
 *
 * @version 4.0
 *
 * @author CIT Dev Team
 *
 * @since 08.04.2016
 */
session_start();

error_reporting(0);

defined('DS') OR define('DS', DIRECTORY_SEPARATOR);

$site_path = dirname(__FILE__) . DS;

$site_url = 'http://' . $_SERVER['HTTP_HOST'] . str_replace(basename($_SERVER['SCRIPT_NAME']), '', $_SERVER['SCRIPT_NAME']);

$loading_url = $site_url . "installer/";

include_once("installer/installer_settings.php");

include_once("installer/installer_functions.php");

$install_step = (isset($_REQUEST['step']) && $_REQUEST['step'] != "") ? $_REQUEST['step'] : 1;

switch ($install_step) {

    case 'action':

        include_once("installer/installation_actions.php");

        break;

    case 'requirements':

    case 1:
        
        $install_active_page = 1;

        include_once("installer/requirements_info.php");

        break;

    case 'verification':

    case 2:

        $install_active_page = 2;
        
        include_once("installer/requirements_checking.php");

        break;

    case 'database':

    case 3:

        $install_active_page = 3;
        
        include_once("installer/database_checking.php");

        break;

    case 'review':

    case 4:

        $install_active_page = 4;
        
        include_once("installer/database_review.php");

        break;

    case 'thanks' :

    case 5:

        $install_active_page = 5;
        
        include_once("installer/thanks.php");

        break;
}