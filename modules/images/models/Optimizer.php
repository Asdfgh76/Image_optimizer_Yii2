<?php

namespace app\modules\images\models;

use yii\base\Model;
use Yii;

/**
 * images module definition class
 */
class Optimizer extends Model
{
    /**
     * @var string $urlimage
     */
    public $urlimage;
   
    /**
     * Валидация $urlimage
     *
     * @return void
     */
    public function rules()
    {
        return [
            [['urlimage'], 'string', 'max' => 255],
        ];
    }
        
    /**
     * Загрузка, оптимизация изображений
     *
     * @param  string $url
     * @return array $params
     */
    function upload($url) 
    {
    if (!preg_match("/^https?:/i", $url) && filter_var($url, FILTER_VALIDATE_URL)) {
            die($error = Yii::$app->session->setFlash('warning', 'Укажите корректную ссылку на удалённый файл!'));
    }   
     $curl = curl_init($url);
     curl_setopt($curl, CURLOPT_HEADER, 0);
     curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
     curl_setopt($curl, CURLOPT_BINARYTRANSFER,1);
     curl_setopt($curl, CURLOPT_BUFFERSIZE, 1024); 
     curl_setopt($curl, CURLOPT_NOPROGRESS, 0);

     curl_setopt($curl, CURLOPT_PROGRESSFUNCTION, function(
     $DownloadSize, $Downloaded, $UploadSize, $Uploaded){
     // Когда будет скачано больше 5 Мбайт, curl прервёт работу
     if ($Downloaded > 1024 * 1024 * 5) {
        return -1;
     }
     });
     $raw = curl_exec($curl);
     $info  = curl_getinfo($curl);
     $error = curl_errno($curl);
     curl_close($curl);
     if ($error === CURLE_OPERATION_TIMEDOUT)  die( Yii::$app->session->setFlash('warning','Превышен лимит ожидания.'));
     if ($error === CURLE_ABORTED_BY_CALLBACK) die(Yii::$app->session->setFlash('warning','Размер не должен превышать 5 Мбайт.'));
     if ($info['http_code'] !== 200) die(Yii::$app->session->setFlash('warning', 'Файл не доступен!'));
     
     // Проверим ключевое слово image (image/jpeg, image/png и т. д.)
     if (strpos($info['content_type'], 'image') === false) die(Yii::$app->session->setFlash('warning', 'Можно загружать только изображения!'));
    
     //Зададим расширение файлу
     if($info['content_type'] == 'image/png')$mime = '.png';
     if($info['content_type'] == 'image/jpeg')$mime = '.jpg';

     // Возьмём данные изображения из его содержимого
     $image = getimagesizefromstring($raw);
     
     // Зададим ограничения для картинок
     $limitWidth  = 1280;
     $limitHeight = 768;
     //print_r($image);
     // Проверим нужные параметры
     if ($image[1] > $limitHeight) die(Yii::$app->session->setFlash('warning', 'Высота изображения не должна превышать '.$limitHeight.' точек.'));
     if ($image[0] > $limitWidth)  die(Yii::$app->session->setFlash('warning', 'Ширина изображения не должна превышать '.$limitWidth.' точек.'));'';
     
     // Сгенерируем новое имя  изображения
     $name = 'optimized_'.time().'_';

     //Создаем массив с данными
     $params = [
     'mime' => $mime,
     'name' => $name,
     ];
     return $params;
    }
          
}
