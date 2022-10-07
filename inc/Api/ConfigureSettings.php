<?php
/**
 * @package MiltonPlugin
 */
namespace SettingsApi\Inc\Api;

use \SettingsApi\Inc\Api\SettingsApi;
use  \SettingsApi\Inc\Api\Callbacks\SettingsCallbacks;
use \SettingsApi\Inc\Api\Callbacks\AdminCallbacks;

class ConfigureSettings{

    private static $settings_api;

    public $callbacks;

    private $subpages = array();

    public function register(){

        self::$settings_api=new SettingsApi();

        $this->callbacks = new AdminCallbacks();

        $this->settings_callbacks = new SettingsCallbacks();

        
        $this->set_settings_sections();
        
        $this->set_settings_fields();

        $this->setSubPages();


        self::$settings_api->addSubPages( $this->subpages )->register();

    }

    public function setSubPages() {
        $this->subpages = array(
            array(
                'parent_slug' => 'settings_api',
                'page_title'  => 'Settings',
                'menu_title'  => 'Settings',
                'capability'  => 'manage_options',
                'menu_slug'   => 'cm_settings',
                'callback'    => array( __CLASS__, 'cm_settings_callback' ),
            ),
            array(
                'parent_slug' => 'settings_api',
                'page_title'  => 'About',
                'menu_title'  => 'About',
                'capability'  => 'manage_options',
                'menu_slug'   => 'about',
                'callback'    => array( $this->callbacks, 'about_callback' ),
            ),
        );
    }

    function set_settings_sections() {
        $sections = array(
            array(
                'id'    => 'settings_api_basics',
                'title' => __( 'Basic Settings', 'settings-api' )
            ),
            array(
                'id'    => 'settings_api_advanced',
                'title' => __( 'Advanced Settings', 'settings-api' )
            )
        );
        self::$settings_api->set_sections( $sections );
    }
    public function set_settings_fields(){
        $settings_fields = array(
            'settings_api_basics' => array(
                array(
                    'name'              => 'text_val',
                    'label'             => __( 'Text Input', 'wedevs' ),
                    'desc'              => __( 'Text input description', 'wedevs' ),
                    'placeholder'       => __( 'Text Input placeholder', 'wedevs' ),
                    'type'              => 'text',
                    'default'           => 'Title',
                    'sanitize_callback' => 'sanitize_text_field'
                ),
            ),
            'settings_api_advanced' => array(
                array(
                    'name'    => 'multicheck',
                    'label'   => __( 'Multile checkbox', 'wedevs' ),
                    'desc'    => __( 'Multi checkbox description', 'wedevs' ),
                    'type'    => 'multicheck',
                    'default' => array('one' => 'one', 'four' => 'four'),
                    'options' => array(
                        'one'   => 'One',
                        'two'   => 'Two',
                        'three' => 'Three',
                        'four'  => 'Four'
                    )
                ),
            )
        );
        self::$settings_api->set_fields($settings_fields);
    }

    public static function cm_settings_callback(){
        echo '<div class="wrap">';

        self::$settings_api->show_navigation();
        self::$settings_api->show_forms();

        echo '</div>';
    }
    public function about_callback(){
        echo '<h2>About Us</h2>';
    }
}