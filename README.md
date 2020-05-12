# Оптимизатор изображений на Yii2

Оптимизирует размеры и качество скачиваемого изображения под ваши требования.
Для оптимизации применяется расширение yii\imagine\Image.
Документация <a href = "https://www.yiiframework.com/extension/yiisoft/yii2-imagine/doc/api/2.2/yii-imagine-image">здесь</a>

## Настройка

Для настройки максимального размера файла откройте файл modules/images/models/Optimizer.php

Изменение  разрешенного максимального размер файла.

```php
    curl_setopt($curl, CURLOPT_PROGRESSFUNCTION, function(
     $DownloadSize, $Downloaded, $UploadSize, $Uploaded){
     // Когда будет скачано больше 5 Мбайт, curl прервёт работу
     if ($Downloaded > 1024 * 1024 * 5) {
        return -1;
     }
     });
```

Изменение  разрешенного максимальных размеров изображения по высоте и ширине.

// Зададим ограничения для картинок

```php
     $limitWidth  = 1280;
     $limitHeight = 768;
```

Настройки для оптимизации изображения откройте файл modules/images/controllers/OptimizerController.php

```php
   $this->height = 1000; // задаем высоту
   $this->width = 400; // задаем ширину
   $this->quality =80; // задаем качество изображения(только для файлов с разрешением jpg, jpeg)
```

Здесь меняем на нужный метод оптимизации yii\imagine\Image

```php
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
```

## Использование

Вводим ссылку в поле формы, нажимаем кнопку "Оптимизировать изображение".
Оптимизированная картинка должна появиться под формой
