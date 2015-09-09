<?php

namespace app\controllers;

use app\models\forms\AddToCartForm;
use app\models\Order;
use app\models\OrderItem;
use app\models\User;
use Yii;
use app\models\Product;
use app\models\search\ProductSearch;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\widgets\ActiveForm;

/**
 * ProductController implements the CRUD actions for Product model.
 */
class ProductController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['create', 'view', 'delete', 'update'],
                        'allow' => true,
                        'roles' => [User::ROLE_ADMIN],
                    ],
                    [
                        'actions' => ['index'],
                        'allow' => true,
                        'roles' => ['@', '?'],
                    ],
                    [
                        'actions' => ['cart', 'add-to-cart', 'validate'],
                        'allow' => true,
                        'roles' => [User::ROLE_USER],
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
     * Lists all Product models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ProductSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Product model.
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
     * Creates a new Product model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Product();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Product model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Product model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->deleteProduct();

        return $this->redirect(['index']);
    }

    /**
     * @param $id
     * @return array
     * @throws BadRequestHttpException
     * @throws NotFoundHttpException
     */
    public function actionAddToCart($id)
    {

        if (!Yii::$app->request->isAjax) {
            throw new BadRequestHttpException();
        }

        $model = new AddToCartForm(['product_id' => $id]);
        if($model->load(Yii::$app->request->post()) && $model->validate()) {
            $products = [];
            if (Yii::$app->session->get('products')) {
                $products = Yii::$app->session->get('products');
            }

            $products[$id]['count'] = isset($products[$id]['count']) ? $products[$id]['count'] + $model->count : $products[$id]['count'] = $model->count;

            Yii::$app->session['products'] = $products;
            Yii::$app->getSession()->setFlash('success', 'The product has been successfully added to you cart!');
            return $this->redirect(Url::to(['/product']));
         }

        Yii::$app->response->format = Response::FORMAT_JSON;

        return [
            $this->renderAjax('addToCart', [
                'model' => $model,
            ])
        ];
    }

    public function actionCart()
    {
        $productsItems = null;
        $dataProvider = null;

        $products = Yii::$app->session->get('products');
        if ($products) {
            $productsIds = array_keys($products);

            $productItems = Product::find()->where(['id' => $productsIds]);
            $dataProvider = new ActiveDataProvider([
                'query' => $productItems,
                'sort' => false
            ]);
        }

        return $this->render('cart', [
            'products' => $products,
            'dataProvider' => $dataProvider
        ]);
    }

    public function actionValidate($id)
    {
        if (!Yii::$app->request->isAjax) {
            throw new BadRequestHttpException();
        }

        $model = new AddToCartForm(['product_id' => $id]);

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
    }
    /**
     * Finds the Product model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Product the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Product::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
