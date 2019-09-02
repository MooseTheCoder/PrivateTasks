<?php if ($this->helper->projectRole->getProjectUserRole($project['id']) === "project-manager"): ?>
    <li>
    <?= $this->url->link(t('Task Security'), 'TaskSecurityController', 'project', ['plugin' => 'privateTasks', 'project_id' => $project['id']]) ?>
    </li>
<?php endif ?>