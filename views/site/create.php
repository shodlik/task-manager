<?php
/* @var $model \models\Task */
$model = $model[0];
?>
<h2><?= $this->title ?></h2>
<hr>
<?= $model->getHtmlErors(); ?>
<form method="post">
    <div class="form-group">
        <label>Имени пользователя<span class="text-danger">*</span></label>
        <input class="form-control" name="username" value="<?= $model->username ?>" placeholder="Введите имени пользователя">
    </div>
    <div class="form-group">
        <label>е-mail<span class="text-danger">*</span></label>
        <input class="form-control" name="email" value="<?= $model->email ?>"  placeholder="Введите е-mail">
    </div>
    <div class="form-group">
        <label>Текст задачи<span class="text-danger">*</span></label>
        <textarea class="form-control" rows="5" name="description" placeholder="Введите текст задачи"><?= $model->description?></textarea>
    </div>
    <div class="form-group text-right">
        <button type="submit" class="btn btn-success">Сохранить</button>
    </div>
</form>