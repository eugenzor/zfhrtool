# Правила верстки #
Какие классы и теги надо использовать в каких случаях:


## Общие классы ##
  * **.content** - общий стиль для центрального блока
```
<div class="content">
  <p>контент</p>
</div>
```
  * **.box**- дополнительный стиль с отступами верх-низ 5рх и право-лево 11рх
```
<div class="box">
  <p>контент</p>
</div>
```
  * **.condition** - для создания скролинга в ячейке размером 130х300
```
<div class="condition">
    текст
</div>
```

  * **.f-left** и **.f-right** - для позиционирования элементов<br />
  * **.hidden** { display: none; }<br />
  * **.show** { display: block; }<br />
  * **.no-margin** { margin: 0; }<br />
  * **.no-padding** { padding: 0; }<br />
  * **.no-bg** { background: none; }<br />
  * **.no-border** { border: none; }<br />
  * **.cc** { clear: both; }<br />
## Текст ##
  * **h1**, **h2**, **h3**, **h4** - заголовки
  * **.error** - ошибка  **.notice** - уведомление  **.success** - успех
  * тег **blockquote** - цитата
  * **.em** текст курсив
  * **.strong** текст жирный
  * **.a-center**, **a-right** и **a-left** -для выравнивания текста


## Таблицы ##
**.base**
```
<table class="base" cellpadding="0" cellspacing="0">
  <thead>
    <tr>
      <th>Заголовки cтолбцов</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td>Содержимое таблицы</td>
    </tr>
  </tbody>
</table>
```


## Кнопки ##
```
<div class="button">
  <ul>
    <li><a href="#">Обычное меню</a></li>
    <li class="active"><a href="#">Второй пункт, активный</a></li>
    <li><a href="#">Последний пункт</a></li>
  </ul>
</div>
```
## Меню навигации ##
Обычное горизонтальное меню
```
<div class="menu-h">
  <ul>
    <li><a href="#">Обычное меню</a></li>
    <li><a href="#">Второй пункт</a></li>
    <li><a href="#">Последний пункт</a></li>
  </ul>
</div>
```
Выпадающее горизонтальное меню
```
<div class="menu-h-d">
  <ul>
    <li><a href="#">Первый пункт</a></li>
    <li><a href="#">Выпадающее меню</a>
      <ul>
        <li><a href="#">Субменю</a>
          <ul>
            <li><a href="#">СубСубменю</a>
              <ul>
                <li><a href="#">СубСубСубменю</a></li>
                <li><a href="#">СубСубСубменю-2</a></li>
              </ul>
            </li>
          </ul>
        </li>
      </ul>
    </li>
    <li><a href="#">Третий</a></li>
    <li><a href="#">Последний пункт</a></li>
  </ul>
</div>
```
Обычное вертикальное меню
```
<ul class="menu-v">
  <li><a href="#">Обычное</a></li>
  <li><a href="#">Вертикальное меню</a>
    <ul>
      <li><a href="#">Субменю</a></li>
      <li><a href="#">Субменю-2</a></li>
    </ul>
  </li>
  <li><a href="#">Третий</a></li>
  <li><a href="#">Последний</a></li>
</ul>
```
Выпадающее вертикальное меню
```
<ul class="menu-v-d">
  <li><a href="#">Выпадающее</a></li>
  <li><a href="#">Вертикальное меню</a>
    <ul>
      <li><a href="#">Субменю</a>
        <ul>
          <li><a href="#">СубСубменю</a>
            <ul>
              <li><a href="#">СубСубСубменю</a></li>
              <li><a href="#">СубСубСубменю-2</a></li>
            </ul>
          </li>
        </ul>
      </li>
    </ul>
  </li>
  <li><a href="#">Меню</a></li>
  <li><a href="#">Конец</a></li>
</ul>
```