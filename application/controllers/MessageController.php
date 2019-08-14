<?php

class MessageController implements IController
{
    public function indexAction()
    {
        $fc = FrontController::getInstance();
        //Добавляем
        $params = $fc->getParams();
        $view = new View();
        //$view->name = "John";
        $view->name = $params['name'];
        $model = new  Message($fc->getDb());
        $messages = $model->all();
        $view->messages = $messages;
        $result = $view->render('../views//messages/index.php', 'список');

        $fc->setBody($result);
    }

    public function editAction()
    {
        $fc = FrontController::getInstance();
        //Добавляем
        $params = $fc->getParams();
        $view = new View();

        $model = new  Message($fc->getDb());
        if(!empty($_POST) && $model->load($_POST) )
$model->save(true);
        if (is_numeric($params['id']) && ((int)$params['id']) > 0) {
            $id = $params['id'];
            $view->messages = $model->one($id);
        }
        $view->schema = $model->describe();
        $view->model = $model;
        $result = $view->render('../views//messages/edit.php', ' править');

        $fc->setBody($result);
    }
    public function createAction()
    {
        $fc = FrontController::getInstance();
        //Добавляем
        $params = $fc->getParams();
        $view = new View();

        $model = new  Message($fc->getDb());
        if(!empty($_POST) && $model->load($_POST,  true) )
$model->save(false);
        if (is_numeric($params['id']) && ((int)$params['id']) > 0) {
            $id = $params['id'];
            $view->messages = $model->one($id);
        }
        $view->schema = $model->describe();
        $view->model = $model;
        $result = $view->render('../views//messages/edit.php');

        $fc->setBody($result);
    }
}
