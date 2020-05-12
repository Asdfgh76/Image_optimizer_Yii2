<?php

namespace app\modules\images\controllers;

use Yii;
use yii\web\Controller;
use app\modules\images\models\Optimizer;
use yii\imagine\Image;

/**
 * Default controller for the `images` module
 * 
 * @package app\modules\images\controllers
 */
class OptimizerController extends Controller
{
    /**
     * @var int $height
     */
    private $height;
    /**
     * @var int $width
     */
    private $width;
    /**
     * @var int $quality
     */
    private $quality;
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        $model = new Optimizer();
        
        $this->height = 1000; // задаем высоту
        $this->width = 400; // задаем ширину
        $this->quality =80; // задаем качество изображения(только для файлов с разрешением jpg, jpeg)
        
        if (Yii::$app->request->isAjax)
        {
          if($form = Yii::$app->request->post())
          { 
            if($url = $form['urlimage'])
            {
                if($params = $model->upload($url))
                {
                    $path = '/upload/' . $params['name'] . $params['mime'];
                    switch($params['mime'])
                    {
                    case '.jpg':
                        // Обрежет по высоте на 400px, по ширине пропорционально 
                        if(!Image::resize($url, $this->height, $this->width)
                        ->save(Yii::getAlias($_SERVER['DOCUMENT_ROOT'].$path,['quality' => $this->quality])))
                        {
                        $msg = Yii::$app->session->setFlash('warning', 'Неудалось оптимизировать изображение');
                        return $msg;
                        }
                        break;
                    case '.png': 
                        // Обрежет по высоте на 400px, по ширине пропорционально
                        if(!Image::resize($url, $this->height, $this->width)
                        ->save(Yii::getAlias($_SERVER['DOCUMENT_ROOT'].$path)))
                        {    
                        $msg = Yii::$app->session->setFlash('warning', 'Неудалось оптимизировать изображение');
                        return $msg;
                        }
                        break;
                        }
                    } 
                } 
           }
        } 
        return $this->render('index', ['model' => $model, 'path' => $path, 'msg' => $msg]);
    }
}
