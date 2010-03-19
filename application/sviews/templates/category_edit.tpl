{include file = 'header.tpl' title = 'Добавить/редактировать категорию :: Категории тестов :: '}
<div id="main">
    <div style="clear:both; margin-bottom:10px; border-top:2px solid #123466"></div>
    <ul class="menu" style="float:right">
        <li style="display:inline"><a href="/index.php">Главная</a></li>
        <li style="display:inline"><a href="/index.php/test">Тесты</a></li>
        <li style="display:inline"><a href="/index.php/category">Категории тестов</a></li>
    </ul>
    <div style="clear:both; margin-bottom:10px; border-top:2px solid #123466"></div>
	<h1 class="title" style="display:inline; border:0">Категории : добавление/редактирование</h1>
    <div style="clear:both; margin-bottom:10px; border-top:2px solid #123466"></div>
    <form action="/index.php/category/update" method="post">
        <label for="categoryname" class="required">Название категории :</label><br />
        <input type="text" name="categoryName" value="{$objCategory->cat_name}" id="categoryname" style="width:300px" /><br />
        <label for="categorydescr">Комментарии :</label><br />
        <textarea name="categoryDescr" id="categorydescr" style="width:300px" rows="3">{$objCategory->cat_descr}</textarea><br />
        <input type="hidden" name="categoryId" value="{$objCategory->cat_id}" />
        <input type="submit" value="Сохранить" />
    </form>
</div>
{include file="footer.tpl"}