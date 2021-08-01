<?php
/* @var $this \vendor\View */
/* @var $searchModel \models\Task */
?>
<h2>Список задач</h2>
<hr>
<div class="row">
    <div class="col-md-12 text-right mb-1">
        <a href="/site/create" class="btn btn-success">Добавить</a>
    </div>
    <div class="col-md-12">
        <?= \vendor\View::Alert()?>
    </div>
    <div class="col-md-12">
        <div>Всего записи: <b><?= $model['count']?></b> </div>
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th><?= $searchModel->sortOrder('username')?></th>
                    <th><?= $searchModel->sortOrder('email')?></th>
                    <th>Текста задачи</th>
                    <th><?= $searchModel->sortOrder('status')?></th>
                </tr>
            </thead>

            <tbody>
            <?php foreach ($model['data'] as $items){?>
                <tr>
                    <td><?= $items['username'] ?> </td>
                    <td><?= $items['email'] ?></td>
                    <td><?= $items['description'] ?></td>
                    <td><?= \models\Task::getStatusHtml($items['status']) ?></td>
                </tr>
            <?php }?>
            </tbody>
        </table>
    </div>
    <div class="col-md-12">
        <?= $searchModel->pagination($model['page'])?>
    </div>
</div>