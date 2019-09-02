<div class="page-header">
    <h2><?= t('Task Security') ?></h2>
</div>

<?php if (!$security): ?>
    <p class="alert"><?= t('Tasks are visible to all users in this project.') ?></p>
    <?= $this->url->link(t('Enable Security'), 'TaskSecurityController', 'project', ['plugin' => 'privateTasks', 'project_id' => $project['id'],'security'=>'enable']) ?>
<?php endif ?>

<?php if ($security): ?>
    <p class="alert"><?= t('Tasks are only visible to the users they are assigned to.') ?></p>
    <?= $this->url->link(t('Disable Security'), 'TaskSecurityController', 'project', ['plugin' => 'privateTasks', 'project_id' => $project['id'],'security'=>'disable']) ?>
<?php endif ?>