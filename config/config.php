<?php
/**
 *  файл настроек
 */

//константы для обращения к контроллерам
define('PathPrefix', '../controllers/');
define('PathPostfix', 'Controller.php');

//> используем шаблон
$template = 'default';

//пути к файлам шаблонов 
define('TemplatePrefix', "../views/{$template}");
define('TemplatePostfix', '.tpl');

//пути к файлам шаблонов в веб-пространстве
define('TemplateWebPath', "/templates/{$template}/");
//<

//> инициализация шаблонизатора Smarty
//put full path to Smatry.class.php
require('../library/Smarty/libs/Smarty.class.php');

$smarty = new Smarty();
$smarty->setTemplateDir(TemplatePrefix);
$smarty->setCompileDir('../tmp/smarty/templates_c');
$smarty->setCacheDir('../tmp/smarty/cache');
$smarty->setConfigDir('../library/Smarty/configs');
$smarty->assign('templateWebPath', TemplateWebPath);
//<