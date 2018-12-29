<?php
/**
 * Шаблон одного отзыва
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

echo '<div class="block-row">';	

	echo '<div class="block-text">';

//echo '<a name="comment'.$result["id"].'"></a>';
if (! empty($result["name"]))
{
	if(is_array($result["name"]))
	{
		$fio = '';
		if(array_key_exists('fio', $result["name"])) 
		{
			$fio .= $result["name"]["fio"];
		}
		$name = '';
		if (! empty($result["name"]["avatar"]))
		{
			$name .= '<img src="'.$result["name"]["avatar"].'" width="'.$result["name"]["avatar_width"].'" height="'.$result["name"]["avatar_height"].'" alt="'.$fio.'" class="avatar"> ';
		}
		$name .= $fio;
		
		if(! empty($result["name"]["user_page"]))
		{
			$name = $name;
		}
	}
	else
	{
		$name = $result["name"];
	}
	echo '<div class="reviews_name">';
	echo $name.'</div>';
}

foreach ($result["params"] as $param)
{

	echo '<div class="rev_param'.$param["id"].' reviews_param'.($param["type"] == 'title' ? '_title' : '').'"><span class="reviews_param__title">'.$param["name"].': </span>';
	if (! empty($param["value"]))
	{
		echo  '<span class="reviews_param_value">';
		switch($param["type"])
		{
			case "attachments":
				foreach ($param["value"] as $a)
				{
					if ($a["is_image"])
					{
						if($param["use_animation"])
						{
							echo ' <a href="'.$a["link"].'" rel="prettyPhoto[gallery'.$result["id"].'reviews]"><img src="'.$a["link_preview"].'"></a> <a href="'.$a["link"].'" rel="prettyPhoto[gallery'.$result["id"].'reviews_link]">'.$a["name"].'</a>';
						}
						else
						{
							echo ' <a href="'.$a["link"].'"><img src="'.$a["link_preview"].'"></a> <a href="'.$a["link"].'">'.$a["name"].'</a>';
						}
					}
					else
					{
						echo ' <a href="'.$a["link"].'">'.$a["name"].'</a>';
					}
				}
				break;

			case "images":
				foreach ($param["value"] as $img)
				{
					echo '<img src="'.$img["src"].'" width="'.$img["width"].'" height="'.$img["height"].'" alt="'.$img["alt"].'" title="'.$img["title"].'">';
				}
				break;

			case 'url':
				echo '<a href="'.$param["value"].'">'.$param["value"].'</a>';
				break;

			case 'email':
				echo '<a href="mailto:'.$param["value"].'">'.$param["value"].'</a>';
				break;
		case 'radio':		
			echo '<div class="ratina">';
			for($i=0; $i<5; $i++)
			{
			   echo '<i class="fa fa-star '.($i<$param["value"] ? 'act' : '').'" aria-hidden="true"></i>';	
			}   
			echo '</div>';
			break;			
 
			default:
				if (is_array($param["value"]))
				{
					foreach ($param["value"] as $p)
					{
						if ($param["value"][0] != $p)
						{
							echo  ', ';
						}
						if (is_array($p))
						{
							echo  $p["name"];
						}
						else
						{
							echo  $p;
						}
					}
				}
				else
				{
					echo $param["value"];
				}
				break;
		}
		echo  '</span>';
	}
	echo  '</div>';
}
echo '</div>';
echo '</div>';
