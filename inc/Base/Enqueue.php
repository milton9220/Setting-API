<?php
/**
 * @package MiltonPlugin
 */
namespace SettingsApi\Inc\Base;

use SettingsApi\Inc\Base\BaseController;

class Enqueue extends BaseController{
    public function register(){
        add_action('admin_enqueue_scripts',array($this,'enqueue'));
    }
    public function enqueue(){
        wp_enqueue_style( 'myplugin-style', $this->plugin_url. 'assets/css/style.css');
        wp_enqueue_style( 'wp-color-picker' );

        wp_enqueue_media();


        wp_enqueue_script( 'jquery' );

        wp_enqueue_script('myplugin-script', $this->plugin_url. 'assets/js/app.js',array('jquery','wp-color-picker'));
    }
 }