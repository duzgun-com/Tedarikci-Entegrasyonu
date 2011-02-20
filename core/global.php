<?php
/*
  $Id$ Yavuz Yasin Düzgün

  Tedarikçi Entegrasyonu, Açýk Kaynak Entegrasyon Çözümüdür
  http://www.duzgun.com

  Copyright (c) 2008 Duzgun.com

  Released under the GNU General Public License
*/

/* 0021 eklenti start */
function core_isdeleted_setopt($start,$pval=false,$sval=false, $link = 'db_link'){
global $$link,$module,$vendor;
if($start){
if($pval)
mysql_query('Update d_products Set price1=null Where isupdate=1 and vendor='.$vendor,$$link) or die(mysql_error());
if($sval)
mysql_query('Update d_products Set stock=0 Where isupdate=1 and vendor='.$vendor,$$link) or die(mysql_error());
}
else
{
if($pval)
mysql_query('Update d_products Set isdeleted=1 Where (price1=0 OR price1=\'\' OR price1 is null) and isupdate=1 and vendor='.$vendor,$$link) or die(mysql_error());
if($sval)
mysql_query('Update d_products Set isdeleted=1 Where (stock=0 OR stock=\'\' OR stock is null) and isupdate=1 and vendor='.$vendor,$$link) or die(mysql_error());
}
}
/* 0021 eklenti end */
/* 0017 eklenti start */
function core_image_setopt($start,$pid=0, $link = 'db_link'){
global $$link,$module,$vendor,$image_directory,$PIMAGEDELETE;
if($start){
mysql_query('Update d_images Set isupdate=0 Where '.(($pid!=0)?'proid='.$pid.' and ':'').'vendor='.$vendor,$$link) or die(mysql_error());
}
else
{
$result = mysql_query('SELECT id,proid,number,imagedir,thumbdir,osid FROM `d_images` WHERE '.(($pid!=0)?'proid='.$pid.' and ':'').'`isupdate`=0 and vendor='.$vendor,$$link);
if (!$result) {
    die('Could not query:' . mysql_error());
}
if(mysql_num_rows($result)>0)
{
while ($mem = tep_db_fetch_array($result)) {
if(($PIMAGEDELETE!=0)&&($mem['imagedir'] != ''))@unlink($image_directory.$mem['imagedir']);
if(($PIMAGEDELETE!=0)&&($mem['thumbdir'] != ''))@unlink($image_directory.$mem['thumbdir']);
connector_image_setopt($mem['osid']);
}
}
mysql_query("DELETE FROM `d_images` Where ".(($pid!=0)?'proid='.$pid.' and ':'')."isupdate=0 and vendor=".$vendor,$$link);
}
}
/* 0017 eklenti end */
// 0016 congif get and set start
function config($key,$val=null,$link = 'db_link')
{
global $$link;
$result = mysql_query("select value from `d_config` Where `name`='".tep_db_input($key)."'",$$link);
if (!$result) {
die('Could not query:' . mysql_error());
}
$reval = false;
if(mysql_num_rows($result)>0)
{
if(mysql_result($result, 0, 0)=="true")$reval = true;
else if(mysql_result($result, 0, 0)=="false")$reval = false;
else $reval = mysql_result($result, 0, 0);
}else
{
$reval = false;
}
if(!empty($val)){
if(mysql_num_rows($result)>0)
{
mysql_query("Update `d_config` set `value`='".tep_db_input($val)."' Where `name`='".tep_db_input($key)."'",$$link);
}else
{
mysql_query("INSERT INTO `d_config` (`name` , `value`)VALUES ('".tep_db_input($key)."','".tep_db_input($val)."')",$$link);
}
$reval = ($val =="true")?true:$val;
$reval = ($val =="false")?false:$val;
}
return $reval;
}
// 0016 congif get and set end

function marjuygula($fiyati,&$marjuygula)
{
foreach ($marjuygula as $mrj)
    {
        if($mrj[0] == 0)
        {
           if($mrj[6]!=null){
           if($mrj[5]==1)$fiyati=$fiyati*$mrj[6];
           else if($mrj[5]==2)$fiyati=$fiyati/$mrj[6];
           else if($mrj[5]==3)$fiyati=$fiyati-$mrj[6];
           else if($mrj[5]==4)$fiyati=$fiyati+$mrj[6];}
        }
        else if($mrj[0] == 1)
        {
           if($mrj[1]==1){
           if($fiyati==$mrj[2])
           if($mrj[6]!=null)
           if($mrj[5]==1)$fiyati=$fiyati*$mrj[6];
           else if($mrj[5]==2)$fiyati=$fiyati/$mrj[6];
           else if($mrj[5]==3)$fiyati=$fiyati-$mrj[6];
           else if($mrj[5]==4)$fiyati=$fiyati+$mrj[6];}
           else if ($mrj[1]==2){
           if($fiyati>$mrj[2])
           if($mrj[6]!=null)
           if($mrj[5]==1)$fiyati=$fiyati*$mrj[6];
           else if($mrj[5]==2)$fiyati=$fiyati/$mrj[6];
           else if($mrj[5]==3)$fiyati=$fiyati-$mrj[6];
           else if($mrj[5]==4)$fiyati=$fiyati+$mrj[6];}
           else if ($mrj[1]==3){
           if($fiyati<$mrj[2])
           if($mrj[6]!=null)
           if($mrj[5]==1)$fiyati=$fiyati*$mrj[6];
           else if($mrj[5]==2)$fiyati=$fiyati/$mrj[6];
           else if($mrj[5]==3)$fiyati=$fiyati-$mrj[6];
           else if($mrj[5]==4)$fiyati=$fiyati+$mrj[6]; }
        }
        else if($mrj[0] == 2)
        {
           if($mrj[1]==1 && $mrj[3]==1){
           if($fiyati==$mrj[2] && $fiyati==$mrj[4])
           if($mrj[6]!=null)
           if($mrj[5]==1)$fiyati=$fiyati*$mrj[6];
           else if($mrj[5]==2)$fiyati=$fiyati/$mrj[6];
           else if($mrj[5]==3)$fiyati=$fiyati-$mrj[6];
           else if($mrj[5]==4)$fiyati=$fiyati+$mrj[6];}
           else if($mrj[1]==1 && $mrj[3]==2){
           if($fiyati==$mrj[2] && $fiyati>$mrj[4])
           if($mrj[6]!=null)
           if($mrj[5]==1)$fiyati=$fiyati*$mrj[6];
           else if($mrj[5]==2)$fiyati=$fiyati/$mrj[6];
           else if($mrj[5]==3)$fiyati=$fiyati-$mrj[6];
           else if($mrj[5]==4)$fiyati=$fiyati+$mrj[6];}
           else if($mrj[1]==1 && $mrj[3]==3){
           if($fiyati==$mrj[2] && $fiyati<$mrj[4])
           if($mrj[6]!=null)
           if($mrj[5]==1)$fiyati=$fiyati*$mrj[6];
           else if($mrj[5]==2)$fiyati=$fiyati/$mrj[6];
           else if($mrj[5]==3)$fiyati=$fiyati-$mrj[6];
           else if($mrj[5]==4)$fiyati=$fiyati+$mrj[6];}
           else if ($mrj[1]==2 && $mrj[3]==1){
           if($fiyati>$mrj[2]  && $fiyati==$mrj[4])
           if($mrj[6]!=null)
           if($mrj[5]==1)$fiyati=$fiyati*$mrj[6];
           else if($mrj[5]==2)$fiyati=$fiyati/$mrj[6];
           else if($mrj[5]==3)$fiyati=$fiyati-$mrj[6];
           else if($mrj[5]==4)$fiyati=$fiyati+$mrj[6];}
           else if ($mrj[1]==2 && $mrj[3]==2){
           if($fiyati>$mrj[2]  && $fiyati>$mrj[4])
           if($mrj[6]!=null)
           if($mrj[5]==1)$fiyati=$fiyati*$mrj[6];
           else if($mrj[5]==2)$fiyati=$fiyati/$mrj[6];
           else if($mrj[5]==3)$fiyati=$fiyati-$mrj[6];
           else if($mrj[5]==4)$fiyati=$fiyati+$mrj[6];}
           else if ($mrj[1]==2 && $mrj[3]==3){
           if($fiyati>$mrj[2]  && $fiyati<$mrj[4])
           if($mrj[6]!=null)
           if($mrj[5]==1)$fiyati=$fiyati*$mrj[6];
           else if($mrj[5]==2)$fiyati=$fiyati/$mrj[6];
           else if($mrj[5]==3)$fiyati=$fiyati-$mrj[6];
           else if($mrj[5]==4)$fiyati=$fiyati+$mrj[6];}
           else if ($mrj[1]==3 && $mrj[3]==1){
           if($fiyati<$mrj[2] && $fiyati==$mrj[4])
           if($mrj[6]!=null)
           if($mrj[5]==1)$fiyati=$fiyati*$mrj[6];
           else if($mrj[5]==2)$fiyati=$fiyati/$mrj[6];
           else if($mrj[5]==3)$fiyati=$fiyati-$mrj[6];
           else if($mrj[5]==4)$fiyati=$fiyati+$mrj[6];}
           else if ($mrj[1]==3 && $mrj[3]==2){
           if($fiyati<$mrj[2] && $fiyati>$mrj[4])
           if($mrj[6]!=null)
           if($mrj[5]==1)$fiyati=$fiyati*$mrj[6];
           else if($mrj[5]==2)$fiyati=$fiyati/$mrj[6];
           else if($mrj[5]==3)$fiyati=$fiyati-$mrj[6];
           else if($mrj[5]==4)$fiyati=$fiyati+$mrj[6];}
           else if ($mrj[1]==3 && $mrj[3]==3){
           if($fiyati<$mrj[2] && $fiyati<$mrj[4])
           if($mrj[6]!=null)
           if($mrj[5]==1)$fiyati=$fiyati*$mrj[6];
           else if($mrj[5]==2)$fiyati=$fiyati/$mrj[6];
           else if($mrj[5]==3)$fiyati=$fiyati-$mrj[6];
           else if($mrj[5]==4)$fiyati=$fiyati+$mrj[6];}
        }
    }
return $fiyati;
}
function formatnumber( $v )
{
$v = str_replace(",",".",$v);
$v = preg_replace("/[^0-9.]+/","",$v);
if($v=='')$v=0;
return $v;
}

function moneyformat($convertnum)
{
        if (!preg_match("/[0-9.,]/", $convertnum)) {
        $convertnum = 0;
        }
        if($convertnum=='')$convertnum=0;
        $convertnum = str_replace(",",".",$convertnum);
        $convertnum = preg_replace("/[^0-9.]/","",$convertnum);
        if (!preg_match("/[.]/", $convertnum)) {
        $convertnum = $convertnum.".00";
        }
        return $convertnum;
}

function mysqlgetdatenow(){
global $datetimenow;
if(empty($datetimenow))$datetimenow=date("Y-m-d H:i:s");
return $datetimenow;
}

function convert_datetime($str) {
list($date, $time) = explode(' ', $str);
list($year, $month, $day) = explode('-', $date);
list($hour, $minute, $second) = explode(':', $time);
$timestamp = mktime($hour, $minute, $second, $month, $day, $year);
return $timestamp;
}

function timestamp_to_mysqldatetime($timestamp = "", $datetime = true)
{
  if(empty($timestamp) || !is_numeric($timestamp)) $timestamp = time();

    return ($datetime) ? date("Y-m-d H:i:s", $timestamp) : date("Y-m-d", $timestamp);
}

function memory_head($link = 'db_link'){
global $$link,$vendor,$memory;
$result = mysql_query('SELECT id,taxcode FROM `d_taxs` WHERE vendor='.$vendor,$$link);
if (!$result) {
    die('Could not query:' . mysql_error());
}
if(mysql_num_rows($result)>0)
{
while ($mem = tep_db_fetch_array($result)) {
$memory[$vendor]['tax'][''.$mem['taxcode'].''] = $mem['id'];
}
}

$result = mysql_query('SELECT id,code FROM `d_currency` WHERE vendor='.$vendor,$$link);
if (!$result) {
    die('Could not query:' . mysql_error());
}
if(mysql_num_rows($result)>0)
{
while ($mem = tep_db_fetch_array($result)) {
$memory[$vendor]['currency'][''.$mem['code'].''] = $mem['id'];
}

}

$result = mysql_query('SELECT id,code,parentcode,osid,hidden FROM `d_categories` WHERE vendor='.$vendor,$$link); //0022 osid ve hidden parametresi eklendi db select
if (!$result) {
    die('Could not query:' . mysql_error());
}
if(mysql_num_rows($result)>0)
{
while ($mem = tep_db_fetch_array($result)) {
$memory[$vendor]['cat'][''.$mem['code'].''] = array(0 =>$mem['id'],1 =>$mem['parentcode'],2 =>$mem['osid'],3 =>$mem['hidden']);   //0022 osid ve hidden parametresi eklendi db select
}
}

$result = mysql_query('SELECT id,bcode FROM `d_brands` WHERE vendor='.$vendor,$$link);
if (!$result) {
    die('Could not query:' . mysql_error());
}
if(mysql_num_rows($result)>0)
{
while ($mem = tep_db_fetch_array($result)) {
$memory[$vendor]['brand'][''.$mem['bcode'].''] = $mem['id'];
}
}

$result = mysql_query('SELECT id, catid, kname FROM `d_keys` WHERE vendor='.$vendor,$$link);
if (!$result) {
    die('Could not query:' . mysql_error());
}
if(mysql_num_rows($result)>0)
{
while ($mem = tep_db_fetch_array($result)) {
$memory[$vendor]['key'][''.$mem['catid'].''][''.$mem['kname'].''] = $mem['id'];
}
}


$result = mysql_query('SELECT id, keyid, vname FROM `d_values` WHERE vendor='.$vendor,$$link);
if (!$result) {
    die('Could not query:' . mysql_error());
}
if(mysql_num_rows($result)>0)
{
while ($mem = tep_db_fetch_array($result)) {
$memory[$vendor]['val'][''.$mem['keyid'].''][''.$mem['vname'].''] = $mem['id'];
}
}

}

function core_category(){
global $module,$vendor;
$dataarray = array();
foreach($module->category as $key=>$value){
if($key != 'code' && $key != 'cname' && $key != 'parentcode') $dataarray[$key]=tep_db_input(getLATIN5($value));
}
cat_query($vendor,$module->category['code'],$module->category['cname'],$module->category['parentcode'],$dataarray);
}

function core_ishiddencat(){
global $module,$vendor;
return cathidden_query($vendor,$module->category['code']);
}

// 0028#1 begin
function core_iscat(){
global $module,$vendor;
return catis_query($vendor,$module->category['code']);
}
// 0028#1 end

function core_brand(){
global $module,$vendor;
$dataarray = array();
foreach($module->brand as $key=>$value){
if($key == 'bcode') $dataarray[$key] = $value;
else if($key == 'bname') $dataarray[$key] = tep_db_input(getLATIN5($value));
else $dataarray[$key] = tep_db_input(getLATIN5($value));
}
brand_query($vendor,$dataarray);
}

function core_product_id(){
global $module,$vendor;
$pcode ='';
$pcode2 ='';
foreach($module->product as $key=>$value){
if($key == 'pcode') $pcode = tep_db_input($value);
else if($key == 'pcode2') $pcode2 = tep_db_input($value);
}
return product_id_query($vendor,$pcode,$pcode2);
}
/*0006 eklenti start*/
function core_special(){
global $module,$vendor;
return special_query($vendor, tep_db_input($module->product['pcode']),5,tep_db_input($module->product['price']),1);
}

function core_special_setopt($start,$pcode='', $link = 'db_link'){  //0017 $pcode eklendi
global $module,$vendor,$$link;
if($start){
mysql_query('Update d_special Set creator=2 Where '.(($pcode!='')?'pcode=\''.$pcode.'\' and ':'').'creator=1 and vendor='.$vendor,$$link) or die(mysql_error());
}
else
{
$result = mysql_query('SELECT pcode FROM `d_special` WHERE '.(($pcode!='')?'pcode=\''.$pcode.'\' and ':'').'`creator`=2 and vendor='.$vendor,$$link);
if (!$result) {
    die('Could not query:' . mysql_error());
}
if(mysql_num_rows($result)>0)
{
while ($mem = tep_db_fetch_array($result)) {
$result_pro = mysql_query('SELECT osid FROM `d_products` WHERE pcode =\''.$mem['pcode'].'\' and vendor='.$vendor.' LIMIT 1',$$link);
if(mysql_num_rows($result_pro)>0)
{
$pro_osid = mysql_result($result_pro, 0, 0);
connector_special_setopt($pro_osid);
}
}
}
mysql_query("DELETE FROM `d_special` Where ".(($pcode!='')?'pcode=\''.$pcode.'\' and ':'')."`creator`=2 and vendor=".$vendor,$$link);
}
}
/*0006 eklenti end*/
/*0017#1 eklenti start*/
function core_image(){
global $module,$vendor,$core_last_product_cat;
$pid =0;
$number=0;
$dataarray = array();
foreach($module->image as $keyword=>$value){
if($keyword == 'pid') $pid = tep_db_input($value);
else if($keyword == 'number') $number = tep_db_input($value);
else if($keyword == 'type') $dataarray[$keyword] = ($value>0)?$value:0;
else $dataarray[$keyword] = tep_db_input($value);
}
return image_query($vendor,$pid,$number,$dataarray);
}

function image_query($vendor,$pid,$number,$dataarray, $link = 'db_link')
{
global $$link,$image_directory,$PIMAGEDELETE;
$result = mysql_query('SELECT id,image,thumb,imagedir,thumbdir FROM `d_images` WHERE number=\''.$number.'\' AND vendor='.$vendor.' AND proid=\''.$pid.'\' LIMIT 1',$$link);
if (!$result) {
    die('Could not query:' . mysql_error());
}
$img_id =0;
if(mysql_num_rows($result)<1)
{
$dataarray['vendor'] = $vendor;
$dataarray['proid'] = $pid;
$dataarray['number'] = $number;
$dataarray['isupdate'] = 1;
$query = mysql_query(tep_db_perform('d_images',$dataarray,'insert'),$$link);
$img_id = mysql_insert_id($$link);
}
else
{
$img_id = mysql_result($result, 0, 0);
//fix rename image start
if(isset($dataarray['image'])){
if(mysql_result($result, 0, 1)!= $dataarray['image'])
{
$dataarray['imagedir'] = 'null';
//0017 deleted image unlink fix start
//eðer daha önce resim kayýtlý ise dosya sil prosedurüne ekle
if(($PIMAGEDELETE!=0)&&(mysql_result($result, 0, 3) != ''))@unlink($image_directory.mysql_result($result, 0, 3));
//0017 deleted image unlink fix end
}
}
if(isset($dataarray['thumb'])){
if(mysql_result($result, 0, 2)!= $dataarray['thumb'])
{
$dataarray['thumbdir'] = 'null';
//0017 deleted image unlink fix start
//eðer daha önce resim kayýtlý ise dosya sil prosedurüne ekle
if(($PIMAGEDELETE!=0)&&(mysql_result($result, 0, 4) != ''))@unlink($image_directory.mysql_result($result, 0, 4));
//0017 deleted image unlink fix end
}
}
// fix rename image end
$dataarray['isupdate'] = 1;
mysql_query(tep_db_perform('d_images',$dataarray,'update','id='.$img_id),$$link);
}
return $img_id;
}
/*0017#1 eklenti end*/

function core_product(){
global $module,$vendor,$core_last_product_cat;
$pcode ='';
$pcode2 ='';
$dataarray = array();
$insert = (isset($module->insert))?$module->insert:true;
foreach($module->product as $key=>$value){
if($key == 'pcode') $pcode = tep_db_input($value);
else if($key == 'pcode2') $pcode2 = tep_db_input($value);
else if($key == 'pname') $dataarray[$key] = tep_db_input(getLATIN5($value));
else if($key == 'price1') $dataarray[$key] = tep_db_input($value);
else if($key == 'price2') $dataarray[$key] = tep_db_input($value);
else if($key == 'price3') $dataarray[$key] = tep_db_input($value);
else if($key == 'currency') $dataarray[$key] = currency_query($vendor,$value);
else if($key == 'tax') $dataarray[$key] = tax_query($vendor,$value);
else if($key == 'stock') $dataarray[$key] = tep_db_input($value);
else if($key == 'measure') $dataarray[$key] = tep_db_input($value);
else if($key == 'catid')
{
$core_last_product_cat = cat_query($vendor,$value,'','');
$dataarray[$key] = $core_last_product_cat;
}
else if($key == 'brand') $dataarray[$key] = brand_query($vendor,array('bcode'=>$value));
else if($key == 'image') $dataarray[$key] = tep_db_input($value);
else if($key == 'extraimage') $dataarray[$key] = tep_db_input($value);
else if($key == 'desc') $dataarray[$key] = tep_db_input(getLATIN5($value));
else if($key == 'isdeleted')
{
if($value !== null) $dataarray[$key] = tep_db_input(getLATIN5($value));
}
else $dataarray[$key] = $value;
}
return product_query($insert,$vendor,$pcode,$pcode2,$dataarray);
}
/* 0011 eklenti start */
function core_feature_setopt($start,$pid=0, $link = 'db_link'){  //0017 $pid eklendi
global $module,$vendor,$$link;
if($start){
mysql_query('Update d_tokeyvalues Set isupdate=0 Where '.(($pid!=0)?'proid='.$pid.' and ':'').'vendor='.$vendor,$$link) or die(mysql_error());
}
else
{
$result = mysql_query('SELECT proid,keyid,valid,osid FROM `d_tokeyvalues` WHERE '.(($pid!=0)?'proid='.$pid.' and ':'').'`isupdate`=0 and vendor='.$vendor,$$link);
if (!$result) {
    die('Could not query:' . mysql_error());
}
if(mysql_num_rows($result)>0)
{

while ($mem = tep_db_fetch_array($result)) {
if($mem['osid']==0)
{
$result_pro = mysql_query('SELECT osid FROM `d_products` WHERE id ='.$mem['proid'].' LIMIT 1',$$link);
$pro_osid = mysql_result($result_pro, 0, 0);
$result_keys = mysql_query('SELECT osid FROM `d_keys` WHERE id ='.$mem['keyid'].' LIMIT 1',$$link);
$pro_keys_osid = mysql_result($result_keys, 0, 0);
$result_values = mysql_query('SELECT osid FROM `d_values` WHERE id ='.$mem['valid'].' LIMIT 1',$$link);
$pro_values_osid = mysql_result($result_values, 0, 0);
connector_feature_setopt($mem['osid'],$pro_osid,$pro_keys_osid,$pro_values_osid);
}
else
{
connector_feature_setopt($mem['osid']);
}
}
}
mysql_query("DELETE FROM `d_tokeyvalues` Where ".(($pid!=0)?'proid='.$pid.' and ':'')."isupdate=0 and vendor=".$vendor,$$link);
}
}
/* 0011 eklenti end */
/*0027 eklenti start*/
function core_product_cat(){
global $module,$vendor;
$pid ='';$pcode ='';$pcode2 ='';
foreach($module->product as $key=>$value){
if($key == 'pid') $pid = tep_db_input($value);
else if($key == 'pcode') $pcode = tep_db_input($value);
else if($key == 'pcode2')$pcode2 = tep_db_input($value);
}
return product_cat_query($vendor,$pid,$pcode,$pcode2);
}
function product_cat_query($vendor,$pid,$pcode,$pcode2, $link = 'db_link')
{
global $$link,$core_last_product_cat;
$result = mysql_query('SELECT catid FROM `d_products` WHERE vendor='.$vendor.' AND '.((!empty($pcode))?'pcode=\''.$pcode.'\'':((!empty($pcode2))?'pcode2=\''.$pcode2.'\'':'id=\''.$pid.'\'')).' LIMIT 1',$$link);
if(!$result){die('Could not query:' . mysql_error());}
if(mysql_num_rows($result)>0){$core_last_product_cat = mysql_result($result, 0, 0);}
return $core_last_product_cat;
}
/*0027 eklenti end*/
function core_feature(){
global $module,$vendor,$core_last_product_cat;
$pid =0;
$key ='';
$val ='';
$number = 0;
foreach($module->feature as $keyword=>$value){
if($keyword == 'pid') $pid = $value;
if($keyword == 'key') $key = ((strlen($value)>250) ? substr($value,0,250): $value);
if($keyword == 'val') $val = ((strlen($value)>250) ? substr($value,0,250): $value);
if($keyword == 'number') $number = formatnumber($value);
}
$keyid = keys_query($key,$core_last_product_cat,$vendor);
$valid = value_query($val,$keyid,$core_last_product_cat,$vendor);
keyvalues_query($pid,$keyid,$valid,$core_last_product_cat,$number,$vendor);
}
/* 0001 eklenti start */
function core_option_setopt($start,$pid=0, $link = 'db_link'){ //0017 $pid eklendi
global $module,$vendor,$$link;
if($start){
mysql_query('Update d_attr Set isupdate=0 Where '.(($pid!=0)?'proid='.$pid.' and ':'').'vendor='.$vendor,$$link) or die(mysql_error());
}
else
{
$result = mysql_query('SELECT proid,keyid,valid,osid FROM `d_attr` WHERE '.(($pid!=0)?'proid='.$pid.' and ':'').'`isupdate`=0 and vendor='.$vendor,$$link);
if (!$result) {
    die('Could not query:' . mysql_error());
}
if(mysql_num_rows($result)>0)
{

while ($mem = tep_db_fetch_array($result)) {
if($mem['osid']==0)
{
$result_pro = mysql_query('SELECT osid FROM `d_products` WHERE id ='.$mem['proid'].' LIMIT 1',$$link);
$pro_osid = mysql_result($result_pro, 0, 0);
$result_keys = mysql_query('SELECT osid FROM `d_attrkey` WHERE id ='.$mem['keyid'].' LIMIT 1',$$link);
$pro_keys_osid = mysql_result($result_keys, 0, 0);
$result_values = mysql_query('SELECT osid FROM `d_attrval` WHERE id ='.$mem['valid'].' LIMIT 1',$$link);
$pro_values_osid = mysql_result($result_values, 0, 0);
connector_option_setopt($mem['osid'],$pro_osid,$pro_keys_osid,$pro_values_osid);
}
else
{
connector_option_setopt($mem['osid']);
}
}
}
mysql_query("DELETE FROM `d_attr` Where ".(($pid!=0)?'proid='.$pid.' and ':'')."isupdate=0 and vendor=".$vendor,$$link);
}
}
/* 0001 eklenti end */
function core_option(){
global $module,$vendor;
$pid =0;
$key ='';
$val ='';
$price1 ='';
$prcpre ='';
$prefix = '';
$stock = '';
$qtpro = null;
foreach($module->option as $keyword=>$value){
if($keyword == 'pid') $pid = $value;
if($keyword == 'key') $key = $value;
if($keyword == 'val') $val = $value;
if($keyword == 'price1') $price1 = $value;
if($keyword == 'prcpre') $prcpre = $value;
if($keyword == 'prefix') $prefix = $value;
if($keyword == 'stock') $stock = $value;
if($keyword == 'qtpro') $qtpro = $value;
}
$keyid = attrkeys_query($key,$qtpro,$vendor);
$valid = attrvalue_query($val,$keyid,$vendor);
return attrkeyvalues_query($pid,$keyid,$valid,$price1,$prcpre,$prefix,$stock,$vendor);
}
/* 0008 eklenti start */
function core_qtstock(){
global $module,$vendor;
$pid =0;
$qtpro=0;
$val ='';
foreach($module->qtstock as $keyword=>$value){
if($keyword == 'pid') $pid = $value;
if($keyword == 'attr') $val = $value;
if($keyword == 'quantity') $qtpro = $value;
}
return qtstock_query($pid,$val,$qtpro,$vendor);
}
/* 0008 eklenti end */
/* 0008 eklenti start */
function core_qtstock_setopt($start, $link = 'db_link'){
global $module,$vendor,$$link;
if($start){
mysql_query('Update `d_qtstock` Set isupdate=0 Where vendor='.$vendor,$$link) or die(mysql_error());
}
else
{
$result = mysql_query('SELECT osid FROM `d_qtstock` WHERE `isupdate`=0 and vendor='.$vendor,$$link);
if (!$result) {
    die('Could not query:' . mysql_error());
}
if(mysql_num_rows($result)>0)
{
while ($mem = tep_db_fetch_array($result)) {
connector_qtstock_setopt($mem['osid']);
}
}
mysql_query("DELETE FROM `d_qtstock` Where `isupdate`=0 and vendor=".$vendor,$$link);
}
}
/* 0008 eklenti end */

function Report() {
global $script_start;
print "<p><center>\n";
echo "<br>Süre: ".round((microtime_float()-$script_start) ,4)." saniye";
//print ", Bellek: ". round(memory_get_usage() / 1024 ,2) ." KB";
print "</p>\n";
}
function microtime_float()
{
    list($utime, $time) = explode(" ", microtime());
    return ((float)$utime + (float)$time);
}

function getLATIN5($str) {
$tr =array(
            "\xf6"  => 'o',
            "\xd6"  => 'o',
            "\x69"  => 'i',
            "\xd6"  => 'o',
            "\xfe"  => 's',
            "\xfd"  => 'Ý',
            "\xde"  => 's',
            "\xd0"  => 'g',
            "\xf0"  => 'g',
            "\xdd"  => 'i',
            "\xC2\xB7"  => "\xB7",
            "\xE2\x80\x98"  => "\x91",
            "\xE2\x80\x99"  => "\x92",
            "\xE2\x80\xA2" => "\x95",
            "\xE2\x80\xA6" =>  "\x85",
            "\xC4\xB1"    => 'ý',
            "\xC4\xB0"    => 'Ý',
            "\xC4\x9F"    => 'ð',
            "\xC4\x9E"    => 'Ð',
            "\xC3\x9C"    => 'Ü',
            "\xC3\xBC"    => 'ü',
            "\xC3\x87"    => 'Ç',
            "\xC3\xA7"    => 'ç',
            "\xC5\x9E"    => 'Þ',
            "\xC5\x9F"    => 'þ',
            "\xC3\x96"    => 'Ö',
            "\xC3\xB6"    => 'ö',
            "\xC2\xAE"    => '®',
            "\xC2\xB4"    => "'",
            "\xE2\x84\xA2" => '™'
);
return strtr($str,$tr);
}

function product_query($insert,$vendor,$pcode,$pcode2,$dataarray, $link = 'db_link')
{
global $$link;
$result = mysql_query('SELECT id FROM `d_products` WHERE vendor='.$vendor.' AND '.((empty($pcode))?'pcode2=\''.$pcode2.'\'':'pcode=\''.$pcode.'\'').' LIMIT 1',$$link);
if (!$result) {
    die('Could not query:' . mysql_error());
}
if((!empty($pcode)) && (!empty($pcode2))){
$dataarray['pcode2'] = $pcode2;
}
$pro_id =0;
if(mysql_num_rows($result)<1)
{
$dataarray['vendor'] = $vendor;
$dataarray['pcode'] = $pcode;
if((!empty($pcode)) && $insert){
$query = mysql_query(tep_db_perform('d_products',$dataarray,'insert'),$$link);
$pro_id = mysql_insert_id($$link);
}
}
else
{
$pro_id = mysql_result($result, 0, 0);
$dataarray['isupdate'] = 1;
mysql_query(tep_db_perform('d_products',$dataarray,'update','id='.$pro_id),$$link);
}
return $pro_id;
}

function product_id_query($vendor,$pcode,$pcode2, $link = 'db_link')
{
global $$link;
$result = mysql_query('SELECT id FROM `d_products` WHERE vendor='.$vendor.' AND '.((empty($pcode))?'pcode2=\''.$pcode2.'\'':'pcode=\''.$pcode.'\'').' LIMIT 1',$$link);
if (!$result) {
    die('Could not query:' . mysql_error());
}
$pro_id =0;
if(mysql_num_rows($result)>0)
{
$pro_id = mysql_result($result, 0, 0);
}
return $pro_id;
}
/*0006 eklenti start*/
function special_query($vendor,$pcode,$rate,$specialprise,$creator, $link = 'db_link')
{
global $$link;
$result = mysql_query('SELECT id FROM `d_special` WHERE pcode=\''.$pcode.'\' AND vendor='.$vendor.' LIMIT 1');
if (!$result) {
    die('Could not query:' . mysql_error());
}

$spec_id =0;
if(mysql_num_rows($result)<1)
{
$query = mysql_query("INSERT INTO `d_special` (`vendor` , `pcode` , `rate` , `discount`, `creator`) VALUES ('".$vendor."','".$pcode."','".$rate."','".$specialprise."','".$creator."')");
$spec_id = mysql_insert_id($$link);
}
else
{
$spec_id = mysql_result($result, 0, 0);                                                                                                                                                                                                /*,`isupdate`=1 eklenti 0001*/
mysql_query("UPDATE `d_special` SET `discount` = '".$specialprise."', `rate`='".$rate."', `creator`='".$creator."' Where id=$spec_id");
}
return $spec_id;
}
/*0006 eklenti end*/
function vendor($vendor, $link = 'db_link')
{
global $$link;
$result = mysql_query('SELECT id FROM `d_vendors` WHERE vdname=\''.tep_db_input($vendor).'\' LIMIT 1',$$link);
if (!$result) {
    die('Could not query:' . mysql_error());
}
$vendor_id =0;
if(mysql_num_rows($result)<1)
{
$query = mysql_query("INSERT INTO `d_vendors` (vdname) VALUES ('".tep_db_input($vendor)."')",$$link);
$vendor_id = mysql_insert_id($$link);
}
else
{
$vendor_id = mysql_result($result, 0, 0);
}
return $vendor_id;
}

function tax_query($vendor,$taxcode, $link = 'db_link')
{
global $$link,$memory;
if(MEMORY_HEAD =='true'){
$numrows = $memory[$vendor]['tax'][''.getLATIN5($taxcode).''];
}else
{
$result = mysql_query('SELECT id FROM `d_taxs` WHERE vendor='.$vendor.' AND taxcode=\''.tep_db_input(getLATIN5($taxcode)).'\' LIMIT 1',$$link);
if (!$result) {
    die('Could not query:' . mysql_error());
}
$numrows = mysql_num_rows($result);
}
$tax_id =0;
if($numrows<1)
{
$query = mysql_query("INSERT INTO `d_taxs` (taxcode,vendor) VALUES ('".tep_db_input(getLATIN5($taxcode))."',".$vendor.")",$$link);
$tax_id = mysql_insert_id($$link);
if(MEMORY_HEAD =='true') $memory[$vendor]['tax'][''.getLATIN5($taxcode).''] = $tax_id;
}
else
{
if(MEMORY_HEAD =='true')
$tax_id = $memory[$vendor]['tax'][''.getLATIN5($taxcode).''];
else
$tax_id = mysql_result($result, 0, 0);
}
return $tax_id;
}

function cat_query($vendor,$catcode,$catname,$parentcode,$dataarray=array(), $link = 'db_link')
{
global $$link,$memory,$ISHIDDENANEWCAT;
if(MEMORY_HEAD =='true'){
$numrows = $memory[$vendor]['cat'][''.getLATIN5($catcode).''][0];
}else
{
$result = mysql_query('SELECT id,parentcode FROM `d_categories` WHERE vendor='.$vendor.' AND code=\''.tep_db_input(getLATIN5($catcode)).'\' LIMIT 1',$$link);
if (!$result) {
    die('Could not query:' . mysql_error());
}
$numrows = mysql_num_rows($result);
}
$cat_id =0;
if($numrows<1)
{
$dataarray['code']=tep_db_input(getLATIN5($catcode));
$dataarray['cname']=tep_db_input(getLATIN5($catname));
$dataarray['parentcode']=tep_db_input(getLATIN5($parentcode));
$dataarray['vendor']=$vendor;
if($ISHIDDENANEWCAT==true) $dataarray['hidden'] = 1;  //0022 yeni eklenen kategoriler iptal seçili olarak eklensin.
$query = mysql_query(tep_db_perform('d_categories',$dataarray,'insert'),$$link);
$cat_id = mysql_insert_id($$link);
if(MEMORY_HEAD =='true') $memory[$vendor]['cat'][''.getLATIN5($catcode).''] = array(0 =>$cat_id,1 =>getLATIN5($parentcode),2=>0,3=>(($ISHIDDENANEWCAT==true)?1:0)); //0022 2 osid 3 hidden parametresi eklendi
}
else
{
if(MEMORY_HEAD =='true'){
$cat_id = $memory[$vendor]['cat'][''.getLATIN5($catcode).''][0];
$getparentcode = $memory[$vendor]['cat'][''.getLATIN5($catcode).''][1];
}else{
$cat_id = mysql_result($result, 0, 0);
$getparentcode = mysql_result($result, 0, 1);
}
if(($parentcode != '') && ($getparentcode != $parentcode))
mysql_query("UPDATE `d_categories` SET `parentcode` = '".tep_db_input(getLATIN5($parentcode))."' Where id=$cat_id");
if(!empty($dataarray))
mysql_query(tep_db_perform('d_categories',$dataarray,'update','id='.$cat_id),$$link);
}
return $cat_id;
}

function cathidden_query($vendor,$catcode,$link = 'db_link')
{
global $$link,$memory,$refcategories;
if(MEMORY_HEAD =='true'){
$cat_id = $memory[$vendor]['cat'][''.getLATIN5($catcode).''][0];
$osid = $memory[$vendor]['cat'][''.getLATIN5($catcode).''][2];
$hidden = $memory[$vendor]['cat'][''.getLATIN5($catcode).''][3];
}else
{
$result = mysql_query('SELECT id,osid,hidden FROM `d_categories` WHERE vendor='.$vendor.' AND code=\''.tep_db_input(getLATIN5($catcode)).'\' LIMIT 1',$$link);
if (!$result) {
    die('Could not query:' . mysql_error());
}
$cat_id = mysql_result($result, 0, 0);
$osid = mysql_result($result, 0, 1);
$hidden = mysql_result($result, 0, 2);
}
if (empty($refcategories)) {
category_dump($vendor);
}
$numrows = 0;
if($osid<0)$numrows = 1;
else if($hidden==1)$numrows = 1;
else if(isset($refcategories[$cat_id][0]) && $refcategories[$cat_id][0]==1)$numrows = 1;
return $numrows;
}

// 0028#2 begin
function catis_query($vendor,$catcode, $link = 'db_link')
{
global $$link,$memory;
if(MEMORY_HEAD =='true'){
$numrows = $memory[$vendor]['cat'][''.getLATIN5($catcode).''][0];
}else
{
$result = mysql_query('SELECT id,parentcode FROM `d_categories` WHERE vendor='.$vendor.' AND code=\''.tep_db_input(getLATIN5($catcode)).'\' LIMIT 1',$$link);
if (!$result) {
    die('Could not query:' . mysql_error());
}
$numrows = mysql_num_rows($result);
}
$cat_id =false;
if($numrows<1){$cat_id=false;}else{$cat_id=true;}
return $cat_id;
}
// 0028#2 end

function brand_query($vendor,$dataarray, $link = 'db_link')
{
global $$link,$memory;
$brandcode = $dataarray['bcode'];
$dataarray['bcode'] = tep_db_input(getLATIN5($brandcode));
$dataarray['vendor']=$vendor;
if(MEMORY_HEAD =='true'){
$numrows = $memory[$vendor]['brand'][''.getLATIN5($brandcode).''];
}else
{
$result = mysql_query('SELECT id FROM `d_brands` WHERE vendor='.$vendor.' AND bcode=\''.tep_db_input(getLATIN5($brandcode)).'\' LIMIT 1',$$link);
if (!$result) {
    die('Could not query:' . mysql_error());
}
$numrows = mysql_num_rows($result);
}
$brand_id =0;
if($numrows<1)
{
$query = mysql_query(tep_db_perform('d_brands',$dataarray,'insert'),$$link);
$brand_id = mysql_insert_id($$link);
if(MEMORY_HEAD =='true') $memory[$vendor]['brand'][''.getLATIN5($brandcode).''] = $brand_id;
}
else
{
if(MEMORY_HEAD =='true')
$brand_id = $memory[$vendor]['brand'][''.getLATIN5($brandcode).''];
else
$brand_id = mysql_result($result, 0, 0);
mysql_query(tep_db_perform('d_brands',$dataarray,'update','vendor='.$vendor.' and id='.$brand_id),$$link);
}
return $brand_id;
}


function keys_query($kname,$catid,$vendor, $link = 'db_link')
{
global $$link,$memory;
if(MEMORY_HEAD =='true'){
$numrows = $memory[$vendor]['key'][''.$catid.''][''.getLATIN5($kname).''];
}else
{
$result = mysql_query('SELECT id FROM `d_keys` WHERE vendor='.$vendor.' AND catid='.$catid.' AND kname=\''.tep_db_input(getLATIN5($kname)).'\' LIMIT 1',$$link);
if (!$result) {
    die('Could not query:' . mysql_error());
}
$numrows = mysql_num_rows($result);
}
$key_id =0;
if($numrows<1)
{
$query = mysql_query("INSERT INTO `d_keys` (kname,catid,vendor) VALUES ('".tep_db_input(getLATIN5($kname))."',".$catid.",".$vendor.")",$$link);
$key_id = mysql_insert_id($$link);
if(MEMORY_HEAD =='true') $memory[$vendor]['key'][''.$catid.''][''.getLATIN5($kname).''] = $key_id;
}
else
{
if(MEMORY_HEAD =='true')
$key_id = $memory[$vendor]['key'][''.$catid.''][''.getLATIN5($kname).''];
else
$key_id = mysql_result($result, 0, 0);
}
return $key_id;
}

function value_query($vname,$keyid,$catid,$vendor, $link = 'db_link')
{
global $$link,$memory;
if(MEMORY_HEAD =='true'){
$numrows = $memory[$vendor]['val'][''.$keyid.''][''.getLATIN5($vname).''];
}else
{
$result = mysql_query('SELECT id FROM `d_values` WHERE keyid='.$keyid.' AND vendor='.$vendor.' AND catid='.$catid.' AND vname=\''.tep_db_input(getLATIN5($vname)).'\' LIMIT 1',$$link);
if (!$result) {
    die('Could not query:' . mysql_error());
}
$numrows =mysql_num_rows($result) ;
}
$val_id =0;
if($numrows<1)
{
$query = mysql_query("INSERT INTO `d_values` (vname,keyid,catid,vendor) VALUES ('".tep_db_input(getLATIN5($vname))."',".$keyid.",".$catid.",".$vendor.")",$$link);
$val_id = mysql_insert_id($$link);
if(MEMORY_HEAD =='true') $memory[$vendor]['val'][''.$keyid.''][''.getLATIN5($vname).''] = $val_id;
}
else
{
if(MEMORY_HEAD =='true')
$val_id = $memory[$vendor]['val'][''.$keyid.''][''.getLATIN5($vname).''];
else
$val_id = mysql_result($result, 0, 0);
}
return $val_id;
}

function keyvalues_query($proid,$keyid,$valid,$catid,$number,$vendor, $link = 'db_link')
{
global $$link;
$result = mysql_query('SELECT id,number FROM `d_tokeyvalues` WHERE proid='.$proid.' AND keyid='.$keyid.' AND vendor='.$vendor.' AND catid='.$catid.' AND valid='.$valid.' LIMIT 1',$$link);
if (!$result) {
    die('Could not query:' . mysql_error());
}
if(mysql_num_rows($result)<1)
{
mysql_query("INSERT INTO `d_tokeyvalues` (proid,valid,keyid,catid,vendor".(($number!=0)?',number':'').") VALUES (".$proid.",".$valid.",".$keyid.",".$catid.",".$vendor.(($number!=0)?','.$number:'').")",$$link);
}
else
{                                                                                                                                                                                              /*,`isupdate`=1 eklenti 0001*/
mysql_query("UPDATE `d_tokeyvalues` SET `isupdate`=1".(($number!=0)?(($number!=mysql_result($result, 0, 1))?',`number`='.$number:''):'')." Where id=".mysql_result($result, 0, 0),$$link);
}      //0025 number sorgusu eklendi. selectte kullanýlan number alaný ve update deki karþýlaþtýrmasý performans açýsýndan deðerlendirilecek. önemli!!! bulgulara göre doðru kullanýmý tercih edilecek.
/*,`isupdate`=1 eklenti 0001#1*/
}

function attrkeyvalues_query($pid,$keyid,$valid,$price1,$prcpre,$prefix,$stock,$vendor, $link = 'db_link')
{
global $$link;
$result = mysql_query('SELECT id FROM `d_attr` WHERE proid='.$pid.' AND keyid='.$keyid.'  AND valid='.$valid.' AND vendor='.$vendor.' LIMIT 1',$$link);
if (!$result) {
    die('Could not query:' . mysql_error());
}

$pro_id =0;
if(mysql_num_rows($result)<1)
{
$query = mysql_query("INSERT INTO `d_attr` (`proid` , `keyid` , `valid` , `price1`, `prcpre`, `prefix` , `stock` , `vendor`) VALUES ('".$pid."','".$keyid."','".$valid."','".tep_db_input(getLATIN5($price1))."','".tep_db_input(getLATIN5($prcpre))."','".tep_db_input(getLATIN5($prefix))."','".tep_db_input(getLATIN5($stock))."','".$vendor."')",$$link);
$pro_id = mysql_insert_id($$link);
}
else
{
$pro_id = mysql_result($result, 0, 0);                                                                                                                                                                                                /*,`isupdate`=1 eklenti 0001*/
mysql_query("UPDATE `d_attr` SET `price1` = '".tep_db_input(getLATIN5($price1))."',`prcpre`= '".tep_db_input(getLATIN5($prcpre))."', `prefix`= '".tep_db_input(getLATIN5($prefix))."', `stock` = '".tep_db_input(getLATIN5($stock))."',`isupdate`=1 Where id=$pro_id",$$link);
}
return array($keyid,$valid);        //  $pro_id
}

function qtstock_query ($pid,$val,$qtpro,$vendor, $link = 'db_link')
{
global $$link;
$result = mysql_query('SELECT id,quantity FROM `d_qtstock` WHERE vendor='.$vendor.' AND proid='.$pid.' AND attr=\''.tep_db_input($val).'\' LIMIT 1',$$link);
if (!$result) {
    die('Could not query:' . mysql_error());
}
$key_id =0;
if(mysql_num_rows($result)<1)
{
$query = mysql_query("INSERT INTO `d_qtstock` (proid,attr,quantity,vendor) VALUES (".$pid.",'".tep_db_input($val)."','".tep_db_input($qtpro)."',".$vendor.")",$$link);
$key_id = mysql_insert_id($$link);
}
else
{
$key_id = mysql_result($result, 0, 0);
mysql_query("UPDATE `d_qtstock` SET `isupdate` = 1".((mysql_result($result, 0, 1)==$qtpro)?'':",quantity = '".tep_db_input($qtpro)."'")." Where id=$key_id",$$link);
}
return $key_id;
}

function attrkeys_query($akname,$qtpro,$vendor, $link = 'db_link')
{
global $$link;
$result = mysql_query('SELECT id '.(($qtpro===null)?'':',qtpro').' FROM `d_attrkey` WHERE vendor='.$vendor.' AND akname=\''.tep_db_input(getLATIN5($akname)).'\' LIMIT 1',$$link);
if (!$result) {
    die('Could not query:' . mysql_error());
}
$key_id =0;
if(mysql_num_rows($result)<1)
{
$query = mysql_query("INSERT INTO `d_attrkey` (akname,vendor".(($qtpro===null)?'':',qtpro').") VALUES ('".tep_db_input(getLATIN5($akname))."',".$vendor.(($qtpro===null)?'':','.tep_db_input($qtpro)).")",$$link);
$key_id = mysql_insert_id($$link);
}
else
{
$key_id = mysql_result($result, 0, 0);
if($qtpro!==null) mysql_query("UPDATE `d_attrkey` SET `qtpro` = '".tep_db_input($qtpro)."' Where id=$key_id",$$link);
}
return $key_id;
}

function attrvalue_query($avname,$keyid,$vendor, $link = 'db_link')
{
global $$link;
$result = mysql_query('SELECT id FROM `d_attrval` WHERE keyid='.$keyid.' AND vendor='.$vendor.' AND avname=\''.tep_db_input(getLATIN5($avname)).'\' LIMIT 1',$$link);
if (!$result) {
    die('Could not query:' . mysql_error());
}
$val_id =0;
if(mysql_num_rows($result)<1)
{
$query = mysql_query("INSERT INTO `d_attrval` (avname,keyid,vendor) VALUES ('".tep_db_input(getLATIN5($avname))."',".$keyid.",".$vendor.")",$$link);
$val_id = mysql_insert_id($$link);
}
else
{
$val_id = mysql_result($result, 0, 0);
}
return $val_id;
}

function currency_query($vendor,$currencycode, $link = 'db_link')
{
global $$link,$memory;
if(MEMORY_HEAD =='true'){
$numrows = $memory[$vendor]['currency'][''.getLATIN5($currencycode).''];
}else
{
$result = mysql_query('SELECT id FROM `d_currency` WHERE vendor='.$vendor.' AND code=\''.tep_db_input(getLATIN5($currencycode)).'\' LIMIT 1',$$link);
if (!$result) {
    die('Could not query:' . mysql_error());
}
$numrows = mysql_num_rows($result);
}
$curry_id =0;
if($numrows<1)
{
$query = mysql_query("INSERT INTO `d_currency` (code,vendor) VALUES ('".tep_db_input(getLATIN5($currencycode))."',".$vendor.")",$$link);
$curry_id = mysql_insert_id($$link);
if(MEMORY_HEAD =='true') $memory[$vendor]['currency'][''.getLATIN5($currencycode).''] = $curry_id;
}
else
{
if(MEMORY_HEAD =='true')
$curry_id = $memory[$vendor]['currency'][''.getLATIN5($currencycode).''];
else
$curry_id = mysql_result($result, 0, 0);
}
return $curry_id;
}

function tep_class_exists($class_name) {
    if (function_exists('class_exists')) {
      return class_exists($class_name);
    } else {
      return true;
    }
}

function tep_db_connect($server = DB_SERVER, $username = DB_SERVER_USERNAME, $password = DB_SERVER_PASSWORD, $database = DB_DATABASE, $link = 'db_link') {
    global $$link;

    if (USE_PCONNECT == 'true') {
      $$link = mysql_pconnect($server, $username, $password);
    } else {
      $$link = mysql_connect($server, $username, $password);
    }
    //mysqllatin5 start
    if(DB_CHARSET != '') mysql($database,'SET NAMES '.DB_CHARSET);
    //mysqllatin5 end
    if ($$link) mysql_select_db($database);

    return $$link;
}

function tep_db_close($link = 'db_link') {
    global $$link;

    return mysql_close($$link);
}

function tep_db_perform($table, $data, $action = 'insert', $parameters = '', $link = 'db_link') {
    if (is_array($data))
    {
    reset($data);
    if ($action == 'insert') {
      $query = 'insert into `' . $table . '` (';
      while (list($columns, ) = each($data)) {
        $query .= '`'.$columns . '`, ';
      }
      $query = substr($query, 0, -2) . ') values (';
      reset($data);
      while (list(, $value) = each($data)) {
        switch ((string)$value) {
          case 'now()':
            $query .= 'now(), ';
            break;
          case 'null':
            $query .= 'null, ';
            break;
          default:
            $query .= '\'' . $value . '\', ';
            break;
        }
      }
      $query = substr($query, 0, -2) . ')';
    } elseif ($action == 'update') {
      $query = 'update `' . $table . '` set ';
      while (list($columns, $value) = each($data)) {
        switch ((string)$value) {
          case 'now()':
            $query .= '`'.$columns . '` = now(), ';
            break;
          case 'null':
            $query .= '`'.$columns .= '` = null, ';
            break;
          default:
            $query .= '`'.$columns . '` = \'' .$value. '\', ';
            break;
        }
      }
      $query = substr($query, 0, -2) . ' where ' . $parameters;
    }
   }
    return $query;
}
function tep_db_query($query, $link = 'db_link') {
    global $$link, $logger;

    if (defined('STORE_DB_TRANSACTIONS') && (STORE_DB_TRANSACTIONS == 'true')) {
      if (!is_object($logger)) $logger = new logger;
      $logger->write($query, 'QUERY');
    }

    $result = mysql_query($query, $$link) or tep_db_error($query, mysql_errno(), mysql_error());

    if (defined('STORE_DB_TRANSACTIONS') && (STORE_DB_TRANSACTIONS == 'true')) {
      if (mysql_error()) $logger->write(mysql_error(), 'ERROR');
    }

    return $result;
}
function tep_db_error($query, $errno, $error) {
    die('<font color="#000000"><b>' . $errno . ' - ' . $error . '<br><br>' . $query . '<br><br><small><font color="#ff0000">[TEP STOP]</font></small><br><br></b></font>');
}

function tep_db_fetch_array($db_query) {
    return mysql_fetch_array($db_query, MYSQL_ASSOC);
}

function tep_db_result($result, $row, $field = '') {
    return mysql_result($result, $row, $field);
}

function tep_db_num_rows($db_query) {
    return mysql_num_rows($db_query);
}

function tep_db_data_seek($db_query, $row_number) {
    return mysql_data_seek($db_query, $row_number);
}

function tep_db_insert_id($link = 'db_link') {
    global $$link;

    return mysql_insert_id($$link);
}

function tep_db_free_result($db_query) {
    return mysql_free_result($db_query);
}

function tep_db_fetch_fields($db_query) {
    return mysql_fetch_field($db_query);
}

function tep_db_output($string) {
    return htmlspecialchars($string);
}

function tep_db_input($string, $link = 'db_link') {
    global $$link;
    $string = str_replace('\n', "\n", $string);
    if (function_exists('mysql_real_escape_string')) {
      return mysql_real_escape_string($string, $$link);
    } elseif (function_exists('mysql_escape_string')) {
      return mysql_escape_string($string);
    }

    //$string = str_replace("\\", "\\\\", $string);
    //$string = str_replace("´", "\'", $string);
    //$string = str_replace("'", "\'", $string);
    //$string = str_replace("”", "\"", $string);
    //return $string;
    return addslashes($string);
}

function tep_db_prepare_input($string) {
    if (is_string($string)) {
      return trim(stripslashes($string));
    } elseif (is_array($string)) {
      reset($string);
      while (list($key, $value) = each($string)) {
        $string[$key] = tep_db_prepare_input($value);
      }
      return $string;
    } else {
      return $string;
    }
}

function inc_copyright($link = 'db_link'){
?>

<p><strong>Tedarikçi Entegrasyonu v.1.4.0.0</strong></p>

<pre>
Ýþbu Son Kullanýcý Lisans Sözleþmesi (&quot;EULA&quot;), ilgili ortamýn, 
basýlý belgelerin , &quot;çevrimiçi&quot; veya elektronik belgeler ve 
Internet tabanlý hizmetlerin de dahil olabileceði ve bilgisayar yazýlýmýný 
kapsayan yukarýda belirtilen Duzgun.com yazýlým ürünü (&quot;Ürün&quot;) 
Duzgun.com (Yavuz Yasin DÜZGÜN veya Satýþ Temsilcisi) ile tarafýnýz 
(bir gerçek kiþi olarak veya bir tüzel kiþi olarak) 
arasýnda yapýlan yasal bir sözleþmedir. Ürüne, iþbu EULA’da yapýlacak 
deðiþiklikler veya EULA’ya konulacak ekler eþlik edebilir. 

ÜRÜNÜ KURMANIZ, KOPYALAMANIZ YA DA HERHANGÝ BÝR ÞEKÝLDE KULLANMANIZ, 
ÝÞBU EULA HÜKÜMLERÝNE TABÝ OLMAYI KABUL ETTÝÐÝNÝZ ANLAMINA GELÝR. 
BU EULA HÜKÜMLERÝNÝ KABUL ETMÝYORSANIZ ÜRÜNÜ KURMAYIN VEYA KULLANMAYIN;
ARIZÝ, NEDEN OLUNAN VE DÝÐER BAZI ZARARLARIN HARÝÇ TUTULMASI. 
Duzgun.com, KUSURLU, KASITLI (ÝHMAL DAHÝL), TAMAMEN SORUMLU OLMALARI, 
SÖZLEÞMEYÝ ÝHLAL ETMELERÝ VEYA Duzgun.com GARANTÝSÝNÝ ÝHLAL ETMELERÝ 
DURUMUNDA VE HATTA OLUÞAN ZARARIN OLASILIÐINDAN ÖNCEDEN HABERDAR EDÝLMÝÞ 
OLSALAR DAHÝ, ÜRÜN’ÜN KULLANIMINDAN YA DA KULLANILAMAMASINDAN, DESTEK VEYA 
DÝÐER HÝZMETLER, BÝLGÝ, YAZILIM UYGULAMALARI SAÐLANMASINDAN VEYA SAÐLANAMAMASINDAN, 
ÜRÜN ARACILIÐIYLA VEYA ÜRÜNÜN KULLANIMI YOLUYLA ERÝÞÝLEN ÝÇERÝKTEN VEYA 
ÝÞBU EULA’NIN HÜKÜMLERÝNDEN DOÐAN VEYA BUNLARLA ÝLÝÞKÝLÝ HERHANGÝ BÝR ÖZEL, 
ARIZÝ, CEZA GEREKTÝREN, DOLAYLI VEYA NEDEN OLUNAN HÝÇBÝR ZARARDAN 
(KAR KAYBI, GÝZLÝ VEYA BAÞKA BÝLGÝLERÝN KAYBI, ÝÞÝN DURMASI, ÞAHISLARA OLAN 
ZARAR, GÝZLÝLÝK KAYBI, ÝYÝ NÝYET VEYA MAKUL DÝKKATÝN GÖSTERÝLMEMESÝ DE DAHÝL 
GÖREVÝN YERÝNE GETÝRÝLEMEMESÝ, ÝHMAL VE HER NASIL OLURSA OLSUN TÜM MADDÝ VEYA 
DÝÐER ZARAR, ZÝYAN VE TAZMÝNAT TALEPLERÝ DAHÝL OLMAK ANCAK BUNLARLA SINIRLI 
KALMAMAK ÜZERE) ÝLGÝLÝ YASALAR ÇERÇEVESÝNDE ÝZÝN VERÝLEN AZAMÝ ÖLÇÜDE 
SORUMLULUKTAN VARESTEDÝR.

Hukuki açýdan aþaðýdaki GNU GPL'in Ýngilizce metni baðlayýcýdýr. 

                    GNU GENERAL PUBLIC LICENSE
                       Version 3, 29 June 2007

 Copyright (C) 2007 Free Software Foundation, Inc. <http://fsf.org/>
 Everyone is permitted to copy and distribute verbatim copies
 of this license document, but changing it is not allowed.

                            Preamble

  The GNU General Public License is a free, copyleft license for
software and other kinds of works.

  The licenses for most software and other practical works are designed
to take away your freedom to share and change the works.  By contrast,
the GNU General Public License is intended to guarantee your freedom to
share and change all versions of a program--to make sure it remains free
software for all its users.  We, the Free Software Foundation, use the
GNU General Public License for most of our software; it applies also to
any other work released this way by its authors.  You can apply it to
your programs, too.

  When we speak of free software, we are referring to freedom, not
price.  Our General Public Licenses are designed to make sure that you
have the freedom to distribute copies of free software (and charge for
them if you wish), that you receive source code or can get it if you
want it, that you can change the software or use pieces of it in new
free programs, and that you know you can do these things.

  To protect your rights, we need to prevent others from denying you
these rights or asking you to surrender the rights.  Therefore, you have
certain responsibilities if you distribute copies of the software, or if
you modify it: responsibilities to respect the freedom of others.

  For example, if you distribute copies of such a program, whether
gratis or for a fee, you must pass on to the recipients the same
freedoms that you received.  You must make sure that they, too, receive
or can get the source code.  And you must show them these terms so they
know their rights.

  Developers that use the GNU GPL protect your rights with two steps:
(1) assert copyright on the software, and (2) offer you this License
giving you legal permission to copy, distribute and/or modify it.

  For the developers' and authors' protection, the GPL clearly explains
that there is no warranty for this free software.  For both users' and
authors' sake, the GPL requires that modified versions be marked as
changed, so that their problems will not be attributed erroneously to
authors of previous versions.

  Some devices are designed to deny users access to install or run
modified versions of the software inside them, although the manufacturer
can do so.  This is fundamentally incompatible with the aim of
protecting users' freedom to change the software.  The systematic
pattern of such abuse occurs in the area of products for individuals to
use, which is precisely where it is most unacceptable.  Therefore, we
have designed this version of the GPL to prohibit the practice for those
products.  If such problems arise substantially in other domains, we
stand ready to extend this provision to those domains in future versions
of the GPL, as needed to protect the freedom of users.

  Finally, every program is threatened constantly by software patents.
States should not allow patents to restrict development and use of
software on general-purpose computers, but in those that do, we wish to
avoid the special danger that patents applied to a free program could
make it effectively proprietary.  To prevent this, the GPL assures that
patents cannot be used to render the program non-free.

  The precise terms and conditions for copying, distribution and
modification follow.

                       TERMS AND CONDITIONS

  0. Definitions.

  "This License" refers to version 3 of the GNU General Public License.

  "Copyright" also means copyright-like laws that apply to other kinds of
works, such as semiconductor masks.

  "The Program" refers to any copyrightable work licensed under this
License.  Each licensee is addressed as "you".  "Licensees" and
"recipients" may be individuals or organizations.

  To "modify" a work means to copy from or adapt all or part of the work
in a fashion requiring copyright permission, other than the making of an
exact copy.  The resulting work is called a "modified version" of the
earlier work or a work "based on" the earlier work.

  A "covered work" means either the unmodified Program or a work based
on the Program.

  To "propagate" a work means to do anything with it that, without
permission, would make you directly or secondarily liable for
infringement under applicable copyright law, except executing it on a
computer or modifying a private copy.  Propagation includes copying,
distribution (with or without modification), making available to the
public, and in some countries other activities as well.

  To "convey" a work means any kind of propagation that enables other
parties to make or receive copies.  Mere interaction with a user through
a computer network, with no transfer of a copy, is not conveying.

  An interactive user interface displays "Appropriate Legal Notices"
to the extent that it includes a convenient and prominently visible
feature that (1) displays an appropriate copyright notice, and (2)
tells the user that there is no warranty for the work (except to the
extent that warranties are provided), that licensees may convey the
work under this License, and how to view a copy of this License.  If
the interface presents a list of user commands or options, such as a
menu, a prominent item in the list meets this criterion.

  1. Source Code.

  The "source code" for a work means the preferred form of the work
for making modifications to it.  "Object code" means any non-source
form of a work.

  A "Standard Interface" means an interface that either is an official
standard defined by a recognized standards body, or, in the case of
interfaces specified for a particular programming language, one that
is widely used among developers working in that language.

  The "System Libraries" of an executable work include anything, other
than the work as a whole, that (a) is included in the normal form of
packaging a Major Component, but which is not part of that Major
Component, and (b) serves only to enable use of the work with that
Major Component, or to implement a Standard Interface for which an
implementation is available to the public in source code form.  A
"Major Component", in this context, means a major essential component
(kernel, window system, and so on) of the specific operating system
(if any) on which the executable work runs, or a compiler used to
produce the work, or an object code interpreter used to run it.

  The "Corresponding Source" for a work in object code form means all
the source code needed to generate, install, and (for an executable
work) run the object code and to modify the work, including scripts to
control those activities.  However, it does not include the work's
System Libraries, or general-purpose tools or generally available free
programs which are used unmodified in performing those activities but
which are not part of the work.  For example, Corresponding Source
includes interface definition files associated with source files for
the work, and the source code for shared libraries and dynamically
linked subprograms that the work is specifically designed to require,
such as by intimate data communication or control flow between those
subprograms and other parts of the work.

  The Corresponding Source need not include anything that users
can regenerate automatically from other parts of the Corresponding
Source.

  The Corresponding Source for a work in source code form is that
same work.

  2. Basic Permissions.

  All rights granted under this License are granted for the term of
copyright on the Program, and are irrevocable provided the stated
conditions are met.  This License explicitly affirms your unlimited
permission to run the unmodified Program.  The output from running a
covered work is covered by this License only if the output, given its
content, constitutes a covered work.  This License acknowledges your
rights of fair use or other equivalent, as provided by copyright law.

  You may make, run and propagate covered works that you do not
convey, without conditions so long as your license otherwise remains
in force.  You may convey covered works to others for the sole purpose
of having them make modifications exclusively for you, or provide you
with facilities for running those works, provided that you comply with
the terms of this License in conveying all material for which you do
not control copyright.  Those thus making or running the covered works
for you must do so exclusively on your behalf, under your direction
and control, on terms that prohibit them from making any copies of
your copyrighted material outside their relationship with you.

  Conveying under any other circumstances is permitted solely under
the conditions stated below.  Sublicensing is not allowed; section 10
makes it unnecessary.

  3. Protecting Users' Legal Rights From Anti-Circumvention Law.

  No covered work shall be deemed part of an effective technological
measure under any applicable law fulfilling obligations under article
11 of the WIPO copyright treaty adopted on 20 December 1996, or
similar laws prohibiting or restricting circumvention of such
measures.

  When you convey a covered work, you waive any legal power to forbid
circumvention of technological measures to the extent such circumvention
is effected by exercising rights under this License with respect to
the covered work, and you disclaim any intention to limit operation or
modification of the work as a means of enforcing, against the work's
users, your or third parties' legal rights to forbid circumvention of
technological measures.

  4. Conveying Verbatim Copies.

  You may convey verbatim copies of the Program's source code as you
receive it, in any medium, provided that you conspicuously and
appropriately publish on each copy an appropriate copyright notice;
keep intact all notices stating that this License and any
non-permissive terms added in accord with section 7 apply to the code;
keep intact all notices of the absence of any warranty; and give all
recipients a copy of this License along with the Program.

  You may charge any price or no price for each copy that you convey,
and you may offer support or warranty protection for a fee.

  5. Conveying Modified Source Versions.

  You may convey a work based on the Program, or the modifications to
produce it from the Program, in the form of source code under the
terms of section 4, provided that you also meet all of these conditions:

    a) The work must carry prominent notices stating that you modified
    it, and giving a relevant date.

    b) The work must carry prominent notices stating that it is
    released under this License and any conditions added under section
    7.  This requirement modifies the requirement in section 4 to
    "keep intact all notices".

    c) You must license the entire work, as a whole, under this
    License to anyone who comes into possession of a copy.  This
    License will therefore apply, along with any applicable section 7
    additional terms, to the whole of the work, and all its parts,
    regardless of how they are packaged.  This License gives no
    permission to license the work in any other way, but it does not
    invalidate such permission if you have separately received it.

    d) If the work has interactive user interfaces, each must display
    Appropriate Legal Notices; however, if the Program has interactive
    interfaces that do not display Appropriate Legal Notices, your
    work need not make them do so.

  A compilation of a covered work with other separate and independent
works, which are not by their nature extensions of the covered work,
and which are not combined with it such as to form a larger program,
in or on a volume of a storage or distribution medium, is called an
"aggregate" if the compilation and its resulting copyright are not
used to limit the access or legal rights of the compilation's users
beyond what the individual works permit.  Inclusion of a covered work
in an aggregate does not cause this License to apply to the other
parts of the aggregate.

  6. Conveying Non-Source Forms.

  You may convey a covered work in object code form under the terms
of sections 4 and 5, provided that you also convey the
machine-readable Corresponding Source under the terms of this License,
in one of these ways:

    a) Convey the object code in, or embodied in, a physical product
    (including a physical distribution medium), accompanied by the
    Corresponding Source fixed on a durable physical medium
    customarily used for software interchange.

    b) Convey the object code in, or embodied in, a physical product
    (including a physical distribution medium), accompanied by a
    written offer, valid for at least three years and valid for as
    long as you offer spare parts or customer support for that product
    model, to give anyone who possesses the object code either (1) a
    copy of the Corresponding Source for all the software in the
    product that is covered by this License, on a durable physical
    medium customarily used for software interchange, for a price no
    more than your reasonable cost of physically performing this
    conveying of source, or (2) access to copy the
    Corresponding Source from a network server at no charge.

    c) Convey individual copies of the object code with a copy of the
    written offer to provide the Corresponding Source.  This
    alternative is allowed only occasionally and noncommercially, and
    only if you received the object code with such an offer, in accord
    with subsection 6b.

    d) Convey the object code by offering access from a designated
    place (gratis or for a charge), and offer equivalent access to the
    Corresponding Source in the same way through the same place at no
    further charge.  You need not require recipients to copy the
    Corresponding Source along with the object code.  If the place to
    copy the object code is a network server, the Corresponding Source
    may be on a different server (operated by you or a third party)
    that supports equivalent copying facilities, provided you maintain
    clear directions next to the object code saying where to find the
    Corresponding Source.  Regardless of what server hosts the
    Corresponding Source, you remain obligated to ensure that it is
    available for as long as needed to satisfy these requirements.

    e) Convey the object code using peer-to-peer transmission, provided
    you inform other peers where the object code and Corresponding
    Source of the work are being offered to the general public at no
    charge under subsection 6d.

  A separable portion of the object code, whose source code is excluded
from the Corresponding Source as a System Library, need not be
included in conveying the object code work.

  A "User Product" is either (1) a "consumer product", which means any
tangible personal property which is normally used for personal, family,
or household purposes, or (2) anything designed or sold for incorporation
into a dwelling.  In determining whether a product is a consumer product,
doubtful cases shall be resolved in favor of coverage.  For a particular
product received by a particular user, "normally used" refers to a
typical or common use of that class of product, regardless of the status
of the particular user or of the way in which the particular user
actually uses, or expects or is expected to use, the product.  A product
is a consumer product regardless of whether the product has substantial
commercial, industrial or non-consumer uses, unless such uses represent
the only significant mode of use of the product.

  "Installation Information" for a User Product means any methods,
procedures, authorization keys, or other information required to install
and execute modified versions of a covered work in that User Product from
a modified version of its Corresponding Source.  The information must
suffice to ensure that the continued functioning of the modified object
code is in no case prevented or interfered with solely because
modification has been made.

  If you convey an object code work under this section in, or with, or
specifically for use in, a User Product, and the conveying occurs as
part of a transaction in which the right of possession and use of the
User Product is transferred to the recipient in perpetuity or for a
fixed term (regardless of how the transaction is characterized), the
Corresponding Source conveyed under this section must be accompanied
by the Installation Information.  But this requirement does not apply
if neither you nor any third party retains the ability to install
modified object code on the User Product (for example, the work has
been installed in ROM).

  The requirement to provide Installation Information does not include a
requirement to continue to provide support service, warranty, or updates
for a work that has been modified or installed by the recipient, or for
the User Product in which it has been modified or installed.  Access to a
network may be denied when the modification itself materially and
adversely affects the operation of the network or violates the rules and
protocols for communication across the network.

  Corresponding Source conveyed, and Installation Information provided,
in accord with this section must be in a format that is publicly
documented (and with an implementation available to the public in
source code form), and must require no special password or key for
unpacking, reading or copying.

  7. Additional Terms.

  "Additional permissions" are terms that supplement the terms of this
License by making exceptions from one or more of its conditions.
Additional permissions that are applicable to the entire Program shall
be treated as though they were included in this License, to the extent
that they are valid under applicable law.  If additional permissions
apply only to part of the Program, that part may be used separately
under those permissions, but the entire Program remains governed by
this License without regard to the additional permissions.

  When you convey a copy of a covered work, you may at your option
remove any additional permissions from that copy, or from any part of
it.  (Additional permissions may be written to require their own
removal in certain cases when you modify the work.)  You may place
additional permissions on material, added by you to a covered work,
for which you have or can give appropriate copyright permission.

  Notwithstanding any other provision of this License, for material you
add to a covered work, you may (if authorized by the copyright holders of
that material) supplement the terms of this License with terms:

    a) Disclaiming warranty or limiting liability differently from the
    terms of sections 15 and 16 of this License; or

    b) Requiring preservation of specified reasonable legal notices or
    author attributions in that material or in the Appropriate Legal
    Notices displayed by works containing it; or

    c) Prohibiting misrepresentation of the origin of that material, or
    requiring that modified versions of such material be marked in
    reasonable ways as different from the original version; or

    d) Limiting the use for publicity purposes of names of licensors or
    authors of the material; or

    e) Declining to grant rights under trademark law for use of some
    trade names, trademarks, or service marks; or

    f) Requiring indemnification of licensors and authors of that
    material by anyone who conveys the material (or modified versions of
    it) with contractual assumptions of liability to the recipient, for
    any liability that these contractual assumptions directly impose on
    those licensors and authors.

  All other non-permissive additional terms are considered "further
restrictions" within the meaning of section 10.  If the Program as you
received it, or any part of it, contains a notice stating that it is
governed by this License along with a term that is a further
restriction, you may remove that term.  If a license document contains
a further restriction but permits relicensing or conveying under this
License, you may add to a covered work material governed by the terms
of that license document, provided that the further restriction does
not survive such relicensing or conveying.

  If you add terms to a covered work in accord with this section, you
must place, in the relevant source files, a statement of the
additional terms that apply to those files, or a notice indicating
where to find the applicable terms.

  Additional terms, permissive or non-permissive, may be stated in the
form of a separately written license, or stated as exceptions;
the above requirements apply either way.

  8. Termination.

  You may not propagate or modify a covered work except as expressly
provided under this License.  Any attempt otherwise to propagate or
modify it is void, and will automatically terminate your rights under
this License (including any patent licenses granted under the third
paragraph of section 11).

  However, if you cease all violation of this License, then your
license from a particular copyright holder is reinstated (a)
provisionally, unless and until the copyright holder explicitly and
finally terminates your license, and (b) permanently, if the copyright
holder fails to notify you of the violation by some reasonable means
prior to 60 days after the cessation.

  Moreover, your license from a particular copyright holder is
reinstated permanently if the copyright holder notifies you of the
violation by some reasonable means, this is the first time you have
received notice of violation of this License (for any work) from that
copyright holder, and you cure the violation prior to 30 days after
your receipt of the notice.

  Termination of your rights under this section does not terminate the
licenses of parties who have received copies or rights from you under
this License.  If your rights have been terminated and not permanently
reinstated, you do not qualify to receive new licenses for the same
material under section 10.

  9. Acceptance Not Required for Having Copies.

  You are not required to accept this License in order to receive or
run a copy of the Program.  Ancillary propagation of a covered work
occurring solely as a consequence of using peer-to-peer transmission
to receive a copy likewise does not require acceptance.  However,
nothing other than this License grants you permission to propagate or
modify any covered work.  These actions infringe copyright if you do
not accept this License.  Therefore, by modifying or propagating a
covered work, you indicate your acceptance of this License to do so.

  10. Automatic Licensing of Downstream Recipients.

  Each time you convey a covered work, the recipient automatically
receives a license from the original licensors, to run, modify and
propagate that work, subject to this License.  You are not responsible
for enforcing compliance by third parties with this License.

  An "entity transaction" is a transaction transferring control of an
organization, or substantially all assets of one, or subdividing an
organization, or merging organizations.  If propagation of a covered
work results from an entity transaction, each party to that
transaction who receives a copy of the work also receives whatever
licenses to the work the party's predecessor in interest had or could
give under the previous paragraph, plus a right to possession of the
Corresponding Source of the work from the predecessor in interest, if
the predecessor has it or can get it with reasonable efforts.

  You may not impose any further restrictions on the exercise of the
rights granted or affirmed under this License.  For example, you may
not impose a license fee, royalty, or other charge for exercise of
rights granted under this License, and you may not initiate litigation
(including a cross-claim or counterclaim in a lawsuit) alleging that
any patent claim is infringed by making, using, selling, offering for
sale, or importing the Program or any portion of it.

  11. Patents.

  A "contributor" is a copyright holder who authorizes use under this
License of the Program or a work on which the Program is based.  The
work thus licensed is called the contributor's "contributor version".

  A contributor's "essential patent claims" are all patent claims
owned or controlled by the contributor, whether already acquired or
hereafter acquired, that would be infringed by some manner, permitted
by this License, of making, using, or selling its contributor version,
but do not include claims that would be infringed only as a
consequence of further modification of the contributor version.  For
purposes of this definition, "control" includes the right to grant
patent sublicenses in a manner consistent with the requirements of
this License.

  Each contributor grants you a non-exclusive, worldwide, royalty-free
patent license under the contributor's essential patent claims, to
make, use, sell, offer for sale, import and otherwise run, modify and
propagate the contents of its contributor version.

  In the following three paragraphs, a "patent license" is any express
agreement or commitment, however denominated, not to enforce a patent
(such as an express permission to practice a patent or covenant not to
sue for patent infringement).  To "grant" such a patent license to a
party means to make such an agreement or commitment not to enforce a
patent against the party.

  If you convey a covered work, knowingly relying on a patent license,
and the Corresponding Source of the work is not available for anyone
to copy, free of charge and under the terms of this License, through a
publicly available network server or other readily accessible means,
then you must either (1) cause the Corresponding Source to be so
available, or (2) arrange to deprive yourself of the benefit of the
patent license for this particular work, or (3) arrange, in a manner
consistent with the requirements of this License, to extend the patent
license to downstream recipients.  "Knowingly relying" means you have
actual knowledge that, but for the patent license, your conveying the
covered work in a country, or your recipient's use of the covered work
in a country, would infringe one or more identifiable patents in that
country that you have reason to believe are valid.

  If, pursuant to or in connection with a single transaction or
arrangement, you convey, or propagate by procuring conveyance of, a
covered work, and grant a patent license to some of the parties
receiving the covered work authorizing them to use, propagate, modify
or convey a specific copy of the covered work, then the patent license
you grant is automatically extended to all recipients of the covered
work and works based on it.

  A patent license is "discriminatory" if it does not include within
the scope of its coverage, prohibits the exercise of, or is
conditioned on the non-exercise of one or more of the rights that are
specifically granted under this License.  You may not convey a covered
work if you are a party to an arrangement with a third party that is
in the business of distributing software, under which you make payment
to the third party based on the extent of your activity of conveying
the work, and under which the third party grants, to any of the
parties who would receive the covered work from you, a discriminatory
patent license (a) in connection with copies of the covered work
conveyed by you (or copies made from those copies), or (b) primarily
for and in connection with specific products or compilations that
contain the covered work, unless you entered into that arrangement,
or that patent license was granted, prior to 28 March 2007.

  Nothing in this License shall be construed as excluding or limiting
any implied license or other defenses to infringement that may
otherwise be available to you under applicable patent law.

  12. No Surrender of Others' Freedom.

  If conditions are imposed on you (whether by court order, agreement or
otherwise) that contradict the conditions of this License, they do not
excuse you from the conditions of this License.  If you cannot convey a
covered work so as to satisfy simultaneously your obligations under this
License and any other pertinent obligations, then as a consequence you may
not convey it at all.  For example, if you agree to terms that obligate you
to collect a royalty for further conveying from those to whom you convey
the Program, the only way you could satisfy both those terms and this
License would be to refrain entirely from conveying the Program.

  13. Use with the GNU Affero General Public License.

  Notwithstanding any other provision of this License, you have
permission to link or combine any covered work with a work licensed
under version 3 of the GNU Affero General Public License into a single
combined work, and to convey the resulting work.  The terms of this
License will continue to apply to the part which is the covered work,
but the special requirements of the GNU Affero General Public License,
section 13, concerning interaction through a network will apply to the
combination as such.

  14. Revised Versions of this License.

  The Free Software Foundation may publish revised and/or new versions of
the GNU General Public License from time to time.  Such new versions will
be similar in spirit to the present version, but may differ in detail to
address new problems or concerns.

  Each version is given a distinguishing version number.  If the
Program specifies that a certain numbered version of the GNU General
Public License "or any later version" applies to it, you have the
option of following the terms and conditions either of that numbered
version or of any later version published by the Free Software
Foundation.  If the Program does not specify a version number of the
GNU General Public License, you may choose any version ever published
by the Free Software Foundation.

  If the Program specifies that a proxy can decide which future
versions of the GNU General Public License can be used, that proxy's
public statement of acceptance of a version permanently authorizes you
to choose that version for the Program.

  Later license versions may give you additional or different
permissions.  However, no additional obligations are imposed on any
author or copyright holder as a result of your choosing to follow a
later version.

  15. Disclaimer of Warranty.

  THERE IS NO WARRANTY FOR THE PROGRAM, TO THE EXTENT PERMITTED BY
APPLICABLE LAW.  EXCEPT WHEN OTHERWISE STATED IN WRITING THE COPYRIGHT
HOLDERS AND/OR OTHER PARTIES PROVIDE THE PROGRAM "AS IS" WITHOUT WARRANTY
OF ANY KIND, EITHER EXPRESSED OR IMPLIED, INCLUDING, BUT NOT LIMITED TO,
THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR
PURPOSE.  THE ENTIRE RISK AS TO THE QUALITY AND PERFORMANCE OF THE PROGRAM
IS WITH YOU.  SHOULD THE PROGRAM PROVE DEFECTIVE, YOU ASSUME THE COST OF
ALL NECESSARY SERVICING, REPAIR OR CORRECTION.

  16. Limitation of Liability.

  IN NO EVENT UNLESS REQUIRED BY APPLICABLE LAW OR AGREED TO IN WRITING
WILL ANY COPYRIGHT HOLDER, OR ANY OTHER PARTY WHO MODIFIES AND/OR CONVEYS
THE PROGRAM AS PERMITTED ABOVE, BE LIABLE TO YOU FOR DAMAGES, INCLUDING ANY
GENERAL, SPECIAL, INCIDENTAL OR CONSEQUENTIAL DAMAGES ARISING OUT OF THE
USE OR INABILITY TO USE THE PROGRAM (INCLUDING BUT NOT LIMITED TO LOSS OF
DATA OR DATA BEING RENDERED INACCURATE OR LOSSES SUSTAINED BY YOU OR THIRD
PARTIES OR A FAILURE OF THE PROGRAM TO OPERATE WITH ANY OTHER PROGRAMS),
EVEN IF SUCH HOLDER OR OTHER PARTY HAS BEEN ADVISED OF THE POSSIBILITY OF
SUCH DAMAGES.

  17. Interpretation of Sections 15 and 16.

  If the disclaimer of warranty and limitation of liability provided
above cannot be given local legal effect according to their terms,
reviewing courts shall apply local law that most closely approximates
an absolute waiver of all civil liability in connection with the
Program, unless a warranty or assumption of liability accompanies a
copy of the Program in return for a fee.

                     END OF TERMS AND CONDITIONS

            How to Apply These Terms to Your New Programs

  If you develop a new program, and you want it to be of the greatest
possible use to the public, the best way to achieve this is to make it
free software which everyone can redistribute and change under these terms.

  To do so, attach the following notices to the program.  It is safest
to attach them to the start of each source file to most effectively
state the exclusion of warranty; and each file should have at least
the "copyright" line and a pointer to where the full notice is found.

    <one line to give the program's name and a brief idea of what it does.>
    Copyright (C) <year>  <name of author>

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.

Also add information on how to contact you by electronic and paper mail.

  If the program does terminal interaction, make it output a short
notice like this when it starts in an interactive mode:

    <program>  Copyright (C) <year>  <name of author>
    This program comes with ABSOLUTELY NO WARRANTY; for details type `show w'.
    This is free software, and you are welcome to redistribute it
    under certain conditions; type `show c' for details.

The hypothetical commands `show w' and `show c' should show the appropriate
parts of the General Public License.  Of course, your program's commands
might be different; for a GUI interface, you would use an "about box".

  You should also get your employer (if you work as a programmer) or school,
if any, to sign a "copyright disclaimer" for the program, if necessary.
For more information on this, and how to apply and follow the GNU GPL, see
<http://www.gnu.org/licenses/>.

  The GNU General Public License does not permit incorporating your program
into proprietary programs.  If your program is a subroutine library, you
may consider it more useful to permit linking proprietary applications with
the library.  If this is what you want to do, use the GNU Lesser General
Public License instead of this License.  But first, please read
<http://www.gnu.org/philosophy/why-not-lgpl.html>.
</pre>
<p><a href="http://www.duzgun.com" target="_blank">http://www.duzgun.com</a><br />
 E-mail: admin@duzgun.com<br />
Duzgun.com Ekibi.</p>
<?php
}

function inc_setting($link = 'db_link'){
global $$link;
$sppc = config('sppc');
if(isset($_GET["id"]) && (!empty($_GET["id"]))){
mysql_query("DELETE FROM `d_task` Where id=".formatnumber($_GET["id"]),$$link);
}
if(isset($_POST["kaydet"])){
mysql_query("INSERT INTO `d_task` (`vendor` , `category` , `product` , `attribute` , `ei` , `eif` , `ea` , `eaf` , `f` , `fm`".(($sppc)?',sppc':'')." )VALUES (".formatnumber($_POST["t"]).",".formatnumber($_POST["k"]).",".formatnumber($_POST["u"]).",".formatnumber($_POST["a"]).",".formatnumber($_POST["ei"]).",".(($_POST["eif"]=='')?"NULL":moneyformat($_POST["eif"])).",".formatnumber($_POST["ea"]).",".(($_POST["eaf"]=='')?"NULL":moneyformat($_POST["eaf"])).",".formatnumber($_POST["f"]).",".(($_POST["fm"]=='')?"NULL":moneyformat($_POST["fm"])).(($sppc)?','.formatnumber($_POST["b"]):'').")",$$link);
}
?>
<table style="font-size: 12px;font-family: Verdana, Arial, Helvetica, sans-serif;"  border="0" align="center" cellpadding="5" cellspacing="0">
<form name="settingform" method="post" action="?process=setting"><tbody><tr>
    <td>&nbsp;</td><td colspan="3" bgcolor="#BEEBFF"><div align="center"><strong><?php echo CONNECTOR;?> ID</strong></div></td>
    <td colspan="2">&nbsp;</td>
  </tr>
  <tr>
    <td>Tedarikçi</td>
    <td bgcolor="#EFEFEF">Kategori</td>
    <td bgcolor="#EFEFEF">Ürün</td>
    <td bgcolor="#EFEFEF">Ürün Attr</td>
    <?php if($sppc) echo '<td>Bayii</td>'; ?>
    <td><strong>(Eðer Ýse) Ýfadesi</strong></td>
    <td><strong>Fiyat Marjý</strong></td>
  </tr>
  <tr>
    <td bgcolor="#F9FBFD" style=" border-bottom: 1px solid #D7E5F2; border-left: 1px solid #D7E5F2;border-top: 1px solid #D7E5F2;">
    <select  style="font-size: 12px;width:55px" name="t" id="t">
    <option value="0">&nbsp;</option>
    <?php
      $result = mysql_query("SELECT id, vdname FROM `d_vendors` Order by vdname",$$link);
      if (!$result) {
          die('Could not query:' . mysql_error());
      }
      if(mysql_num_rows($result)>0)
      {

      while ($vendors = tep_db_fetch_array($result)) {
     ?>
            <option value="<?php echo $vendors["id"]; ?>"><?php echo $vendors["id"]; ?>.<?php echo $vendors["vdname"]; ?></option>
     <?php
        }
      }
     ?>
              </select></td>
    <td bgcolor="#F9FBFD" style=" border-bottom: 1px solid #D7E5F2; border-left: 1px solid #D7E5F2;border-top: 1px solid #D7E5F2;"><input name="k"  style="font-size: 12px;" type="text" id="k" size="5" /></td>
    <td bgcolor="#F9FBFD" style=" border-bottom: 1px solid #D7E5F2; border-left: 1px solid #D7E5F2;border-top: 1px solid #D7E5F2;"><input name="u"  style="font-size: 12px;" type="text" id="u" size="5" /></td>
    <td bgcolor="#F9FBFD" style=" border-bottom: 1px solid #D7E5F2; border-left: 1px solid #D7E5F2;border-top: 1px solid #D7E5F2;"><input name="a" style="font-size: 12px;"  type="text" id="a" size="5" /></td>
    <?php if($sppc){ ?>
    <td bgcolor="#F9FBFD" style=" border-bottom: 1px solid #D7E5F2; border-left: 1px solid #D7E5F2;border-top: 1px solid #D7E5F2;">
    <select  style="font-size: 12px;width:55px" name="b" id="b">
    <option value="0">&nbsp;</option>
    <?php
      $result = mysql_query("SELECT id, name FROM `d_sppc` Order by name",$$link);
      if (!$result) {
          die('Could not query:' . mysql_error());
      }
      if(mysql_num_rows($result)>0)
      {

      while ($vendors = tep_db_fetch_array($result)) {
     ?>
            <option value="<?php echo $vendors["id"]; ?>"><?php echo $vendors["id"]; ?>.<?php echo $vendors["name"]; ?></option>
     <?php
        }
      }
     ?>
     </select>
    </td>
    <?php } ?>
    <td style="border: 1px solid #D7E5F2;border-collapse: collapse;" bgcolor="#F9FBFD"><label>
      Fiyat
          <select  style="font-size: 12px;" name="ei" id="ei">
            <option value="1">Eþit</option>
            <option value="2">Büyük</option>
            <option value="3">Küçük</option>
              </select>
      <input  style="font-size: 12px;" name="eif" type="text" id="eif" size="5" />
      ,
      <select  style="font-size: 12px;" name="ea" id="ea">
        <option value="1">Eþit</option>
        <option value="2">Büyük</option>
        <option value="3">Küçük</option>
      </select>
      <input  style="font-size: 12px;" name="eaf" type="text" id="eaf" size="5" />
    </label></td>
    <td style=" border-bottom: 1px solid #D7E5F2; border-right: 1px solid #D7E5F2;border-top: 1px solid #D7E5F2;border-collapse: collapse;" bgcolor="#F9FBFD">Fiyat
      <select style="font-size: 12px;"  name="f" id="f">
        <option value="1">Çarp</option>
        <option value="2">Böl</option>
        <option value="3">Çýkar</option>
        <option value="4">Ekle</option>
      </select>
  <input  style="font-size: 12px;" name="fm" type="text" id="fm" value="" size="5" /></td></tr>
  <tr>
    <td colspan="<?php echo ($sppc)?7:6; ?>"><div align="right">
      <input type="submit" name="kaydet" id="kaydet" value="Marj Ekle" />
    </div></td>
  </tr></tbody>
</form>
</table>
<?php
}

function inc_special($link = 'db_link'){
global $$link;
$sppc = config('sppc');
if(isset($_GET["id"]) && (!empty($_GET["id"]))){
connector_special($_GET["id"]);
}
if(isset($_POST["kaydet"])){
mysql_query("INSERT INTO `d_special` (`vendor` , `pcode` , `rate` , `discount`, `creator` ".(($sppc)?',sppc':'').")VALUES (".formatnumber($_POST["t"]).",'".tep_db_input($_POST["p"])."',".formatnumber($_POST["f"]).",".(($_POST["fm"]=='')?"NULL":moneyformat($_POST["fm"])).",0".(($sppc)?','.formatnumber($_POST["b"]):'').")",$$link);
}
?>
<table style="font-size: 12px;font-family: Verdana, Arial, Helvetica, sans-serif;"  border="0" align="center" cellpadding="5" cellspacing="0">
<form name="specialform" method="post" action="?process=special"><tbody>
  <tr>
    <td>Tedarikçi</td>
    <td bgcolor="#EFEFEF">Ürün Kodu</td>
    <?php if($sppc) echo '<td>Bayii</td>';?>
    <td><strong>Fiyat Marjý</strong></td>
  </tr>
  <tr>
    <td bgcolor="#F9FBFD" style=" border-bottom: 1px solid #D7E5F2; border-left: 1px solid #D7E5F2;border-top: 1px solid #D7E5F2;">
    <select  style="font-size: 12px;width:55px" name="t" id="t">
    <option value="0">&nbsp;</option>
    <?php
      $result = mysql_query("SELECT id, vdname FROM `d_vendors` Order by vdname",$$link);
      if (!$result) {
          die('Could not query:' . mysql_error());
      }
      if(mysql_num_rows($result)>0)
      {

      while ($vendors = tep_db_fetch_array($result)) {
     ?>
            <option value="<?php echo $vendors["id"]; ?>"><?php echo $vendors["id"]; ?>.<?php echo $vendors["vdname"]; ?></option>
     <?php
        }
      }
     ?>
              </select></td>
    <td bgcolor="#F9FBFD" style=" border-bottom: 1px solid #D7E5F2; border-left: 1px solid #D7E5F2;border-top: 1px solid #D7E5F2;"><input name="p"  style="font-size: 12px;" type="text" id="k" size="5" /></td>
    <?php if($sppc){ ?>
    <td bgcolor="#F9FBFD" style="border: 1px solid #D7E5F2;">
    <select  style="font-size: 12px;width:55px" name="b" id="b">
    <option value="0">&nbsp;</option>
    <?php
      $result = mysql_query("SELECT id, name FROM `d_sppc` Order by name",$$link);
      if (!$result) {
          die('Could not query:' . mysql_error());
      }
      if(mysql_num_rows($result)>0)
      {

      while ($vendors = tep_db_fetch_array($result)) {
     ?>
            <option value="<?php echo $vendors["id"]; ?>"><?php echo $vendors["id"]; ?>.<?php echo $vendors["name"]; ?></option>
     <?php
        }
      }
     ?>
     </select>
    </td>
    <?php } ?>
    <td style=" border-bottom: 1px solid #D7E5F2; border-right: 1px solid #D7E5F2;border-top: 1px solid #D7E5F2;border-collapse: collapse;" bgcolor="#F9FBFD">Fiyat
      <select style="font-size: 12px;"  name="f" id="f">
        <option value="1">Çarp</option>
        <option value="2">Böl</option>
        <option value="3">Çýkar</option>
        <option value="4">Ekle</option>
        <option value="5">Eþit</option>
      </select>
  <input  style="font-size: 12px;" name="fm" type="text" id="fm" value="" size="5" /></td></tr>
  <tr>
    <td colspan="6"><div align="right">
      <input type="submit" name="kaydet" id="kaydet" value="Marj Ekle" />
    </div></td>
  </tr></tbody>
</form>
</table>
<?php
}

function inc_deleted($link = 'db_link'){
global $$link,$image_directory,$PSUBIMAGELIMIT,$PIMAGEDELETE;
$start=$_GET['start'];
$limit = $_GET['limit'];
if((!($start > 0)) || (isset($_POST['ara']))) {
$start = 0;
}

if (isset($_POST['ara']))
$ara = $_POST['ara'];
else if(!empty($_GET['ara']))
$ara = $_GET['ara'];
else $ara = '';

// qt pro active start
$qtpro = config('qtpro');
// qt pro active end

$querystr = '?process=deleted&start='.$start.'&limit='.$limit.'&ara='.$ara.((isset($_GET["secure"]))?'&secure=true':'');
search($querystr,'ara','search',$ara);
/*
$degiskenler = array(0 => 'p`.`osid',1=>'c`.`osid', 2=>'p`.`osid', 3=>'p`.`pcode', 4=>'p`.`pname');
$tanimlar = array(0 => 'n', 1=>'n', 2=>'n', 3=>'s', 4=>'s');
$where = searchquery($ara,$degiskenler,$tanimlar);
*/
$where = preparewhere($ara,'p`.`osid AS n ; c`.`osid AS n ; p`.`osid AS n ; p`.`pcode AS s ; p`.`pname AS s');

if(isset($_GET["id"]) && (!empty($_GET["id"]))){
connector_deleted($qtpro);
}

if(isset($_GET["del"]) && (!empty($_GET["del"]))){
if ($where == '')
$sql = 'SELECT p.id as id,p.osid as osid FROM `d_products` p, `d_categories` c  Where c.id = p.catid and p.isupdate=0'.((isset($_GET["secure"]))?'':' and p.osid<>0');
else
$sql = 'SELECT p.id as id,p.osid as osid FROM `d_products` p, `d_categories` c  Where c.id = p.catid and p.isupdate=0'.((isset($_GET["secure"]))?'':' and p.osid<>0').' and '.$where;
  $result = mysql_query($sql,$$link);
  if (!$result) {
      die('Could not query:' . mysql_error());
  }
if(mysql_num_rows($result)>0)
{
while ($tasks = tep_db_fetch_array($result)) {
connector_alldeleted($tasks['id'],$tasks['osid'],$qtpro);
}
}
}

if ($where == '')
$sql = 'SELECT count(*) FROM `d_products` p, `d_categories` c  Where c.id = p.catid and p.isupdate=0'.((isset($_GET["secure"]))?'':' and p.osid<>0');
else
$sql = 'SELECT count(*) FROM `d_products` p, `d_categories` c  Where c.id = p.catid and p.isupdate=0'.((isset($_GET["secure"]))?'':' and p.osid<>0').' and '.$where;

$result = mysql_query($sql,$$link);
if (!$result) {
    die('Could not query:' . mysql_error());
}
$nume = 0;
if(mysql_num_rows($result)>0)
{
$nume = mysql_result($result, 0, 0);
}

if($start > $nume) {
$start = $nume-$limit;
}
if(!($limit > 0)) {
$limit = 10;
}

?>
<table style="font-size: 12px;font-family: Verdana, Arial, Helvetica, sans-serif;"  border="0" align="center" cellpadding="5" cellspacing="0">
<tr>
    <td colspan="7"><div align="center">
<?php
paging($nume,$start,$limit,'start','?process=deleted&limit='.$limit.'&ara='.$ara.((isset($_GET["secure"]))?'&secure=true':''));
?>
    </div></td>
    </tr>
<tr>
    <td bgcolor="#EFEFEF">Kategori#1</td>
    <td bgcolor="#EFEFEF">Ürün#2</td>
    <td bgcolor="#EFEFEF">Ürün Kodu#3</td>
    <td bgcolor="#EFEFEF">Ürün Adý#4</td>
    <td bgcolor="#EFEFEF">&nbsp;</td>
  </tr>
  <?php

  if ($where == '')
  $sql = 'SELECT p.id as id,p.osid as pid,c.osid as cid,p.pcode as pcode,p.pname as pname FROM `d_products` p , `d_categories` c Where c.id = p.catid and p.isupdate=0'.((isset($_GET["secure"]))?'':' and p.osid<>0').' Limit '.$start.','.$limit;
  else
  $sql = 'SELECT p.id as id,p.osid as pid,c.osid as cid,p.pcode as pcode,p.pname as pname FROM `d_products` p , `d_categories` c Where c.id = p.catid and p.isupdate=0'.((isset($_GET["secure"]))?'':' and p.osid<>0').' and '.$where.' Limit '.$start.','.$limit;

  $result = mysql_query($sql,$$link);
  if (!$result) {
      die('Could not query:' . mysql_error());
  }
  if(mysql_num_rows($result)>0)
  {

  while ($tasks = tep_db_fetch_array($result)) {
  ?>
  <tr>
    <td bgcolor="#F9FBFD" style=" border-bottom: 1px solid #D7E5F2; border-left: 1px solid #D7E5F2;border-top: 1px solid #D7E5F2;">
    <?php
      if ($tasks["cid"] !=0){
      echo $tasks["cid"];
      }else
      {
       echo "&nbsp;";
      }
      ?>
    </td>
    <td bgcolor="#F9FBFD" style=" border-bottom: 1px solid #D7E5F2; border-left: 1px solid #D7E5F2;border-top: 1px solid #D7E5F2;">
    <?php
      if ($tasks["pid"] !=0){
      echo $tasks["pid"];
      }else
      {
       echo "&nbsp;";
      }
      ?>
    </td>
    <td bgcolor="#F9FBFD" style=" border-bottom: 1px solid #D7E5F2; border-left: 1px solid #D7E5F2;border-top: 1px solid #D7E5F2;">
      <?php
      if ($tasks["pcode"] !=null){
      echo $tasks["pcode"];
      }else
      {
       echo "&nbsp;";
      }
      ?></td>
    <td bgcolor="#F9FBFD" style=" border-bottom: 1px solid #D7E5F2; border-left: 1px solid #D7E5F2;border-top: 1px solid #D7E5F2;">
      <?php
      if ($tasks["pname"] !=null){
      echo $tasks["pname"];
      }else
      {
       echo "&nbsp;";
      }
      ?></td>
    <td style=" border-bottom: 1px solid #D7E5F2; border-right: 1px solid #D7E5F2;border-top: 1px solid #D7E5F2;border-collapse: collapse;" bgcolor="#F9FBFD"><a href="<?php echo $querystr;?>&id=<?php echo $tasks["pid"];?>">sil</a></td>
  </tr>
  <?php
    }
    echo '<td colspan="5"><div align="right"><a href="'.$querystr.'&del=all">Tümünü Sil</a></div></td>';
   }
  ?>
</table>
<?php
}

function inc_setting_list($link = 'db_link'){
global $$link;
$sppc = config('sppc');
$start=$_GET['start'];
$limit = $_GET['limit'];
if((!($start > 0)) || (isset($_POST['ara']))) {
$start = 0;
}

if (isset($_POST['ara']))
$ara = $_POST['ara'];
else if(!empty($_GET['ara']))
$ara = $_GET['ara'];
else $ara = '';

$querystr = '?process=setting&start='.$start.'&limit='.$limit.'&ara='.$ara;
search($querystr,'ara','search',$ara);
/*
$degiskenler = array(0 => 'product',1 => 'vendor', 2=>'category', 3=>'product', 4=>'attribute');
$tanimlar = array(0 => 'n', 1=>'n', 2=>'n', 3=>'n', 4=>'n');
$where = searchquery($ara,$degiskenler,$tanimlar);
*/
$where = preparewhere($ara,'product AS n ; vendor AS n ; category AS n ; product AS n ; attribute AS n'.(($sppc)?' ; sppc AS n':''));

if ($where == '')
$sql = 'SELECT count(*) FROM `d_task`';
else
$sql = 'SELECT count(*) FROM `d_task` Where '.$where;

$result = mysql_query($sql,$$link);
if (!$result) {
    die('Could not query:' . mysql_error());
}
$nume = 0;
if(mysql_num_rows($result)>0)
{
$nume = mysql_result($result, 0, 0);
}

if($start > $nume) {
$start = $nume-$limit;
}
if(!($limit > 0)) {
$limit = 10;
}

?>
<table style="font-size: 12px;font-family: Verdana, Arial, Helvetica, sans-serif;"  border="0" align="center" cellpadding="5" cellspacing="0">
<tr>
    <td colspan="7"><div align="center">
<?php
paging($nume,$start,$limit,'start','?process=setting&limit='.$limit.'&ara='.$ara);
?>
    </div></td>
    </tr>
<tr>
    <td bgcolor="#EFEFEF">Tedarikçi#1</td>
    <td bgcolor="#EFEFEF">Kategori#2</td>
    <td bgcolor="#EFEFEF">Ürün#3</td>
    <td bgcolor="#EFEFEF">Ürün Attr#4</td>
     <?php if($sppc) echo '<td bgcolor="#EFEFEF">Bayii</td>'; ?>
    <td bgcolor="#EFEFEF">(Eðer Ýse) Ýfadesi</td>
    <td bgcolor="#EFEFEF">Fiyat Marjý</td>
    <td bgcolor="#EFEFEF">&nbsp;</td>
  </tr>
  <?php
  if ($where == '')
  $sql = 'SELECT `id`,`vendor`,`category`,`product`,`attribute`,`ei`,`eif`,`ea`,`eaf`,`f`,`fm`,sppc FROM `d_task` Limit '.$start.','.$limit;
  else
  $sql = 'SELECT `id`,`vendor`,`category`,`product`,`attribute`,`ei`,`eif`,`ea`,`eaf`,`f`,`fm`,sppc FROM `d_task` WHERE '.$where.' Limit '.$start.','.$limit;

  $result = mysql_query($sql,$$link);
  if (!$result) {
      die('Could not query:' . mysql_error());
  }
  if(mysql_num_rows($result)>0)
  {

  while ($tasks = tep_db_fetch_array($result)) {
  ?>
  <tr>
    <td bgcolor="#F9FBFD" style=" border-bottom: 1px solid #D7E5F2; border-left: 1px solid #D7E5F2;border-top: 1px solid #D7E5F2;">
    <?php
      if ($tasks["vendor"] !=0){
            $resultv = mysql_query("SELECT vdname FROM `d_vendors` Where id=".$tasks["vendor"]." Limit 1",$$link);
            if (!$resultv) {
                die('Could not query:' . mysql_error());
            }
            if(mysql_num_rows($resultv)>0)
            {
            echo mysql_result($resultv, 0, 0);
            }

      }else
      {
       echo "&nbsp;";
      }
      ?>
    </td>
    <td bgcolor="#F9FBFD" style=" border-bottom: 1px solid #D7E5F2; border-left: 1px solid #D7E5F2;border-top: 1px solid #D7E5F2;">
    <?php
      if ($tasks["category"] !=0){
      echo $tasks["category"];
      }else
      {
       echo "&nbsp;";
      }
      ?>
    </td>
    <td bgcolor="#F9FBFD" style=" border-bottom: 1px solid #D7E5F2; border-left: 1px solid #D7E5F2;border-top: 1px solid #D7E5F2;">
    <?php
      if ($tasks["product"] !=0){
      echo $tasks["product"];
      }else
      {
       echo "&nbsp;";
      }
      ?>
    </td>
    <td bgcolor="#F9FBFD" style=" border-bottom: 1px solid #D7E5F2; border-left: 1px solid #D7E5F2;border-top: 1px solid #D7E5F2;">
      <?php
      if ($tasks["attribute"] !=0){
      echo $tasks["attribute"];
      }else
      {
       echo "&nbsp;";
      }
      ?></td>
          <?php
    if($sppc) echo '<td bgcolor="#F9FBFD" style=" border-bottom: 1px solid #D7E5F2; border-left: 1px solid #D7E5F2;border-top: 1px solid #D7E5F2;">'.$tasks["sppc"].'</td>';
    ?>
    <td style="border: 1px solid #D7E5F2;border-collapse: collapse;" bgcolor="#F9FBFD"><label>
      Fiyat
          <?php
      if ($tasks["eif"] !=null){
      if($tasks["ei"]==1) echo "= ". $tasks["eif"];
      else if($tasks["ei"]==2) echo "> ". $tasks["eif"];
      else if($tasks["ei"]==3) echo "< ". $tasks["eif"];
      }
      if ($tasks["eaf"] !=null){
       ?>
      ,
          <?php
      if($tasks["ea"]==1) echo "= ". $tasks["eaf"];
      else if($tasks["ea"]==2) echo "> ". $tasks["eaf"];
      else if($tasks["ea"]==3) echo "< ". $tasks["eaf"];
      }
       ?>
    </label></td>
    <td style=" border-bottom: 1px solid #D7E5F2; border-right: 1px solid #D7E5F2;border-top: 1px solid #D7E5F2;border-collapse: collapse;" bgcolor="#F9FBFD">Fiyat
      <?php
      if ($tasks["fm"] !=null){
      if($tasks["f"]==1) echo "* ".$tasks["fm"];
      else if($tasks["f"]==2) echo "/ ".$tasks["fm"];
      else if($tasks["f"]==3) echo "- ".$tasks["fm"];
      else if($tasks["f"]==4) echo "+ ".$tasks["fm"];
      }
       ?></td>
    <td style=" border-bottom: 1px solid #D7E5F2; border-right: 1px solid #D7E5F2;border-top: 1px solid #D7E5F2;border-collapse: collapse;" bgcolor="#F9FBFD"><a href="?process=setting&limit=<?php echo $limit;?>&start=<?php echo $start;?>&id=<?php echo $tasks["id"];?>">sil</a></td>
  </tr>
  <?php
    }
   }
  ?>
</table>
<?php
}

function inc_special_list($link = 'db_link'){
global $$link;
$sppc = config('sppc');
$start=$_GET['start'];
$limit = $_GET['limit'];
if((!($start > 0)) || (isset($_POST['ara']))) {
$start = 0;
}

if (isset($_POST['ara']))
$ara = $_POST['ara'];
else if(!empty($_GET['ara']))
$ara = $_GET['ara'];
else $ara = '';

$querystr = '?process=special&start='.$start.'&limit='.$limit.'&ara='.$ara;
search($querystr,'ara','search',$ara);

$where = preparewhere($ara,'pcode AS s ; vendor AS n ; pcode AS s ;'.(($sppc)?' sppc AS n ;':'').' rate AS n ; discount AS n');
if ($where == '')
$sql = 'SELECT count(*) FROM `d_special`';
else
$sql = 'SELECT count(*) FROM `d_special` Where '.$where;

$result = mysql_query($sql,$$link);
if (!$result) {
    die('Could not query:' . mysql_error());
}
$nume = 0;
if(mysql_num_rows($result)>0)
{
$nume = mysql_result($result, 0, 0);
}

if($start > $nume) {
$start = $nume-$limit;
}
if(!($limit > 0)) {
$limit = 10;
}

?>
<table style="font-size: 12px;font-family: Verdana, Arial, Helvetica, sans-serif;"  border="0" align="center" cellpadding="5" cellspacing="0">
<tr>
    <td colspan="7"><div align="center">
<?php
paging($nume,$start,$limit,'start','?process=special&limit='.$limit.'&ara='.$ara);
?>
    </div></td>
    </tr>
<tr>
    <td bgcolor="#EFEFEF">Tedarikçi</td>
    <td bgcolor="#EFEFEF">Ürün Kodu</td>
    <?php if($sppc) echo '<td bgcolor="#EFEFEF">Bayii</td>'; ?>
    <td bgcolor="#EFEFEF">Ýndirim</td>
    <td bgcolor="#EFEFEF">&nbsp;</td>
  </tr>
  <?php
  if ($where == '')
  $sql = 'SELECT `id`,`vendor`,`pcode`,`rate`,`discount`,sppc FROM `d_special` Order by id desc Limit '.$start.','.$limit;
  else
  $sql = 'SELECT `id`,`vendor`,`pcode`,`rate`,`discount`,sppc FROM `d_special` WHERE '.$where.' Order by id desc Limit '.$start.','.$limit;

  $result = mysql_query($sql,$$link);
  if (!$result) {
      die('Could not query:' . mysql_error());
  }
  if(mysql_num_rows($result)>0)
  {

  while ($tasks = tep_db_fetch_array($result)) {
  ?>
  <tr>
    <td bgcolor="#F9FBFD" style=" border-bottom: 1px solid #D7E5F2; border-left: 1px solid #D7E5F2;border-top: 1px solid #D7E5F2;">
    <?php
      if ($tasks["vendor"] !=0){
            $resultv = mysql_query("SELECT vdname FROM `d_vendors` Where id=".$tasks["vendor"]." Limit 1",$$link);
            if (!$resultv) {
                die('Could not query:' . mysql_error());
            }
            if(mysql_num_rows($resultv)>0)
            {
            echo mysql_result($resultv, 0, 0);
            }

      }else
      {
       echo "&nbsp;";
      }
      ?>
    </td>
    <td bgcolor="#F9FBFD" style=" border-bottom: 1px solid #D7E5F2; border-left: 1px solid #D7E5F2;border-top: 1px solid #D7E5F2;">
    <?php
      if ($tasks["pcode"] !=''){
      echo $tasks["pcode"];
      }else
      {
       echo "&nbsp;";
      }
      ?>
    </td>
    <?php
    if($sppc) echo '<td bgcolor="#F9FBFD" style=" border-bottom: 1px solid #D7E5F2; border-left: 1px solid #D7E5F2;border-top: 1px solid #D7E5F2;">'.$tasks["sppc"].'</td>';
    ?>
    <td style=" border-bottom: 1px solid #D7E5F2; border-left: 1px solid #D7E5F2; border-right: 1px solid #D7E5F2;border-top: 1px solid #D7E5F2;border-collapse: collapse;" bgcolor="#F9FBFD">Fiyat
      <?php
      if ($tasks["discount"] !=null){
      if($tasks["rate"]==1) echo "* ".$tasks["discount"];
      else if($tasks["rate"]==2) echo "/ ".$tasks["discount"];
      else if($tasks["rate"]==3) echo "- ".$tasks["discount"];
      else if($tasks["rate"]==4) echo "+ ".$tasks["discount"];
      else if($tasks["rate"]==5) echo "= ".$tasks["discount"];
      }
       ?></td>
    <td style=" border-bottom: 1px solid #D7E5F2; border-right: 1px solid #D7E5F2;border-top: 1px solid #D7E5F2;border-collapse: collapse;" bgcolor="#F9FBFD"><a href="?process=special&limit=<?php echo $limit;?>&start=<?php echo $start;?>&id=<?php echo $tasks["id"];?>">sil</a></td>
  </tr>
  <?php
    }
   }
  ?>
</table>
<?php
}

function inc_header(){
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-9" />
<title>Tedarikçi Entegrasyonu Powered By Duzgun.com</title>
</head>
<body>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
<td height="94" colspan="2"><a href=core.php><img src="images/TElogo.gif" width="301" height="89" border=0 /></a></td>
</tr>
<tr>
<td colspan="2" bgcolor="#BEEBFF" align="right"style="font-size: 12px;font-family: Verdana, Arial, Helvetica, sans-serif;" ><img src="images/pixel.gif" width="1" height="10" /><a href=?process=update>Yazýlým Güncellemeleri</a> | <a href=?process=copyright>Kullaným Sözleþmesi</a> | <a href=?process=pairing> Genel Ayar Tanýmlarý </a> | <a href=?process=deleted>Tedarik Edilemeyen Ürünler</a>  |  <a href=?process=setting>Fiyat Transfer Ayarlarý</a>  |  <a href=?process=special>Ýndirimli Ürünler</a> &nbsp;</td>
</tr>
<tr>
<td valign="top" width="230" height="160"><img src="images/pixel.gif" width="230" height="10" /><br />
<table width="200" border="0" cellspacing="0" cellpadding="0">
<tr>
<td colspan="3"><img src="images/menuust.gif" width="219" height="31" /></td>
</tr>
<tr>
<td width="13" rowspan="2"><img src="images/menusol.gif" width="13" height="174" /></td>
<td width="194" height="160" align="center" valign="middle"><img src="images/menuitem.gif" width="183" height="141" border="0" usemap="#Map" />
<map name="Map" id="Map">
<area shape="rect" coords="0,0,182,22" href="?process=xmldownload" />
<area shape="rect" coords="0,28,182,51" href="?process=read" />
<area shape="rect" coords="0,56,182,79" href="?process=imagedownload" />
<area shape="rect" coords="0,84,182,107" href="?process=compare" />
<area shape="rect" coords="0,115,182,138" href="?process=transfer" />
</map>
</td>
<td width="12" rowspan="2"><img src="images/menusag.gif" width="12" height="174" /></td>
</tr>
<tr>
<td><img src="images/menualt.gif" width="194" height="14" /></td>
</tr>
</table>

<table border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td><img src="images/pixel.gif" width="10" height="1" /></td>
    <td width="200">
<br/>
    </td><td><img src="images/pixel.gif" width="10" height="1" /></td>
  </tr>
</table>

</td>
<td valign="top" width="100%"><img src="images/pixel.gif" width="200" height="10" /><br />
<?php
}

function inc_footer(){
?>
<p align="center">Copyright © 2008-<?php echo date('Y');?> <a href="http://www.duzgun.com" target="_blank">Duzgun.com</a> All rights   reserved.<br />
Coded by: Yavuz Yasin DÜZGÜN</p>
<br />
</td>
</tr>
</table>
</body>
</html>
<?php
}
function compare(){
?>
<div align="center">
<img src="images/compare.gif" width="450" height="220" border="0" usemap="#Map2">
<map name="Map2">
<map name="Map2">
<area shape="rect" coords="9,27,217,55" href="?process=compare&action=language">
<area shape="rect" coords="9,72,217,100" href="?process=compare&action=tax">
<area shape="rect" coords="9,162,217,190" href="?process=compare&action=option">
<area shape="rect" coords="9,117,217,145" href="?process=compare&action=category">
<area shape="rect" coords="231,162,439,190" href="?process=compare&action=feature">
<area shape="rect" coords="231,27,439,55" href="?process=compare&action=currency">
<area shape="rect" coords="231,72,439,100" href="?process=compare&action=brand">
<area shape="rect" coords="231,117,439,145" href="?process=compare&action=product">
</map>
</map>
</div>
<?php
}

function language_modify($link = 'db_link'){
global $$link,$installed_modules;

echo '<table width="100%" border="0" cellspacing="0" cellpadding="10"><tr><td valign=top>'."\n";

if(isset($_POST["kaydet"]) && (!empty($_POST["langs"]))){
foreach(explode(',',$_POST["langs"]) as $lang_id){
mysql_query("UPDATE `d_vendors` SET `languageid` = '".tep_db_input($_POST["language_".$lang_id])."' Where id='".tep_db_input($lang_id)."'",$$link);
}

}

echo '<form name="taxform" method="post" action="?process=compare&action=language">';
ksort($installed_modules);
$langids = array();
foreach($installed_modules as $class)
{
$vendor = vendor($class);
$module = new $class;

$result = mysql_query('SELECT id,vdname,languageid FROM `d_vendors` WHERE id='.$vendor,$$link);
if (!$result) {
    die('Could not query:' . mysql_error());
}
if(mysql_num_rows($result)>0)
{
echo '<table width="100%" border="0" cellpadding="0" cellspacing="0"><tr>'."\n";
echo '<td bgcolor="#9AC1E5"><table width="100%" border="0" cellpadding="3" cellspacing="1">'."\n";
echo '<tr><td bgcolor="#FFFFFF" height="10"><font face="Georgia, Times New Roman, Times, serif" color="#0099FF">'.$module->title.'</font></td>'."\n";
echo '</tr><tr><td bgcolor="#FFFFFF">'."\n";

echo '<table border=0 cellspacing=0 cellpadding=4><tr><td>Tedarikçi Kodu</td><td>-&gt;Dil Id</td></tr>'."\n";
while ($langs = tep_db_fetch_array($result)) {
$langids[] = $langs['id'];
echo '<tr>'."\n";
echo '<td><input type="text" value="'.$langs['vdname'].'" size="8" disabled="disabled"/></td>'."\n";
echo '<td><input name="language_'.$langs['id'].'" type="text" value="'.$langs['languageid'].'" size="8" /></td>'."\n";
echo '</tr>'."\n";
}
echo '</table>'."\n";

echo '</td></tr></table></td></tr></table><br/>'."\n";
}
}
echo '<input name="langs" type="hidden" id="langs" value="'.join(',',$langids).'" size="15"/>'."\n";
echo '<input type="submit" name="kaydet" value="Kaydet"></form></td><td valign=top>'."\n";
echo '<table  width="100%" border="0" cellpadding="2" cellspacing="0">'."\n";
echo '<tr><td bgcolor="#9AC1E5"><table width="100%" border="0" cellpadding="3" cellspacing="1"><tr>'."\n";
echo '<td bgcolor="#FFFFFF" height="10"><font face="Georgia, Times New Roman, Times, serif" color="#0099FF">'.CONNECTOR.'</font></td>'."\n";
echo '</tr><tr><td bgcolor="#FFFFFF">'."\n";

connector_language();

echo '</td></tr></table></td></tr></table>'."\n";
echo '</td></tr></table>'."\n";

}

function tax_modify($link = 'db_link'){
global $$link,$installed_modules;

echo '<table width="100%" border="0" cellspacing="0" cellpadding="10"><tr><td valign=top>'."\n";

if(isset($_POST["kaydet"]) && (!empty($_POST["taxids"]))){
foreach(explode(',',$_POST["taxids"]) as $taxid){
mysql_query("UPDATE `d_taxs` SET `taxname` = '".tep_db_input($_POST["taxname_".$taxid])."',`osid`= '".tep_db_input($_POST["taxosid_".$taxid])."' Where id='".tep_db_input($taxid)."'",$$link);
}

}

echo '<form name="taxform" method="post" action="?process=compare&action=tax">';
ksort($installed_modules);
$taxids = array();
foreach($installed_modules as $class)
{
$vendor = vendor($class);
$module = new $class;

$result = mysql_query('SELECT id,taxcode,taxname,osid FROM `d_taxs` WHERE vendor='.$vendor,$$link);
if (!$result) {
    die('Could not query:' . mysql_error());
}
if(mysql_num_rows($result)>0)
{
echo '<table  width="100%" border="0" cellpadding="0" cellspacing="0"><tr>'."\n";
echo '<td bgcolor="#9AC1E5"><table width="100%" border="0" cellpadding="3" cellspacing="1">'."\n";
echo '<tr><td bgcolor="#FFFFFF" height="10"><font face="Georgia, Times New Roman, Times, serif" color="#0099FF">'.$module->title.'</font></td>'."\n";
echo '</tr><tr><td bgcolor="#FFFFFF">'."\n";

echo '<table border=0 cellspacing=0 cellpadding=4><tr><td>Vergi Kodu</td><td>Vergi Adý</td><td>-&gt;Sistem Id</td></tr>'."\n";
while ($taxs = tep_db_fetch_array($result)) {
$taxids[] = $taxs['id'];
echo '<tr>'."\n";
echo '<td><input type="text" value="'.$taxs['taxcode'].'" size="8" disabled="disabled"/></td>'."\n";
echo '<td><input name="taxname_'.$taxs['id'].'" type="text" value="'.$taxs['taxname'].'" size="10" /></td>'."\n";
echo '<td><input name="taxosid_'.$taxs['id'].'" type="text" value="'.$taxs['osid'].'" size="8" /></td>'."\n";
echo '</tr>'."\n";
}
echo '</table>'."\n";

echo '</td></tr></table></td></tr></table><br/>'."\n";
}
}
echo '<input name="taxids" type="hidden" id="taxids" value="'.join(',',$taxids).'" size="15"/>'."\n";
echo '<input type="submit" name="kaydet" value="Kaydet"></form></td><td valign=top>'."\n";
echo '<table  width="100%" border="0" cellpadding="2" cellspacing="0">'."\n";
echo '<tr><td bgcolor="#9AC1E5"><table width="100%" border="0" cellpadding="3" cellspacing="1"><tr>'."\n";
echo '<td bgcolor="#FFFFFF" height="10"><font face="Georgia, Times New Roman, Times, serif" color="#0099FF">'.CONNECTOR.'</font></td>'."\n";
echo '</tr><tr><td bgcolor="#FFFFFF">'."\n";

connector_tax();

echo '</td></tr></table></td></tr></table>'."\n";
echo '</td></tr></table>'."\n";

}


function currency_modify($link = 'db_link'){
global $$link,$installed_modules;

echo '<table width="100%" border="0" cellspacing="0" cellpadding="10"><tr><td valign=top>'."\n";

if(isset($_POST["kaydet"]) && (!empty($_POST["currencyids"]))){
foreach(explode(',',$_POST["currencyids"]) as $currencyid){
mysql_query("UPDATE `d_currency` SET `name` = '".tep_db_input($_POST["currencyname_".$currencyid])."',`osid`= '".tep_db_input($_POST["currencyosid_".$currencyid])."' Where id='".tep_db_input($currencyid)."'",$$link);
}

}

echo '<form name="currencyform" method="post" action="?process=compare&action=currency">';
ksort($installed_modules);
$currenyids = array();
foreach($installed_modules as $class)
{
$vendor = vendor($class);
$module = new $class;

$result = mysql_query('SELECT id,code,name,osid FROM `d_currency` WHERE vendor='.$vendor,$$link);
if (!$result) {
    die('Could not query:' . mysql_error());
}
if(mysql_num_rows($result)>0)
{
echo '<table  width="100%" border="0" cellpadding="0" cellspacing="0"><tr>'."\n";
echo '<td bgcolor="#9AC1E5"><table width="100%" border="0" cellpadding="3" cellspacing="1">'."\n";
echo '<tr><td bgcolor="#FFFFFF" height="10"><font face="Georgia, Times New Roman, Times, serif" color="#0099FF">'.$module->title.'</font></td>'."\n";
echo '</tr><tr><td bgcolor="#FFFFFF">'."\n";

echo '<table border=0 cellspacing=0 cellpadding=4><tr><td>Para Birimi Kodu</td><td>Para Birimi Adý</td><td>-&gt;Sistem Id</td></tr>'."\n";
while ($currencys = tep_db_fetch_array($result)) {
$currencyids[] = $currencys['id'];
echo '<tr>'."\n";
echo '<td><input type="text" value="'.$currencys['code'].'" size="8" disabled="disabled"/></td>'."\n";
echo '<td><input name="currencyname_'.$currencys['id'].'" type="text" value="'.$currencys['name'].'" size="10" /></td>'."\n";
echo '<td><input name="currencyosid_'.$currencys['id'].'" type="text" value="'.$currencys['osid'].'" size="8" /></td>'."\n";
echo '</tr>'."\n";
}
echo '</table>'."\n";

echo '</td></tr></table></td></tr></table><br/>'."\n";
}
}
if (count($currencyids)>0){
echo '<input name="currencyids" type="hidden" id="currencyids" value="'.join(',',$currencyids).'" size="15"/>'."\n";
echo '<input type="submit" name="kaydet" value="Kaydet">';
}
echo '</form></td><td valign=top>'."\n";
echo '<table  width="100%" border="0" cellpadding="2" cellspacing="0">'."\n";
echo '<tr><td bgcolor="#9AC1E5"><table width="100%" border="0" cellpadding="3" cellspacing="1"><tr>'."\n";
echo '<td bgcolor="#FFFFFF" height="10"><font face="Georgia, Times New Roman, Times, serif" color="#0099FF">'.CONNECTOR.'</font></td>'."\n";
echo '</tr><tr><td bgcolor="#FFFFFF">'."\n";

connector_currency();

echo '</td></tr></table></td></tr></table>'."\n";
echo '</td></tr></table>'."\n";

}

function category_modify($link = 'db_link'){
global $$link,$installed_modules;

echo '<table width="100%" border="0" cellspacing="0" cellpadding="10"><tr><td valign=top>'."\n";

if(isset($_POST["kaydet"]) && (!empty($_POST["categoryids"]))){
foreach(explode(',',$_POST["categoryids"]) as $categoryid){
if ($_POST["categoryiptal_".$categoryid] == 1){
//if($_POST["categoryosid_".$categoryid] == 0) oscid sýfýrlama bölümü gereksiz olarak not tutuldu.
mysql_query("UPDATE `d_categories` SET hidden = 1, `cname` = '".tep_db_input($_POST["categoryname_".$categoryid])."',`osid`= '".tep_db_input($_POST["categoryosid_".$categoryid])."'".(isset($_POST["categoryospi_".$categoryid])?",`ospi`= '".tep_db_input($_POST["categoryospi_".$categoryid])."'":"")." Where id='".tep_db_input($categoryid)."'",$$link);
}
else
{
mysql_query("UPDATE `d_categories` SET hidden = 0, `cname` = '".tep_db_input($_POST["categoryname_".$categoryid])."',`osid`= '".tep_db_input($_POST["categoryosid_".$categoryid])."'".(isset($_POST["categoryospi_".$categoryid])?",`ospi`= '".tep_db_input($_POST["categoryospi_".$categoryid])."'":"")." Where id='".tep_db_input($categoryid)."'",$$link);
}
}
}

$start=$_GET['start'];
$limit = $_GET['limit'];
if((!($start > 0)) || (isset($_POST['ara']))) {
$start = 0;
}
$startos = $_GET['startos'];
$limitos = $_GET['limitos'];
if((!($startos > 0)) || (isset($_POST['araos']))) {
$startos = 0;
}

if (isset($_POST['ara']))
$ara = $_POST['ara'];
else if(!empty($_GET['ara']))
$ara = $_GET['ara'];
else $ara = '';

if (isset($_POST['araos']))
$araos = $_POST['araos'];
else if(!empty($_GET['araos']))
$araos = $_GET['araos'];
else $araos = '';

$querystr = $PHP_SELF.'?process=compare&action=category'.((isset($_GET['module']))?'&module='.$_GET['module']:'').'&start='.$start.'&limit='.$limit.'&startos='.$startos.'&limitos='.$limitos.'&ara='.$ara.'&araos='.$araos;
search($querystr,'ara','searchos',$ara);
/*
$degiskenler = array(0 => 'cname', 1=>'code', 2=>'parentcode', 3=>'cname', 4=>'osid', 5=>'hidden');
$tanimlar = array(0 => 's', 1=>'s', 2=>'s', 3=>'s', 4=>'n', 5=>'n');
$where = searchquery($ara,$degiskenler,$tanimlar);
*/
$where = preparewhere($ara,'cname AS s ; code AS s ; parentcode AS s ; cname AS s ; osid AS n ; ospi AS n ; hidden AS n');
// 0005 eklenti

if ($where == '')
$sql = 'SELECT count(*) FROM `d_categories` Group by vendor Order by count(*) Desc Limit 1';
else
$sql = 'SELECT count(*) FROM `d_categories` Where '.$where.' Group by vendor Order by count(*) Desc Limit 1';

$result = mysql_query($sql,$$link);
if (!$result) {
    die('Could not query:' . mysql_error());
}
$nume = 0;
if(mysql_num_rows($result)>0)
{
$nume = mysql_result($result, 0, 0);
}

if($start > $nume) {
$start = $nume-$limit;
}
if(!($limit > 0)) {
$limit = 10;
}

echo '<table border="0" width="100%" align="center" cellpadding="0" cellspacing="0"><form name="categoryform" method="post" action="'.$querystr.'"><tbody><tr><td>';
paging($nume,$start,$limit,'start',$PHP_SELF.'?process=compare&action=category'.((isset($_GET['module']))?'&module='.$_GET['module']:'').'&limit='.$limit.'&startos='.$startos.'&limitos='.$limitos.'&ara='.$ara.'&araos='.$araos);
ksort($installed_modules);
$currenyids = array();
foreach($installed_modules as $class)
{
$vendor = vendor($class);
$module = new $class;
if ($where == '')
$sql = 'SELECT id,code,parentcode,cname,osid,ospi,hidden FROM `d_categories` WHERE vendor='.$vendor.' order by osid,id  Limit '.$start.','.$limit;
else
$sql = 'SELECT id,code,parentcode,cname,osid,ospi,hidden FROM `d_categories` WHERE '.$where.' AND vendor='.$vendor.' order by osid,id  Limit '.$start.','.$limit;

$result = mysql_query($sql,$$link);
if (!$result) {
    die('Could not query:' . mysql_error());
}
if(mysql_num_rows($result)>0)
{
echo '<table  width="100%" border="0" cellpadding="0" cellspacing="0"><tr>'."\n";
echo '<td bgcolor="#9AC1E5"><table width="100%" border="0" cellpadding="3" cellspacing="1">'."\n";
echo '<tr><td bgcolor="#FFFFFF" height="10"><font face="Georgia, Times New Roman, Times, serif" color="#0099FF">'.$module->title.'</font></td>'."\n";
echo '</tr><tr><td bgcolor="#FFFFFF">'."\n";

echo '<table border=0 cellspacing=0 cellpadding=4><tr><td>Kategori</td><td>Parent</td><td>Kategori Adý</td><td>-&gt;Sistem Id</td><td>-&gt;Parent Id</td><td>Ýptal</td></tr>'."\n";
while ($categorys = tep_db_fetch_array($result)) {
$categoryids[] = $categorys['id'];
echo '<tr>'."\n";
echo '<td><input type="text" value="'.$categorys['code'].'" size="4" disabled="disabled"/></td>'."\n";
echo '<td><input type="text" value="'.$categorys['parentcode'].'" size="4" disabled="disabled"/></td>'."\n";
echo '<td><input name="categoryname_'.$categorys['id'].'" type="text" value="'.$categorys['cname'].'" size="10" /></td>'."\n";
echo '<td><input name="categoryosid_'.$categorys['id'].'" type="text" value="'.$categorys['osid'].'" size="6" /></td>'."\n";
echo '<td><input name="categoryospi_'.$categorys['id'].'" type="text" value="'.$categorys['ospi'].'" size="6" '.(($categorys['osid']!=0)?'disabled="disabled"':'').'/></td>'."\n";
echo '<td><input name="categoryiptal_'.$categorys['id'].'" type="checkbox" value="1" '.(($categorys['hidden']==1)? 'checked="checked" ':'').'/></td>'."\n";
echo '</tr>'."\n";
}
echo '</table>'."\n";

echo '</td></tr></table></td></tr></table><br/>'."\n";
}
}
if (count($categoryids)>0){
echo '<input name="categoryids" type="hidden" id="categoryids" value="'.join(',',$categoryids).'" size="15"/>'."\n";
echo '<input type="submit" name="kaydet" value="Kaydet">';
}
echo '</td></tr></tbody></form></table></td><td valign=top>'."\n";





$result = mysql_query('SELECT `languageid` FROM `d_vendors` GROUP BY `languageid`',$$link);
if (!$result) {
    die('Could not query:' . mysql_error());
}
if(mysql_num_rows($result)>0)
{
$language = array();
while ($langs = tep_db_fetch_array($result)) {
$language[] = $langs['languageid'];
}

search($querystr,'araos','searchos',$araos);
/*
$degiskenler = array(0 => 'cd`.`categories_name', 1=>'cs`.`categories_id', 2=>'cs`.`parent_id', 3=>'cd`.`categories_name');
$tanimlar = array(0 => 's', 1=>'n', 2=>'n', 3=>'s');
$where = searchquery($araos,$degiskenler,$tanimlar);
*/
connector_category($start,$startos,$ara,$araos,$limit,$limitos,$language);
}
echo '</td></tr></table>'."\n";

}

function paging($nume,$start,$limit,$startstr,$page_name){

$eu = ($start - 0);
$thi  = $eu + $limit;
$back = $eu - $limit;
$next = $eu + $limit;

echo "<table align = 'center' width='80%'><tr><td align='left' width='20%'>";
if($back >=0) {
print "<a href='$page_name&$startstr=$back'><font face='Verdana' size='2'>Geri</font></a>";
}
echo "</td><td align=center width='60%'>";
$i=0;
$l=1;
$ls = $eu - ($limit*5);
$gs = $eu + ($limit*5);
if($ls<0) $gs = $gs-$ls;
if($gs>$nume) $ls = $ls-($gs-$nume);

for($i=0;$i < $nume;$i=$i+$limit){
if((($i>$ls) && ($i<$gs))){
if($i <> $eu){
echo " <a href='$page_name&$startstr=$i'><font face='Verdana' size='2'>$l</font></a> ";
}
else { echo "<font face='Verdana' size='2' color=red>$l</font>";
}
}
$l=$l+1;
 }
echo "</td><td align='right' width='20%'>";
if($thi < $nume) {
print "<a href='$page_name&$startstr=$next'><font face='Verdana' size='2'>Ýleri</font></a>";
}
echo "</td></tr></table>";


}

function search($querystr,$ara,$search,$aratext)
{
?>
<table border="0" align="center" cellpadding="0" cellspacing="0">
<form id="searchform" name="searchform" method="post" action="<?php echo $querystr;?>">
<tbody>
<tr>
<td>
  <input name="<?php echo $ara;?>" type="text" id="<?php echo $ara;?>" value="<?php echo $aratext;?>" size="8" />
  <input type="submit" name="<?php echo $search;?>" id="<?php echo $search;?>" value="ara" />
  (Ýpucu = 1:cpu 2:intel)

</td>
</tr>
</tbody>
</form>
</table>
<?php
}

function searchquery($str,$degiskenler,$tanimlar){
$where = '';
if (!empty($str))
{
foreach ($degiskenler as $key=>$val)
{
if(preg_match("/".$key."\:(.+?)(\d\:|$)/", $str,$matches))
{
$text = ereg_replace(" $","", $matches[1]);
if($where == '')
{
if ($tanimlar[$key] == 'n'){
$where =  "`".$val."` = '".tep_db_input($text)."'";
}else{
if ($text == '')
$where =  "`".$val."` = ''";
else
$where =  "`".$val."` like '%".tep_db_input($text)."%'";
}
}else{
if ($tanimlar[$key] == 'n'){
$where = $where.' AND `'. $val."` = '".tep_db_input($text)."'";
}else{
if ($text == '')
$where = $where.' AND `'. $val."` = ''";
else
$where = $where.' AND `'. $val."` like '%".tep_db_input($text)."%'";
}
}
}
}
if($where == '')
{
if ($tanimlar[0] == 'n'){
$where =  "`".$degiskenler[0]."` = '".tep_db_input($str)."'";
}else{
$where =  "`".$degiskenler[0]."` like '%".tep_db_input($str)."%'";
}
}
}
return $where;
}

function brand_modify($link = 'db_link'){
global $$link,$installed_modules;

echo '<table width="100%" border="0" cellspacing="0" cellpadding="10"><tr><td valign=top>'."\n";

if(isset($_POST["kaydet"]) && (!empty($_POST["categoryids"]))){
foreach(explode(',',$_POST["categoryids"]) as $categoryid){
mysql_query("UPDATE `d_brands` SET `bname` = '".tep_db_input($_POST["categoryname_".$categoryid])."',`osid`= '".tep_db_input($_POST["categoryosid_".$categoryid])."' Where id='".tep_db_input($categoryid)."'",$$link);
}

}

$start=$_GET['start'];
$limit = $_GET['limit'];
if((!($start > 0)) || (isset($_POST['ara']))) {
$start = 0;
}
$startos = $_GET['startos'];
$limitos = $_GET['limitos'];
if((!($startos > 0)) || (isset($_POST['araos']))) {
$startos = 0;
}

if (isset($_POST['ara']))
$ara = $_POST['ara'];
else if(!empty($_GET['ara']))
$ara = $_GET['ara'];
else $ara = '';

if (isset($_POST['araos']))
$araos = $_POST['araos'];
else if(!empty($_GET['araos']))
$araos = $_GET['araos'];
else $araos = '';

$querystr = $PHP_SELF.'?process=compare&action=brand'.((isset($_GET['module']))?'&module='.$_GET['module']:'').'&start='.$start.'&limit='.$limit.'&startos='.$startos.'&limitos='.$limitos.'&ara='.$ara.'&araos='.$araos;
search($querystr,'ara','searchos',$ara);
/*
$degiskenler = array(0 => 'bname', 1=>'bcode', 2=>'bname', 3=>'osid');
$tanimlar = array(0 => 's', 1=>'s', 2=>'s', 3=>'n');
$where = searchquery($ara,$degiskenler,$tanimlar);
*/
$where = preparewhere($ara,'bname AS s ; bcode AS s ; bname AS s ; osid AS n');

if ($where == '')
$sql = 'SELECT count(*) FROM `d_brands` Group by vendor Order by count(*) Desc Limit 1';
else
$sql = 'SELECT count(*) FROM `d_brands` Where '.$where.' Group by vendor Order by count(*) Desc Limit 1';

$result = mysql_query($sql,$$link);
if (!$result) {
    die('Could not query:' . mysql_error());
}
$nume = 0;
if(mysql_num_rows($result)>0)
{
$nume = mysql_result($result, 0, 0);
}

if($start > $nume) {
$start = $nume-$limit;
}
if(!($limit > 0)) {
$limit = 10;
}

echo '<table border="0" width="100%" align="center" cellpadding="0" cellspacing="0"><form name="categoryform" method="post" action="'.$querystr.'"><tbody><tr><td>';
paging($nume,$start,$limit,'start',$PHP_SELF.'?process=compare&action=brand'.((isset($_GET['module']))?'&module='.$_GET['module']:'').'&limit='.$limit.'&startos='.$startos.'&limitos='.$limitos.'&ara='.$ara.'&araos='.$araos);
ksort($installed_modules);
$currenyids = array();
foreach($installed_modules as $class)
{
$vendor = vendor($class);
$module = new $class;
if ($where == '')
$sql = 'SELECT id,bcode,bname,osid FROM `d_brands` WHERE vendor='.$vendor.' order by osid,id  Limit '.$start.','.$limit;
else
$sql = 'SELECT id,bcode,bname,osid FROM `d_brands` WHERE '.$where.' AND vendor='.$vendor.' order by osid,id  Limit '.$start.','.$limit;

$result = mysql_query($sql,$$link);
if (!$result) {
    die('Could not query:' . mysql_error());
}
if(mysql_num_rows($result)>0)
{
echo '<table  width="100%" border="0" cellpadding="0" cellspacing="0"><tr>'."\n";
echo '<td bgcolor="#9AC1E5"><table width="100%" border="0" cellpadding="3" cellspacing="1">'."\n";
echo '<tr><td bgcolor="#FFFFFF" height="10"><font face="Georgia, Times New Roman, Times, serif" color="#0099FF">'.$module->title.'</font></td>'."\n";
echo '</tr><tr><td bgcolor="#FFFFFF">'."\n";

echo '<table border=0 cellspacing=0 cellpadding=4><tr><td>Marka Kodu</td><td>Marka Adý</td><td>-&gt;Sistem Id</td></tr>'."\n";
while ($categorys = tep_db_fetch_array($result)) {
$categoryids[] = $categorys['id'];
echo '<tr>'."\n";
echo '<td><input type="text" value="'.$categorys['bcode'].'" size="8" disabled="disabled"/></td>'."\n";
echo '<td><input name="categoryname_'.$categorys['id'].'" type="text" value="'.$categorys['bname'].'" size="10" /></td>'."\n";
echo '<td><input name="categoryosid_'.$categorys['id'].'" type="text" value="'.$categorys['osid'].'" size="8" /></td>'."\n";
echo '</tr>'."\n";
}
echo '</table>'."\n";

echo '</td></tr></table></td></tr></table><br/>'."\n";
}
}
if (count($categoryids)>0){
echo '<input name="categoryids" type="hidden" id="categoryids" value="'.join(',',$categoryids).'" size="15"/>'."\n";
echo '<input type="submit" name="kaydet" value="Kaydet">';
}
echo '</td></tr></tbody></form></table></td><td valign=top>'."\n";

$result = mysql_query('SELECT `languageid` FROM `d_vendors` GROUP BY `languageid`',$$link);
if (!$result) {
    die('Could not query:' . mysql_error());
}
if(mysql_num_rows($result)>0)
{
$language = array();
while ($langs = tep_db_fetch_array($result)) {
$language[] = $langs['languageid'];
}

search($querystr,'araos','searchos',$araos);
/*
$degiskenler = array(0 => 'cs`.`manufacturers_name', 1=>'cs`.`manufacturers_id', 2=>'cs`.`manufacturers_name', 3=>'cd`.`manufacturers_url');
$tanimlar = array(0 => 's', 1=>'n', 2=>'s', 3=>'s');
$where = searchquery($araos,$degiskenler,$tanimlar);
*/
connector_manufacturer($start,$startos,$ara,$araos,$limit,$limitos,$language);
}
echo '</td></tr></table>'."\n";

}

function product_modify($link = 'db_link'){
global $$link,$installed_modules;

echo '<table width="100%" border="0" cellspacing="0" cellpadding="10"><tr><td valign=top>'."\n";

if(isset($_POST["kaydet"]) && (!empty($_POST["categoryids"]))){
foreach(explode(',',$_POST["categoryids"]) as $categoryid){
if ($_POST["categoryiptal_".$categoryid] == 1)
mysql_query("UPDATE `d_products` SET hidden = 1, `pname` = '".tep_db_input($_POST["categoryname_".$categoryid])."',`osid`= '".tep_db_input($_POST["categoryosid_".$categoryid])."' Where id='".tep_db_input($categoryid)."'",$$link);
else
mysql_query("UPDATE `d_products` SET hidden = 0, `pname` = '".tep_db_input($_POST["categoryname_".$categoryid])."',`osid`= '".tep_db_input($_POST["categoryosid_".$categoryid])."' Where id='".tep_db_input($categoryid)."'",$$link);
}

}

$start=$_GET['start'];
$limit = $_GET['limit'];
if((!($start > 0)) || (isset($_POST['ara']))) {
$start = 0;
}
$startos = $_GET['startos'];
$limitos = $_GET['limitos'];
if((!($startos > 0)) || (isset($_POST['araos']))) {
$startos = 0;
}

if (isset($_POST['ara']))
$ara = $_POST['ara'];
else if(!empty($_GET['ara']))
$ara = $_GET['ara'];
else $ara = '';

if (isset($_POST['araos']))
$araos = $_POST['araos'];
else if(!empty($_GET['araos']))
$araos = $_GET['araos'];
else $araos = '';

$querystr = $PHP_SELF.'?process=compare&action=product'.((isset($_GET['module']))?'&module='.$_GET['module']:'').'&start='.$start.'&limit='.$limit.'&startos='.$startos.'&limitos='.$limitos.'&ara='.$ara.'&araos='.$araos;
search($querystr,'ara','searchos',$ara);
/*
$degiskenler = array(0 => 'pname', 1=>'pcode', 2=>'pname', 3=>'osid', 4=>'hidden');
$tanimlar = array(0 => 's', 1=>'s', 2=>'s', 3=>'n', 4=>'n');
$where = searchquery($ara,$degiskenler,$tanimlar);
*/
$where = preparewhere($ara,'pname AS s ; pcode AS s ; pname AS s ; osid AS n ; hidden AS n');

if ($where == '')
$sql = 'SELECT count(*) FROM `d_products` Group by vendor Order by count(*) Desc Limit 1';
else
$sql = 'SELECT count(*) FROM `d_products` Where '.$where.' Group by vendor Order by count(*) Desc Limit 1';

$result = mysql_query($sql,$$link);
if (!$result) {
    die('Could not query:' . mysql_error());
}
$nume = 0;
if(mysql_num_rows($result)>0)
{
$nume = mysql_result($result, 0, 0);
}

if($start > $nume) {
$start = $nume-$limit;
}
if(!($limit > 0)) {
$limit = 10;
}

echo '<table border="0" width="100%" align="center" cellpadding="0" cellspacing="0"><form name="categoryform" method="post" action="'.$querystr.'"><tbody><tr><td>';
paging($nume,$start,$limit,'start',$PHP_SELF.'?process=compare&action=product'.((isset($_GET['module']))?'&module='.$_GET['module']:'').'&limit='.$limit.'&startos='.$startos.'&limitos='.$limitos.'&ara='.$ara.'&araos='.$araos);
ksort($installed_modules);
$currenyids = array();
foreach($installed_modules as $class)
{
$vendor = vendor($class);
$module = new $class;
if ($where == '')
$sql = 'SELECT id,pcode,pname,osid,hidden FROM `d_products` WHERE vendor='.$vendor.' order by osid,id  Limit '.$start.','.$limit;
else
$sql = 'SELECT id,pcode,pname,osid,hidden FROM `d_products` WHERE '.$where.' AND vendor='.$vendor.' order by osid,id  Limit '.$start.','.$limit;

$result = mysql_query($sql,$$link);
if (!$result) {
    die('Could not query:' . mysql_error());
}
if(mysql_num_rows($result)>0)
{
echo '<table  width="100%" border="0" cellpadding="0" cellspacing="0"><tr>'."\n";
echo '<td bgcolor="#9AC1E5"><table width="100%" border="0" cellpadding="3" cellspacing="1">'."\n";
echo '<tr><td bgcolor="#FFFFFF" height="10"><font face="Georgia, Times New Roman, Times, serif" color="#0099FF">'.$module->title.'</font></td>'."\n";
echo '</tr><tr><td bgcolor="#FFFFFF">'."\n";

echo '<table border=0 cellspacing=0 cellpadding=4><tr><td>Ürün Kodu</td><td>Ürün Adý</td><td>-&gt;Sistem Id</td><td>Ýptal</td></tr>'."\n";
while ($categorys = tep_db_fetch_array($result)) {
$categoryids[] = $categorys['id'];
echo '<tr>'."\n";
echo '<td><input type="text" value="'.$categorys['pcode'].'" size="5" disabled="disabled"/></td>'."\n";
echo '<td><input name="categoryname_'.$categorys['id'].'" type="text" value="'.$categorys['pname'].'" size="20" /></td>'."\n";
echo '<td><input name="categoryosid_'.$categorys['id'].'" type="text" value="'.$categorys['osid'].'" size="5" /></td>'."\n";
echo '<td><input name="categoryiptal_'.$categorys['id'].'" type="checkbox" value="1" '.(($categorys['hidden']==1)? 'checked="checked" ':'').'/></td>'."\n";
echo '</tr>'."\n";
}
echo '</table>'."\n";

echo '</td></tr></table></td></tr></table><br/>'."\n";
}
}
if (count($categoryids)>0){
echo '<input name="categoryids" type="hidden" id="categoryids" value="'.join(',',$categoryids).'" size="15"/>'."\n";
echo '<input type="submit" name="kaydet" value="Kaydet">';
}
echo '</td></tr></tbody></form></table></td><td valign=top>'."\n";





$result = mysql_query('SELECT `languageid` FROM `d_vendors` GROUP BY `languageid`',$$link);
if (!$result) {
    die('Could not query:' . mysql_error());
}
if(mysql_num_rows($result)>0)
{
$language = array();
while ($langs = tep_db_fetch_array($result)) {
$language[] = $langs['languageid'];
}

search($querystr,'araos','searchos',$araos);
/*
$degiskenler = array(0 => 'cd`.`products_name', 1=>'cs`.`products_id', 2=>'cs`.`products_model', 3=>'cd`.`products_name');
$tanimlar = array(0 => 's', 1=>'n', 2=>'s', 3=>'s');
$where = searchquery($araos,$degiskenler,$tanimlar);
*/
connector_product($start,$startos,$ara,$araos,$limit,$limitos,$language);
}
echo '</td></tr></table>'."\n";

}

function option_modify($link = 'db_link'){
global $$link,$installed_modules;

echo '<table width="100%" border="0" cellspacing="0" cellpadding="10"><tr><td valign=top>'."\n";

if(isset($_POST["kaydet"]) && (!empty($_POST["valids"])) && (!empty($_POST["keyids"]))){
foreach(explode(',',$_POST["valids"]) as $valsid){
mysql_query("UPDATE `d_attrval` SET `osid`= '".tep_db_input($_POST["vosid_".$valsid])."' Where id='".tep_db_input($valsid)."'",$$link);
}
foreach(explode(',',$_POST["keyids"]) as $keysid){
mysql_query("UPDATE `d_attrkey` SET `osid` = '".tep_db_input($_POST["kosid_".$keysid])."' Where id='".tep_db_input($keysid)."'",$$link);
}
}

$start=$_GET['start'];
$limit = $_GET['limit'];
if((!($start > 0)) || (isset($_POST['ara']))) {
$start = 0;
}
$startos = $_GET['startos'];
$limitos = $_GET['limitos'];
if((!($startos > 0)) || (isset($_POST['araos']))) {
$startos = 0;
}

if (isset($_POST['ara']))
$ara = $_POST['ara'];
else if(!empty($_GET['ara']))
$ara = $_GET['ara'];
else $ara = '';

if (isset($_POST['araos']))
$araos = $_POST['araos'];
else if(!empty($_GET['araos']))
$araos = $_GET['araos'];
else $araos = '';

$querystr = $PHP_SELF.'?process=compare&action=option'.((isset($_GET['module']))?'&module='.$_GET['module']:'').'&start='.$start.'&limit='.$limit.'&startos='.$startos.'&limitos='.$limitos.'&ara='.$ara.'&araos='.$araos;
search($querystr,'ara','searchos',$ara);
/*
$degiskenler = array(0 => 'vs`.`avname', 1=>'a`.`proid', 2=>'ks`.`akname', 3=>'ks`.`osid', 4=>'vs`.`avname', 5=>'vs`.`osid');
$tanimlar = array(0 => 's', 1=>'n', 2=>'s', 3=>'n', 4=>'s', 5=>'n');
$where = searchquery($ara,$degiskenler,$tanimlar);
*/
$where = preparewhere($ara,'vs`.`avname AS s ; a`.`proid AS n ; ks`.`akname AS s ; ks`.`osid AS n ; vs`.`avname AS s ; vs`.`osid AS n');

if ($where == '')
$sql = 'SELECT count( * ) FROM `d_attr` a LEFT JOIN `d_attrkey` ks ON a.keyid = ks.id LEFT JOIN `d_attrval` vs ON a.valid = vs.id Group by ks.vendor Order by count(*) Desc Limit 1';
else
$sql = 'SELECT count( * ) FROM `d_attr` a LEFT JOIN `d_attrkey` ks ON a.keyid = ks.id LEFT JOIN `d_attrval` vs ON a.valid = vs.id Where '.$where.' Group by ks.vendor Order by count(*) Desc Limit 1';

$result = mysql_query($sql,$$link);
if (!$result) {
    die('Could not query:' . mysql_error());
}
$nume = 0;
if(mysql_num_rows($result)>0)
{
$nume = mysql_result($result, 0, 0);
}

if($start > $nume) {
$start = $nume-$limit;
}
if(!($limit > 0)) {
$limit = 10;
}

echo '<table border="0" width="100%" align="center" cellpadding="0" cellspacing="0"><form name="categoryform" method="post" action="'.$querystr.'"><tbody><tr><td>';
paging($nume,$start,$limit,'start',$PHP_SELF.'?process=compare&action=option'.((isset($_GET['module']))?'&module='.$_GET['module']:'').'&limit='.$limit.'&startos='.$startos.'&limitos='.$limitos.'&ara='.$ara.'&araos='.$araos);
ksort($installed_modules);
$keyids = array();
$valids = array();
foreach($installed_modules as $class)
{
$vendor = vendor($class);
$module = new $class;
if ($where == '')
$sql = 'SELECT a.proid AS pid,ks.id AS kid, vs.id AS vid, ks.akname AS kname, vs.avname AS vname, ks.osid AS kosid, vs.osid AS vosid FROM `d_attr` a LEFT JOIN `d_attrkey` ks ON a.keyid = ks.id LEFT JOIN `d_attrval` vs ON a.valid = vs.id WHERE vs.vendor='.$vendor.' order by ks.osid,vs.osid,ks.id,vs.id  Limit '.$start.','.$limit;
else
$sql = 'SELECT a.proid AS pid,ks.id AS kid, vs.id AS vid, ks.akname AS kname, vs.avname AS vname, ks.osid AS kosid, vs.osid AS vosid FROM `d_attr` a LEFT JOIN `d_attrkey` ks ON a.keyid = ks.id LEFT JOIN `d_attrval` vs ON a.valid = vs.id WHERE '.$where.' AND vs.vendor='.$vendor.' order by ks.osid,vs.osid,ks.id,vs.id Limit '.$start.','.$limit;

$result = mysql_query($sql,$$link);
if (!$result) {
    die('Could not query:' . mysql_error());
}
if(mysql_num_rows($result)>0)
{
echo '<table  width="100%" border="0" cellpadding="0" cellspacing="0"><tr>'."\n";
echo '<td bgcolor="#9AC1E5"><table width="100%" border="0" cellpadding="3" cellspacing="1">'."\n";
echo '<tr><td bgcolor="#FFFFFF" height="10"><font face="Georgia, Times New Roman, Times, serif" color="#0099FF">'.$module->title.'</font></td>'."\n";
echo '</tr><tr><td bgcolor="#FFFFFF">'."\n";

echo '<table border=0 cellspacing=0 cellpadding=4><tr><td>Ürün Id</td><td>Anahtar</td><td>-&gt;Id#1</td><td>Deðer</td><td>-&gt;Id#2</td></tr>'."\n";
while ($categorys = tep_db_fetch_array($result)) {

echo '<tr>'."\n";
echo '<td><input type="text" value="'.$categorys['pid'].'" size="3" disabled="disabled"/></td>'."\n";
echo '<td><input type="text" value="'.$categorys['kname'].'" size="7" disabled="disabled"/></td>'."\n";
if(!in_array($categorys['kid'],$keyids)){
echo '<td><input name="kosid_'.$categorys['kid'].'" type="text" value="'.$categorys['kosid'].'" size="3" /></td>'."\n";
$keyids[] = $categorys['kid'];
}else
echo '<td><input type="text" value="'.$categorys['kosid'].'" size="3" disabled="disabled"/></td>'."\n";
echo '<td><input type="text" value="'.$categorys['vname'].'" size="7" disabled="disabled"/></td>'."\n";
if(!in_array($categorys['vid'],$valids)){
echo '<td><input name="vosid_'.$categorys['vid'].'" type="text" value="'.$categorys['vosid'].'" size="3" /></td>'."\n";
$valids[] = $categorys['vid'];
}else
echo '<td><input type="text" value="'.$categorys['vosid'].'" size="3" disabled="disabled"/></td>'."\n";
echo '</tr>'."\n";
}
echo '</table>'."\n";

echo '</td></tr></table></td></tr></table><br/>'."\n";
}
}
if ((count($valids)>0) && (count($keyids)>0)){
echo '<input name="valids" type="hidden" id="valids" value="'.join(',',$valids).'" size="15"/>'."\n";
echo '<input name="keyids" type="hidden" id="keyids" value="'.join(',',$keyids).'" size="15"/>'."\n";
echo '<input type="submit" name="kaydet" value="Kaydet">';
}
echo '</td></tr></tbody></form></table></td><td valign=top>'."\n";





$result = mysql_query('SELECT `languageid` FROM `d_vendors` GROUP BY `languageid`',$$link);
if (!$result) {
    die('Could not query:' . mysql_error());
}
if(mysql_num_rows($result)>0)
{
$language = array();
while ($langs = tep_db_fetch_array($result)) {
$language[] = $langs['languageid'];
}

search($querystr,'araos','searchos',$araos);
/*
$degiskenler = array(0 => 'pv`.`products_options_values_name', 1=>'p`.`products_id', 2=>'pk`.`products_options_name', 3=>'pk`.`products_options_id', 4=>'pv`.`products_options_values_name', 5=>'pv`.`products_options_values_id');
$tanimlar = array(0 => 's', 1=>'n', 2=>'s', 3=>'n', 4=>'s', 5=>'n');
$where = searchquery($araos,$degiskenler,$tanimlar);
*/
connector_attribute($start,$startos,$ara,$araos,$limit,$limitos,$language);
}
echo '</td></tr></table>'."\n";

}

function feature_modify($link = 'db_link'){
global $$link,$installed_modules;

echo '<table width="100%" border="0" cellspacing="0" cellpadding="10"><tr><td valign=top>'."\n";

if(isset($_POST["kaydet"]) && (!empty($_POST["valids"])) && (!empty($_POST["keyids"]))){
foreach(explode(',',$_POST["valids"]) as $valsid){
mysql_query("UPDATE `d_values` SET `osid`= '".tep_db_input($_POST["vosid_".$valsid])."' Where id='".tep_db_input($valsid)."'",$$link);
}
foreach(explode(',',$_POST["keyids"]) as $keysid){
mysql_query("UPDATE `d_keys` SET `osid` = '".tep_db_input($_POST["kosid_".$keysid])."' Where id='".tep_db_input($keysid)."'",$$link);
}
}

$start=$_GET['start'];
$limit = $_GET['limit'];
if((!($start > 0)) || (isset($_POST['ara']))) {
$start = 0;
}
$startos = $_GET['startos'];
$limitos = $_GET['limitos'];
if((!($startos > 0)) || (isset($_POST['araos']))) {
$startos = 0;
}

if (isset($_POST['ara']))
$ara = $_POST['ara'];
else if(!empty($_GET['ara']))
$ara = $_GET['ara'];
else $ara = '';

if (isset($_POST['araos']))
$araos = $_POST['araos'];
else if(!empty($_GET['araos']))
$araos = $_GET['araos'];
else $araos = '';

$querystr = $PHP_SELF.'?process=compare&action=feature'.((isset($_GET['module']))?'&module='.$_GET['module']:'').'&start='.$start.'&limit='.$limit.'&startos='.$startos.'&limitos='.$limitos.'&ara='.$ara.'&araos='.$araos;
search($querystr,'ara','searchos',$ara);
/*
$degiskenler = array(0 => 'vs`.`vname', 1=>'ks`.`catid', 2=>'ks`.`kname', 3=>'ks`.`osid', 4=>'vs`.`vname', 5=>'vs`.`osid');
$tanimlar = array(0 => 's', 1=>'n', 2=>'s', 3=>'n', 4=>'s', 5=>'n');
$where = searchquery($ara,$degiskenler,$tanimlar);
*/
$where = preparewhere($ara,'vs`.`vname AS s ; ks`.`catid AS n ; ks`.`kname AS s ; ks`.`osid AS n ; vs`.`vname AS s ; vs`.`osid AS n');

if ($where == '')
$sql = 'SELECT count(*) FROM `d_keys` ks, `d_values` vs Where ks.id = vs.keyid Group by ks.vendor Order by count(*) Desc Limit 1';
else
$sql = 'SELECT count(*) FROM `d_keys` ks, `d_values` vs Where ks.id = vs.keyid AND '.$where.' Group by ks.vendor Order by count(*) Desc Limit 1';

$result = mysql_query($sql,$$link);
if (!$result) {
    die('Could not query:' . mysql_error());
}
$nume = 0;
if(mysql_num_rows($result)>0)
{
$nume = mysql_result($result, 0, 0);
}

if($start > $nume) {
$start = $nume-$limit;
}
if(!($limit > 0)) {
$limit = 10;
}

echo '<table border="0" width="100%" align="center" cellpadding="0" cellspacing="0"><form name="categoryform" method="post" action="'.$querystr.'"><tbody><tr><td>';
paging($nume,$start,$limit,'start',$PHP_SELF.'?process=compare&action=feature'.((isset($_GET['module']))?'&module='.$_GET['module']:'').'&limit='.$limit.'&startos='.$startos.'&limitos='.$limitos.'&ara='.$ara.'&araos='.$araos);
ksort($installed_modules);
$keyids = array();
$valids = array();
foreach($installed_modules as $class)
{
$vendor = vendor($class);
$module = new $class;
if ($where == '')
$sql = 'SELECT ks.catid AS catid,ks.id AS kid, vs.id AS vid, ks.kname AS kname, vs.vname AS vname, ks.osid AS kosid, vs.osid AS vosid FROM `d_keys` ks LEFT JOIN `d_values` vs ON ks.id = vs.keyid WHERE vs.vendor='.$vendor.' order by ks.osid,vs.osid,ks.id,vs.id  Limit '.$start.','.$limit;
else
$sql = 'SELECT ks.catid AS catid,ks.id AS kid, vs.id AS vid, ks.kname AS kname, vs.vname AS vname, ks.osid AS kosid, vs.osid AS vosid FROM `d_keys` ks LEFT JOIN `d_values` vs ON ks.id = vs.keyid WHERE '.$where.' AND vs.vendor='.$vendor.' order by ks.osid,vs.osid,ks.id,vs.id Limit '.$start.','.$limit;

$result = mysql_query($sql,$$link);
if (!$result) {
    die('Could not query:' . mysql_error());
}
if(mysql_num_rows($result)>0)
{
echo '<table  width="100%" border="0" cellpadding="0" cellspacing="0"><tr>'."\n";
echo '<td bgcolor="#9AC1E5"><table width="100%" border="0" cellpadding="3" cellspacing="1">'."\n";
echo '<tr><td bgcolor="#FFFFFF" height="10"><font face="Georgia, Times New Roman, Times, serif" color="#0099FF">'.$module->title.'</font></td>'."\n";
echo '</tr><tr><td bgcolor="#FFFFFF">'."\n";

echo '<table border=0 cellspacing=0 cellpadding=4><tr><td>Kategori</td><td>Anahtar</td><td>-&gt;Id#1</td><td>Deðer</td><td>-&gt;Id#2</td></tr>'."\n";
while ($categorys = tep_db_fetch_array($result)) {
$valids[] = $categorys['vid'];
echo '<tr>'."\n";
echo '<td><input type="text" value="'.$categorys['catid'].'" size="3" disabled="disabled"/></td>'."\n";
echo '<td><input type="text" value="'.$categorys['kname'].'" size="7" disabled="disabled"/></td>'."\n";
if(!in_array($categorys['kid'],$keyids)){
echo '<td><input name="kosid_'.$categorys['kid'].'" type="text" value="'.$categorys['kosid'].'" size="3" /></td>'."\n";
$keyids[] = $categorys['kid'];
}else
echo '<td><input type="text" value="'.$categorys['kosid'].'" size="3" disabled="disabled"/></td>'."\n";
echo '<td><input type="text" value="'.$categorys['vname'].'" size="7" disabled="disabled"/></td>'."\n";
echo '<td><input name="vosid_'.$categorys['vid'].'" type="text" value="'.$categorys['vosid'].'" size="3" /></td>'."\n";
echo '</tr>'."\n";
}
echo '</table>'."\n";

echo '</td></tr></table></td></tr></table><br/>'."\n";
}
}
if ((count($valids)>0) && (count($keyids)>0)){
echo '<input name="valids" type="hidden" id="valids" value="'.join(',',$valids).'" size="15"/>'."\n";
echo '<input name="keyids" type="hidden" id="keyids" value="'.join(',',$keyids).'" size="15"/>'."\n";
echo '<input type="submit" name="kaydet" value="Kaydet">';
}
echo '</td></tr></tbody></form></table></td><td valign=top>'."\n";





$result = mysql_query('SELECT `languageid` FROM `d_vendors` GROUP BY `languageid`',$$link);
if (!$result) {
    die('Could not query:' . mysql_error());
}
if(mysql_num_rows($result)>0)
{
$language = array();
while ($langs = tep_db_fetch_array($result)) {
$language[] = $langs['languageid'];
}

search($querystr,'araos','searchos',$araos);
/*
$degiskenler = array(0 => 'pv`.`products_options_values_name', 1=>'pk`.`categories_options_id', 2=>'pk`.`products_options_name', 3=>'pk`.`products_options_id', 4=>'pv`.`products_options_values_name', 5=>'pv`.`products_options_values_id');
$tanimlar = array(0 => 's', 1=>'n', 2=>'s', 3=>'n', 4=>'s', 5=>'n');
$where = searchquery($araos,$degiskenler,$tanimlar);
*/
connector_property($start,$startos,$ara,$araos,$limit,$limitos,$language);
}
echo '</td></tr></table>'."\n";

}

function spec_dump($vendor,$link = 'db_link'){
global $$link,$refspec;
$refspec = array();
$query = "SELECT vendor, pcode, rate, discount, creator, osid, id FROM d_special Where vendor=0 or vendor=".$vendor;
$result = mysql_query($query,$$link) or die(mysql_error());
if(mysql_num_rows($result)>0){
while ($row = mysql_fetch_assoc($result)) {
$refspec[$row['pcode']] = array($row['rate'],$row['discount'],$row['osid'],$row['id']);
}
mysql_free_result($result);
}
}

function marj_dump($vendor,$link = 'db_link'){
global $$link,$refmarj;
$refmarj = array();
$query = "SELECT vendor, category, product, attribute, ei, eif, ea, eaf, f, fm FROM d_task Where vendor=0 or vendor=".$vendor;
$result = mysql_query($query,$$link) or die(mysql_error());
if(mysql_num_rows($result)>0){
while ($row = mysql_fetch_assoc($result)) {
$reftype =0;
if (($row['eif']!=null) AND ($row['eaf']!=null)) $reftype =2;
else if (($row['eif']!=null) AND ($row['eaf']==null)) $reftype =1;
else if (($row['eif']==null) AND ($row['eaf']==null)) $reftype =0;
if($row['attribute']!=0)
$refmarj[1][$row['attribute']][] = array($reftype,$row['ei'],$row['eif'],$row['ea'],$row['eaf'],$row['f'],$row['fm']);
else if($row['product']!=0)
$refmarj[2][$row['product']][] = array($reftype,$row['ei'],$row['eif'],$row['ea'],$row['eaf'],$row['f'],$row['fm']);
else if($row['category']!=0)
$refmarj[3][$row['category']][] = array($reftype,$row['ei'],$row['eif'],$row['ea'],$row['eaf'],$row['f'],$row['fm']);
else if($row['vendor']!=0)
$refmarj[4][$row['vendor']][] = array($reftype,$row['ei'],$row['eif'],$row['ea'],$row['eaf'],$row['f'],$row['fm']);
else
$refmarj[0][] = array($reftype,$row['ei'],$row['eif'],$row['ea'],$row['eaf'],$row['f'],$row['fm']);
}
mysql_free_result($result);
}
}

function category_dump($vendor,$link = 'db_link'){
global $$link,$refcategories;
$treecat = array();
$refcategories = array();
$query = "SELECT id, code, parentcode, hidden FROM d_categories where vendor=".$vendor." order by parentcode";
$result = mysql_query($query,$$link) or die(mysql_error());
if(mysql_num_rows($result)>0){
while ($row = mysql_fetch_assoc($result)) {
$treecat[$row['code']] = array($row['parentcode'],$row['id'],$row['hidden']);
}
$i = 0;
mysql_data_seek($result,0);
while ($row = mysql_fetch_assoc($result)) {
$treecat[$row['code']] = array($row['parentcode'],$row['id'],$row['hidden']);
$parentcode = $row['parentcode'];
$parent = array();
if($row['code'] == $parentcode)
{
$parent[] = array($row['code'],$treecat[$row['code']][1],$treecat[$row['code']][2]);
$treecat[$row['code']][3] = $treecat[$row['code']][2];
$treecat[$row['code']][4] = $treecat[$row['code']][1];
$treecat[$row['code']][5] = 0;
}
else
{
$parent[] = array($row['code'],$treecat[$row['code']][1],$treecat[$row['code']][2]);
$j=0;
while(true){
if (empty($parentcode)) break;
if (!isset($treecat[$parentcode][0])) break;
$break=false; for ($l=0; $l < count($parent); $l++) {if($parent[$l][0]==$parentcode){$break=true;break;}}
if($break) break;
if ($j==20) break;
$parent[] = array($parentcode,$treecat[$parentcode][1],$treecat[$parentcode][2]);
$parentcode = $treecat[$parentcode][0];
$j++;
}
$hidden = '0';
$catpath = '';
$parentid = 0;
if(count($parent)>1) $parentid = $parent[1][1];
for ($k=0; $k < count($parent); $k++)  {
if($parent[$k][2] == '1') $hidden = '1';
if($catpath == '') $catpath = $parent[$k][1];
else $catpath = $parent[$k][1].'/'.$catpath;
}
$treecat[$row['code']][3] = $hidden;
$treecat[$row['code']][4] = $catpath;
$treecat[$row['code']][5] = $parentid;
}
$i++;
}
foreach (array_keys($treecat) as $key ) {
$refcategories[$treecat[$key][1]] = array($treecat[$key][3],$treecat[$key][4],$treecat[$key][5]);
}
}
unset($treecat);
mysql_free_result($result);
}

function image_save_path($path){
global $image_directory;
$path = $image_directory.$path;
$mode = 0777;
$dirs = array();
$path = rtrim(preg_replace(array("/\\\\/", "/\/{2,}/"), "/", $path), "/");
$e = explode("/", ltrim($path, "/"));
if(substr($path, 0, 1) == "/") {
$e[0] = "/".$e[0];
}
$c = count($e);
$s = 0;
$dirs = $e;
for($i = ($c-1); $i >= 0; $i--) {
$cp = join('/',$dirs);
if(is_dir($cp)){$s=$i;break;}
array_pop($dirs);
}
if($s != ($c-1))
{
for($i = ($s+1); $i < $c; $i++) {
if(!is_dir($cp) && !@mkdir($cp, $mode)) {
return false;
}
$cp .= "/".$e[$i];
}
return @mkdir($path, $mode);
}
else
return true;
}

function get_extension_type($ctype){
$extension = '';
switch ($ctype) {
case "image/gif":
    $extension = 'gif';
    break;
case "image/jpeg":
    $extension = 'jpg';
    break;
case "image/jpg":
    $extension = 'jpg';
    break;
case "image/jpe":
    $extension = 'jpg';
    break;
case "image/tif":
    $extension = 'tif';
    break;
case "image/tiff":
    $extension = 'tif';
    break;
case "image/png":
    $extension = 'png';
    break;
default:
    $extension = null;
}
return $extension;
}

function image_save($vendor,$vendorcode,$extension,$findstr,$start,$limit,$link = 'db_link'){
global $$link,$refcategories,$image_directory;
category_dump($vendor);
$row_number=0;
$query = "SELECT p.id as id, p.catid as catid, i.id as imgid, i.number as number, i.image as image, i.imagedir as imagedir, i.imagex as imagex, i.imagey as imagey, i.thumb as thumb, i.thumbdir as thumbdir, i.thumbx as thumbx, i.thumby as thumby FROM `d_products` p, `d_images` i WHERE (p.`id` = i.`proid`) AND p.`hidden` =0 AND i.`vendor`=".$vendor.(($limit!=0)?(' Limit '.$start.', '.$limit):'');
$result = mysql_query($query,$$link) or die(mysql_error());
while ($row = mysql_fetch_assoc($result)) {
$row_number++;
if($refcategories[$row['catid']][0] == "0"){
$imagedir =null;
$thumbdir =null;
image_save_path($vendorcode.'/'.$refcategories[$row['catid']][1]);
if((!empty($row['image'])) && empty($row['imagedir'])){
$extens = image_indir((($extension)?$extension.$row['image']:$row['image']),$image_directory.$vendorcode.'/'.$refcategories[$row['catid']][1].'/'.$row['id'].(($row['number']==0)?'':'_'.$row['number']),$findstr);
if($extens !=null)
{
$imagedir = $vendorcode.'/'.$refcategories[$row['catid']][1].'/'.$row['id'].(($row['number']==0)?'':'_'.$row['number']).'.'.$extens;
mysql_query('Update d_images Set imagedir=\''.$vendorcode.'/'.$refcategories[$row['catid']][1].'/'.$row['id'].(($row['number']==0)?'':'_'.$row['number']).'.'.$extens.'\' Where id='.$row['imgid'],$$link) or die(mysql_error());
}
else mysql_query('Update d_images Set imagedir = NULL Where id='.$row['imgid'],$$link) or die(mysql_error());
}
if((!empty($row['thumb'])) && empty($row['thumbdir'])){
$extens = image_indir((($extension)?$extension.$row['thumb']:$row['thumb']),$image_directory.$vendorcode.'/'.$refcategories[$row['catid']][1].'/'.$row['id'].'_t'.(($row['number']==0)?'':'_'.$row['number']),$findstr);
if($extens !=null)
{
$thumbdir = $vendorcode.'/'.$refcategories[$row['catid']][1].'/'.$row['id'].'_t'.(($row['number']==0)?'':'_'.$row['number']).'.'.$extens;
mysql_query('Update d_images Set thumbdir=\''.$vendorcode.'/'.$refcategories[$row['catid']][1].'/'.$row['id'].'_t'.(($row['number']==0)?'':'_'.$row['number']).'.'.$extens.'\' Where id='.$row['imgid'],$$link) or die(mysql_error());
}
else mysql_query('Update d_images Set thumbdir = NULL Where id='.$row['imgid'],$$link) or die(mysql_error());
}
if($imagedir!=null)connector_image_download($row['id'],$row['catid'],$row['number'],$row['imgid'],$row['image'],$imagedir,$row['imagex'],$row['imagey'],$row['thumb'],$thumbdir,$row['thumbx'],$row['thumby'],$vendor);
}
}
return ($limit!=0&&$row_number>0)?1:null;
}
function getHttpCode($url)
    {
      $ch = curl_init();
      if (!$ch) {
          die("Couldn't initialize a cURL handle");
      }
      @curl_setopt($ch, CURLOPT_URL,$url);
      @curl_setopt($ch, CURLOPT_HEADER, true);
      @curl_setopt($ch, CURLOPT_NOBODY, false);
      @curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      $response = @curl_exec($ch);
      if(strpos($response,"\r\n\r\n")!==false)
      {
      list($response_headers,$response_body) = explode("\r\n\r\n",$response,2);
      $data = array(null,null);
      if (empty($response)) {
      curl_close($ch);
      } else {
      $info = curl_getinfo($ch);
      curl_close($ch);
      if($info['http_code']===302 || $info['http_code']===302)
      {
        if(preg_match("/Location:\s([^\r\n]+)/",$response_headers,$matches))
        {
        $data = getHttpCode(rel2abs($matches[1],$url));
        }
      }else
      {
        $data = array((isset($info['content_type']))?$info['content_type']:null,$response_body);
      }
      }
      }
      else
      {
        $data = array(null,$response);
      }
      return $data;
}
function rel2abs($relative, $baseparts)
    {
        if (parse_url($relative, PHP_URL_SCHEME) != '') return $relative;
        if ($relative[0]=='#' || $relative[0]=='?') return $baseparts.$relative;
        $cparts=(parse_url($baseparts));
        $dparts = preg_replace('#/[^/]*$#', '', $cparts['path']);
        if ($relative[0] == '/') $dparts = '';
        $absolute = $cparts['host'].$dparts.'/'.$relative;
        $rparts = array('#(/\.?/)#', '#/(?!\.\.)[^/]+/\.\./#');
        for($nparts=1; $nparts>0; $absolute=preg_replace($rparts, '/', $absolute, -1, $nparts)) {}
        return $cparts['scheme'].'://'.$absolute;
        }
function image_indir($adres,$ad,$findstr)
{
    /*if (!extension_loaded('curl')) {
        die("Extension yuklu  degil!");
    }
    $ch = curl_init($adres);
    if (!$ch) {
        die("Curl oturumu Baþlatýlamadý..");
    }
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $data = curl_exec($ch);
    $content_type = curl_getinfo( $ch, CURLINFO_CONTENT_TYPE );
    curl_close($ch);
    */
    $response = getHttpCode($adres);
    $content_type = $response[0];
    $data = $response[1];
    if(substr($adres,0,3)=="ftp") $content_type = "image/jpeg";
    if(strpos($adres,'e-gama.com')>0) $content_type = "image/png"; //gama özel
    if($findstr!==false){if(bin2hex($data)==$findstr)return null;}
    if(get_extension_type($content_type)==null) return null;
    $islem = fopen( $ad.".".get_extension_type($content_type), "wb");
    fwrite($islem, $data);
    fclose($islem);
    if (!($islem)) {
        die($adres." => Dosya yuklenemedi");
    }
    return get_extension_type($content_type);
}
function download_file($remote_file, $newfilename = '', $save_to='', $timeout=3600, $remote_referer='')
{
global $FOLLOWLOCATION,$success,$endofprocess,$endofprocessparams;
$filelock = $save_to.$newfilename.".lock";
if (!file_exists($filelock))
{
$endofprocess = 1;
$endofprocessparams = $filelock;
$fh = fopen($filelock, 'w') or die("Can't open xml for writing");
fclose($fh) or die("Can't close xml");

    $success = 0;
    if(empty($newfilename)) $newfilename = array_shift(explode('?', basename($remote_file))); // yeni isim verilmezse karþý sunucudaki dosya ismini al
    $fh = fopen($save_to.$newfilename.'.buff', 'w') or die("Can't open xml for writing");
    if (-1 == fwrite($fh, '0')) { die("Can't write to xml"); }
    fclose($fh) or die("Can't close xml");
    $ch = curl_init();
    $opts = array(
      CURLOPT_URL => $remote_file,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_TIMEOUT => $timeout,
      CURLOPT_CONNECTTIMEOUT => $timeout,
      CURLOPT_HEADER => 0);
    /*0007 eklenti end*/
    if (file_exists($save_to.$newfilename.".cook"))
    {
      $opts[CURLOPT_COOKIEFILE] = $save_to.$newfilename.".cook";
      $opts[CURLOPT_COOKIEJAR] = $save_to.$newfilename.".cook";
      $opts[CURLOPT_REFERER] = $remote_referer;
    }
    /*0007 eklenti end*/
      if(isset($FOLLOWLOCATION))
      if($FOLLOWLOCATION==true) $opts[CURLOPT_FOLLOWLOCATION] = true;

      curl_setopt_array($ch, $opts);
      $result = curl_exec($ch);
      $out = fopen($save_to.$newfilename, 'w');
      if ($out == FALSE){
        echo "$newfilename - Dosya yazýlamadý<br>";
        return -1;
      }
      if (-1 == fwrite($out, $result)) { die("Can't write to xml"); }
      fclose($out);
    if(!curl_errno($ch))
        {
          $info = curl_getinfo($ch, CURLINFO_HTTP_CODE);
          if($info>199&&$info<207)
          {
          $success = $info;
          }
    }
    /*
    if($result === false)
    {
      user_error(curl_error($ch));
      echo "<br>cURL hatasý : ".curl_error ($ch);
      return false;
    }

    $response = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    */
    curl_close($ch);
    /*
    if($response != 200) {
    	user_error("HTTP geri gelen: $response");
    	return $response;
    	}
    else return true;
    */
if (file_exists($save_to.$newfilename.".cook")){unlink($save_to.$newfilename.".cook");}
$endofprocess = 0;
unlink($filelock);

if($success == 0)
    {
        echo "<center><FAILED/>$newfilename Ýndirme Baþarýsýz Lütfen Tekrar Deneyin! :". filesize($save_to.$newfilename) ."</center>";
        return false;
    }
    else
    {
        unlink($save_to.$newfilename.'.buff');
        echo "<center><SUCCESS/>$newfilename Ýndirme Baþarýlý :". filesize($save_to.$newfilename) ."</center>";
        return true;
    }

}
else
{
    echo "<center><CROSS/>Þu an yürütülen baþka bir iþlem var. Kilit dosyasý: $filelock</center>";
    return false;
}
/*
-1 : Dosya yazýlamadý
0 : cURL hatasý oluþtu
1 : Ýþlem baþarýlý- dosya baþarý ile downlaod edilip yazýldý
diðer : web sayfasý hatasý. Örn: 404 dosya sunucuda bulunamadý
*/
}
/*0003 eklenti start*/
   class remote_file {
	var $X2354 = false; // $debug
	var $X6745 = false; // $file
	var $X9865 = 0;  //  $position
	var $X5643 = 0;  //  $size
	var $X9833 = array(); // $headers
	var $X4324 = false; // $allow_seek
    var $X6532 = 300; //  $timeout
	// Get header info from curl request
	function getHeader($needle) {
		# Find requested header
		foreach ($this->X9833 as $header) {
			if (strstr($header, $needle)) {
				return trim(substr($header, strpos($header, $needle) + strlen($needle)));
			}
		}
	}

	// Remote file init
	function remote_file($X6745) {
	  global $success;
	    $success = 0;
        $this->X6745 = '';
        $ch = curl_init($X6745);
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_NOBODY, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, $this->X6532);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)');
        ob_start();
        curl_exec ($ch);
        $this->X9833 = explode("\n", ob_get_contents());
        ob_end_clean();
        if(!curl_errno($ch))
        {
          $info = curl_getinfo($ch, CURLINFO_HTTP_CODE);
          if($info>199&&$info<207)
          {
          $success = $info;
          }
        }else
        {
          echo 'Curl error: ' . curl_error($ch);
        }
        curl_close($ch);

		// Get file size & whether server supports ranges
		$this->X5643 = $this->getHeader('Content-Length:');
		$this->X4324 = (int)($this->getHeader('Accept-Ranges:') == 'bytes');
		// Some urls are pointers to the real file location
        /*
        // File size
        $regex = '/Content-Length:[\s]*([0-9]+)/';
        preg_match($regex, $head, $matches);
        $results['filesize'] = isset($matches[1]) ? (int) $matches[1] : NULL;
        // File mime
        $regex = '/Accept-Ranges:[\s]*bytes/i';
        preg_match($regex, $head, $matches);
        $results['filerange'] = isset($matches[0]) ? $matches[0] : NULL;
        */
       // var_dump($this->getHeader('Location:'));
		if (!$this->X6745 = $this->getHeader('Location:')) $this->X6745 = $X6745;
	}

	// Set pointer.  If negative set from EOF
	function fseek($X9865) {
		$this->X9865 = $X9865;
		if ($X9865 < 0) $this->X9865 = $this->X5643 + $X9865;
	}

	// Read file from curr pointer to bytes, update pointer, return data
	function fread($bytes) {
	  global $success;
		if (!$this->X5643) return false;
		$from = $this->X9865;
        if ($this->X9865 >= $this->X5643) return false;
		$to = $this->X9865 + $bytes - 1;
        if($to>=$this->X5643) $to = $this->X5643;
        $success = 0;
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $this->X6745);
		if ($this->X2354) curl_setopt($curl, CURLOPT_VERBOSE, 1);
		curl_setopt($curl, CURLOPT_RANGE, "$from-$to");
        //echo $this->X5643."=$from-$to<br>";
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, $this->X6532);
        curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)');
		$content = curl_exec($curl);
        if(!curl_errno($curl))
        {
          $info = curl_getinfo($curl, CURLINFO_HTTP_CODE);
          if($info>199&&$info<207)
          {
          $success = $info;
          }
        }
		curl_close($curl);
		$this->X9865 += $bytes;
		return $content;
	}
}

function download_remote_file(&$ch,$filename,$perbyte = 20480,$downbytetimeout = 1800,$startbyte = 0)
{
global $success,$endofprocess,$endofprocessparams;
$filebuff = $filename.".buff";
$filelock = $filename.".lock";
if (!file_exists($filelock))
{
$endofprocess = 1;
$endofprocessparams = $filelock;
$fh = fopen($filelock, 'w') or die("Can't open xml for writing");
fclose($fh) or die("Can't close xml");
if($success != 0)
{
    if (file_exists($filebuff)) {
    $fh = fopen($filebuff, 'r');
    $startbyte = trim(fread($fh, filesize($filebuff)));
    fclose($fh);
    }
    else
    {
    $fh = fopen($filename, 'w') or die("Can't open xml for writing");
    fclose($fh) or die("Can't close xml");
    $startbyte = 0;
    }

    if (!file_exists($filename)) {
    $fh = fopen($filename, 'w') or die("Can't open xml for writing");
    fclose($fh) or die("Can't close xml");
    $startbyte = 0;
    }else
    {
      if ((time()-filemtime($filename))>$downbytetimeout)
      {
        $fh = fopen($filename, 'w') or die("Can't open xml for writing");
        fclose($fh) or die("Can't close xml");
        $startbyte = 0;
      }
    }

    $fh = fopen($filebuff, 'w') or die("Can't open xml for writing");
    if (-1 == fwrite($fh, $startbyte)) { die("Can't write to xml"); }
    fclose($fh) or die("Can't close xml");

    $ch->fseek($startbyte);
    while($data = $ch->fread($perbyte))
    {
        $startbyte +=  strlen($data);
            if($success != 0)
            {
                $fh = fopen($filename, 'a') or die("can't open xml for append");
                fwrite($fh, $data);
                fclose($fh);

                $fh = fopen($filebuff, 'w') or die("Can't open xml for writing");
                if (-1 == fwrite($fh, $startbyte)) { die("Can't write to xml"); }
                fclose($fh) or die("Can't close xml"); //if($startbyte>4000000)exit;
            }
            else
            {
                break;
            }
    }
    if($success != 0) unlink($filebuff);
}
$endofprocess = 0;
unlink($filelock);

if($success == 0)
{
    echo "<center><FAILED/>".basename($filename)." Ýndirme Baþarýsýz Lütfen Tekrar Deneyin! ".$startbyte.'='.$ch->X5643."</center>";
}
else
{
    echo "<center><SUCCESS/>".basename($filename)." Ýndirme Baþarýlý :". $ch->X5643."</center>";
}

}else
{
    echo "<center><CROSS/>".basename($filename)." Þu an yürütülen baþka bir iþlem var. Kilit dosyasý: ".basename($filelock)."</center>";
}
}

function shutdown($link = 'db_link')
{
  global $$link,$endofprocess,$endofprocessparams;
  if($endofprocess==1)
  unlink($endofprocessparams);
  if($endofprocess==2)
  mysql_query("Update `d_config` set `value`='false' Where `name`='transfer'",$$link);
}
/*0003 eklenti end*/


/*0005 eklenti start*/
function preparewhere($findstr,$get_sql){
$ArrayTable  = array();
$degiskenler = array();
$tanimlar    = array();

if(strpos( $get_sql, ";" ) )
{
$ArrayTable = explode( ";", $get_sql );
}

foreach ($ArrayTable as $TableField)  {
list($FieldName,$FieldType) = sscanf(trim($TableField), "%[a-zA-Z0-9,.`_] AS %1s");
$degiskenler[] = $FieldName;
$tanimlar[] = $FieldType;
}

return searchquery($findstr,$degiskenler,$tanimlar);
}
/*0005 eklenti end*/
/*0006 eklenti start*/
function download_socket_file($domain,$port,$path,$file){
global $xml_directory;
$fp = fsockopen($domain, $port, $errno, $errstr, 6000);
if (!$fp) {
    echo "$errstr ($errno)<br />\n";
} else {
    $out = "GET ".$path." HTTP/1.1\r\n";
    $out .= "Host: ".$domain."\r\n";
    $out .= "Connection: Close\r\n\r\n";
    fwrite($fp, $out);
    $send ='';
    do
    {
        $send .= fread($fp, 1024);

    } while ( strpos ( $send, "\r\n\r\n" ) === false );
    $startlength = strlen($send);
    $count  = strpos ( $send, "\r\n\r\n" );
    $header = substr($send,0,$count);
    $send = substr($send,$count+4);
    preg_match("/Content-Length:\s*(\d*)/",$header,$matches);
    $length=$matches[1]-$startlength;
    $lensplit =  (int)($length / 8192);
    $lensplitfark = $length-($lensplit*8192);
     $Handle = fopen($xml_directory.$file, 'w');
     fwrite($Handle, $send);
     fclose($Handle);
     for ($i=1; $i<=$lensplit; $i++)  {
     $Handle = fopen($xml_directory.$file, 'a');
     fwrite($Handle, fread($fp, 8192));
     fclose($Handle);
     }
     $Handle = fopen($xml_directory.$file, 'a');
     fwrite($Handle, fread($fp, $lensplitfark));
     fclose($Handle);
    fclose($fp);
}
}
/*0006 eklenti end*/
function pertrans($start,$limit,$selectmodule,$installed_modules,$link = 'db_link'){
global $$link;
if($selectmodule=='')
{
$vendorids = array();
foreach($installed_modules as $class){
$vendorids[]=vendor($class);
}
$result = mysql_query('SELECT count(*) FROM `d_products` WHERE hidden=0 AND vendor in ('.join(',',$vendorids).')',$$link);
if (!$result) {
    die('Could not query:' . mysql_error());
}
$total = 0;
if(mysql_num_rows($result)>0)
{
$total = mysql_result($result, 0, 0);
}
}
else
{
$result = mysql_query('SELECT count(*) FROM `d_products` WHERE hidden=0 AND vendor='.vendor($selectmodule),$$link);
if (!$result) {
    die('Could not query:' . mysql_error());
}
$total = 0;
if(mysql_num_rows($result)>0)
{
$total = mysql_result($result, 0, 0);
}
}
$start=(($limit+$start)>$total)?0:$limit+$start;
?>
<table  border="0" align="center" cellpadding="5" cellspacing="0" style="font-size: 12px;font-family: Verdana, Arial, Helvetica, sans-serif;">
<form name="transferform" method="get" action="core.php">
<tbody>
<tr>
<td width="80" nowrap bgcolor="#EFEFEF">Toplam</td>
<td width="80" nowrap bgcolor="#EFEFEF">Kayýt Sayýsý</td>
<td width="100" nowrap bgcolor="#EFEFEF">Baþlangýç Kaydý</td>
<td width="180" bgcolor="#BEEBFF"><strong>Tedarikci</strong></td>
</tr>
<tr>
<td valign="top" style=" border-bottom: 1px solid #D7E5F2; border-left: 1px solid #D7E5F2;border-top: 1px solid #D7E5F2;"><LABEL><strong><?php echo $total;?></strong></LABEL></td>
<td valign="top" style=" border-bottom: 1px solid #D7E5F2; border-left: 1px solid #D7E5F2;border-top: 1px solid #D7E5F2;"><input name="limit" type="text" value="<?php echo $limit;?>" size="5" style="font-size: 12px;" /></td>
<td valign="top" style=" border-bottom: 1px solid #D7E5F2; border-left: 1px solid #D7E5F2;border-top: 1px solid #D7E5F2;"><input name="start" type="text" value="<?php echo $start;?>" size="5" style="font-size: 12px;" /></td>
<td nowrap bgcolor="#F9FBFD" style="border: 1px solid #D7E5F2;border-collapse: collapse;">
<label><input name="module" type="radio" value="" <?php if($selectmodule=='') echo 'checked="checked"'; ?>/>hepsi</label>
<?php
foreach($installed_modules as $class){
echo '<br /><label><input type="radio" name="module" value="'.$class.'" '.(($selectmodule==$class)?'checked="checked"':'').' />'.$class.'</label>';
}
?>
</td>
</tr>
<tr>
<td colspan="4" align="right" valign="top" >
<input type="hidden" name="process" value="transfer"/>
<input type="submit" value="Entegrasyonu Baþlat" /></td>
</tr>
</tbody>
</form>
</table>
<?php
}
?>