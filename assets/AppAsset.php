<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\assets;

use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'assets/css/bootstrap.min.css',
        'css/site.css',
        'assets/css/main.css',
        'assets/css/red.css',
        'assets/css/owl.carousel.css',
        'assets/css/owl.transitions.css',
        'assets/css/animate.min.css',
        'assets/css/font-awesome.min.css'

    ];
    public $js = [
        ['assets/e1226d74/jquery.min.js','position'=>\yii\web\view::POS_HEAD],
        ['assets/js/html5shiv.js','condition'=>'lte IE9','position'=>\yii\web\view::POS_HEAD],
        ['assets/js/respond.min.js','condition'=>'lte IE9','position'=>\yii\web\view::POS_HEAD]
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
