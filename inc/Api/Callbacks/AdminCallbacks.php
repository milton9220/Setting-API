<?php
/**
 * @package MiltonPlugin
 */
namespace SettingsApi\Inc\Api\Callbacks;

use \SettingsApi\Inc\Base\BaseController;

class AdminCallbacks extends BaseController{

    public function adminDashboard(){
        return require_once("$this->plugin_path/templates/admin/admin.php");
    }
    public function cm_settings(){
        return "Hello";
    }

}