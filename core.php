<?php
/*
  $Id$ Yavuz Yasin Düzgün

  Tedarikçi Entegrasyonu, Açýk Kaynak Entegrasyon Çözümüdür
  http://www.duzgun.com

  Copyright (c) 2008 Duzgun.com

  Released under the GNU General Public License
*/

//0001 eklenti attribute fix
//0002 eklenti hidden category fix
//0003 eklenti split remote file, xml downloader
//0004 eklenti shutdown lock process
//0005 eklenti preparewhere
//0006 eklenti indirim özelliði
//0007 eklenti preauth
//0008 eklenti qtpro
//0009 product model  length no limit
//0009 product name  length no limit
//0009 product desc  update control
//0010 product name  update control
//0010 multible image adding feature
//0010 if image name was rename then updates
//0011 core_feature_setopt ürün özelliði fix lendi
//0012 Set stock=0 Where isupdate=0 kodu eklendi. Tedarik edilemeyen ürünler için stok 0 giriþi.
//0012 remove deleted products's images
//0012 disable / enable senkronizasyon
//0013 fix image parameter with core_product
//0014 transfer kilidi aç metodu eklendi
//0015 fix images PUPDATEIMAGE=1
//0016 Per Trans start, Parçalý Aktarým
//0016 congif get and set start
//0017 deleted image unlink fix
//0017 resimleri sil(dir is null) metodu eklendi. unlink kullanýlmadý.
//0017 core_special_setopt fonksiyonuna $pcode parametresi eklendi.
//0017 core_feature_setopt fonksiyonuna $pid parametresi eklendi.
//0017 core_option_setopt fonksiyonuna $pid parametresi eklendi.
//0017 core_image_setopt fonksiyonu eklendi.
//0018 imagedownload ve xmlread fonksiyonlarýna parçalý iþlem özelliði eklendi.
//0019 connector_transfer_insert bug return $osid yeni altyapý için eklendi
//0020 category parent_id özelliði eklendi.
//0021 core_product $insert özelliði eklendi.
//0021 core_isdeleted_setopt fonksiyonu eklendi.
//0022 -1 osid kontrolü
//0022 yeni eklenen kategoriler iptal seçili olarak eklensin.
//0022 Memory_Head $memory[$vendor]['cat'] deðiþkenine 2 nolu osid ve 3 nolu hidden anahtarý eklendi.
//0022 core_ishiddencat() fonksiyonu eklendi fonksiyon içinde cathidden_query iþlevi eklendi.
//0023 osid -1 ve hidden lerin isupdate lerini 0 olmasý ve tedarik edilemeyen kutusuna atýlmasýný saðyan sorgu eklendi
//0023 NOT: Category_Dump array values index 3 deðeri kategori iptal ise yüklenmiyor bu nedenle isset kullanýmý þarttýr.
//0024 $ISUPDATESTATUS global deðiþkeni eklendi.
//0025 keyvalues_query number parametresi eklendi. updatesql.php güncelleme eklendi. 0025 tarihli kodlarýn performans testi yapýlacak.
//0026 d_sppc tablosu, d_task içine sppc alaný, d_special içine sppc alaný eklendi. bayi aktif pasif özelliði ve Fiyat Transfer Ayarlarý , Ýndirimli Ürünler için bayi alný dinamik eklendi.
//0026 bayi modül desteði eklenmemiþtir. bayi true olduðunda Fiyat Transfer Ayarlarý , Ýndirimli Ürünler bölümlerinde taným yapýlabilir fakat iþleme alýnmaz.
//0027 core_product_cat() fonksiyonu eklendi.
//0027 connector_properties_length() fonksiyonu eklendi.
//0028 core_iscat() fonksiyonu eklendi.

include('config.php');
include('core/global.php');
include('core/oscommerce.php');
//0004 shutdown lock process
register_shutdown_function('shutdown');
$script_start = microtime_float();
tep_db_connect();
$core_last_product_cat = 0;
$refcategories = array();
$refmarj = array();
$refspec = array();
$PMCHARLENGTH = isset($PMCHARLENGTH)?$PMCHARLENGTH:12;
$PNCHARLENGTH = isset($PNCHARLENGTH)?$PNCHARLENGTH:64;
$PDESCISUPDATE = isset($PDESCISUPDATE)?$PDESCISUPDATE:0;
$PNAMEISUPDATE = isset($PNAMEISUPDATE)?$PNAMEISUPDATE:0;
$PSUBIMAGELIMIT = isset($PSUBIMAGELIMIT)?$PSUBIMAGELIMIT:0;
$PUPDATEIMAGE = isset($PUPDATEIMAGE)?$PUPDATEIMAGE:0;
$PUPDATESTATUS= isset($PUPDATESTATUS)?$PUPDATESTATUS:false;
$PIMAGEDELETE = isset($PIMAGEDELETE)?$PIMAGEDELETE:0;
$ISHIDDENANEWCAT = isset($ISHIDDENANEWCAT)?$ISHIDDENANEWCAT:false;
$ISUPDATESTATUS = isset($ISUPDATESTATUS)?$ISUPDATESTATUS:false;
$file_extension = substr($PHP_SELF, strrpos($PHP_SELF, '.'));
$directory_array = array();

if ($dir = @dir($module_directory)) {
  while ($file = $dir->read()) {
    if (substr($file, strrpos($file, '.')) == $file_extension) {
      $directory_array[] = $file;
    }
  }
  sort($directory_array);
  $dir->close();
}

$installed_modules = array();
for ($i=0, $n=sizeof($directory_array); $i<$n; $i++) {
$file = $directory_array[$i];
include($module_directory . $file);
$class = substr($file, 0, strrpos($file, '.'));
if (tep_class_exists($class)) {
      $module = new $class;
      if ($module->check() > 0) {
        if ($module->sort_order > 0) {
          $installed_modules[$module->sort_order] = $class;
        } else {
          $installed_modules[] = $class;
        }
      }
    }
}

inc_header();

if ($_GET['process'] == 'run')
{
$start = isset($_GET['start'])?formatnumber($_GET['start']):0;
$limit = isset($_GET['limit'])?formatnumber($_GET['limit']):0;
$class = $_GET['module'];
$function = $_GET['function'];
$vendor = vendor($class);
if(MEMORY_HEAD =='true') memory_head();
$module = new $class;
if ($function=='product'&&$start==0) mysql_query('Update d_products Set isupdate=0 Where vendor='.$vendor,$db_link) or die(mysql_error());
if ($function=='stock') mysql_query('Update d_products Set stock=0 Where osid<>0 and vendor='.$vendor,$db_link) or die(mysql_error());
$result = $module->$function();
if ($function=='product'&&$result==null) mysql_query('Update d_products Set stock=0 Where isupdate=0 and osid<>0 and vendor='.$vendor,$db_link) or die(mysql_error());
if($result==1)echo "<center><br><p><a href=?process=run&module=".$class."&function=".$function."&start=".($start+$limit)."&limit=".$limit.">Parçalý Ýþlem Henüz Bitmedi.. Lütfen Tekrar Týklayýn.</a></p></center>";
else echo "<center><br><p><a href=?process=read>Ýþlem Tamamlandý! Devam etmek için Týklayýn.</a></p></center>";
}
else if ($_GET['process'] == 'read')
{
ksort($installed_modules);
foreach($installed_modules as $class)
{
$module = new $class;
?>
    <table  width="716" border="0" cellpadding="0" cellspacing="0">
    <tr>
        <td bgcolor="#9AC1E5">
        <table width="716" border="0" cellpadding="3" cellspacing="1">
        <tr>
            <td bgcolor="#FFFFFF" height="10"><font face="Georgia, Times New Roman, Times, serif" color="#0099FF"><?php echo $module->title;?></font></td>
        </tr>
        <tr>
            <td bgcolor="#FFFFFF"><br/>
                <table border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <?php
                    if($module->category){
                    ?>
                    <td width=100 align=center>
                    <a href="?process=run&module=<?php echo $class;?>&function=category"><image border=0 src="images/kategori.gif"/></a>
                    </td>
                    <?php
                    }
                    if($module->brand){
                    ?>
                    <td width=100 align=center>
                    <a href="?process=run&module=<?php echo $class;?>&function=brand"><image border=0 src="images/markalar.gif"/></a>
                    </td>
                    <?php
                    }
                    if($module->product){
                    ?>
                    <td width=100 align=center>
                    <a href="?process=run&module=<?php echo $class;?>&function=product"><image border=0 src="images/urunler.gif"/></a>
                    </td>
                    <?php
                    }
                    if($module->option){
                    ?>
                    <td width=100 align=center>
                    <a href="?process=run&module=<?php echo $class;?>&function=option"><image border=0 src="images/secenekler.gif"/></a>
                    </td>
                    <?php
                    }
                    if($module->feature){
                    ?>
                    <td width=100 align=center>
                    <a href="?process=run&module=<?php echo $class;?>&function=feature"><image border=0 src="images/ozellikler.gif"/></a>
                    </td>
                    <?php
                    }
                    if($module->price){
                    ?>
                    <td width=100 align=center>
                    <a href="?process=run&module=<?php echo $class;?>&function=price"><image border=0 src="images/fiyatlar.gif"/></a>
                    </td>
                    <?php
                    }
                    if($module->stock){
                    ?>
                    <td width=100 align=center>
                    <a href="?process=run&module=<?php echo $class;?>&function=stock"><image border=0 src="images/stoklar.gif"/></a>
                    </td>
                    <?php
                    }
                    ?>
                  </tr>
                </table><br/>
            </td>
        </tr>
        </table>

        </td>
    </tr>
    </table><br/>
<?php
}
}else if ($_GET['process'] == 'compare')
{
if(isset($_GET['module']) && !empty($_GET['module']))
{
if(in_array($_GET['module'],$installed_modules)){
$installed_modules = array($_GET['module']);
}
}
if(!isset($_GET['action'])){
compare();
}
else if ($_GET['action'] == 'language'){
language_modify();
}
else if ($_GET['action'] == 'tax'){
tax_modify();
}
else if ($_GET['action'] == 'currency'){
currency_modify();
}
else if ($_GET['action'] == 'category'){
category_modify();
}
else if ($_GET['action'] == 'brand'){
brand_modify();
}
else if ($_GET['action'] == 'product'){
product_modify();
}
else if ($_GET['action'] == 'option'){
option_modify();
}
else if ($_GET['action'] == 'feature'){
feature_modify();
}
}
else if ($_GET['process'] == 'imagedownload')
{
if(isset($_GET['module'])){
$start = isset($_GET['start'])?formatnumber($_GET['start']):0;
$limit = isset($_GET['limit'])?formatnumber($_GET['limit']):0;
$class = $_GET['module'];
$vendor = vendor($class);
$module = new $class;
$result==null;
if(isset($_GET['delete']))
mysql_query("UPDATE `d_images` SET `imagedir`=null,`thumbdir`=null Where vendor='".$vendor."'",$db_link);
else
$result=image_save($vendor,$module->code,(isset($module->imagepreadd)?$module->imagepreadd:false),(isset($module->imagefilter)?$module->imagefilter():false),$start,$limit);
if($result==1)echo "<center><br><p><a href=?process=imagedownload&module=".$class."&start=".($start+$limit)."&limit=".$limit.">Parçalý Ýþlem Henüz Bitmedi.. Lütfen Tekrar Týklayýn.</a></p></center>";
else echo "<center><br><p><a href=?process=imagedownload>Ýþlem Tamamlandý! Devam etmek için Týklayýn.</a></p></center>";
}
else
{
ksort($installed_modules);
foreach($installed_modules as $class)
{
$module = new $class;
$vendor = vendor($class);
//pre version T.E $result = mysql_query('SELECT count(*) FROM `d_products` WHERE `imagedir` IS NOT NULL AND vendor='.$vendor,$db_link);
$result = mysql_query('SELECT count(DISTINCT i.`proid`) AS total FROM `d_products` p,`d_images` i WHERE(p.`id`=i.`proid`) AND i.`imagedir` IS NOT NULL AND i.`vendor`='.$vendor,$db_link);
if (!$result) {
    die('Could not query:' . mysql_error());
}
$nume = 0;
if(mysql_num_rows($result)>0)
{
$nume = mysql_result($result, 0, 0);
}
?>
    <table  width="716" border="0" cellpadding="0" cellspacing="0">
    <tr>
        <td bgcolor="#9AC1E5">
        <table width="716" border="0" cellpadding="3" cellspacing="1">
        <tr>
            <td bgcolor="#FFFFFF" height="10">
            <div style="display:block;width:48%;float:left;text-align:left;"><font face="Georgia, Times New Roman, Times, serif" color="#0099FF"><?php echo $module->title;?></font></div>
            <div style="display:block;width:48%;float:right;text-align:right;"><a href="?process=imagedownload&module=<?php echo $class;?>&delete=all" style="text-decoration:none;color:#000">Sil</a></div>
            <div style="clear:both;"></div>
            </td>
        </tr>
        <tr>
            <td bgcolor="#FFFFFF"><br/>
                <table border="0" cellspacing="0" cellpadding="0">
                  <tr><td width=5 align=center></td>
                    <td width=100 align=center>
                    <a href="?process=imagedownload&module=<?php echo $class;?>"><image border=0 src="images/resimindir.gif"/></a>
                    </td><td align="right" width="100%"><font face="Georgia, Times New Roman, Times, serif" Size=2>Toplam: <?php echo $nume;?></font>&nbsp;</td>
                  </tr>
                </table><br/>
            </td>
        </tr>
        </table>

        </td>
    </tr>
    </table><br/>
<?php
}
}
}
else if ($_GET['process'] == 'xmldownload')
{
if(isset($_GET['module'])){
$class = $_GET['module'];
$vendor = vendor($class);
$module = new $class;
$start = false;
foreach ($module->xml as $key => $val){
if (file_exists($xml_directory.$module->code.'/'.$val.'.buff')){$start = true;}
}
foreach ($module->xml as $key => $val){
if(($start && file_exists($xml_directory.$module->code.'/'.$val.'.buff')) || !$start){
if(empty($module->xmlperbyte))
{
/*0007 eklenti end*/
if(empty($module->preauth))
{
download_file($key,$val,$xml_directory.$module->code.'/');
}
else
{
download_file($key,$val,$xml_directory.$module->code.'/',3600,$module->preauth($key));
}
/*0007 eklenti end*/
}
else
{
$success = 0;
$ch = new remote_file($key);
if($ch->X4324)   // allow_seek
download_remote_file($ch,$xml_directory.$module->code.'/'.$val,$module->xmlperbyte);
else
download_file($key,$val,$xml_directory.$module->code.'/');
}
}
}
// 0000 eklenti start
if(count($module->xml)==0)
{
if(!empty($module->preauth))
{
$success = ($module->preauth(''))?1:0;
}
}
// 0000 eklenti end
if($success==0)
{
echo "<center><FAILED/><br><p><a href=?process=xmldownload&module=".$module->code.">Baðlatý Kesildi! Lütfen Tekrar Deneyin.</a></p></center>";
}
else
{
echo "<center><SUCCESS/><br><p><a href=?process=xmldownload>Ýþlem Tamamlandý! Devam etmek için Týklayýn.</a></p></center>";
}
}
else
{
ksort($installed_modules);
foreach($installed_modules as $class)
{
$module = new $class;
?>
    <table  width="716" border="0" cellpadding="0" cellspacing="0">
    <tr>
        <td bgcolor="#9AC1E5">
        <table width="716" border="0" cellpadding="3" cellspacing="1">
        <tr>
            <td bgcolor="#FFFFFF" height="10"><font face="Georgia, Times New Roman, Times, serif" color="#0099FF"><?php echo $module->title;?></font></td>
        </tr>
        <tr>
            <td bgcolor="#FFFFFF"><br/>
                <table border="0" cellspacing="0" cellpadding="0">
                  <tr><td width=5 align=center></td>
                    <td width=100 align=center>
                    <a href="?process=xmldownload&module=<?php echo $class;?>"><image border=0 src="images/xmlindir.gif"/></a>
                    </td>
                  </tr>
                </table><br/>
            </td>
        </tr>
        </table>

        </td>
    </tr>
    </table><br/>
<?php
}
}
}
else if ($_GET['process'] == 'transfer')
{
$transfer_lock = config('transfer');
if(!$transfer_lock){
$endofprocess = 2;
config('transfer','true');
$defaultcurrency = connector_defaultcurrency();
$multicurry = config('multicurry');
$qtpro = config('qtpro');
$sppc = config('sppc');
$propertieslength = connector_properties_length();   //0027  properties length
// 0016 Per Trans start
$pertrans = config('pertrans');
$installed_modules_copy = array();
if($pertrans==true)$installed_modules_copy = $installed_modules;
$start = isset($_GET['start'])?formatnumber($_GET['start']):0;
$limit = isset($_GET['limit'])?formatnumber($_GET['limit']):0;
// 0016 Per Trans end

if(isset($_GET['module']) && !empty($_GET['module']))
{
if(in_array($_GET['module'],$installed_modules)){       //if (ereg('^[a-z\.A-Z\_0-9 -]*$',$_GET['module']))
$installed_modules = array($_GET['module']);
}
}
ksort($installed_modules);
if(($pertrans==false) || isset($_GET['module'])){  // 0016 Per Trans not block
foreach($installed_modules as $class)
{
$vendor = vendor($class);
category_dump($vendor);
$languageid=0;
$result = mysql_query('SELECT languageid FROM `d_vendors` WHERE id='.$vendor,$db_link);
if (!$result) {
    die('Could not query 1:' . mysql_error());
}
if(mysql_num_rows($result)>0)
{
$languageid = mysql_result($result, 0, 0);
}

connector_transfer_category($languageid);

spec_dump($vendor);

marj_dump($vendor);
$refmarjcat = array();
foreach($refcategories as $key=>$val){
if (isset($refmarj[3][$val[3]]))
{
   $refmarjcat[$val[3]] = $val[3];
}
else
{
if(strpos($val[1],'/') !== false)
{
foreach(array_reverse(split('/',$val[1])) as $vs){
if (isset($refmarj[3][$refcategories[$vs][3]]))
{
   $refmarjcat[$val[3]] = $refcategories[$vs][3];
   break;
}
}
}
}
}

$arr_brand = array();
connector_transfer_manufacturer($vendor,$languageid);

$arr_currency = array();
$result = mysql_query('SELECT `id`,`osid` FROM `d_currency` WHERE vendor='.$vendor,$db_link);
if (!$result) {
    die('Could not query:' . mysql_error());
}
if(mysql_num_rows($result)>0)
{
while ($currency = tep_db_fetch_array($result)) {
$arr_currency[$currency['id']]=$currency['osid'];
}
}

$arr_currency_value = array();
connector_transfer_currency($vendor,$languageid);

$arr_tax = array();
$result = mysql_query('SELECT `id`,`osid` FROM `d_taxs` WHERE vendor='.$vendor,$db_link);
if (!$result) {
    die('Could not query 2:' . mysql_error());
}
if(mysql_num_rows($result)>0)
{
while ($tax = tep_db_fetch_array($result)) {
$arr_tax[$tax['id']]=$tax['osid'];
}
}
/*
$subimages = array();
for ($i=1; $i<=$PSUBIMAGELIMIT; $i++)  {
$subimages[] = '`subimagedir'.$i.'`';
}
//deleted , `image`, `imagedir`'.(($PSUBIMAGELIMIT>0)?','.(implode(',',$subimages)):'').', `extraimage`
*/
$Array = array();
foreach($refcategories as $key=>$val)
{
$value = 0;
$value = (isset($val[3]))?(($val[3]=='-1')?1:0):1;
$value = (isset($val[0]))?(($val[0]=='1')?1:$value):1;
if($value==1)$Array[]=$key;
}
mysql_query("UPDATE `d_products` SET `isupdate` = 0 WHERE vendor=".$vendor." AND (`hidden`=1".(empty($Array)?'':' OR `catid` in ('.join(',',$Array).')').")",$db_link) or die(mysql_error());  //0023 osid -1 ve hidden lerin isupdate lerini 0 olmasý ve tedarik edilemeyen kutusuna atýlmasýný saðlar
$result = mysql_query('SELECT `id`, `pcode`, `pname`, `price1`, `price2`, `price3`, `currency`, `tax`, `stock`, `measure`, `catid`, `brand`, `desc`, `osid`, `isupdate`, `adddate`, `isdeleted`, `hidden`'.(($PUPDATEIMAGE===2)?',`imagelock`':'').' FROM `d_products` WHERE hidden=0 AND vendor='.$vendor.(($limit!=0)?(' Limit '.$start.','.$limit):''),$db_link);
if (!$result) {
    die('Could not query 3:' . mysql_error());
}
if(mysql_num_rows($result)>0)
{
while ($product = tep_db_fetch_array($result)) {
$cat_hidden = isset($refcategories[$product['catid']][0])?$refcategories[$product['catid']][0]:1;
$cat_hidden = isset($refcategories[$product['catid']][3])?(($refcategories[$product['catid']][3]<0)?1:$cat_hidden):$cat_hidden; //0022 -1 osid kontrolü
//$mfiyati = moneyformat($product['price1']);   snowbird 0011
$mfiyati = empty($product['price2'])?moneyformat($product['price1']):moneyformat($product['price2']);
$fiyati = 0.00;
$parabirimi = isset($arr_currency[$product['currency']])?$arr_currency[$product['currency']]:$defaultcurrency;
if($product['osid'] == 0)
{
    if($cat_hidden != 1){
    $fiyati = moneyformat($product['price1'])/$arr_currency_value[$parabirimi];

    // uygulanacak marjlar  baþla
    $marjuygula = array();
    if (isset($refmarj[3][$refmarjcat[$refcategories[$product['catid']][3]]]))
    {
    $marjuygula = $refmarj[3][$refmarjcat[$refcategories[$product['catid']][3]]];
    }
    else if (isset($refmarj[4][$vendor]))
    {
    $marjuygula = $refmarj[4][$vendor];
    }
    else if (isset($refmarj[0]))
    {
    $marjuygula = $refmarj[0];
    }
    // uygulanacak marjlar  bitiþ
    $fiyati = marjuygula($fiyati,$marjuygula);
    //0019 connector_transfer_insert return $osid yeni altyapý için eklendi
    $osid = connector_transfer_insert($osid,$product,$fiyati,$mfiyati,$parabirimi,$multicurry,$qtpro,$languageid,$vendor);
    }
}
else
{
    if($cat_hidden != 1){
    $fiyati = moneyformat($product['price1'])/$arr_currency_value[$parabirimi];
    $osid   = $product['osid'];
    // uygulanacak marjlar  baþla
    $marjuygula = array();
    if (isset($refmarj[2][$osid]))
    {
    $marjuygula = $refmarj[2][$osid];
    }
    else if (isset($refmarj[3][$refmarjcat[$refcategories[$product['catid']][3]]]))
    {
    $marjuygula = $refmarj[3][$refmarjcat[$refcategories[$product['catid']][3]]];
    }
    else if (isset($refmarj[4][$vendor]))
    {
    $marjuygula = $refmarj[4][$vendor];
    }
    else if (isset($refmarj[0]))
    {
    $marjuygula = $refmarj[0];
    }
    // uygulanacak marjlar  bitiþ
    $fiyati = marjuygula($fiyati,$marjuygula);
    connector_transfer_update($osid,$product,$fiyati,$mfiyati,$parabirimi,$multicurry,$qtpro,$languageid,$vendor);
    }
}
/*0002 eklenti start*/
if($cat_hidden != 1){
connector_transfer_extras($osid,$product,$fiyati,$mfiyati,$parabirimi,$multicurry,$qtpro,$languageid,$vendor);
}
/*0002 eklenti end*/
}
}

}
echo "<center><SUCCESS/>Entegrasyon Ýþlemi Tamamlandý!</center>";
if($pertrans==true)pertrans($start,$limit,(isset($_GET['module'])?$_GET['module']:''),$installed_modules_copy);  // 0016 Per Trans not block
}
else
{
if($pertrans==true)pertrans($start,$limit,(isset($_GET['module'])?$_GET['module']:''),$installed_modules_copy);   // 0016 Per Trans not block
}
$endofprocess = 0;
mysql_query("Update `d_config` set `value`='false' Where `name`='transfer'",$db_link);
}
else
{
echo '<center><CROSS/>Lütfen Bekleyin.. Þu an yürütülen baþka bir iþlem var. <strong><a href="?process=pairing&action=isactive&transfer=false">Kilidi aç</a></strong></center>';
}
}
else if ($_GET['process'] == 'setting')
{
inc_setting();
inc_setting_list();
}
else if ($_GET['process'] == 'special')
{
inc_special();
inc_special_list();
}
else if ($_GET['process'] == 'copyright')
{
inc_copyright();
}
else if ($_GET['process'] == 'deleted')
{
inc_deleted();
}
else if ($_GET['process'] == 'update')
{
echo '<br/>';
$ch = curl_init('http://www.duzgun.com/programaccess/TEUpdate.php?v=1.4.0.0&l=tr&c='.count($installed_modules));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
echo curl_exec($ch);
curl_close($ch);
}
else if ($_GET['process'] == 'pairing')
{
if(!isset($_GET['action'])){
?>
<ul style="list-style:none;text-align:center;display:block;">
<li style="height:50px;">
<a href="core.php?process=pairing&action=category">Kategori Eþleþtirme Sihirbazý</a></li>
<li style="height:50px;">
<a href="core.php?process=pairing&action=isactive">Modül Kullanýmý (Açýk /Kapalý) </a></li>
<?php
/*<li style="list-style: disc;">
<a href="core.php?process=pairing&function=product" style="display:block;width:80px">Ürün</a></li>
*/?>
</ul>
<?php
}
else if($_GET['action'] == 'isactive')
{
$multicurry = config('multicurry',(isset($_GET['multicurry'])?$_GET['multicurry']:null));
// 0014 Transfer start
$transfer = config('transfer',(isset($_GET['transfer'])?$_GET['transfer']:null));
// 0014 Transfer End
// 0008 QT Pro start
$qtpro = config('qtpro',(isset($_GET['qtpro'])?$_GET['qtpro']:null));
// 0008 QT Pro End
// 0016 Per Trans start
$pertrans = config('pertrans',(isset($_GET['pertrans'])?$_GET['pertrans']:null));
// 0016 Per Trans End
// 0026#1 SPPC start
$sppc = config('sppc',(isset($_GET['sppc'])?$_GET['sppc']:null));
// 0026#1 SPPC End
?>
<div align="center"><br><br><strong>
<a href="?process=pairing&action=isactive&pertrans=<?php echo ($pertrans==true)?"false":"true"; ?>">Parçalý Aktarým (<?php echo ($pertrans==true)?"Açýk":"Kapalý"; ?>)</a> <br><br>
<a href="?process=pairing&action=isactive&qtpro=<?php echo ($qtpro==true)?"false":"true"; ?>">QT Pro (<?php echo ($qtpro==true)?"Açýk":"Kapalý"; ?>)</a> <br><br>
<a href="?process=pairing&action=isactive&multicurry=<?php echo ($multicurry==true)?"false":"true"; ?>">Çoklu Para Birimi (<?php echo ($multicurry==true)?"Açýk":"Kapalý"; ?>)</a> <br><br>
<a href="?process=pairing&action=isactive&sppc=<?php echo ($sppc==true)?"false":"true"; ?>">Bayi Modülü (<?php echo ($sppc==true)?"Açýk":"Kapalý"; ?>)</a>
</strong></div>
<?php
}
else if ($_GET['action'] == 'category')
{
connector_pairing_category();
}
}
else
{
?><br/>
<strong>Açýklamalar:</strong><br />
    1. Xml kaynaðýndan indir; menüsünden tedarikçinizin modülünde tanýmlanan xml kaynak dosyalarýný indirin.<br />
    2. Xml verilerini oku; menüsünden tedarikçinizin xml verilerinin sisteminize kaydedilmesi için listelenen fonksiyonlarý týklayýn.<br />
    3. Sunucundan resimleri indir; menüsünden tedarikçinizin ürün resimlerini modülde belirttiðiniz kaynaktan indirir. Bu seçeneði resimlerin boyutlarýna ve sayýlarýna göre sunucunuzun timeout süresine göre bir kaç kez týklayabilirsiniz.<br />
    4. Transfer öncesi karþýlaþtýr; menüsünde yýldýzlý olarak belirtilen butonlar ilk kurulumda ayarlanmasý gereken tanýmlarý içerir bunlardan;
    <ol>
  <li>Transfer dili entegrasyon tanýmlarý; Tedarikçinizin hangi dilde xml verisi sunduðunu belirler.
  Tedarikçinizin Dil Id kýsmýna sað tarafta listelenen <?php echo CONNECTOR;?> Dil Id sinin numarasýný giriniz. Tanýmlama iþlemi sonucunda Kaydet e týklayýn.</li>
  <li>Para birimi entegrasyon tanýmlarý; Tedarikçinizin ürün bilgileri sisteme kaydedildiðinde ürün fiyatlandýrmasýnda kullanýlan para birimi kodlarý listelenmektedir. Bu kodlarý sað bölümde listelenen <?php echo CONNECTOR;?> Sistem Id ile eþleþtirip Kaydet e týklayýn.</li>
  <li>Vergi entegrasyonu tanýmlarý; Tedarikçinizin ürünler için uyguladýðý kdv cinsini <?php echo CONNECTOR;?> ile eþleþtirin ve Kaydet e týklayýn.</li>
</ol>
    <p>Diðer seçenekler ek opsiyon olarak verilmiþtir. Diðer seçenekler ile kategorileri veya ürünleri iptal edebilirsiniz veya birleþtirebilirsiniz.<br />
5. Entegrasyonu baþlat a týklayarak verilerin düzenli olarak <?php echo CONNECTOR;?> sisteminize eklenmesini ve sitenizde sunulmasýný saðlayabilirsiniz.<br />
6.
Fiyat Transfer Ayarlarý ile ürün bazlý, kategori bazlý yada tedarikçi bazlý fiyat tanýmlamalarý yapabilirsiniz. Bir sonraki Entegrasyonu baþta a týklamanýz durumunda fiyatlarýnýza sihirbaz ile tanýmladýðýnýz marjlar uygulanacaktýr.<br />
7. Tedarik Edilemeyen Ürünler menusundan tedarikçinizin ürünleri sisteminize eklendikten bir müddet sonra tedarikçiniz artýk o ürünü kaldýrmýþ olabilir. Sizde ayný ürünü sisteminizden silebilirsiniz.<br />
8. Kullaným sözleþmesini dikkatlice okuyunuz.</p>
    <p>Tedarikçi Entegrasyonunu kullandýðýnýz için teþekkür ederiz.</p>
<?php
}


Report();
inc_footer();
?>