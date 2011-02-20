<?php
/*
  $Id$ Yavuz Yasin D�zg�n

  Tedarik�i Entegrasyonu, A��k Kaynak Entegrasyon ��z�m�d�r
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

//Tan�mlaman�z gereken de�i�kenler.
/*
DB_CHARSET de�i�keni i�in gerekli tan�m.
+----------+-----------------------------+---------------------+--------+
| Charset  | Description                 | Default collation   | Maxlen |
+----------+-----------------------------+---------------------+--------+
| latin1   | ISO 8859-1 West European    | latin1_swedish_ci   | 1      |
| latin5   | ISO 8859-9 Turkish          | latin5_turkish_ci   | 1      |
| utf8     | UTF-8 Unicode               | utf8_general_ci     | 3      |
| ascii    | US ASCII                    | ascii_general_ci    | 1      |
+----------+-----------------------------+---------------------+--------+
latin1 i�in bo� b�rakabilirsiniz.
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

//$PHP_SELF sunucunuzda global de�iken olarak tan�ml� de�ilse $PHP_SELF de�i�keni tan�mlan�r.
$PHP_SELF = $_SERVER['PHP_SELF'];
//PHP CURL EXTENSION; CURLOPT_FOLLOWLOCATION destekli de�il ise false yap�n�z.
$FOLLOWLOCATION = false;
//products_model alan�n�n uzunlu�unu yaz�n.
$PMCHARLENGTH   = 30;
//products_name alan�n�n uzunlu�unu yaz�n.
$PNCHARLENGTH   = 64;
//products_desc i�in g�ncelleme i�lemi. 0=>g�ncelleme,1=>g�ncelle,2=> d_isupdate alan�na g�re davran 0=>g�ncelleme, 1=>g�ncelle
$PDESCISUPDATE  = 1;
//products_subimage limit
$PSUBIMAGELIMIT = 0;
//products_name i�in g�ncelleme i�lemi. 0=>g�ncelleme,1=>g�ncelle,2=> d_isupdate alan�na g�re davran 0=>g�ncelleme, 1=>g�ncelle
$PNAMEISUPDATE  = 1;
//products_image behavior 0 ise �ncelik tedarikci, 1 otomatik kullan�c�, 2 ise kullan�c� m�dahalesi ile imagelock field is true
$PUPDATEIMAGE = 1;
//�r�n disable / enable senkronizasyon �zelli�i tedarik�i mod�l�ne ba��ml� olsun ise true olmal�d�r. Mod�lde isdeleted alan� ile d�zenleme gerektirir. true kullan�ld� ise e-ticaret sisteminizden �r�n� disable etti�inizde isdeleted mod�lde tan�ml� de�ilse g�ncellemelerde otomatik enable e d�n��ecektir. Bu nedenle mod�lde isdeleted kullan�lmad��� durumda false tercih edilmelidir.
$PUPDATESTATUS = true;
//Tedarik edilemeyen �r�nler sildi�inide uygulanacak kural, 0=>Resimler kals�n, 1=>Sadece Tedarikciden gelen resimler silinsin, 2=>Tedarikciden gelen ve oscommerce aray�z�nden eklenenler dahil t�m� silinsin.
$PIMAGEDELETE = 2;
//Yeni eklenen kategoriler iptal olarak eklensin.
$ISHIDDENANEWCAT = true;
//�r�n disable / enable senkronizasyon �zelli�i isupdate ba��ml� olsun ise true olmal�d�r. isupdate de�eri tedarik edilemeyen �r�n anlam�na gelmektedir. isupdate kategori sistemid -1, kategori iptal, �r�n iptal ve art�k data kayna��ndan okunamayan �r�nlerde tedarik edilemeyen anlam�nda kullan�lmaktad�r.
$ISUPDATESTATUS = true;
?>