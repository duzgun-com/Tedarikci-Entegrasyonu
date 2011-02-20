<?php
/*
  $Id$ Yavuz Yasin Düzgün

  Tedarikçi Entegrasyonu, Açýk Kaynak Entegrasyon Çözümüdür
  http://www.duzgun.com

  Copyright (c) 2008 Duzgun.com

  Released under the GNU General Public License
*/

include('../config.php');
$db = mysql_connect(DB_SERVER, DB_SERVER_USERNAME, DB_SERVER_PASSWORD);
mysql_select_db(DB_DATABASE);
if(isset($_POST['submit']))
{
$tedarikci = preg_split('/-/',$_POST['td']);
$tedarikciid = $tedarikci[0];
$tedarikciname = $tedarikci[1];
@mysql_query("DELETE FROM `products_attributes` WHERE `products_id` in(SELECT osid FROM d_products where osid!=0 and vendor=$tedarikciid)");
@mysql_query("DELETE FROM `products_description` WHERE `products_id` in(SELECT osid FROM d_products where osid!=0 and vendor=$tedarikciid)");
@mysql_query("DELETE FROM `products_properties` WHERE `products_id` in(SELECT osid FROM d_products where osid!=0 and vendor=$tedarikciid)");
@mysql_query("DELETE FROM `products_to_categories` WHERE `products_id` in(SELECT osid FROM d_products where osid!=0 and vendor=$tedarikciid)");
@mysql_query("DELETE FROM `products` WHERE `products_id` in(SELECT osid FROM d_products where osid!=0 and vendor=$tedarikciid)");
$result = mysql_query("SELECT `osid` FROM `d_categories` WHERE `osid`!=0 and `vendor`=$tedarikciid");
while ($categories = mysql_fetch_array($result)) {
@mysql_query("DELETE FROM `categories` WHERE `categories_id`=".$categories["osid"]." and 1>(SELECT count(*) FROM `products_to_categories` WHERE `categories_id`=".$categories["osid"].")");
@mysql_query("DELETE FROM `categories_description` WHERE `categories_id`=".$categories["osid"]." and 1>(SELECT count(*) FROM `products_to_categories` WHERE `categories_id`=".$categories["osid"].")");
}
@mysql_query("DELETE FROM d_attr where vendor=$tedarikciid");
@mysql_query("DELETE FROM d_attrkey where vendor=$tedarikciid");
@mysql_query("DELETE FROM d_attrval where vendor=$tedarikciid");
@mysql_query("DELETE FROM d_brands where vendor=$tedarikciid");
@mysql_query("DELETE FROM d_categories where vendor=$tedarikciid");
@mysql_query("DELETE FROM d_currency where vendor=$tedarikciid");
@mysql_query("DELETE FROM d_images where vendor=$tedarikciid");
@mysql_query("DELETE FROM d_keys where vendor=$tedarikciid");
@mysql_query("DELETE FROM d_products where vendor=$tedarikciid");
@mysql_query("DELETE FROM d_qtstock where vendor=$tedarikciid");
@mysql_query("DELETE FROM d_special where vendor=$tedarikciid");
@mysql_query("DELETE FROM d_task where vendor=$tedarikciid");
@mysql_query("DELETE FROM d_taxs where vendor=$tedarikciid");
@mysql_query("DELETE FROM d_tokeyvalues where vendor=$tedarikciid");
@mysql_query("DELETE FROM d_values where vendor=$tedarikciid");
@mysql_query("DELETE FROM d_vendors where id=$tedarikciid");
$dirname = $image_directory.$tedarikciname;
if (is_dir($dirname)) delete_directory($dirname);
echo "<b>".$tedarikciname. "</b> modülü Tüm kayýtlarýyla silindi.<br> Modülü iptal etmek için <b>modules/$tedarikciname.php</b> dosyasýný silmeniz yeterlidir.<br> Dilerseniz modülü yeniden de kurabilirsiniz.";
}
else
{
?>
<form id="form1" name="form1" method="post" action="deletemodule.php">
<select  name="td" id="td">
<?php
$result = mysql_query("SELECT id, vdname FROM `d_vendors` Order by vdname");
while ($vendors = mysql_fetch_array($result)) {
?>
<option value="<?php echo $vendors["id"]; ?>-<?php echo $vendors["vdname"]; ?>"><?php echo $vendors["vdname"]; ?></option>
<?php
}
?>
</select>
<input type="submit" name="submit" id="submit" value="Seçili Olan Tedarikçiyi Silmek Ýstediðinizden Emin Misiniz?" />
</form>
<?php
}
function delete_directory($dirname) {
if (is_dir($dirname))
$dir_handle = opendir($dirname);
if (!$dir_handle)
return false;
while($file = readdir($dir_handle)) {
if ($file != "." && $file != "..") {
if (!is_dir($dirname."/".$file))
unlink($dirname."/".$file);
else
delete_directory($dirname.'/'.$file);
}
}
closedir($dir_handle);
rmdir($dirname);
return true;
}
?>