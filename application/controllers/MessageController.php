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
            $view->saved = $model->save(true);
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
            $view->saved = $model->save(false);
        if (is_numeric($params['id']) && ((int)$params['id']) > 0) {
            $id = $params['id'];
            $view->messages = $model->one($id);
        }
        $view->schema = $model->describe();
        $view->model = $model;
        if ($fc->is_ajax)
            $result = $view->body('../views//messages/edit.php', 'создать запись');
        else
            $result = $view->render('../views//messages/edit.php', 'создать запись');

        $fc->setBody($result);
    }

    public function viewAction()
    {
        $fc = FrontController::getInstance();
        //Добавляем
        $params = $fc->getParams();
        $view = new View();

        $model = new  Message($fc->getDb());

        if (is_numeric($params['id']) && ((int)$params['id']) > 0) {
            $id = $params['id'];
            $view->messages = $model->one($id);
        }
        $view->schema = $model->describe();
        $view->model = $model;
        if ($fc->is_ajax)
            $result = $view->body('../views//messages/view.php', 'просмотр записи');
        else
            $result = $view->render('../views//messages/view.php', 'просмотр записи');

        $fc->setBody($result);
    }

    public function deleteAction()
    {
        $fc = FrontController::getInstance();
        //Добавляем
        $params = $fc->getParams();
        $view = new View();

        $model = new  Message($fc->getDb());

        if (is_numeric($params['id']) && ((int)$params['id']) > 0) {
            $id = $params['id'];
            $view->messages = $model->one($id);
        }
        $view->schema = $model->delete($id);
        $view->model = $model;
        header('Location:/message/index');
    }
}
