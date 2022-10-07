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

    public function about_callback(){
        return require_once("$this->plugin_path/templates/admin/about.php");
    }

}