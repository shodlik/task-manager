<?php
/* @var $content string */
/* @var $this \vendor\View */

?>
<html>
<head>
    <link href="/css/bootstrap.min.css" rel="stylesheet">
    <script src="/js/bootstrap.js"></script>
        <title><?= $this->title ?></title>
</head>
<body>
<?php $this->beginPage() ?>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow">
    <div class="container">
    <a class="navbar-brand" href="#">Доска задач</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav">
            <li class="nav-item <?= ($_SERVER['REQUEST_URI']=="/" || $_SERVER['REQUEST_URI']=="/site/index")?'active':'' ?>">
                <a class="nav-link" href="/">Главная <span class="sr-only">(current)</span></a>
            </li>
            <?php if(\vendor\Base::isGuest()==true){ ?>
            <li class="nav-item <?= ($_SERVER['REQUEST_URI']=="/site/login")?'active':'' ?>">
                <a class="nav-link" href="/site/login">Вход</a>
            </li>
            <?php }else{ ?>
            <li class="nav-item <?= ($_SERVER['REQUEST_URI']=="/site/profil")?'active':'' ?>">
                <a class="nav-link" href="/site/profil">Профил</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/site/logout">Выход(<?= \vendor\Base::getUsername()?>)</a>
            </li>
            <?php }?>

        </ul>
    </div>
    </div>
</nav>
<div class="container pt-2">
    <?php eval("?>".$content."<?php")  ?>
</div>
<?php $this->endPage() ?>
</body>
</html>

