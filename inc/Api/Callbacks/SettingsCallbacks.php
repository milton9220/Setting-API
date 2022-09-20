<?php
/**
 * @package MiltonPlugin
 */
namespace SettingsApi\Inc\Api\Callbacks;

use SettingsApi\Inc\Api\SettingsApi;
class SettingsCallbacks{
    private $settings_api;

    public function __construct(){
        $this->settings_api=new SettingsApi;
    }
    public function cm_settings($value){
        echo $value;
        echo '<div class="wrap">';

        $this->settings_api->show_navigation();
        $this->settings_api->show_forms();

        echo '</div>';
    }

}