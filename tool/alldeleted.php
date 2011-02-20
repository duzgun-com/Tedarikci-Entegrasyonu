<?php
/*
  $Id$ Yavuz Yasin Düzgün

  Tedarikçi Entegrasyonu, Açýk Kaynak Entegrasyon Çözümüdür
  http://www.duzgun.com

  Copyright (c) 2008 Duzgun.com

  Released under the GNU General Public License
*/

if(isset($_POST['submit']))
{
include('../config.php');
$db = mysql_connect(DB_SERVER, DB_SERVER_USERNAME, DB_SERVER_PASSWORD);
mysql_select_db(DB_DATABASE);
$TEXT_SQL = <<< EOPA
TRUNCATE `categories`;
TRUNCATE `categories_description`;
TRUNCATE `d_attr`;
TRUNCATE `d_attrkey`;
TRUNCATE `d_attrval`;
TRUNCATE `d_brands`;
TRUNCATE `d_categories`;
TRUNCATE `d_config`;
TRUNCATE `d_currency`;
TRUNCATE `d_images`;
TRUNCATE `d_keys`;
TRUNCATE `d_keysgroup`;
TRUNCATE `d_products`;
TRUNCATE `d_qtstock`;
TRUNCATE `d_special`;
TRUNCATE `d_task`;
TRUNCATE `d_taxs`;
TRUNCATE `d_tokeyvalues`;
TRUNCATE `d_values`;
TRUNCATE `d_vendors`;
TRUNCATE `geo_zones`;
TRUNCATE `manufacturers`;
TRUNCATE `manufacturers_info`;
TRUNCATE `products`;
TRUNCATE `products_attributes`;
TRUNCATE `products_attributes_download`;
TRUNCATE `products_description`;
TRUNCATE `products_notifications`;
TRUNCATE `products_options`;
TRUNCATE `products_options_values`;
TRUNCATE `products_options_values_to_products_options`;
TRUNCATE `products_properties`;
TRUNCATE `products_prop_options`;
TRUNCATE `products_prop_options_values`;
TRUNCATE `products_prop_options_values_to_products_prop_options`;
TRUNCATE `products_to_categories`
EOPA;

$READ = explode ( ";", $TEXT_SQL );
foreach ( $READ AS $RED )
{
  mysql_query ( trim($RED) );
}

$modules = array();
if ($dir = @dir($module_directory)) {
while(($file = $dir->read()) !== false) {
if($file != '.' && $file != '..')
{
if (substr($file, strrpos($file, '.')) == '.php')
{
$modules[] = substr($file,0, strrpos($file, '.'));
}
}
}
$dir->close();
}
foreach ($modules as $module)
{
$dirname = $image_directory.$module;
if (is_dir($dirname)) delete_directory($dirname);
}
echo "Tüm kayýtlar silindi. T.E. Sýfýrdan kuruluma hazýrdýr.";
}
else
{
?>
<form id="form1" name="form1" method="post" action="alldeleted.php">
<input type="submit" name="submit" id="submit" value="Tüm Oscommerce ve Tedarikçi Ürün ve Kategori Kayýtlarýný Silmek Ýstediðinizden Emin Misiniz?" />
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