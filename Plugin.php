<?php

namespace Kanboard\Plugin\PrivateTasks;

use Kanboard\Core\Plugin\Base;
use Kanboard\Core\Translator;
use Kanboard\Model\TaskModel;
use Kanboard\Core\Http\Route;
use Kanboard\Core\Security\Role;

class Plugin extends Base
{
    public function initialize()
    {
        // Access
        $this->projectAccessMap->add('TaskSecurityController', array('resetAll'), Role::APP_ADMIN);
        $this->projectAccessMap->add('TaskSecurityController', array('project', 'checkMeta','enablePrivateTasks','disablePrivateTasks'), Role::PROJECT_MANAGER);
        // Private Tasks Query
        $this->hook->on('formatter:board:query', function (\PicoDb\Table &$query) {
            $project_id = $_GET['project_id']; // Project ID
            if(in_array($project_id, json_decode($this->configModel->get('privateTasks_proj_priv_tasks'),true))){
                // Private tasks is enabled
                $userID = session_get('user')['id'];
                $userRoleInProject = $this->helper->projectRole->getProjectUserRole($project_id);
                if($userRoleInProject != "project-manager"){
                    $query->eq(TaskModel::TABLE.'.owner_id',$userID);
                }
            }
        });

        // Attach CSS
        $this->hook->on('template:layout:css', array('template' => 'plugins/PrivateTasks/Template/css/private-tasks.css'));
        // Settings Nav Bar
        $this->template->hook->attach('template:project:sidebar', 'privateTasks:project/sidebar');
        // Private Tasks Header Reminder
        $this->template->hook->attach('template:project-header:view-switcher','privateTasks:project/private-status-header',['security'=>in_array($_GET['project_id'], json_decode($this->configModel->get('privateTasks_proj_priv_tasks'),true))]);
    }

    public function onStartup()
    {
        Translator::load($this->languageModel->getCurrentLanguage(), __DIR__.'/Locale');
    }

    public function getPluginName()
    {
        return 'PrivateTasks';
    }

    public function getPluginDescription()
    {
        return t('Plugin to only show tasks assigned to you');
    }

    public function getPluginAuthor()
    {
        return 'Mark Ireland';
    }

    public function getPluginVersion()
    {
        return '1.0.0';
    }

    public function getPluginHomepage()
    {
        return 'https://github.com/moosethecoder/PrivateTasks';
    }
}

