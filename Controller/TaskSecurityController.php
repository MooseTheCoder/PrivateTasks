<?php

namespace Kanboard\Plugin\PrivateTasks\Controller;

use Kanboard\Controller\BaseController;

class TaskSecurityController extends BaseController{
    var $config_var = "privateTasks_proj_priv_tasks";
    public function project(){ // LOCKED TO ROLE::PROJECT_MANAGER
        $project = $this->getProject();
        $status = $this->checkMeta($project['id']);
        if(isset($_GET['security'])){
            if($_GET['security'] == "enable"){
                $status = $this->enablePrivateTasks($project['id']);
            }
            if($_GET['security'] == "disable"){
                $status = $this->disablePrivateTasks($project['id']);
            }

        }
        $this->response->html($this->helper->layout->project('privateTasks:project/security', ['title' => t('Task Security'),'project'=> $project,'security'=>$status]));
    }

    public function checkMeta($project_id){ // LOCKED TO ROLE::PROJECT_MANAGER
        if(!$this->configModel->exists($this->config_var)){
            $this->configModel->save([$this->config_var=>json_encode([])]);
            return false;
        }
        return in_array($project_id,json_decode($this->configModel->get($this->config_var),true));
    }

    private function enablePrivateTasks($project_id){ // LOCKED TO ROLE::PROJECT_MANAGER
        if(!$this->checkMeta($project_id)){
            $config = json_decode($this->configModel->get($this->config_var),true);
            $config[]= $project_id;
            $config = json_encode($config);
            $this->configModel->save([$this->config_var=>$config]);
            return true;
        }
    }

    private function disablePrivateTasks($project_id){ // LOCKED TO ROLE::PROJECT_MANAGER
        if($this->checkMeta($project_id)){
            $config = json_decode($this->configModel->get($this->config_var),true);
            unset($config[array_search($project_id,$config)]);
            $config = json_encode($config);
            $this->configModel->save([$this->config_var=>$config]);
            return false;
        }
    }

    public function resetAll(){ // Locked to ROLE::APP_ADMIN
        $this->configModel->remove($this->config_var);
        $this->configModel->save([$this->config_var,json_encode([])]);
        echo "All projects now have public tasks.";
    }
}