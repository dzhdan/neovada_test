<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\assets;

use yii\web\AssetBundle;
use yii\web\View;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class JqueryAsset extends AssetBundle
{
    public $sourcePath = '@bower/jquery/dist';
    public $jsOptions = [
        'position' => \yii\web\View::POS_HEAD
    ];
    public $js = [
        'jquery.js',
    ];
}
