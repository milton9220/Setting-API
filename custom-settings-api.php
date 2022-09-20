<?php
/**
 * @package MiltonPlugin
 */
/****
Plugin Name:Custom Settings Api
Plugin URI:
Author: Milton
Author URI:
Description: Our 2019 default theme is designed to show off the power of the block editor. It features custom styles for all the default blocks, and is built so that what you see in the editor looks like what you'll see on your website. Twenty Nineteen is designed to be adaptable to a wide range of websites, whether you’re running a photo blog, launching a new business, or supporting a non-profit. Featuring ample whitespace and modern sans-serif headlines paired with classic serif body text, it's built to be beautiful on all screen sizes.
Version: 1.0
License: GNU General Public License v2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Text Domain:settings-api
******/



if(! defined('ABSPATH')){
    die;
}

if(file_exists(dirname(__FILE__).'/vendor/autoload.php')){
    require_once dirname(__FILE__).'/vendor/autoload.php';
}

if(class_exists('SettingsApi\\Inc\\Init')){
    SettingsApi\Inc\Init::register_services();
}