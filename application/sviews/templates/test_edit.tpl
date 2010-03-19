{include file = 'header.tpl' title = 'Добавление/редактирование категории :: Тесты :: '}
{literal}
<script type="text/javascript" language="javascript"> 
<!--
//<![CDATA[
	function SetAction(action)
	{
		
		var form = document.getElementById('questionForm');
		form.formAction.value = action;
		return true;
	}
//]]>
-->
</script>
{/literal}
<div id="main">
    <div style="clear:both; margin-bottom:10px; border-top:2px solid #123466"></div>
    <ul class="menu" style="float:right">
        <li style="display:inline"><a href="/index.php">Главная</a></li>
        <li style="display:inline"><a href="/index.php/test">Тесты</a></li>
        <li style="display:inline"><a href="/index.php/category">Категории тестов</a></li>
        <li style="display:inline"><a href="/index.php/question/edit/testId/{$objTest->t_id}">Добавить вопрос</a></li>
    </ul>
    <div style="clear:both; margin-bottom:10px; border-top:2px solid #123466"></div>
	<h1 class="title" style="display:inline; border:0">Тесты : добавление/редактирование</h1>
    <div style="clear:both; margin-bottom:10px; border-top:2px solid #123466"></div>
    <form action="/index.php/test/update" method="post" id="questionForm">
        <label for="testname" class="required">Название теста :</label><br />
        <input type="text" name="testName" value="{$objTest->t_name}" id="testname" style="width:300px" /><br />
        <label for="categorydescr">Категория теста :</label><br />
        <select name="categoryId">
{foreach from=$arrCategory item=arrCategoryList}
            <option value="{$arrCategoryList.cat_id}"{if $arrCategoryList.cat_id eq $objTest->cat_id} selected="selected"{/if}>{$arrCategoryList.cat_name}</option>
{/foreach}
        </select><br />
        Список вопросов теста :
        <table width="100%" border="1" cellpadding="0" cellspacing="0">
            <tr class="head">
                <td>№</td>
                <td>Текст вопроса</td>
                <td>к-во вариантов ответов</td>
                <td class="action">Действия</td>
            </tr>
{if isset($arrQuestion) AND !empty($arrQuestion)}
    {foreach from=$arrQuestion item=questionList name=questionLoop}
            <tr class="item_row">
                <td>{$questionList.tq_sort_index}</td>
                <td>{$questionList.tq_text}</td>
                <td>{$questionList.tq_answer_amount}</td>
                <td class="action">
                    <a href="/index.php/question/edit/testId/{$objTest->t_id}/questionId/{$questionList.tq_id}">Изменить</a><br />
                    <a href="/index.php/question/remove/testId/{$objTest->t_id}/questionId/{$questionList.tq_id}" onClick="return confirm('Вы действительно хотите удалить вопрос №{$questionList.tq_id}');">
                        Удалить
                    </a><br />
                    {if !$smarty.foreach.questionLoop.first}<a href="/index.php/question/up/testId/{$objTest->t_id}/questionId/{$questionList.tq_id}">вверх</a><br />{/if}
                    {if !$smarty.foreach.questionLoop.last}<a href="/index.php/question/down/testId/{$objTest->t_id}/questionId/{$questionList.tq_id}">вниз</a><br />{/if}
                </td>
            </tr>
    {/foreach}
{else}
            <tr class="item_row">
                <td></td>
                <td colspan="5">нет вопросов</td>
            </tr>	
{/if}
        </table>
        <input type="hidden" name="testQuestionAmount" value="{$intQuestionAmount}" />
        <input type="hidden" name="testId" value="{$objTest->t_id}" />
        <input type="hidden" name="formAction" id="formAction" value="" />
        <input type="submit" value="Добавить вопрос" onclick="return SetAction('questionAdd');" /><br />
        <input type="submit" value="Сохранить" />
    </form>
</div>
{include file="footer.tpl"}