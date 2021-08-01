<?php namespace controller;
use models\Task;
use models\Users;
use vendor\Base;
use vendor\Controller;
use vendor\View;

/**
 * Created by PhpStorm.
 * User: Jasmina
 * Date: 30.07.2021
 * Time: 23:59
 */

class SiteController extends Controller
{

    public function actionIndex(){
        $this->title = "Список задач";
        phpinfo();
        $searchModel = new Task();
        $model = $searchModel->findSearch($this->get());
        return $this->render("index",['model'=>$model,'searchModel'=>$searchModel]);
    }

    public function actionProfil(){
        $this->isAdmin();
        $this->title = "Кабинет";
        $searchModel = new Task();
        $model = $searchModel->findSearch($this->get());
        return $this->render("profil",['model'=>$model,'searchModel'=>$searchModel]);
    }

    public function actionAccepted(){
            $this->isAdmin();
            $id = $this->get('id');
            $res = Task::update(["id={$id}"], ["status=" . Task::STATUS_ACCEPTED]);
            if ($res)
                View::setAlert("success", 'Успешно выполнена');
            return $this->redirect('/site/profil');
    }

    public function actionCreate(){
        $this->title = "Добавить задач";
        $model = new Task();
        $model->status = Task::STATUS_NEW;
        if($model->load($this->post()) && $model->save()){
            View::setAlert('success','Успешно создан задачу');
            $this->redirect('/site/index');
        }
        return $this->render("create",['model'=>[$model]]);
    }

    public function actionUpdate(){
        $this->isAdmin();
        $id = $this->get('id');
        $this->title = "Добавить задач";
        $model = new Task();
        $model->findOne(['id'=>$id]);
        if($model->status==Task::STATUS_ACCEPTED){
            View::setAlert('warning','Задача уже выполнена. отредакурет запрешено.');
            $this->redirect('/site/profil');die;
        }
        if($model->load($this->post()) && $model->save()){
            View::setAlert('success','Успешно создан задачу');
            $this->redirect('/site/profil');
        }
        return $this->render("create",['model'=>[$model]]);
    }

    public function actionLogin(){
        $this->title = "Авторизоваться";
        if(Base::isGuest()) {
            $model = new Users();
            if ($this->post() && $model->login($this->post())) {
                return $this->redirect('/site/profil');
            }
            return $this->render('login');
        } else {
            return $this->redirect('/site/index');
        }
    }

    public function actionLogout(){
        Users::logout();
        return $this->redirect('/site/index');
    }
}