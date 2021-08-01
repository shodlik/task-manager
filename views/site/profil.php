<?php
/* @var $this \vendor\View */
/* @var $searchModel \models\Task */
?>
<h2>Список задач</h2>
<hr>
<div class="row">
    <div class="col-md-12">
        <?= \vendor\View::Alert()?>
    </div>
    <div class="col-md-12">
        <div>Всего записи: <b><?= $model['count']?></b> </div>
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th><?= $searchModel->sortOrder('username')?></th>
                    <th><?= $searchModel->sortOrder('email')?></th>
                    <th>Текста задачи</th>
                    <th width="110px">Движ</th>
                </tr>
            </thead>

            <tbody>
            <?php foreach ($model['data'] as $items){?>
                <tr>
                    <td>#<?= $items['id'] ?> </td>
                    <td><?= $items['username'] ?> </td>
                    <td><?= $items['email'] ?></td>
                    <td><?= $items['description'] ?></td>
                    <td>
                        <?php if($items['status']!=\models\Task::STATUS_ACCEPTED){ ?>
                        <a href="/site/accepted?id=<?= $items['id']?>" class="btn btn-success btn-sm shadow-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-check-square"><polyline points="9 11 12 14 22 4"></polyline><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path></svg>
                        </a>
                        <a href="/site/update?id=<?= $items['id']?>" class="btn btn-light btn-sm shadow-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                        </a>
                        <?php } else{?>
                            <?= \models\Task::getStatusHtml($items['status']) ?>
                        <?php }?>
                    </td>
                </tr>
            <?php }?>
            </tbody>
        </table>
    </div>
    <div class="col-md-12">
        <?= $searchModel->pagination($model['page'])?>
    </div>
</div>