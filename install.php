<?php
/*
  $Id$ Yavuz Yasin Düzgün

  Tedarikçi Entegrasyonu, Açýk Kaynak Entegrasyon Çözümüdür
  http://www.duzgun.com

  Copyright (c) 2008 Duzgun.com

  Released under the GNU General Public License
*/
if(isset($_GET['step']))
{
include('config.php');
$db = mysql_connect(DB_SERVER, DB_SERVER_USERNAME, DB_SERVER_PASSWORD);
if(DB_CHARSET != '') mysql(DB_DATABASE,'SET NAMES '.DB_CHARSET);
mysql_select_db(DB_DATABASE);

$TEXT_SQL = <<< EOPA
CREATE TABLE IF NOT EXISTS `d_attr` (
  `id` int(11) NOT NULL auto_increment,
  `proid` int(11) default '0',
  `keyid` int(11) default '0',
  `valid` int(11) default '0',
  `price1` varchar(20) default NULL,
  `prcpre` varchar(20) default NULL,
  `prefix` varchar(1) default NULL,
  `stock` varchar(10) default NULL,
  `vendor` int(11) default '0',
  PRIMARY KEY  (`id`),
  KEY `proid` (`proid`,`vendor`)
) ENGINE=MyISAM AUTO_INCREMENT=1 ;
EOPA;
mysql_query ( trim($TEXT_SQL) );
$TEXT_SQL = <<< EOPA
CREATE TABLE IF NOT EXISTS `d_attrkey` (
  `id` int(11) NOT NULL auto_increment,
  `akname` varchar(250) default NULL,
  `vendor` int(11) default '0',
  `osid` int(11) default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 ;
EOPA;
mysql_query ( trim($TEXT_SQL) );
$TEXT_SQL = <<< EOPA
CREATE TABLE IF NOT EXISTS `d_attrval` (
  `id` int(11) NOT NULL auto_increment,
  `avname` varchar(250) default NULL,
  `keyid` int(11) default '0',
  `vendor` int(11) default '0',
  `osid` int(11) default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 ;
EOPA;
mysql_query ( trim($TEXT_SQL) );
$TEXT_SQL = <<< EOPA
CREATE TABLE IF NOT EXISTS `d_brands` (
  `id` int(11) NOT NULL auto_increment,
  `bcode` varchar(50) default NULL,
  `bname` varchar(250) default NULL,
  `vendor` int(11) default '0',
  `osid` int(11) default '0',
  PRIMARY KEY  (`id`),
  KEY `bcode` (`bcode`,`vendor`)
) ENGINE=MyISAM AUTO_INCREMENT=1 ;
EOPA;
mysql_query ( trim($TEXT_SQL) );
$TEXT_SQL = <<< EOPA
CREATE TABLE IF NOT EXISTS `d_categories` (
  `id` int(11) NOT NULL auto_increment,
  `code` varchar(50) default '',
  `cname` varchar(250) default NULL,
  `parentcode` varchar(50) default '',
  `vendor` int(11) default '0',
  `osid` int(11) default '0',
  `hidden` tinyint(1) default '0',
  PRIMARY KEY  (`id`),
  KEY `code` (`code`,`parentcode`,`vendor`)
) ENGINE=MyISAM AUTO_INCREMENT=1 ;
EOPA;
mysql_query ( trim($TEXT_SQL) );
$TEXT_SQL = <<< EOPA
CREATE TABLE IF NOT EXISTS `d_config` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(10) default NULL,
  `value` varchar(10) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 ;
EOPA;
mysql_query ( trim($TEXT_SQL) );
$TEXT_SQL = <<< EOPA
CREATE TABLE IF NOT EXISTS `d_currency` (
  `id` int(11) NOT NULL auto_increment,
  `code` varchar(50) default NULL,
  `name` varchar(50) default NULL,
  `osid` int(11) default '0',
  `vendor` int(11) default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 ;
EOPA;
mysql_query ( trim($TEXT_SQL) );
$TEXT_SQL = <<< EOPA
CREATE TABLE IF NOT EXISTS `d_keys` (
  `id` int(11) NOT NULL auto_increment,
  `kname` varchar(250) default NULL,
  `adjoin` varchar(10) default '',
  `catid` int(11) default '0',
  `vendor` int(11) default '0',
  `osid` int(11) default '0',
  PRIMARY KEY  (`id`),
  KEY `kname` (`kname`,`catid`,`vendor`)
) ENGINE=MyISAM AUTO_INCREMENT=1 ;
EOPA;
mysql_query ( trim($TEXT_SQL) );
$TEXT_SQL = <<< EOPA
CREATE TABLE IF NOT EXISTS `d_keysgroup` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(100) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 ;
EOPA;
mysql_query ( trim($TEXT_SQL) );
$TEXT_SQL = <<< EOPA
CREATE TABLE IF NOT EXISTS `d_products` (
  `id` int(11) NOT NULL auto_increment,
  `pcode` varchar(50) default NULL,
  `pname` varchar(250) default NULL,
  `price1` varchar(20) default NULL,
  `price2` varchar(20) default NULL,
  `price3` varchar(20) default NULL,
  `currency` int(11) default '0',
  `tax` int(11) default '0',
  `stock` varchar(10) default NULL,
  `measure` varchar(10) default NULL,
  `catid` int(11) default '0',
  `brand` int(11) default '0',
  `image` varchar(250) default NULL,
  `imagedir` varchar(100) default NULL,
  `extraimage` varchar(250) default NULL,
  `desc` text,
  `osid` int(11) default '0',
  `vendor` tinyint(2) default '0',
  `isupdate` tinyint(4) default '1',
  `adddate` timestamp NULL default CURRENT_TIMESTAMP,
  `isdeleted` tinyint(1) default '0',
  `hidden` tinyint(1) default '0',
  PRIMARY KEY  (`id`),
  KEY `pcode` (`pcode`,`vendor`),
  KEY `osid` (`osid`)
) ENGINE=MyISAM AUTO_INCREMENT=1 ;
EOPA;
mysql_query ( trim($TEXT_SQL) );
$TEXT_SQL = <<< EOPA
CREATE TABLE IF NOT EXISTS `d_task` (
  `id` int(4) NOT NULL auto_increment,
  `vendor` int(11) default '0',
  `category` int(11) default '0',
  `product` int(11) default '0',
  `attribute` int(11) default '0',
  `ei` tinyint(1) default '0',
  `eif` decimal(15,4) default '0.0000',
  `ea` tinyint(1) default '0',
  `eaf` decimal(15,4) default '0.0000',
  `f` tinyint(1) default '0',
  `fm` float default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 ;
EOPA;
mysql_query ( trim($TEXT_SQL) );
$TEXT_SQL = <<< EOPA
CREATE TABLE IF NOT EXISTS `d_taxs` (
  `id` int(11) NOT NULL auto_increment,
  `taxcode` varchar(50) default NULL,
  `taxname` varchar(50) default NULL,
  `osid` int(11) default '0',
  `vendor` tinyint(2) default '0',
  PRIMARY KEY  (`id`),
  KEY `id` (`taxcode`,`vendor`)
) ENGINE=MyISAM AUTO_INCREMENT=1 ;
EOPA;
mysql_query ( trim($TEXT_SQL) );
$TEXT_SQL = <<< EOPA
CREATE TABLE IF NOT EXISTS `d_tokeyvalues` (
  `id` int(11) NOT NULL auto_increment,
  `proid` int(11) default '0',
  `catid` int(11) default '0',
  `keyid` int(11) default '0',
  `valid` int(11) default '0',
  `vendor` int(11) default '0',
  `osid` int(11) default '0',
  PRIMARY KEY  (`id`),
  KEY `proid` (`proid`,`catid`,`keyid`,`valid`)
) ENGINE=MyISAM AUTO_INCREMENT=1 ;
EOPA;
mysql_query ( trim($TEXT_SQL) );
$TEXT_SQL = <<< EOPA
CREATE TABLE IF NOT EXISTS `d_values` (
  `id` int(11) NOT NULL auto_increment,
  `vname` varchar(250) default NULL,
  `keyid` int(11) default '0',
  `catid` int(11) default '0',
  `vendor` int(11) default '0',
  `osid` int(11) default '0',
  PRIMARY KEY  (`id`),
  KEY `vname` (`vname`,`keyid`,`catid`,`vendor`)
) ENGINE=MyISAM AUTO_INCREMENT=1 ;
EOPA;
mysql_query ( trim($TEXT_SQL) );
$TEXT_SQL = <<< EOPA
CREATE TABLE IF NOT EXISTS `d_vendors` (
  `id` tinyint(2) NOT NULL auto_increment,
  `vdname` varchar(250) default NULL,
  `languageid` tinyint(2) default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 ;
EOPA;
mysql_query ( trim($TEXT_SQL) );
$TEXT_SQL = <<< EOPA
CREATE TABLE IF NOT EXISTS `products_prop_options` (
  `products_options_id` int(11) NOT NULL default '1',
  `categories_options_id` int(11) NOT NULL default '0',
  `language_id` int(11) NOT NULL default '1',
  `products_options_name` varchar(32) NOT NULL default '',
  `firm` tinyint(1) default '0',
  PRIMARY KEY  (`products_options_id`,`language_id`),
  KEY `categories_options_id` (`categories_options_id`,`language_id`,`products_options_name`)
) ENGINE=MyISAM;
EOPA;
mysql_query ( trim($TEXT_SQL) );
$TEXT_SQL = <<< EOPA
CREATE TABLE IF NOT EXISTS `products_prop_options_values` (
  `products_options_values_id` int(11) NOT NULL default '1',
  `categories_options_values_id` int(11) NOT NULL default '0',
  `language_id` int(11) NOT NULL default '1',
  `products_options_values_name` varchar(64) NOT NULL default '',
  `firm` tinyint(1) default '0',
  PRIMARY KEY  (`products_options_values_id`,`language_id`),
  KEY `categories_options_values_id` (`categories_options_values_id`,`language_id`,`products_options_values_name`)
) ENGINE=MyISAM;
EOPA;
mysql_query ( trim($TEXT_SQL) );
$TEXT_SQL = <<< EOPA
CREATE TABLE IF NOT EXISTS `products_prop_options_values_to_products_prop_options` (
  `products_options_values_to_products_options_id` int(11) NOT NULL auto_increment,
  `products_options_id` int(11) NOT NULL default '0',
  `products_options_values_id` int(11) NOT NULL default '0',
  `firm` tinyint(1) default '0',
  PRIMARY KEY  (`products_options_values_to_products_options_id`),
  KEY `products_options_id` (`products_options_id`,`products_options_values_id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 ;
EOPA;
mysql_query ( trim($TEXT_SQL) );
$TEXT_SQL = <<< EOPA
CREATE TABLE IF NOT EXISTS `products_properties` (
  `products_attributes_id` int(11) NOT NULL auto_increment,
  `products_id` int(11) NOT NULL default '0',
  `categories_id` int(11) NOT NULL default '0',
  `options_id` int(11) NOT NULL default '0',
  `options_values_id` int(11) NOT NULL default '0',
  `sort_order` tinyint(4) default '0',
  `firm` tinyint(1) default '0',
  PRIMARY KEY  (`products_attributes_id`),
  KEY `products_id` (`products_id`,`categories_id`,`options_id`,`options_values_id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 ;
EOPA;
mysql_query ( trim($TEXT_SQL) );
$TEXT_SQL = <<< EOPA
CREATE TABLE IF NOT EXISTS `d_special` (
  `id` int(11) NOT NULL auto_increment,
  `vendor` int(11) default '0',
  `pcode` varchar(50) default '',
  `rate` tinyint(1) default '0',
  `discount` float default '0',
  `creator` tinyint(1) default '0',
  `osid` int(11) default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM;
EOPA;
mysql_query ( trim($TEXT_SQL) );

$result = mysql_query ("SHOW COLUMNS FROM `d_attr` LIKE 'isupdate'");
if (!$result) {
    die('Could not query:' . mysql_error());
}
if(mysql_num_rows($result)<1)
{
mysql_query ("ALTER TABLE `d_attr` ADD `isupdate` TINYINT( 1 ) NULL DEFAULT '1' AFTER `stock`");
}
$result = mysql_query ("SHOW COLUMNS FROM `d_attr` LIKE 'osid'");
if (!$result) {
    die('Could not query:' . mysql_error());
}
if(mysql_num_rows($result)<1)
{
mysql_query ("ALTER TABLE `d_attr` ADD `osid` INT NULL DEFAULT '0' AFTER `isupdate`");
}
$result = mysql_query ("SHOW COLUMNS FROM `d_attrkey` LIKE 'qtpro'");
if (!$result) {
    die('Could not query:' . mysql_error());
}
if(mysql_num_rows($result)<1)
{
mysql_query ("ALTER TABLE `d_attrkey` ADD `qtpro` TINYINT( 1 ) NULL DEFAULT '0' AFTER `akname`");
}
$TEXT_SQL = <<< EOPA
CREATE TABLE IF NOT EXISTS `d_qtstock` (
  `id` int(11) NOT NULL auto_increment,
  `proid` int(11) NOT NULL default '0',
  `attr` varchar(255) NOT NULL,
  `quantity` int(11) NOT NULL default '0',
  `isupdate` tinyint(1) default '1',
  `vendor` int(11) default '0',
  `osid` int(11) default '0',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `idx_attr` (`id`,`attr`)
) ENGINE=MyISAM AUTO_INCREMENT=1 ;
EOPA;
mysql_query ( trim($TEXT_SQL) );

//-- 18.03.2009 güncelleme

mysql_query ('ALTER TABLE `products` CHANGE `products_model` `products_model` VARCHAR( 50 ) DEFAULT NULL');

$result = mysql_query ("SHOW COLUMNS FROM `products_description` LIKE 'd_isupdate'");
if (!$result) {
    die('Could not query:' . mysql_error());
}
if(mysql_num_rows($result)<1)
{
mysql_query ("ALTER TABLE `products_description` ADD `d_isupdate` TINYINT( 1 ) NULL DEFAULT '1'");
}

$query = mysql_query ("SHOW TABLES LIKE 'd_images'");
if (!$query) {
    die('Could not query:' . mysql_error());
}
if(mysql_num_rows($query)<1)
{
$result = mysql_query ("SHOW COLUMNS FROM `d_products` LIKE 'subimage1'");
if (!$result) {
    die('Could not query:' . mysql_error());
}
if(mysql_num_rows($result)<1)
{
$TEXT_SQL = <<< EOPA
ALTER TABLE `d_products` ADD `subimage1` VARCHAR( 250 ) NULL AFTER `extraimage` ,
ADD `subimage2` VARCHAR( 250 ) NULL AFTER `subimage1` ,
ADD `subimage3` VARCHAR( 250 ) NULL AFTER `subimage2` ,
ADD `subimage4` VARCHAR( 250 ) NULL AFTER `subimage3` ,
ADD `subimage5` VARCHAR( 250 ) NULL AFTER `subimage4` ,
ADD `subimage6` VARCHAR( 250 ) NULL AFTER `subimage5` ,
ADD `subimagedir1` VARCHAR( 100 ) NULL AFTER `subimage6` ,
ADD `subimagedir2` VARCHAR( 100 ) NULL AFTER `subimagedir1` ,
ADD `subimagedir3` VARCHAR( 100 ) NULL AFTER `subimagedir2` ,
ADD `subimagedir4` VARCHAR( 100 ) NULL AFTER `subimagedir3` ,
ADD `subimagedir5` VARCHAR( 100 ) NULL AFTER `subimagedir4` ,
ADD `subimagedir6` VARCHAR( 100 ) NULL AFTER `subimagedir5` ;
EOPA;
mysql_query ( trim($TEXT_SQL) );
}
}

$result = mysql_query ("SHOW COLUMNS FROM `d_products` LIKE 'imagelock'");
if (!$result) {
    die('Could not query:' . mysql_error());
}
if(mysql_num_rows($result)<1)
{
mysql_query ("ALTER TABLE `d_products` ADD `imagelock` TINYINT( 1 ) NULL DEFAULT '0'");
}

$result = mysql_query ("SHOW COLUMNS FROM `d_tokeyvalues` LIKE 'isupdate'");
if (!$result) {
    die('Could not query:' . mysql_error());
}
if(mysql_num_rows($result)<1)
{
mysql_query ("ALTER TABLE `d_tokeyvalues` ADD `isupdate` TINYINT( 1 ) NULL DEFAULT '1' AFTER `valid`");
}

$result = mysql_query ("SHOW COLUMNS FROM `d_categories` LIKE 'ospi'");
if (!$result) {
    die('Could not query:' . mysql_error());
}
if(mysql_num_rows($result)<1)
{
mysql_query ("ALTER TABLE `d_categories` ADD `ospi` INT NULL DEFAULT '0' AFTER `osid`");
}

$result = mysql_query ("SHOW COLUMNS FROM `d_products` LIKE 'pcode2'");
if (!$result) {
    die('Could not query:' . mysql_error());
}
if(mysql_num_rows($result)<1)
{
mysql_query ("ALTER TABLE `d_products` ADD `pcode2` VARCHAR( 50 ) NULL AFTER `pcode`");
}

$result = mysql_query ("SHOW KEYS FROM `d_products`");
if (!$result) {
    die('Could not query:' . mysql_error());
}
if(mysql_num_rows($result)<1)
{
mysql_query ("ALTER TABLE `d_products` ADD INDEX ( `pcode2` )");
}
else
{
  $pcode2 = true;
  while ($response = mysql_fetch_array($result)) {
    if($response['Column_name']== 'pcode2') $pcode2 = false;
  }
  if($pcode2)mysql_query ("ALTER TABLE `d_products` ADD INDEX ( `pcode2` )");
}
$query = mysql_query ("SHOW TABLES LIKE 'd_images'");
if (!$query) {
    die('Could not query:' . mysql_error());
}
if(mysql_num_rows($query)<1)
{
$TEXT_SQL = <<< EOPA
CREATE TABLE IF NOT EXISTS `d_images` (
  `id` int(11) NOT NULL auto_increment,
  `proid` int(11) NOT NULL,
  `number` tinyint(2) NOT NULL default '0',
  `image` varchar(250) default '',
  `imagedir` varchar(250) default NULL,
  `imagex` int(5) default '0',
  `imagey` int(5) default '0',
  `thumb` varchar(250) default '',
  `thumbdir` varchar(250) default NULL,
  `thumbx` int(5) default '0',
  `thumby` int(5) default '0',
  `isupdate` tinyint(1) default '1',
  `vendor` tinyint(2) default '0',
  `osid` int(11) default '0',
  PRIMARY KEY  (`id`),
  KEY `proid` (`proid`)
) ENGINE=MyISAM;
EOPA;
mysql_query ( trim($TEXT_SQL) );
$TEXT_SQL = <<< EOPA
INSERT INTO `d_images` (
`proid` ,
`number` ,
`image` ,
`imagedir`,
`vendor`
)
SELECT `id`,0,`image`,`imagedir`,`vendor` FROM `d_products`
EOPA;
mysql_query ( trim($TEXT_SQL) );
$TEXT_SQL = <<< EOPA
ALTER TABLE `d_products`
  DROP `image`,
  DROP `imagedir`,
  DROP `extraimage`,
  DROP `subimage1`,
  DROP `subimage2`,
  DROP `subimage3`,
  DROP `subimage4`,
  DROP `subimage5`,
  DROP `subimage6`,
  DROP `subimagedir1`,
  DROP `subimagedir2`,
  DROP `subimagedir3`,
  DROP `subimagedir4`,
  DROP `subimagedir5`,
  DROP `subimagedir6`;
EOPA;
mysql_query ( trim($TEXT_SQL) );
}
// 23.12.2009 ve sonrasý..
$result = mysql_query ("SHOW COLUMNS FROM `d_categories` LIKE 'adddate'");
if (!$result) {
    die('Could not query:' . mysql_error());
}
if(mysql_num_rows($result)<1)
{
mysql_query ("ALTER TABLE `d_categories` ADD `adddate` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP");
}

$result = mysql_query ("SHOW COLUMNS FROM `d_brands` LIKE 'adddate'");
if (!$result) {
    die('Could not query:' . mysql_error());
}
if(mysql_num_rows($result)<1)
{
mysql_query ("ALTER TABLE `d_brands` ADD `adddate` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP");
}

$result = mysql_query ("SHOW COLUMNS FROM `d_tokeyvalues` LIKE 'number'");
if (!$result) {
    die('Could not query:' . mysql_error());
}
if(mysql_num_rows($result)<1)
{
mysql_query ("ALTER TABLE `d_tokeyvalues` ADD `number` TINYINT( 4 ) NULL DEFAULT '0'");
}

$query = mysql_query ("SHOW TABLES LIKE 'd_sppc'");
if (!$query) {
    die('Could not query:' . mysql_error());
}
if(mysql_num_rows($query)<1)
{
$TEXT_SQL = <<< EOPA
CREATE TABLE IF NOT EXISTS `d_sppc` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL,
  `osid` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 ;
EOPA;
mysql_query ( trim($TEXT_SQL) );
}

$result = mysql_query ("SHOW COLUMNS FROM `d_task` LIKE 'sppc'");
if (!$result) {
    die('Could not query:' . mysql_error());
}
if(mysql_num_rows($result)<1)
{
mysql_query ("ALTER TABLE `d_task` ADD `sppc` INT( 11 ) NULL DEFAULT '0'");
}

$result = mysql_query ("SHOW COLUMNS FROM `d_special` LIKE 'sppc'");
if (!$result) {
    die('Could not query:' . mysql_error());
}
if(mysql_num_rows($result)<1)
{
mysql_query ("ALTER TABLE `d_special` ADD `sppc` INT( 11 ) NULL DEFAULT '0'");
}

echo "<p><b>Tedarikçi Entegrasyonu v.1.4.0.0 SQL tablolarý aktarýldý.. </b><br><br><b>./tool/</b> dizininde kullanýþlý yardýmcý araçlarda bulunmaktadýr. ./tool/protection.php yi kullanarak Tedarikçi Entegrasyonu yazýlýmýný kurduðunuz dizini ve \$xml_directory ile tanýmlanan dizini farklý biçimde yetkilendirmeyi unutmayýnýz.</b><br><br>Tedarikçi entegrasyonunu tercih ettiðiniz için teþekkür ederiz.</p><p>http://www.duzgun.com<br />E-mail: admin@duzgun.com<br />";
}
else{
$serverfilepath = rtrim(str_replace('\\','/',getcwd()),'/').'/';
$serverfilepath_up = substr( $serverfilepath, 0, strrpos( $serverfilepath, '/', -2 ) )."/";
?>
<p><strong>Tedarikçi Entegrasyonu yazýlýmý kurulumu için aþaðýdaki iþlemleri uygulamalýsýnýz..</strong></p>
<p><strong>1.)</strong><br />
  Mevcut olan Tedarikçi entegrasyonu yazýlýmý dosyalarýný oscommerce kurulu olduðu dizine yeni bir dizin açarak kaydedin ve install.php dosyasýný çalýþtýrýn. Zaten bu þekilde ise bu maddeyi geçebilirsiniz.</p>
<p><strong>2.)</strong><br />
Dizin yetkilendirmeyi parola lý hale getirmek için unix sistemlerinde</p>
<p>.htaccess ve .htpasswd dosyasý kullanýmýyla<br />
  <a href="http://www.duzgun.com/oscommerce-guvenlik-security/parola-korumali-dizinler-icin-htaccess-ve-htpasswd-olusturucu-t-3037.html" target="_blank">http://www.duzgun.com/oscommerce-guvenlik-security/parola-korumali-dizinler-icin-htaccess-ve-htpasswd-olusturucu-t-3037.html</a><br />
  ile veya ./tool/protection.php yi kullanarak tedarikçi entegrasyonunun bulunduðu dizini ve xml dosyasýný ayrý ayrý þifreli hale getirin.<br />
  Bu günvenlik için önemli bir noktadýr.</p>
<p>Windows sistemlerinde ise dizin þifreleme için host arayüzünü kullanabilirsiniz.</p>
<p><b>Aþaðýdaki maddeleri uygulamak için config.php dosyasýný açýn.</b></p>
<p><strong>3.)</strong></p>
<p>define('DB_SERVER', '<b>localhost</b>');<br />
  define('DB_SERVER_USERNAME', '<b>root</b>');<br />
  define('DB_SERVER_PASSWORD', '<b>111</b>');<br />
  define('DB_DATABASE', '<b>oscommerce</b>');</p>
<p>deðiþkenlerine mysql bilgilerini yazýn.</p>
<p><strong>4.)</strong><br />
  Tedarikçi entegrasyonu sýk kullanýlan deðerleri bellekte tutabilir.<br />
Bu iþlemlerin hýzlý olmasýný saðlar.</p>
<p>define('MEMORY_HEAD', 'true');</p>
<p>MEMORY_HEAD global deðiþkenine true deðeri atadýðýnda sýk kullanýlan deðerler bellekte tutulur.</p>
<p><strong>5.)</strong><br />
$module_directory = '<b><?php echo $serverfilepath; ?>modules/</b>';</p>
<p>ile $module_directory deðiþkenine tedarikçi entegrasyonu indirdiðiniz dosya ile gelen &quot;modules&quot;<br />
  dizininin yolu yazýlýr sonuna &quot;/&quot; karakteri ekli olmalý</p>
<p><strong>6.)</strong><br />
$xml_directory = '<b><?php echo $serverfilepath; ?>xml/</b>';</p>
<p>ile $xml_directory deðiþkenine tedarikçi entegrasyonu indirdiðiniz dosya ile gelen &quot;xml&quot;<br />
  dizininin yolu yazýlýr sonuna &quot;/&quot; karakteri ekli olmalýdýr.<br />
  $xml_directory tanýmlanan dosyanýn chmod eriþimi 0777 olmalýdýr.</p>
<p><strong>7.)</strong><br />
$image_directory = '<b><?php echo $serverfilepath_up; ?>images/</b>';</p>
<p>ile $image_directory deðiþkenine oscommerce ana dizininizde bulunan &quot;images&quot; klasörünü tanýmlamanýz gerekir. Tedarikçi entegrasyonu indirilen tedarikçi resimlerini bu dizine kaydeder ve oscommerce<br />
  resim yolu olarak &quot;images/{moduladi}/{..}&quot; þeklinde kaydeder.<br />
  $image_directory tanýmlanan dosyanýn chmod eriþimi 0777 olmalýdýr.</p>
<p><strong>8.)<a href="install.php?step"> 1-7 maddeleri uyguladýysanýz sql tablolarýný yüklemek veya güncellemek için týklayýn.</a></strong></p><br /><br /><br />
<?php } ?>