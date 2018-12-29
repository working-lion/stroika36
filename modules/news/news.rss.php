<?php
/**
 * RSS лента новостей
 * 
 * @package    DIAFAN.CMS
 * @author     diafan.ru
 * @version    6.0
 * @license    http://www.diafan.ru/license.html
 * @copyright  Copyright (c) 2003-2018 OOO «Диафан» (http://www.diafan.ru/)
 */

if (! defined('DIAFAN'))
{
	$path = __FILE__;
	while(! file_exists($path.'/includes/404.php'))
	{
		$parent = dirname($path);
		if($parent == $path) exit;
		$path = $parent;
	}
	include $path.'/includes/404.php';
}

$site_ids = $this->diafan->_route->id_module('news');
if(empty($site_ids))
{
	Custom::inc('includes/404.php');
}

$limit = 15;
$time = mktime(23, 59, 0, date("m"), date("d"), date("Y"));

$rows = DB::query_fetch_all("SELECT e.id, e.created, e.[name], e.[anons], e.site_id FROM {news} AS e"
.($this->diafan->configmodules('where_access_element', 'news') ? " LEFT JOIN {access} AS a ON a.element_id=e.id AND a.module_name='news' AND a.element_type='element'" : "")
." WHERE e.[act]='1' AND e.trash='0'"
." AND e.created<=%d AND e.date_start<=%d AND (e.date_finish=0 OR e.date_finish>=%d)"
.($this->diafan->configmodules('where_access_element', 'news') ? " AND (e.access='0' OR e.access='1' AND a.role_id=".$this->diafan->_users->role_id.")" : '')
." AND e.site_id IN (".implode(",", $site_ids).")"
." ORDER BY e.created DESC, e.id DESC LIMIT ".$limit, $time, $time, $time);

$last  = '';
$items  = '';

foreach ($rows as $row)
{
	$link = $this->diafan->_route->link($row["site_id"], $row["id"], "news");
	if(! $link)
	{
		continue;
	}
	if (empty($last))
	{
		$last = date("D, d F Y H:i:s T", $row['created']);
	}
	$items .= "
	<item>
		<title>".$this->diafan->prepare_xml($row['name'])."</title>
		<link>".BASE_PATH_HREF.$link."</link>
		<description>".$this->diafan->prepare_xml($row['anons'])."</description>
		<pubDate>".date("D, d F Y H:i:s T", $row['created'])."</pubDate>"
		.($this->diafan->configmodules("comments", "news", $row["site_id"]) ? "
		<comments>".BASE_PATH_HREF.$link."</comments>" : "")."
	</item>";
}

$xml = '<?xml version="1.0"?>
<rss version="2.0">
	<channel>
		<title>'.$this->diafan->_('Новости', false).'</title>
		<link>'.BASE_PATH_HREF.'</link>
		<description>'.$this->diafan->_('Последние новости', false).' '.BASE_URL.'.</description>
		<language>ru-ru</language>
		<lastBuildDate>'.$last.'</lastBuildDate>
		<generator>DIAFAN.CMS version '.VERSION_CMS.'</generator>
		'.$items.'
	</channel>
</rss>';

header('Content-type: application/xml; charset=utf-8'); 
header('Connection: close');
//header('Content-Length: '. utf::strlen($xml));
header('Date: '.date('r'));
echo $xml;
exit;