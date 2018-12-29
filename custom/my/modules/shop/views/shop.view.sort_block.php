<?php
/**
 * Шаблон блока «Сортировать» с ссылками на направление сортировки
 * 
 * @package    DIAFAN.CMS
 * @author     diafan.ru
 * @version    6.0
 * @license    http://www.diafan.ru/license.html
 * @copyright  Copyright (c) 2003-2016 OOO «Диафан» (http://www.diafan.ru/)
 */

if (! defined('DIAFAN'))
{
    $path = __FILE__; $i = 0;
	while(! file_exists($path.'/includes/404.php'))
	{
		if($i == 10) exit; $i++;
		$path = dirname($path);
	}
	include $path.'/includes/404.php';
}
       
$link_sort   = $result["link_sort"];
$sort_config = $result['sort_config'];

for ($i = 1; $i <= count($sort_config['sort_directions']); $i++)
{
	if(!empty ($sort_config["sort_fields_names"][$i])) {
	$name_sort[]=$sort_config["sort_fields_names"][$i].' ↓';
	}
	else {
	$j=$i-1;
	$name_sort[]=$sort_config["sort_fields_names"][$j].' ↑';	
	}
}

$kol=0;
for ($i = 1; $i <= count($sort_config['sort_directions']); $i++)
{
	if (!empty ($link_sort[$i])) {
    $kol++;
	}
}
echo '<div class="block sort">'; 
echo '<div class="sort__title">'.$this->diafan->_('Сортировать по').'</div>';
echo '<select class="sort" id="selectmenu1" onchange="window.location.href=this.options[this.selectedIndex].value">';
/* if ($kol==count($sort_config['sort_directions'])) {
	echo '<option selected value="'.BASE_PATH_HREF.$this->diafan->_site->rewrite.'/">Сортировать по</option>';
}
else {
     echo '<option value="'.BASE_PATH_HREF.$this->diafan->_site->rewrite.'/">Сортировать по</option>';
} */
for ($i = 1; $i <= count($sort_config['sort_directions']); $i++)
{

	if ($link_sort[$i])
	{
	echo '<option value="'.BASE_PATH_HREF.$link_sort[$i].'">';	
	echo $name_sort[$i-1];
	echo '</option>';		
	}
	else {
	echo '<option value="" selected>';	
	echo $name_sort[$i-1];
	echo '</option>';	
	}
}

echo '</select>';

echo '</div>';