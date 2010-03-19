{include file = 'header.tpl' title = 'Категории тестов :: '}
<div id="main">
    <div style="clear:both; margin-bottom:10px; border-top:2px solid #123466"></div>
    <ul class="menu" style="float:right">
        <li style="display:inline"><a href="/index.php">Главная</a></li>
        <li style="display:inline"><a href="/index.php/test">Тесты</a></li>
        <li style="display:inline"><a href="/index.php/category/edit">Добавить категорию</a></li>
    </ul>
    <div style="clear:both; margin-bottom:10px; border-top:2px solid #123466"></div>
	<h1 class="title" style="display:inline; border:0">Категории : </h1>
    <div style="clear:both; margin-bottom:10px; border-top:2px solid #123466"></div>
	<!-- Таблица категорий -->
	<table width="100%" border="1" cellpadding="0" cellspacing="0">
		<tr class="head">
			<td>№</td>
			<td>Категория</td>
			<td>Комментарии</td>
			<td class="action">Действия</td>
		</tr>
{if isset($arrCategory) AND !empty($arrCategory)}
	{foreach from=$arrCategory item=CategoryList}
		<tr class="item_row">
            <td>{$CategoryList.cat_id}</td>
			<td>{$CategoryList.cat_name}</td>
			<td>{$CategoryList.cat_descr}</td>
			<td class="action">
				<a href="/index.php/category/edit/categoryId/{$CategoryList.cat_id}">Изменить</a><br />
				<a href="/index.php/category/remove/categoryId/{$CategoryList.cat_id}" onClick="return confirm('Вы действительно хотите удалить категорию \'{$CategoryList.cat_name}\'');">
					Удалить
				</a>
			</td>
		</tr>
	{/foreach}
{else}
		<tr class="item_row">
			<td></td>
			<td colspan="5">нет категорий</td>
		</tr>	
{/if}
	</table>
	<!-- End of Таблица тестов-->	
</div>
{include file="footer.tpl"}