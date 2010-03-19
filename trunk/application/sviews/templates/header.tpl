<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>{$title}Система тестирования :: MarketGid</title>
    <link href="/styles/tablesorter.css" media="screen" rel="stylesheet" type="text/css" />
    <link href="/styles/main.css" media="screen" rel="stylesheet" type="text/css" />
	<script type="text/javascript" src="/scripts/jquery-1.4.2.min.js"></script> 
    <script type="text/javascript" src="/scripts/jquery.tablesorter.min.js"></script>
    {literal}
    <script type="text/javascript" language="javascript"> 
    <!--
    //<![CDATA[
        $(document).ready(function() 
            { 
                $("#tableTest").tablesorter( { headers: { 0: { sorter: false}, 4: {sorter: false}, 5: {sorter: false} },
											   widgets: ['zebra']});
            } 
        ); 
    //]]>
    -->
    </script>
    {/literal}
</head>

<body>
    <div class="menu-h">
        <ul class="navigation"> 
            <li class="active"><a href="/index.php/">Главная</a></li> 
            <li><a href="/index.php/user/signin">Войти</a></li> 
            <li><a href="/index.php/user/signup">Зарегистрироваться</a></li> 
            <li><a href="/index.php/test">Тесты</a></li> 
            <li><a href="/index.php/user/signout">Выйти</a></li> 
        </ul>
    </div>
	<h1>Система тестирования MarketGid</h1>