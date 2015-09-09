<?php

namespace app\controllers;

use app\models\OrderItem;
use app\models\search\OrderSearch;
use app\models\User;
use Yii;
use app\models\Order;
use yii\data\ActiveDataProvider;
use yii\db\Query;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * OrderController implements the CRUD actions for Order model.
 */
class OrderController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index', 'view', 'clear-cart', 'checkout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['update', 'delete'],
                        'allow' => true,
                        'roles' => [User::ROLE_ADMIN],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all Order models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new OrderSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * @param $id
     * @return string
     * @throws ForbiddenHttpException
     * @throws NotFoundHttpException
     */
    public function actionView($id)
    {
        $identity = Yii::$app->user->identity;
        $order = Order::findOne(['id' => $id]);

        if ($identity->role == User::ROLE_USER && $order->buyer_id != $identity->id ) {
            throw new ForbiddenHttpException();
        }

        $orderItems = OrderItem::find()
            ->joinWith('product')
            ->where(['order_id' => $id]);

        $orderItemsDataProvider = new ActiveDataProvider([
            'query' => $orderItems,
            'sort' => false
        ]);

        $total = (new Query())
            ->select('sum(oi.count * p.price)')
            ->from('{{%order_items}} oi')
            ->innerJoin('{{%products}} p', 'oi.product_id = p.id')
            ->where(['oi.order_id' => $id])
            ->groupBy('oi.order_id')
            ->scalar();


        return $this->render('view', [
            'model' => $this->findModel($id),
            'orderItemsDataProvider' => $orderItemsDataProvider,
            'total' => $total
        ]);
    }

    /**
     * Deletes an existing Order model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $order = $this->findModel($id);
        $order->deleted = true;
        $order->save();

        return $this->redirect(['index']);
    }

    /**
     * @return \yii\web\Response
     */
    public function actionCheckout()
    {
        $user = Yii::$app->user->identity;

        $products = Yii::$app->session->get('products');

        if ($products) {
            if (Order::create($user->id, $products)) {
                Yii::$app->session->remove('products');
            }
        }

        return $this->redirect(['/product']);
    }

    /**
     * @return \yii\web\Response
     */
    public function actionClearCart()
    {
        Yii::$app->session->remove('products');
        return $this->redirect('/product');
    }

    /**
     * Finds the Order model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Order the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Order::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
