<?php
    function generatePhotoLinksArray($url){
        /*Создаем массив с ссылками на странице выставок с фотографиями*/

        //Получаем имя каталога фото с пагинацией
        $html = file_get_contents($url);
        //Создаем объект DOMDocument
        $dom = new DOMDocument;
        //Игнорируем предупреждения и ошибки
        libxml_use_internal_errors(true);
        $dom->loadHTML($html);

        $xpath = new DOMXPath($dom);
        
        //Получаем количество элементов в данном каталоге, ятобы далее расчитать количество страниц для цикла перебора
        $countElement = $xpath->query('//span[@class="count"]')->item(0);
        $countText = str_replace(',','',$countElement->textContent);
        $countPage = (intval($countText/25))+1;

        //Цикл для прохода по пагинации и собирация всех ссылок на картинки в массив
        $domPage = new DOMDocument;
        //$y = 1;
        for ($i = 1; $i <= $countPage; $i++){
            sleep(2);
            $urlPage = $url.'&sp='.$i;
            $htmlPage = file_get_contents($urlPage);
            echo $i;
            $domPage->loadHtml($htmlPage);
            $xpathPage = new DOMXPath($domPage);
            $urlPic = $xpathPage->query('//img[@class="iconic "]');
            foreach ($urlPic as $value){
                if (str_contains($value->getAttribute('src'), 't.gif') == true){
                    $serviceUrl = strstr($value->getAttribute('src'), 't.gif', true).'u.tif';
                    if (str_ends_with($serviceUrl, '.tif')){
                        $linkImg[] = str_replace('/service/', '/master/', $serviceUrl);
                    }else{
                        $linkImg[] = $serviceUrl;
                    }
                    sleep(2);
                }else{
                    $serviceUrl = strstr($value->getAttribute('src'), '_', true).'u.tif';
                    if (str_ends_with($serviceUrl, '.tif')){
                        $linkImg[] = str_replace('/service/', '/master/', $serviceUrl);
                    }else{
                        $linkImg[] = $serviceUrl;
                    }
                    sleep(2);
                }
                
            }
        }
        return $linkImg;
    }

    function createFileLink($linkImg){
        $line = implode(',', $linkImg);
        //Записываем текст в файл, если файл не сушествует он будет создан
        file_put_contents('link_photo.txt', $line);
        print_r($linkImg);
    }
    function downloadPhoto(){

        // Папка, в которую вы хотите сохранить изображения
        $downloadFolder = 'img/';
        $filePath = 'link_photo.txt';
        $fileName = file_get_contents($filePath, true);
        $linkImg = explode(',', $fileName);

        foreach ($linkImg as $index => $value) {
                $start_time_photo = microtime(true);
                // Получаем имя файла из URL
                $filename = $downloadFolder . 'image' .'_'. $index . '.jpg';
                $success = false;
                
                
                //Запускаем цикл, который будет отлавливать ошибки, и пытаться снова запустить скачивание файла
                while (!$success) {  
                    // Скачиваем файл и сохраняем его
                    $fileContent = @file_get_contents($value);
                    if ($fileContent !== false){
                        
                        file_put_contents($filename, $fileContent);
                        $end_time_photo = microtime(true);
                        $execution_time_photo = $end_time_photo - $start_time_photo;
                        echo "Файл $filename успешно скачан за ".round($execution_time_photo, 2)." секунд.\n";
                        $success = true;
                        // if ($execution_time_photo < 50){
                        //     sleep(10);
                        // }
                    } else{
                        echo "Ошибка при скачивании файла $filename\n";
                        sleep(300);
                    }
                }
        }
        echo "Все файлы успешно скачаны в папку Gorskiy.\n";
    }
    
    function parser($url){
        $start_time = microtime(true);
        createFileLink(generatePhotoLinksArray($url));
        $end_time = microtime(true);
        $execution_time = $end_time - $start_time;
        echo "Время выполнения скрипта: $execution_time секунд";
        
    }
    function downloadStart(){
        $start_time = microtime(true);
        downloadPhoto();
        $end_time = microtime(true);
        $execution_time = $end_time - $start_time;
        echo "Время скачивания всех фотографий: $execution_time секунд";
        
    }

    //parser('https://www.loc.gov/collections/prokudin-gorskii/?fa=original-format:photo,+print,+drawing');
    downloadStart();
    
    
    

//r.jpg
//v.jpg
//u.tif
/*Путь к папке в сервере /var/www/html*/
/*Путь к логам сервера /var/log/apache2/error.log*/
/*Путь к жестаку в 160 гигов /mnt/sdc/www/*/

?>