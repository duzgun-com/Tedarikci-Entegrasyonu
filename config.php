<?php
/*
  $Id$ Yavuz Yasin Düzgün

  Tedarikçi Entegrasyonu, Açýk Kaynak Entegrasyon Çözümüdür
  http://www.duzgun.com

  Copyright (c) 2008 Duzgun.com

  Released under the GNU General Public License
*/

ini_set('max_execution_time', '0'); // 0 = no limit
ini_set('safe_mode','off');
ini_set('display_errors','on');
ini_set('memory_limit','2000M');
// Set the level of error reporting
error_reporting(E_ALL & ~E_NOTICE);

//Tanýmlamanýz gereken deðiþkenler.
/*
DB_CHARSET deðiþkeni için gerekli taným.
+----------+-----------------------------+---------------------+--------+
| Charset  | Description                 | Default collation   | Maxlen |
+----------+-----------------------------+---------------------+--------+
| latin1   | ISO 8859-1 West European    | latin1_swedish_ci   | 1      |
| latin5   | ISO 8859-9 Turkish          | latin5_turkish_ci   | 1      |
| utf8     | UTF-8 Unicode               | utf8_general_ci     | 3      |
| ascii    | US ASCII                    | ascii_general_ci    | 1      |
+----------+-----------------------------+---------------------+--------+
latin1 için boþ býrakabilirsiniz.
*/
define('DB_CHARSET', '');
define('DB_COLLATE', '');
define('USE_PCONNECT', 'false');
define('MEMORY_HEAD', 'true');

define('DB_SERVER', 'localhost');
define('DB_SERVER_USERNAME', 'root');
define('DB_SERVER_PASSWORD', '111');
define('DB_DATABASE', 'catalog'); //

$module_directory = 'C:/inetpub/wwwroot/tedarikci/modules/';
$xml_directory =    'C:/inetpub/wwwroot/tedarikci/xml/';
$image_directory =  'C:/inetpub/wwwroot/images/';     // Oscommerce Images directory

//$PHP_SELF sunucunuzda global deðiken olarak tanýmlý deðilse $PHP_SELF deðiþkeni tanýmlanýr.
$PHP_SELF = $_SERVER['PHP_SELF'];
//PHP CURL EXTENSION; CURLOPT_FOLLOWLOCATION destekli deðil ise false yapýnýz.
$FOLLOWLOCATION = false;
//products_model alanýnýn uzunluðunu yazýn.
$PMCHARLENGTH   = 30;
//products_name alanýnýn uzunluðunu yazýn.
$PNCHARLENGTH   = 64;
//products_desc için güncelleme iþlemi. 0=>güncelleme,1=>güncelle,2=> d_isupdate alanýna göre davran 0=>güncelleme, 1=>güncelle
$PDESCISUPDATE  = 1;
//products_subimage limit
$PSUBIMAGELIMIT = 0;
//products_name için güncelleme iþlemi. 0=>güncelleme,1=>güncelle,2=> d_isupdate alanýna göre davran 0=>güncelleme, 1=>güncelle
$PNAMEISUPDATE  = 1;
//products_image behavior 0 ise öncelik tedarikci, 1 otomatik kullanýcý, 2 ise kullanýcý müdahalesi ile imagelock field is true
$PUPDATEIMAGE = 1;
//ürün disable / enable senkronizasyon özelliði tedarikçi modülüne baðýmlý olsun ise true olmalýdýr. Modülde isdeleted alaný ile düzenleme gerektirir. true kullanýldý ise e-ticaret sisteminizden ürünü disable ettiðinizde isdeleted modülde tanýmlý deðilse güncellemelerde otomatik enable e dönüþecektir. Bu nedenle modülde isdeleted kullanýlmadýðý durumda false tercih edilmelidir.
$PUPDATESTATUS = true;
//Tedarik edilemeyen ürünler sildiðinide uygulanacak kural, 0=>Resimler kalsýn, 1=>Sadece Tedarikciden gelen resimler silinsin, 2=>Tedarikciden gelen ve oscommerce arayüzünden eklenenler dahil tümü silinsin.
$PIMAGEDELETE = 2;
//Yeni eklenen kategoriler iptal olarak eklensin.
$ISHIDDENANEWCAT = true;
//ürün disable / enable senkronizasyon özelliði isupdate baðýmlý olsun ise true olmalýdýr. isupdate deðeri tedarik edilemeyen ürün anlamýna gelmektedir. isupdate kategori sistemid -1, kategori iptal, ürün iptal ve artýk data kaynaðýndan okunamayan ürünlerde tedarik edilemeyen anlamýnda kullanýlmaktadýr.
$ISUPDATESTATUS = true;
?>