<?php
/**
 * Created by PhpStorm.
 * User: Jasmina
 * Date: 01.08.2021
 * Time: 21:49
 */
?>
<div class="row justify-content-md-center mt-5">

    <div class="col-md-5">
        <div class="card shadow">
            <div class="card-body">
                <h5 class="card-title"><?= $this->title ?></h5>
                <h6 class="card-subtitle mb-2 text-muted">Введите разрешенный логин и пароль для входа в систему</h6>
                <form method="post">

                    <div class="form-group">
                        <input type="text" class="form-control" name="username" placeholder="Введите свой логин">
                    </div>
                    <div class="form-group">
                        <input type="password" class="form-control" name="password" placeholder="Введите пароль">
                    </div>
                    <?= \vendor\View::Alert()?>
                    <div class="form-group">
                        <button type="submit" class="btn btn-success btn-block">Вход</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>