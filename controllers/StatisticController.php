<?php

namespace app\controllers;

use app\models\forms\MonthStatisticForm;
use app\models\forms\PopularityForm;
use app\models\OrderItem;
use app\models\Product;
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


class StatisticController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => array(
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['month', 'popularity'],
                        'allow' => true,
                        'roles' => [User::ROLE_ADMIN],
                    ],
                ],
            ),
        ];
    }

    public function actionMonth()
    {
        $monthForm = new MonthStatisticForm();

        $data = $monthForm->search(Yii::$app->request->get($monthForm->formName()));

        $yearRange = $monthForm->getYearRange();
        $data['monthForm'] = $monthForm;
        $data['years'] = $yearRange;

        return $this->render('month', $data);
    }

    public function actionPopularity($id)
    {
        $popularityForm = new PopularityForm();

        $dataProvider = $popularityForm->search($id);
        $product = Product::findOne($id);

        return $this->render('popularity', [
            'dataProvider' => $dataProvider,
            'product' => $product
        ]);
    }
}
