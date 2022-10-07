<?php
/**
 * @package MiltonPlugin
 */
namespace SettingsApi\Inc\Pages;

use \SettingsApi\Inc\Api\Callbacks\AdminCallbacks;

use \SettingsApi\Inc\Api\SettingsApi;

use \SettingsApi\Inc\Api\ConfigureSettings;

class Dashboard {
    public $settings;

    public $callbacks;

    public $sanitizeCallbacks;

    public $pages = array();

    public function register() {
        $this->settings = new SettingsApi();

        $this->callbacks = new AdminCallbacks();


        $this->setPages();


        $this->settings->addPages( $this->pages )->withSubPage( 'Dashboard' )->register();
    }

    public function setPages() {
        $this->pages = array(
            array(
                'page_title' => 'Custom Settings Api',
                'menu_title' => 'Cm Settings Api',
                'capability' => 'manage_options',
                'menu_slug'  => 'settings_api',
                'callback'   => array( ConfigureSettings::class, 'cm_settings_callback' ),
                'icon_url'   => 'dashicons-smiley',
                'position'   => '60',
            ),
        );
    }


}
