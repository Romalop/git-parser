# git-parser
# Данная программа необходима для парсинга фотографий Прокудин-Горского с Библиотеки Конгресса США

Каталог выставки
https://www.loc.gov/collections/prokudin-gorskii/?fa=original-format:photo,+print,+drawing


Порядок
1. Необходимо получить все страницы выставки, на которых уже лежат необходимые фотографии
2. Вытащить ссылку на картинку с одной страницы
- [ ] С помощью пхп ссылку на картинку со страницы картинки
- [ ] Страницу со списком картинок и разбирать ее собираем ссылки на страницы картинок в массив
- [ ] Далее вытаскиваем все ссылки на картинки
- [ ] И далее сохраняем все ссылки на картинки в файл
- [ ] Сделать задержку при скачивании картинок 2 секунды, функция sleep
- [ ] После скачиваем все картинки из массива