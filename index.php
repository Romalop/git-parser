<?php
    /*Создаем массив с ссылками на страницы выставок с фотографиями*/
    $url = 'https://www.loc.gov/exhibits/empire/';
    
    //Получаем имя страниц выставки
    $html = file_get_contents($url);
    // Создаем объект DOMDocument
    $dom = new DOMDocument;
    // Игнорировать предупреждения и ошибки
    libxml_use_internal_errors(true);
    //Загружаем HTML-код
    $dom->loadHTML($html);

    //Получаем все элементы <a> внутри <div> с id="tert_nav"
    $divElement = $dom->getElementById('tert_nav');
    $links = $divElement->getElementsByTagName('a');

    //Инициализируем пустой массив для имен страниц
    $linkPage = [];

    //Обрабатываем каждую найденную ссылку и добавляем имена старниц в массив
    foreach ($links as $link) {
        // Получаем атрибут href и добавляем его в массив
        $linkPage[] = $link->getAttribute('href');
    }
    
    //Получаем ссылки на страниц выставок и добавляем в массив//
    foreach($linkPage as $value){
        if (str_ends_with($value, ".html")){
            $urlExhibitions[] = $url.$value;
        }
    }
    
    /*Переходим по страницам и собираем все ссылки на картинки в один большой массив*/

    foreach ($urlExhibitions as $value){
        $html = file_get_contents($value);
        $dom = new DOMDocument; 
        $dom->loadHTML($html);
        $xpath = new DOMXPath($dom);

        //Используем XPath-выражение для выбора элемента с классом pic-box-alt w-borderr
        $element = $xpath->query('//div[@class="pic-box-alt w-border"]/a');

        foreach ($element as $value){
            $linkImg[] = $url.$value->getAttribute('href');
        }
    }
    print_r($linkImg);

    /*Скачиваем изображения и сохраняем их в папку Downloads*/

    // Папка, в которую вы хотите сохранить изображения
    $downloadFolder = '/Users/roman/Downloads/';

    // Проверяем, существует ли папка Download, и создаем ее, если нет
    /*if (!is_dir($downloadFolder)) {
        mkdir($downloadFolder, 0755, true);
    }*/

    // Итерируем по ссылкам и скачиваем файлы
    foreach ($linkImg as $index => $value) {
        // Получаем имя файла из URL
        $filename = $downloadFolder . 'image' . $index . '.jpg';

        // Скачиваем файл и сохраняем его
        $fileContent = file_get_contents($value);
        file_put_contents($filename, $fileContent);

        echo "Файл $filename успешно скачан.\n";
        sleep(10);
    }

    echo "Все файлы успешно скачаны в папку Download.\n";

    

   
    
    

?>