<?php
    function collector($url){
        //Запоминаем текущее время до выполнения скрипта
        $start_time = microtime(true);

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
        for ($i=1; $i <= $countPage; $i++){
            sleep(5);
            $urlPage = $url.'&sp='.$i;
            $htmlPage = file_get_contents($urlPage);
            echo $i;
            $domPage->loadHtml($htmlPage);
            $xpathPage = new DOMXPath($domPage);
            $urlPic = $xpathPage->query('//img[@class="iconic "]');
            foreach ($urlPic as $value){
                $serviceUrl = strstr($value->getAttribute('src'), '_', true).'u.tif';
                if (str_ends_with($serviceUrl, '.tif')){
                    $linkImg[] = str_replace('/service/', '/master/', $serviceUrl);
                }else{
                    $linkImg[] = $serviceUrl;
                }
                sleep(2);
            }
        }
        return $linkImg;

    }

    function createFileLink($linkImg){
        $line = implode(',', $linkImg);
        //Записываем текст в файл, если файл не сушествует он будет создан
        file_put_contents('/Users/roman/Downloads/link_photo.txt', $line);
        print_r($linkImg);
    }
    
    function parser($url){
        $start_time = microtime(true);
        $linkImg = collector($url);
        createFileLink($linkImg);
        $end_time = microtime(true);
        $execution_time = $end_time - $start_time;
        echo "Время выполнения скрипта: $execution_time секунд";

    }

    function downloadPhoto(){

    }
    parser('https://www.loc.gov/collections/prokudin-gorskii/?fa=original-format:photo,+print,+drawing');

    // /*Скачиваем изображения и сохраняем их в папку Downloads*/

    // // Папка, в которую вы хотите сохранить изображения
    // $downloadFolder = '/Users/roman/Downloads/Gorskiy/';

    // // Итерируем по ссылкам и скачиваем файлы
    // foreach ($linkImg as $index => $value) {
    //     // Получаем имя файла из URL
    //     $filename = $downloadFolder . 'image' . $index . '.jpg';

    //     // Скачиваем файл и сохраняем его
    //     $fileContent = file_get_contents($value);
    //     file_put_contents($filename, $fileContent);

    //     echo "Файл $filename успешно скачан.\n";
    //     sleep(2);
    // }

    // echo "Все файлы успешно скачаны в папку Gorskiy.\n";

//r.jpg
//v.jpg
//u.tif
?>