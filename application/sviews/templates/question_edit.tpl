{include file = 'header.tpl' title = 'Добавить/редактировать вопрос :: Вопросы :: Тесты :: '}
{literal}
<script type="text/javascript" language="javascript"> 
<!--
//<![CDATA[
	function addAnswer(num)
	{
			num = num + 1; 
			var table = document.getElementById("answerTable");
			var noDataRow = document.getElementById("no_rows");
			if (noDataRow) {
				table.tBodies[0].deleteRow(noDataRow.sectionRowIndex);
			}
			var newRow=table.insertRow(-1);
			var newCell = newRow.insertCell(0);
			newCell.innerHTML="<textarea name=\"answer[" + num + "][text]\" style=\"width:300px\" rows=\"3\"></textarea>";
			
			var newCell = newRow.insertCell(1);
			newCell.innerHTML="<input type=\"checkbox\" name=\"answer[" + num + "][flag]\" />";
			
			document.getElementById("addAnswerButton").innerHTML = '<button type="button" onclick="addAnswer(' + num + '); return true;"><span>Добавить вариант ответа</span></button>';
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
    </ul>
    <div style="clear:both; margin-bottom:10px; border-top:2px solid #123466"></div>
	<h1 class="title" style="display:inline; border:0">Вопросы : добавление/редактирование (тест №{$objTest->t_id}, '{$objTest->t_name}' <a href="/index.php/test/edit/testId/{$objTest->t_id}">редактировать тест</a>)</h1>
    <div style="clear:both; margin-bottom:10px; border-top:2px solid #123466"></div>
    <form action="/index.php/question/update" method="post">
        <label for="questiontext" class="required">Текст вопроса :</label><br />
        <textarea name="questionText" id="questionname" style="width:300px" rows="3">{$objQuestion->tq_text}</textarea><br />

        Варианты ответов :
        <table width="100%" border="1" cellpadding="0" cellspacing="0" id="answerTable">
            <thead>
            	<tr>
                    <th>Ответ</th>
                    <th>правильно / не правильно</th>
                </tr>
            </thead>
            <tbody>
{if isset($arrAnswer) AND !empty($arrAnswer)}
    {foreach from=$arrAnswer item=AnswerList name=answerLoop}
                <tr class="item_row">
                    <td><textarea name="answer[{$smarty.foreach.answerLoop.iteration}][text]" style="width:300px" rows="3">{$AnswerList.tqa_text}</textarea></td>
                    <td><input type="checkbox" name="answer[{$smarty.foreach.answerLoop.iteration}][flag]" {if $AnswerList.tqa_flag}checked="checked"{/if} /></td>
                </tr>
    {/foreach}
{else}
                <tr class="item_row" id="no_rows"><td colspan="2">нет вариантов ответов</td></tr>	
{/if}
            </tbody>
        </table>
		<div id="addAnswerButton">
        	<button type="button" onclick="addAnswer({if isset($arrAnswer) AND !empty($arrAnswer)}{$smarty.foreach.answerLoop.iteration}{else}1{/if}); return true;"><span>Добавить вариант ответа</span></button>
        </div>
        <input type="hidden" name="testId" value="{$objTest->t_id}" />
        <input type="hidden" name="questionId" value="{$objQuestion->tq_id}" />
        <input type="submit" value="Сохранить" />
    </form>
</div>
{include file="footer.tpl"}