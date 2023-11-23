<!DOCTYPE html>
    <html>
        <head>
            <meta charset="utf8">
            <title>Парсер фото Прокудин-Горского</title>
        </head>
        <body>
            
        </body>
    </html>
<?php
    $url = 'https://www.loc.gov/exhibits/empire/empire-ru.html';
    
    /*Получаем имя страниц выставки*/
    $html = file_get_contents($url);
    // Создаем объект DOMDocument
    $dom = new DOMDocument;

    // Загружаем HTML-код
    $dom->loadHTML($html);

    // Получаем все элементы <a> внутри <div> с id="tert_nav"
    $divElement = $dom->getElementById('tert_nav');
    $links = $divElement->getElementsByTagName('a');

    // Инициализируем пустой массив для имен страниц
    $linkPage = [];

    // Обрабатываем каждую найденную ссылку и добавляем имена старниц в массив
    foreach ($links as $link) {
        // Получаем атрибут href и добавляем его в массив
        $linkPage[] = $link->getAttribute('href');
    }
    
    /*Получаем ссылки на страниц выставок и добавляем в массив*/
    foreach($linkPage as $value){
        if (str_ends_with($value, ".html")){
            $urlExhibitions[] = 'https://www.loc.gov/exhibits/empire/'.$value;
        }
    }

    /*Переходим по страницам и собираем все ссылки на картинки в один большой массив*/
    
?>

<!--<div id="tert_nav">
    <strong>Секции выставки:</strong>
    <a href="gorskii-ru.html">Фотограф царя: Прокудин-Горский</a> | 
    <a href="architecture-ru.html">Архитектура</a> | 
    <a href="ethnic-ru.html">Этническое разнообразие</a> | 
    <a href="transport-ru.html">Транспорт</a> | 
    <a href="work-ru.html">Люди за работой</a> | Получение цветных изображений | 
    <a href="//www.loc.gov/pictures/collection/prok/">Поиск по всей коллекции</a>
</div>-->