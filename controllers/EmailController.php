<?php

namespace app\controllers;

use Yii;
use app\models\Email;
use app\models\EmailSend;
use app\models\EmailSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \DateTime;

/**
 * EmailController implements the CRUD actions for Email model.
 */
class EmailController extends Controller
{
    public $pSize = 20;

    public function myvardump($var)
    {
        static $int = 0;
        echo '<pre><b style="background: blue;padding: 1px 5px;">' . $int . '</b> ';
        var_dump($var);
        echo '</pre>';
        $int++;
    }

    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all Email models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new EmailSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->setSort(['attributes' =>['sent_date', 'status', 'email']]);        
        $dataProvider->setPagination(array('pageSize' => $this->pSize));
//        $query=  Model::find();
//        $dataProvider=new \yii\data\ActiveDataProvider([
//        'query' => $query,
//        'pagination' => [
//                'pageSize' => 5,
//                ],
//        'sort' => ['attributes' => ['status']],
//       ]);
        $model = new EmailSend();
        $date = new DateTime();
        $model->body = $date->format('Y-m-d H:i:s');


        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'model' => $model,
        ]);
    }

    public function actionSendemail()
    {
        $modelEmailSend = new EmailSend();
        $modelEmail = new Email();
        $date = new DateTime();
        $modelEmailSend->body = $date->format('Y-m-d H:i:s');

        $flag = true;


        if ($modelEmail->load(Yii::$app->request->post(), '') &&
                $modelEmailSend->load(Yii::$app->request->post()) &&
                //$modelEmail->validate() &&
                $modelEmail->selection != Null)
        {
            //var_dump($modelEmail);
            //var_dump($modelEmailSend);
            //var_dump($_POST);
            //exit();

            foreach ($modelEmail->selection as $id)
            {
                $model = $this->findModel($id);
                if (!$modelEmailSend->send($id))
                {
                    $flag = false;
                    $model->status = 2;
                } else
                {                    
                    $model->status = 1;
                    $date = new DateTime();
                    $model->sent_date=$date->format('Y-m-d H:i:s');
                    //var_dump($_POST);
            //exit();
                }
                try{$model->save();} catch(\Exception $ex){var_dump($ex);
            exit();};
            }

            if (!$flag)
            {
                Yii::$app->session->setFlash('error', 'Сообщение отправлено не всем адресатам!');
            } else
            {
                Yii::$app->session->setFlash('success', 'Отправка успешно завершена');
            }
        } else
        {
            Yii::$app->session->setFlash('error', 'Ошибка отправки!');
            if ($modelEmail->selection == NULL)
            {
                Yii::$app->session->setFlash('error', 'Выберите хотя бы 1 адрес!');
            }
        }
        //return $this->goHome();

        $searchModel = new EmailSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->setSort(['attributes' =>['sent_date', 'status', 'email']]);
        $dataProvider->setPagination(array('pageSize' => $this->pSize));

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'model' => $modelEmailSend,
        ]);
    }

    /**
     * Displays a single Email model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Email model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Email();

        if ($model->load(Yii::$app->request->post()) && $model->save())
        {
            //return $this->redirect(['view', 'id' => $model->id]);
            return $this->goHome();
        } else
        {
            return $this->render('create', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Email model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save())
        {
            return $this->redirect(['view', 'id' => $model->id]);
        } else
        {
            return $this->render('update', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Email model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        return $this->redirect(['index']);
    }

    /**
     * Finds the Email model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Email the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Email::findOne($id)) !== null)
        {
            return $model;
        } else
        {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
