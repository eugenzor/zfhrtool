{include file = 'header.tpl' title = 'Тесты :: '}
<div id="main">
    <div style="clear:both; margin-bottom:10px; border-top:2px solid #123466"></div>
    <ul class="menu" style="float:right">
        <li style="display:inline"><a href="/index.php">Главная</a></li>
        <li style="display:inline"><a href="/index.php/category">Редактор категорий</a></li>
        <li style="display:inline"><a href="/index.php/test/edit">Добавить тест</a></li>
    </ul>
    <div style="clear:both; margin-bottom:10px; border-top:2px solid #123466"></div>
	<h1 class="title" style="display:inline; border:0">Тесты : </h1>
    <div style="clear:both; margin-bottom:10px; border-top:2px solid #123466"></div>
    <form action="/index.php/test" method="post">
        <fieldset>
            <legend>Фильтр</legend>
            Категории :
            <select name="categoryId" onChange="this.form.submit();" style="display:inline">
                <option value="-1">Все категории</option>
    {foreach from=$arrCategory item=arrCategoryList}
                <option value="{$arrCategoryList.cat_id}"{if $arrCategoryList.cat_id eq $categoryId} selected="selected"{/if}>{$arrCategoryList.cat_name}</option>
    {/foreach}
            </select>
            Название теста :
            <input type="text" maxlength="255" size="10" name="strTestFilter" />
            <input type="submit" value="Применить" />
        </fieldset>
    </form>    
	<!-- Таблица тестов -->
	<table width="100%" border="1" cellpadding="0" cellspacing="0" id="tableTest" class="tablesorter">
    <thead>
		<tr>
			<th>№</th>
			<th>Дата создания</th>
			<th>Название</th>
			<th>Категория</th>
			<th>к-во вопросов</th>
			<th>Действия</th>
		</tr>
    </thead>
    <tbody>
{if isset($arrTest) AND !empty($arrTest)}
	{foreach from=$arrTest item=testList}
		<tr>
			<td>{$testList.t_id}</td>
			<td>{$testList.t_date}</td>
			<td>{$testList.t_name}</td>
			<td>{$testList.cat_name}</td>
			<td>{$testList.t_quest_amount}</td>
			<td>
				<a href="/index.php/test/edit/testId/{$testList.t_id}">Изменить</a><br />
				<a href="/index.php/test/remove/testId/{$testList.t_id}" onClick="return confirm('Вы действительно хотите удалить тест \' {$testList.t_name}\'');">Удалить</a><br />
			</td>
		</tr>
	{/foreach}
{else}
		<tr>
			<td></td>
			<td colspan="5">нет тестов</td>
		</tr>	
{/if}
	</tbody>
	</table>
	<!-- End of Таблица тестов-->	
</div>
{include file="footer.tpl"}