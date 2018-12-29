<?php
/**
 * Шаблон платежа через систему Яндекс.Касса
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

/* echo $result["text"]; */
echo '<div class="info_pay">'.$result["text"].'</div>';
?>
<form id="pay" name="pay" method="POST" action="<?php echo $result["test"] ? 'https://demomoney.yandex.ru/eshop.xml' : 'https://money.yandex.ru/eshop.xml'; ?>">
	<input type="hidden" name="cms_name" value="diafan">
	<input name="shopID" value="<?php echo $result["shopid"]; ?>" type="hidden">
	<input name="scid" value="<?php echo $result["scid"]; ?>" type="hidden">
	<input name="sum" value="<?php echo $result["summ"]; ?>" type="hidden">
	<input name="customerNumber" value="<?php echo $result["cust_id"];?>" type="hidden"> 
	<input name="orderNumber" value="<?php echo $result["order_id"]; ?>" type="hidden">
	<input name="shopSuccessURL" value="<?php echo BASE_PATH_HREF.$result["cart_rewrite"].'/step3/';?>" type="hidden"> 
	<input name="shopFailURL" value="<?php echo BASE_PATH_HREF.$result["cart_rewrite"].'/step4/';?>" type="hidden"> 
	<input name="cps_email" value="<?php echo $result["cust_email"]; ?>" type="hidden">
	<input name="cps_phone" value="<?php echo $result["cust_phone"]; ?>" type="hidden">
	<input name="custName" value="<?php echo $result["cust_name"]; ?>" type="hidden">
	<input name="custAddr" value="<?php echo $result["cust_addr"]; ?>" type="hidden">
	<input name="custEMail" value="<?php echo $result["cust_email"]; ?>" type="hidden">
	
	<input name="orderDetails" value="<?php echo $result["order_details"];?>" type="hidden">
	
	<?php if(count($result["types"]) > 1)
	{ ?>
	<span class="tyui"><?php echo $this->diafan->_('Способ оплаты');?>: </span><select name="paymentType">
		<?php
		foreach($result["types"] as $k => $v)
		{
			echo '<option value="'.$k.'">'.$this->diafan->_($v, false).'</option>';
		}
		?>
	</select>
	<?php }
	elseif(count($result["types"]) == 1)
	{
		foreach($result["types"] as $k => $v)
		{
			echo '<input name="paymentType" type="hidden" value="'.$k.'">';
		}
	}
	?>
	<p><input type="submit" value="<?php echo $this->diafan->_('Оплатить', false);?>"></p>
</form>