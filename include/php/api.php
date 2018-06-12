<?php
/**
 * API Class 
 * @var Ambiguous $_API
 */

$_API['key'] = @isset($_POST['key']) ? $_POST['key'] : @$_GET['key'];
$_API['domain'] = $_SERVER['HTTP_HOST'];


/**
 * Analyze hidden user fields
 * 
 **/
$getArray = explode('&',$_SERVER["QUERY_STRING"]);
$hiddenFields = "";

foreach (preg_grep("/^forward\_.*/", $getArray) as $tmpValue)
{
    $vars = explode('=',$tmpValue);
    $key = $vars[0];
    $value = $vars[1];
    $hiddenFields .= "<input type=\"hidden\" name=\"$key\" value=\"$value\" />\n";
}

if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
    $_API['clientIP'] = $_SERVER['HTTP_CLIENT_IP'];
} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    $_API['clientIP'] = $_SERVER['HTTP_X_FORWARDED_FOR'];
} else {
    $_API['clientIP'] = $_SERVER['REMOTE_ADDR'];
}

require "sqlink.php";
$_USER['domain'] = $_SERVER['HTTP_HOST'];
if(!isset($_API['key']))
{
    die('Error #1511 - Missing API Key.');   
}

if(!LindaSQL::verifyAPIKey($_API['key'], $_API['domain']))
{
    die('Error #1512 - Could not verify domain ownership.');
}



$width = @isset($_GET['width']) ? $_GET['width'] : "128";
$height = @isset($_GET['height']) ? $_GET['height'] : "32";

?>

<form method="POST" action="http://localhost/linda_wallet/pay">
	<input type="hidden" name="key" value="<?=$_API['key'];?>" />
	<input type="hidden" name="domain" value="<?=$_API['domain'];?>" />
	<input type="hidden" name="ipClient" value="<?=$_API['clientIP'];?>" />
	<input type="hidden" name="itemID" value="1" />
	<?=$hiddenFields;?>
	<input type="image" name="submit_blue" value="blue" alt="blue" style="width: <?=$width;?>px; height: <?=$height;?>px;" src="https://www.atvzone.ca/product_images/uploaded_images/paynow.png">
</form>

