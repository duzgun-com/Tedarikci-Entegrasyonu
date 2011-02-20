<?php
/*
  $Id$ Yavuz Yasin Düzgün

  Tedarikçi Entegrasyonu, Açýk Kaynak Entegrasyon Çözümüdür
  http://www.duzgun.com

  Copyright (c) 2008 Duzgun.com

  Released under the GNU General Public License
*/

define('CONNECTOR', 'Oscommerce');
function connector_properties_length($link = 'db_link')
{
global $$link;
$result=mysql_query("SHOW COLUMNS FROM `products_prop_options` LIKE 'products_options_name'",$$link);
$propertyopt = 1000;
if (!$result) {
    die('Could not query:'. mysql_error());
}
if( mysql_num_rows( $result ) > 0 )
{
$row=mysql_fetch_array($result);
if(preg_match("/varchar\((.*?)\)/", $row['Type'], $matches))
$propertyopt = $matches[1];
}
$result=mysql_query("SHOW COLUMNS FROM `products_prop_options_values` LIKE 'products_options_values_name'",$$link);
$propertyoptval = 1000;
if (!$result) {
    die('Could not query:'. mysql_error());
}
if( mysql_num_rows( $result ) > 0 )
{
$row=mysql_fetch_array($result);
if(preg_match("/varchar\((.*?)\)/", $row['Type'], $matches))
$propertyoptval = $matches[1];
}
return array($propertyopt,$propertyoptval);
}
function connector_qtstock_setopt($qtstock_osid, $link = 'db_link')
{
global $$link;
mysql_query("DELETE FROM `products_stock` Where products_stock_id='".$qtstock_osid."'",$$link);
}
function connector_image_setopt($image_osid)
{

}
function connector_option_setopt($attr_osid,$products_osid=0,$attrkey_osid=0,$attrval_osid=0, $link = 'db_link')
{
global $$link;
if($attr_osid==0)
mysql_query("DELETE FROM `products_attributes` Where products_id=".$products_osid." and options_id=".$attrkey_osid." and options_values_id=".$attrval_osid,$$link);
else
mysql_query("DELETE FROM `products_attributes` Where products_attributes_id=".$attr_osid,$$link);
}
function connector_feature_setopt($tokeyvalues_osid,$products_osid=0,$keys_osid=0,$values_osid=0, $link = 'db_link')
{
global $$link;
if($tokeyvalues_osid==0)
mysql_query("DELETE FROM `products_properties` Where products_id=".$products_osid." and options_id=".$keys_osid." and options_values_id=".$values_osid,$$link);
else
mysql_query("DELETE FROM `products_properties` Where products_attributes_id=".$tokeyvalues_osid,$$link);
}
function connector_special_setopt($osid, $link = 'db_link')
{
global $$link;
mysql_query("DELETE FROM `specials` Where products_id=".$osid,$$link);
}
function connector_image_download($pid,$catid,$number,$imgid,$image,$imagedir,$imagex,$imagey,$thumb,$thumbdir,$thumbx,$thumby,$vendor)
{

}
function transfer_features($pid,$poid,$vendor,$languageid,$catid,$mfiyati,$pfiyati,$parabirimi,$multicurry,$qtpro, $link = 'db_link')
{
global $$link,$refcategories,$refmarj,$refmarjcat,$arr_currency_value;
$ID_To_osID = array();
$result = mysql_query('SELECT id,proid,keyid,valid,price1,prcpre,prefix,stock FROM `d_attr` WHERE proid='.$pid.' AND vendor='.$vendor,$$link);
if (!$result) {
    die('Could not query:'. mysql_error());
}
if(mysql_num_rows($result)>0)
{
while ($keyval = tep_db_fetch_array($result)) {
$result_keys = mysql_query('SELECT akname,osid,id'.(($qtpro)?',qtpro':'').' FROM `d_attrkey` WHERE id ='.$keyval['keyid'].' LIMIT 1',$$link);
$pro_keys_osid = mysql_result($result_keys, 0, 1);
if ($pro_keys_osid == 0)
{
$result_prop_options = mysql_query('SELECT products_options_id'.(($qtpro)?',products_options_track_stock':'').' FROM `products_options` WHERE language_id='.$languageid.' AND products_options_name=\''.tep_db_input((strlen(mysql_result($result_keys, 0, 0))>32) ? substr(mysql_result($result_keys, 0, 0),0,32): mysql_result($result_keys, 0, 0)).'\' LIMIT 1',$$link);
if (!$result_prop_options) {
    die('Could not query:'. mysql_error());
}
if(mysql_num_rows($result_prop_options)>0)
{
$pro_keys_osid = mysql_result($result_prop_options, 0, 0);
/*0001 eklenti start*/
if(mysql_result($result_keys, 0, 1)==0) mysql_query("UPDATE `d_attrkey` SET `osid` = '".$pro_keys_osid."' Where id=".mysql_result($result_keys, 0, 2));
/*0001 eklenti end*/
}
else
{
$result_prop_options_maxid = mysql_query('SELECT max(`products_options_id`) From `products_options`');
$pro_keys_osid = mysql_result($result_prop_options_maxid, 0, 0);
if ($pro_keys_osid == null) $pro_keys_osid =0;
$pro_keys_osid +=1;
mysql_query("INSERT INTO `products_options` ( `products_options_id` , `language_id` , `products_options_name` ".(($qtpro)?",`products_options_track_stock`":'')." ) Values ('".$pro_keys_osid."', '".$languageid."', '".tep_db_input((strlen(mysql_result($result_keys, 0, 0))>32) ? substr(mysql_result($result_keys, 0, 0),0,32): mysql_result($result_keys, 0, 0))."'".(($qtpro)?",'".mysql_result($result_keys, 0, 3)."'":'').")",$$link);
mysql_query("UPDATE `d_attrkey` SET `osid` = '".$pro_keys_osid."' Where id=".mysql_result($result_keys, 0, 2));
}
}
$result_values = mysql_query('SELECT avname,keyid,osid,id FROM `d_attrval` WHERE id ='.$keyval['valid'].' LIMIT 1',$$link);
$pro_values_osid = mysql_result($result_values, 0, 2);
if ($pro_values_osid == 0)
{
$result_prop_options_values = mysql_query('SELECT products_options_values_id FROM `products_options_values` WHERE language_id='.$languageid.' AND products_options_values_name=\''.tep_db_input((strlen(mysql_result($result_values, 0, 0))>64) ? substr(mysql_result($result_values, 0, 0),0,64): mysql_result($result_values, 0, 0)).'\' LIMIT 1',$$link);
if (!$result_prop_options_values) {
    die('Could not query:'. mysql_error());
}
if(mysql_num_rows($result_prop_options_values)>0)
{
$pro_values_osid = mysql_result($result_prop_options_values, 0, 0);
/*0001 eklenti start*/
if(mysql_result($result_values, 0, 2)==0) mysql_query("UPDATE `d_attrval` SET `osid` = '".$pro_values_osid."' Where id=".mysql_result($result_values, 0, 3));
/*0001 eklenti end*/
}
else
{
$result_prop_options_values_maxid = mysql_query('SELECT max(`products_options_values_id`) From `products_options_values`');
$pro_values_osid = mysql_result($result_prop_options_values_maxid, 0, 0);
if ($pro_values_osid == null) $pro_values_osid =0;
$pro_values_osid +=1;
mysql_query("INSERT INTO `products_options_values` ( `products_options_values_id` , `language_id` , `products_options_values_name` ) Values ('".$pro_values_osid."', '".$languageid."', '".tep_db_input((strlen(mysql_result($result_values, 0, 0))>64) ? substr(mysql_result($result_values, 0, 0),0,64): mysql_result($result_values, 0, 0))."')",$$link);
mysql_query("UPDATE `d_attrval` SET `osid` = '".$pro_values_osid."' Where id=".mysql_result($result_values, 0, 3));
}
}
$result_prop_options_values_to_option = mysql_query('SELECT products_options_values_to_products_options_id FROM `products_options_values_to_products_options` WHERE products_options_id='.$pro_keys_osid.' AND products_options_values_id='.$pro_values_osid.' LIMIT 1',$$link);
if (!$result_prop_options_values_to_option) {
    die('Could not query:'. mysql_error());
}
if(mysql_num_rows($result_prop_options_values_to_option)<1)
{
mysql_query("INSERT INTO `products_options_values_to_products_options` ( `products_options_id` , `products_options_values_id`) Values ('".$pro_keys_osid."','".$pro_values_osid."')",$$link);
}

$result_products_properties = mysql_query('SELECT products_attributes_id FROM `products_attributes` WHERE products_id='.$poid.' AND options_id='.$pro_keys_osid.' AND options_values_id='.$pro_values_osid.'  LIMIT 1',$$link);
if (!$result_products_properties) {
    die('Could not query:'. mysql_error());
}
if(mysql_num_rows($result_products_properties)>0)
{
$pro_attributes_id = mysql_result($result_products_properties, 0, 0);

    $fiyati = moneyformat($keyval['price1'])/$arr_currency_value[$parabirimi];

// uygulanacak marjlar  baþla
    $marjuygula = array();
    if (isset($refmarj[1][$pro_attributes_id]))
    {
    $marjuygula = $refmarj[1][$pro_attributes_id];
    }
    if (isset($refmarj[2][$poid]))
    {
    $marjuygula = $refmarj[2][$poid];
    }
    else if (isset($refmarj[3][$refmarjcat[$refcategories[$catid][3]]]))
    {
    $marjuygula = $refmarj[3][$refmarjcat[$refcategories[$catid][3]]];
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
    if($multicurry==true){
    $fiyati = $fiyati*$arr_currency_value[$parabirimi];
    }
    $price_prefix = '';
    if($fiyati==0){
    $fiyati = 0.00;
    $price_prefix = '';
    }else if($fiyati > $pfiyati){
    $fiyati = $fiyati - $pfiyati;
    $price_prefix = '+';
    }else if($fiyati < $pfiyati){
    $fiyati = $pfiyati-$fiyati;
    $price_prefix = '-';
    }else
    {
    $fiyati = 0.00;
    $price_prefix = '';
    }

mysql_query("UPDATE `products_attributes` SET `options_values_price`=".$fiyati." , `price_prefix`='".$price_prefix."' Where products_attributes_id=".$pro_attributes_id,$$link);
}
else
{
    $fiyati = moneyformat($keyval['price1'])/$arr_currency_value[$parabirimi];

// uygulanacak marjlar  baþla
    $marjuygula = array();
    if (isset($refmarj[2][$poid]))
    {
    $marjuygula = $refmarj[2][$poid];
    }
    else if (isset($refmarj[3][$refmarjcat[$refcategories[$catid][3]]]))
    {
    $marjuygula = $refmarj[3][$refmarjcat[$refcategories[$catid][3]]];
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
    if($multicurry==true){
    $fiyati = $fiyati*$arr_currency_value[$parabirimi];
    }
    $price_prefix = '';
    if($fiyati==0){
    $fiyati = 0.00;
    $price_prefix = '';
    }else if($fiyati > $pfiyati){
    $fiyati = $fiyati - $pfiyati;
    $price_prefix = '+';
    }else if($fiyati < $pfiyati){
    $fiyati = $pfiyati-$fiyati;
    $price_prefix = '-';
    }else
    {
    $fiyati = 0.00;
    $price_prefix = '';
    }
mysql_query("INSERT INTO `products_attributes` ( `products_id` , `options_id`, `options_values_id`,`options_values_price`,`price_prefix`) Values ('".$poid."','".$pro_keys_osid."','".$pro_values_osid."',".$fiyati.",'".$price_prefix."')",$$link);
$pro_attributes_id = mysql_insert_id($$link);
}
mysql_query("UPDATE `d_attr` SET `osid` = '".$pro_attributes_id."' Where id=".$keyval["id"]);
$ID_To_osID["key"][$keyval['keyid']] = $pro_keys_osid;
$ID_To_osID["val"][$keyval['valid']] = $pro_values_osid;
}
}
return $ID_To_osID;
}

function transfer_qtpro($pid,$poid,$vendor,$ID_To_osID,$link = 'db_link')
{
global $$link;
$result = mysql_query('SELECT id,attr,quantity,isupdate,osid  FROM `d_qtstock` WHERE proid='.$pid.' AND vendor='.$vendor,$$link);
if (!$result) {
    die('Could not query:'. mysql_error());
}
if(mysql_num_rows($result)>0)
{
while ($keyval = tep_db_fetch_array($result))
{
$Array = array();
if(!empty($keyval["attr"])){
if(strpos($keyval["attr"],',')>0)
{
 $ArrayTable = explode(',',$keyval["attr"]);
 foreach($ArrayTable as $TableField)
 {
   if (strpos($TableField,'-')>0)
   {
      $val = explode('-',$TableField);
      $Array[] = $ID_To_osID["key"][$val[0]]."-".$ID_To_osID["val"][$val[1]];
   }
 }
}else if (strpos($keyval["attr"],'-')>0)
{
 $val = explode('-',$keyval["attr"]);
 $Array[] = $ID_To_osID["key"][$val[0]]."-".$ID_To_osID["val"][$val[1]];
}
if(count($Array)>0)
{
$result_prop_options = mysql_query('SELECT products_stock_id,products_stock_quantity FROM `products_stock` WHERE products_id='.$poid.' AND products_stock_attributes=\''.tep_db_input(implode(',',$Array)).'\' LIMIT 1',$$link);
if (!$result_prop_options) {
    die('Could not query:'. mysql_error());
}
if(mysql_num_rows($result_prop_options)>0)
{
$pro_keys_osid = mysql_result($result_prop_options, 0, 0);
if(mysql_result($result_prop_options, 0, 1) != $keyval["quantity"]) mysql_query("UPDATE `products_stock` SET `products_stock_quantity` = '".$keyval["quantity"]."' Where products_stock_id=".$pro_keys_osid);  //osid güncellemesi eklenebilir.
}
else
{
mysql_query("INSERT INTO `products_stock` ( `products_id` , `products_stock_attributes` , `products_stock_quantity` ) Values ('".$poid."', '".implode(',',$Array)."', '".$keyval["quantity"]."')",$$link);
$val_id = mysql_insert_id($$link);
mysql_query("UPDATE `d_qtstock` SET `osid` = '".$val_id."' Where id=".$keyval["id"]);
}
}
}
}
}
}

function transfer_properties($pid,$poid,$vendor,$languageid,$link = 'db_link')
{
global $$link,$refcategories,$propertieslength;
$result = mysql_query('SELECT catid,keyid,valid,id,number FROM `d_tokeyvalues` WHERE osid=0 AND proid='.$pid.' AND vendor='.$vendor,$$link);
if (!$result) {
    die('Could not query:'. mysql_error());
}
if(mysql_num_rows($result)>0)
{
while ($keyval = tep_db_fetch_array($result)) {
$result_keys = mysql_query('SELECT kname,catid,osid,id FROM `d_keys` WHERE id ='.$keyval['keyid'].' LIMIT 1',$$link);
$pro_keys_osid = mysql_result($result_keys, 0, 2);
if ($pro_keys_osid == 0)
{
$result_prop_options = mysql_query('SELECT products_options_id FROM `products_prop_options` WHERE categories_options_id='.$refcategories[mysql_result($result_keys, 0, 1)][3].' AND language_id='.$languageid.' AND products_options_name=\''.tep_db_input((strlen(mysql_result($result_keys, 0, 0))>$propertieslength[0]) ? substr(mysql_result($result_keys, 0, 0),0,$propertieslength[0]): mysql_result($result_keys, 0, 0)).'\' LIMIT 1',$$link);
if (!$result_prop_options) {
    die('Could not query:'. mysql_error());
}
if(mysql_num_rows($result_prop_options)>0)
{
$pro_keys_osid = mysql_result($result_prop_options, 0, 0);
}
else
{
$result_prop_options_maxid = mysql_query('SELECT max(`products_options_id`) From `products_prop_options`');
$pro_keys_osid = mysql_result($result_prop_options_maxid, 0, 0);
if ($pro_keys_osid == null) $pro_keys_osid =0;
$pro_keys_osid +=1;
mysql_query("INSERT INTO `products_prop_options` ( `products_options_id` , `categories_options_id` , `language_id` , `products_options_name` ) Values ('".$pro_keys_osid."','".$refcategories[mysql_result($result_keys, 0, 1)][3]."', '".$languageid."', '".tep_db_input((strlen(mysql_result($result_keys, 0, 0))>$propertieslength[0]) ? substr(mysql_result($result_keys, 0, 0),0,$propertieslength[0]): mysql_result($result_keys, 0, 0))."')",$$link);
mysql_query("UPDATE `d_keys` SET `osid` = '".$pro_keys_osid."' Where id=".mysql_result($result_keys, 0, 3));
}

}
$result_values = mysql_query('SELECT vname,keyid,catid,osid,id FROM `d_values` WHERE id ='.$keyval['valid'].' LIMIT 1',$$link);
$pro_values_osid = mysql_result($result_values, 0, 3);
if ($pro_values_osid == 0)
{
$result_prop_options_values = mysql_query('SELECT products_options_values_id FROM `products_prop_options_values` WHERE categories_options_values_id='.$refcategories[mysql_result($result_values, 0, 2)][3].' AND language_id='.$languageid.' AND products_options_values_name=\''.tep_db_input((strlen(mysql_result($result_values, 0, 0))>$propertieslength[1]) ? substr(mysql_result($result_values, 0, 0),0,$propertieslength[1]): mysql_result($result_values, 0, 0)).'\' LIMIT 1',$$link);
if (!$result_prop_options_values) {
		die('Could not query:'. mysql_error());
}
if(mysql_num_rows($result_prop_options_values)>0)
{
$pro_values_osid = mysql_result($result_prop_options_values, 0, 0);
}
else
{
$result_prop_options_values_maxid = mysql_query('SELECT max(`products_options_values_id`) From `products_prop_options_values`');
$pro_values_osid = mysql_result($result_prop_options_values_maxid, 0, 0);
if ($pro_values_osid == null) $pro_values_osid =0;
$pro_values_osid +=1;
mysql_query("INSERT INTO `products_prop_options_values` ( `products_options_values_id` , `categories_options_values_id` , `language_id` , `products_options_values_name` ) Values ('".$pro_values_osid."','".$refcategories[mysql_result($result_values, 0, 2)][3]."', '".$languageid."', '".tep_db_input((strlen(mysql_result($result_values, 0, 0))>$propertieslength[1]) ? substr(mysql_result($result_values, 0, 0),0,$propertieslength[1]): mysql_result($result_values, 0, 0))."')",$$link);
mysql_query("UPDATE `d_values` SET `osid` = '".$pro_values_osid."' Where id=".mysql_result($result_values, 0, 4));
}
}
$result_prop_options_values_to_option = mysql_query('SELECT products_options_values_to_products_options_id FROM `products_prop_options_values_to_products_prop_options` WHERE products_options_id='.$pro_keys_osid.' AND products_options_values_id='.$pro_values_osid.' LIMIT 1',$$link);
if (!$result_prop_options_values_to_option) {
    die('Could not query:'. mysql_error());
}
if(mysql_num_rows($result_prop_options_values_to_option)<1)
{
mysql_query("INSERT INTO `products_prop_options_values_to_products_prop_options` ( `products_options_id` , `products_options_values_id`) Values ('".$pro_keys_osid."','".$pro_values_osid."')",$$link);
}
                                                                        //0025  selectte sort_order eklendi.
$result_products_properties = mysql_query('SELECT products_attributes_id,sort_order FROM `products_properties` WHERE products_id='.$poid.' AND categories_id='.$refcategories[mysql_result($result_keys, 0, 1)][3].' AND options_id='.$pro_keys_osid.' AND options_values_id='.$pro_values_osid.'  LIMIT 1',$$link);
if (!$result_products_properties) {
    die('Could not query:'. mysql_error());
}
if(mysql_num_rows($result_products_properties)>0)
{
$pro_attributes_id = mysql_result($result_products_properties, 0, 0);
if($keyval['number']!=0 && $keyval['number']!=mysql_result($result_products_properties, 0, 1)) mysql_query("UPDATE `products_properties` SET `sort_order` = '".$keyval['number']."' Where products_attributes_id=".$pro_attributes_id,$$link);    //0025 eklendi.
}
else
{
mysql_query("INSERT INTO `products_properties` ( `products_id` , `categories_id`, `options_id`, `options_values_id`".(($keyval['number']!=0)?',`sort_order`':'').") Values ('".$poid."','".$refcategories[mysql_result($result_keys, 0, 1)][3]."','".$pro_keys_osid."','".$pro_values_osid."'".(($keyval['number']!=0)?','.$keyval['number']:'').")",$$link);   //0025 $keyval['number'] eklendi.
$pro_attributes_id = mysql_insert_id($$link);
}
mysql_query("UPDATE `d_tokeyvalues` SET `osid` = '".$pro_attributes_id."' Where id=".$keyval["id"],$$link);
}
}
}
/*0016#1 eklenti start*/
function connector_transfer_extras($osid,$product,$fiyati,$mfiyati,$parabirimi,$multicurry,$qtpro,$languageid,$vendor,$link = 'db_link')
{
global $$link;
transfer_properties($product['id'],$osid,$vendor,$languageid);
$ID_To_osID = transfer_features($product['id'],$osid,$vendor,$languageid,$product['catid'],$mfiyati,$fiyati,$parabirimi,$multicurry,$qtpro);
if($qtpro)
{
transfer_qtpro($product['id'],$osid,$vendor,$ID_To_osID);
}
}
function connector_transfer_insert($osid,$product,$fiyati,$mfiyati,$parabirimi,$multicurry,$qtpro,$languageid,$vendor,$link = 'db_link')
{
global $$link,$ISUPDATESTATUS,$PUPDATESTATUS,$PSUBIMAGELIMIT,$PMCHARLENGTH,$PNCHARLENGTH,$arr_currency_value,$arr_tax,$arr_brand,$refcategories,$refspec;

    $defaultimagedir = '';
    $subimages = array();
    $subimagesvalue = array();
    $result = mysql_query('SELECT imagedir FROM `d_images` WHERE proid='.$product['id'].' and vendor='.$vendor.' Order by number Limit '.($PSUBIMAGELIMIT+1),$$link);
    if (!$result) {
        die('Could not query:' . mysql_error());
    }
    if(mysql_num_rows($result)>0)
    {
    $i=0;
    while ($mem = tep_db_fetch_array($result)) {
    if($i==0)$defaultimagedir = $mem['imagedir'];
    else
    {
      $subimages[] = '`products_subimage'.$i.'`';
      $subimagesvalue[] = "'".tep_db_input($mem['imagedir'])."'";
    }
    $i++;
    }
    }
    for ($i=count($subimages)+1; $i<=$PSUBIMAGELIMIT; $i++)  {
    $subimages[] = '`products_subimage'.$i.'`';
    $subimagesvalue[] = "''";
    }
    $mode = 1;                      // 0024 status update 
    if($PUPDATESTATUS) $mode = ($product['isdeleted']==1)?0:1;
    if($ISUPDATESTATUS && $mode==1) $mode = ($product['isupdate']==0)?0:1;

    if($multicurry==true){
    $fiyati = $fiyati*$arr_currency_value[$parabirimi];
    $sql = "INSERT INTO `products` (`products_quantity` , `products_model` , `products_image`".(($PSUBIMAGELIMIT>0)?','.(implode(',',$subimages)):'')." , `products_price` , `products_date_added` , `products_weight` , `products_status` , `products_tax_class_id` , `manufacturers_id` , `products_ordered`,`products_cost`,`products_currencies_id`)";
    $sql.= " VALUES (";
    $sql.= "'".tep_db_input(formatnumber($product['stock']))."', '".tep_db_input((strlen($product['pcode'])>$PMCHARLENGTH) ? substr($product['pcode'],0,$PMCHARLENGTH): $product['pcode'])."', '".tep_db_input($defaultimagedir)."'".(($PSUBIMAGELIMIT>0)?','.(implode(',',$subimagesvalue)):'').", '".tep_db_input($fiyati)."', '".tep_db_input($product['adddate'])."', '".tep_db_input(((formatnumber($product['measure'])<1000)?formatnumber($product['measure']):999))."', '".$mode."', '".tep_db_input(isset($arr_tax[$product['tax']])?$arr_tax[$product['tax']]:0)."', '".tep_db_input(isset($arr_brand[$product['brand']])?$arr_brand[$product['brand']]:0)."', '0', '".tep_db_input($mfiyati)."', '".tep_db_input($parabirimi)."'";
    $sql.= ")";
    }else{
    $sql = "INSERT INTO `products` (`products_quantity` , `products_model` , `products_image`".(($PSUBIMAGELIMIT>0)?','.(implode(',',$subimages)):'')." , `products_price` , `products_date_added` , `products_weight` , `products_status` , `products_tax_class_id` , `manufacturers_id` , `products_ordered`)";
    $sql.= " VALUES (";
    $sql.= "'".tep_db_input(formatnumber($product['stock']))."', '".tep_db_input((strlen($product['pcode'])>$PMCHARLENGTH) ? substr($product['pcode'],0,$PMCHARLENGTH): $product['pcode'])."', '".tep_db_input($defaultimagedir)."'".(($PSUBIMAGELIMIT>0)?','.(implode(',',$subimagesvalue)):'').", '".tep_db_input($fiyati)."', '".tep_db_input($product['adddate'])."', '".tep_db_input(((formatnumber($product['measure'])<1000)?formatnumber($product['measure']):999))."', '".$mode."', '".tep_db_input(isset($arr_tax[$product['tax']])?$arr_tax[$product['tax']]:0)."', '".tep_db_input(isset($arr_brand[$product['brand']])?$arr_brand[$product['brand']]:0)."', '0'";
    $sql.= ")";
    }
    if(!mysql_query($sql,$$link))echo "Hatalý iþlem, Kod 1<br>";
    $osid = mysql_insert_id($$link);
    $sql = "INSERT INTO `products_description` (`products_id`, `language_id`, `products_name`, `products_description`)";
    $sql.= " VALUES (".$osid.", ".$languageid.", '".tep_db_input((strlen($product['pname'])>$PNCHARLENGTH) ? substr($product['pname'],0,$PNCHARLENGTH): $product['pname'])."', '".tep_db_input($product['desc'])."')";
    mysql_query($sql,$$link);
    mysql_query("INSERT INTO `products_to_categories` ( `products_id` , `categories_id` ) VALUES (".$osid.", '".tep_db_input(isset($refcategories[$product['catid']][3])?$refcategories[$product['catid']][3]:0)."')",$$link);
    mysql_query("Update `d_products` SET osid=".$osid." Where id=".$product['id'],$$link);

/*0006 eklenti start*/
    $pcode = (strlen($product['pcode'])>$PMCHARLENGTH) ? substr($product['pcode'],0,$PMCHARLENGTH): $product['pcode'];

    if (isset($refspec[tep_db_input($pcode)]))
    {
        $rate = formatnumber($refspec[tep_db_input($pcode)][0]);
        $discount = moneyformat($refspec[tep_db_input($pcode)][1]);
        $specialprise = $fiyati;
        if ($rate == 1) $specialprise = $fiyati*$discount;
        else if ($rate == 2)$specialprise = $fiyati/$discount;
        else if ($rate == 3)$specialprise = $fiyati-$discount;
        else if ($rate == 4)$specialprise = $fiyati+$discount;
        else if ($rate == 5)$specialprise = $discount;
        $specialresult = mysql_query('SELECT `specials_id` FROM `specials` WHERE `products_id`= '.$osid.' Limit 1' ,$$link);
        if (!$specialresult) {
            die('Could not query:' . mysql_error());
        }
        if(mysql_num_rows($specialresult)>0)
        {
          mysql_query("UPDATE `specials` SET `specials_new_products_price` = '".tep_db_input($specialprise)."',`specials_last_modified` = now() WHERE `specials_id` =".mysql_result($specialresult, 0, 0)." LIMIT 1",$$link);
        }
        else
        {
          mysql_query("INSERT INTO `specials` (`products_id` ,`specials_new_products_price` ,`specials_date_added` ,`specials_last_modified` ,`expires_date` ,`date_status_change` ,`status`) VALUES ('".$osid."', '".tep_db_input($specialprise)."', now(), now(), NULL, now(), '1')",$$link);
        }
    }
return $osid;
/*0006 eklenti end*/
}

function connector_transfer_update($osid,$product,$fiyati,$mfiyati,$parabirimi,$multicurry,$qtpro,$languageid,$vendor,$link = 'db_link')
{
global $$link,$ISUPDATESTATUS,$PUPDATEIMAGE,$PUPDATESTATUS,$PSUBIMAGELIMIT,$PMCHARLENGTH,$PNCHARLENGTH,$PDESCISUPDATE,$PNAMEISUPDATE,$arr_currency_value,$arr_tax,$arr_brand,$refcategories,$refspec,$class;
    $product['imagedir'] = '';
    $result = mysql_query('SELECT imagedir FROM `d_images` WHERE proid='.$product['id'].' and vendor='.$vendor.' Order by number Limit '.($PSUBIMAGELIMIT+1),$$link);
    if (!$result) {
        die('Could not query:' . mysql_error());
    }
    if(mysql_num_rows($result)>0)
    {
    $i=0;
    while ($mem = tep_db_fetch_array($result)) {
    if($i==0)$product['imagedir'] = $mem['imagedir'];
    else
    {
    $product['subimagedir'.$i] = $mem['imagedir'];
    }
    $i++;
    }
    }
    for ($i=count($subimages)+1; $i<=$PSUBIMAGELIMIT; $i++)  {
    $product['subimagedir'.$i] = '';
    }

    $pupdateimageevent = true;
    $pupdateimage = array();
    if($PUPDATEIMAGE ===1)
    {
      $subimages = array();
      $pupdateimage[0] = true;
      for ($i=1; $i<=$PSUBIMAGELIMIT; $i++)  {
      $pupdateimage[$i] = true;
      $subimages[] = 'products_subimage'.$i;
      }
      $pupdateimageresult = mysql_query('SELECT products_image'.(($PSUBIMAGELIMIT>0)?','.(implode(',',$subimages)):'').' FROM `products` WHERE `products_id` ='.tep_db_input($osid).' LIMIT 1',$$link);
      if (!$pupdateimageresult) {
        die('Could not query:' . mysql_error());
      }
      if(mysql_num_rows($pupdateimageresult)>0)
      {
        //fix images PUPDATEIMAGE=1
        $pupdateimage[0] = (preg_match('/^'.$class.'/', mysql_result($pupdateimageresult, 0, 0)))?true:((mysql_result($pupdateimageresult, 0, 0)=='')?true:false);
        for ($i=1; $i<=$PSUBIMAGELIMIT; $i++){
        $pupdateimage[$i] = (preg_match('/^'.$class.'/', mysql_result($pupdateimageresult, 0, $i)))?true:((mysql_result($pupdateimageresult, 0, $i)=='')?true:false);
        }
      }
    }else if($PUPDATEIMAGE ===2)
    {
    $pupdateimageevent=($product['imagelock'] == 1)?false:true;
    }

    $mode = 1;                            // 0024 status update
    if($PUPDATESTATUS) $mode = ($product['isdeleted']==1)?0:1;
    if($ISUPDATESTATUS && $mode==1) $mode = ($product['isupdate']==0)?0:1;

    if($multicurry==true){
    $fiyati = $fiyati*$arr_currency_value[$parabirimi];
    $sql  = "UPDATE `products` SET `products_model` = '".tep_db_input((strlen($product['pcode'])>$PMCHARLENGTH) ? substr($product['pcode'],0,$PMCHARLENGTH): $product['pcode'])."', ";
    $sql .= "`products_price` = '".tep_db_input($fiyati)."', ";
    $sql .= "`products_last_modified` = '".mysqlgetdatenow()."', ";
    $sql .= "`products_quantity` = '".tep_db_input(formatnumber($product['stock']))."', ";
    $sql .= "`products_tax_class_id` = '".tep_db_input(isset($arr_tax[$product['tax']])?$arr_tax[$product['tax']]:0)."', ";
    $sql .= "`products_weight` = '".tep_db_input(((formatnumber($product['measure'])<1000)?formatnumber($product['measure']):999))."', ";
    if($PUPDATEIMAGE===1)
    {
    if($pupdateimage[0])$sql .= "`products_image` = ".(empty($product['imagedir'])?'null':"'".tep_db_input($product['imagedir'])."'").", ";
    for ($i=1; $i<=$PSUBIMAGELIMIT; $i++)  {
    if($pupdateimage[$i])$sql .= "`products_subimage".$i."` = ".(empty($product['subimagedir'.$i])?'null':"'".tep_db_input($product['subimagedir'.$i])."'").", ";
    }
    }else if ($PUPDATEIMAGE ===2)
    {
    if($pupdateimageevent){
    $sql .= "`products_image` = ".(empty($product['imagedir'])?'null':"'".tep_db_input($product['imagedir'])."'").", ";
    for ($i=1; $i<=$PSUBIMAGELIMIT; $i++)  {
    $sql .= "`products_subimage".$i."` = ".(empty($product['subimagedir'.$i])?'null':"'".tep_db_input($product['subimagedir'.$i])."'").", ";
    }
    }
    }
    else {
    $sql .= "`products_image` = ".(empty($product['imagedir'])?'null':"'".tep_db_input($product['imagedir'])."'").", ";
    for ($i=1; $i<=$PSUBIMAGELIMIT; $i++)  {
    $sql .= "`products_subimage".$i."` = ".(empty($product['subimagedir'.$i])?'null':"'".tep_db_input($product['subimagedir'.$i])."'").", ";
    }
    }
    $sql .= "`products_cost` = '".tep_db_input($mfiyati)."', ";
    $sql .= "`products_currencies_id` = '".tep_db_input($parabirimi)."', ";
    if($PUPDATESTATUS || $ISUPDATESTATUS) $sql .= "`products_status` = '".$mode."', ";
    $sql .= "`manufacturers_id` = '".tep_db_input(isset($arr_brand[$product['brand']])?$arr_brand[$product['brand']]:0)."' WHERE `products_id` =".tep_db_input($osid)." LIMIT 1";
    }else{
    $sql  = "UPDATE `products` SET `products_model` = '".tep_db_input((strlen($product['pcode'])>$PMCHARLENGTH) ? substr($product['pcode'],0,$PMCHARLENGTH): $product['pcode'])."', ";
    $sql .= "`products_price` = '".tep_db_input($fiyati)."', ";
    $sql .= "`products_last_modified` = '".mysqlgetdatenow()."', ";
    $sql .= "`products_quantity` = '".tep_db_input(formatnumber($product['stock']))."', ";
    $sql .= "`products_tax_class_id` = '".tep_db_input(isset($arr_tax[$product['tax']])?$arr_tax[$product['tax']]:0)."', ";
    $sql .= "`products_weight` = '".tep_db_input(((formatnumber($product['measure'])<1000)?formatnumber($product['measure']):999))."', ";
    if($PUPDATEIMAGE===1)
    {
    if($pupdateimage[0])$sql .= "`products_image` = ".(empty($product['imagedir'])?'null':"'".tep_db_input($product['imagedir'])."'").", ";
    for ($i=1; $i<=$PSUBIMAGELIMIT; $i++)  {
    if($pupdateimage[$i])$sql .= "`products_subimage".$i."` = ".(empty($product['subimagedir'.$i])?'null':"'".tep_db_input($product['subimagedir'.$i])."'").", ";
    }
    }else if ($PUPDATEIMAGE ===2)
    {
    if($pupdateimageevent){
    $sql .= "`products_image` = ".(empty($product['imagedir'])?'null':"'".tep_db_input($product['imagedir'])."'").", ";
    for ($i=1; $i<=$PSUBIMAGELIMIT; $i++)  {
    $sql .= "`products_subimage".$i."` = ".(empty($product['subimagedir'.$i])?'null':"'".tep_db_input($product['subimagedir'.$i])."'").", ";
    }
    }
    }
    else {
    $sql .= "`products_image` = ".(empty($product['imagedir'])?'null':"'".tep_db_input($product['imagedir'])."'").", ";
    for ($i=1; $i<=$PSUBIMAGELIMIT; $i++)  {
    $sql .= "`products_subimage".$i."` = ".(empty($product['subimagedir'.$i])?'null':"'".tep_db_input($product['subimagedir'.$i])."'").", ";
    }
    }
    if($PUPDATESTATUS  || $ISUPDATESTATUS) $sql .= "`products_status` = '".$mode."', ";
    $sql .= "`manufacturers_id` = '".tep_db_input(isset($arr_brand[$product['brand']])?$arr_brand[$product['brand']]:0)."' WHERE `products_id` =".tep_db_input($osid)." LIMIT 1";
    }
    if(!mysql_query($sql,$$link))echo "Hatalý iþlem, Kod 2<br>";
    /*0000#0 eklenti start*/
    if ($PDESCISUPDATE === 1)
    {
      mysql_query("UPDATE `products_description` SET `products_description` ='".tep_db_input($product['desc'])."'".(($PNAMEISUPDATE === 1)?(",`products_name` ='".tep_db_input((strlen($product['pname'])>$PNCHARLENGTH) ? substr($product['pname'],0,$PNCHARLENGTH): $product['pname'])."'"):'')." WHERE `language_id`=".$languageid." and `products_id` =".tep_db_input($osid)." LIMIT 1",$$link);
    }
    else if ($PDESCISUPDATE === 2)
    {
      mysql_query("UPDATE `products_description` SET `products_description` ='".tep_db_input($product['desc'])."'".(($PNAMEISUPDATE === 2)?(",`products_name` ='".tep_db_input((strlen($product['pname'])>$PNCHARLENGTH) ? substr($product['pname'],0,$PNCHARLENGTH): $product['pname'])."'"):'')." WHERE `language_id`=".$languageid." and `products_id` =".tep_db_input($osid)." and `d_isupdate`=1 LIMIT 1",$$link);
    }
    if(($PNAMEISUPDATE !== $PDESCISUPDATE)&&($PNAMEISUPDATE !== 0))
    {
      if ($PNAMEISUPDATE === 1)
      {
        mysql_query("UPDATE `products_description` SET `products_name` ='".tep_db_input((strlen($product['pname'])>$PNCHARLENGTH) ? substr($product['pname'],0,$PNCHARLENGTH): $product['pname'])."' WHERE `language_id`=".$languageid." and `products_id` =".tep_db_input($osid)." LIMIT 1",$$link);
      }
      else if ($PNAMEISUPDATE === 2)
      {
        mysql_query("UPDATE `products_description` SET `products_name` ='".tep_db_input((strlen($product['pname'])>$PNCHARLENGTH) ? substr($product['pname'],0,$PNCHARLENGTH): $product['pname'])."' WHERE `language_id`=".$languageid." and `products_id` =".tep_db_input($osid)." and `d_isupdate`=1 LIMIT 1",$$link);
      }
    }
/*0000#0 eklenti end*/
/*0006 eklenti start*/
    $pcode = (strlen($product['pcode'])>$PMCHARLENGTH) ? substr($product['pcode'],0,$PMCHARLENGTH): $product['pcode'];
    if (isset($refspec[tep_db_input($pcode)]))
    {
        $rate = formatnumber($refspec[tep_db_input($pcode)][0]);
        $discount = moneyformat($refspec[tep_db_input($pcode)][1]);
        $specialprise = $fiyati;
        if ($rate == 1) $specialprise = $fiyati*$discount;
        else if ($rate == 2)$specialprise = $fiyati/$discount;
        else if ($rate == 3)$specialprise = $fiyati-$discount;
        else if ($rate == 4)$specialprise = $fiyati+$discount;
        else if ($rate == 5)$specialprise = $discount;
        $specialresult = mysql_query('SELECT `specials_id` FROM `specials` WHERE `products_id`= '.$osid.' Limit 1' ,$$link);
        if (!$specialresult) {
            die('Could not query:' . mysql_error());
        }
        if(mysql_num_rows($specialresult)>0)
        {
          mysql_query("UPDATE `specials` SET `specials_new_products_price` = '".tep_db_input($specialprise)."',`specials_last_modified` = now() WHERE `specials_id` =".mysql_result($specialresult, 0, 0)." LIMIT 1",$$link);
        }
        else
        {
          mysql_query("INSERT INTO `specials` (`products_id` ,`specials_new_products_price` ,`specials_date_added` ,`specials_last_modified` ,`expires_date` ,`date_status_change` ,`status`) VALUES ('".$osid."', '".tep_db_input($specialprise)."', now(), now(), NULL, now(), '1')",$$link);
        }
    }
/*0006 eklenti end*/
}

function connector_transfer_currency($vendor,$languageid,$link = 'db_link')
{
global $$link,$arr_currency_value;
$result = mysql_query('SELECT `currencies_id`,`value` FROM `currencies`',$$link);
if (!$result) {
    die('Could not query:' . mysql_error());
}
if(mysql_num_rows($result)>0)
{
while ($currency_value = tep_db_fetch_array($result)) {
$arr_currency_value[$currency_value['currencies_id']]=$currency_value['value'];
}
}
}

function connector_transfer_manufacturer($vendor,$languageid,$link = 'db_link')
{
global $$link,$arr_brand;
$result = mysql_query('SELECT `id`,`bcode`,`bname`,`osid` FROM `d_brands` WHERE vendor='.$vendor,$$link);
if (!$result) {
    die('Could not query:' . mysql_error());
}
if(mysql_num_rows($result)>0)
{
while ($brand = tep_db_fetch_array($result)) {
$brandname = empty($brand['bname'])?$brand['bcode']:$brand['bname'];
if(!empty($brandname)){
if($brand['osid'] == 0)
{
		if(strlen($brandname)>32)$brandname=substr($brandname,0,32);
        //mysql_query("INSERT INTO `manufacturers` (`manufacturers_name`) VALUES ('".tep_db_input($brandname)."')",$db_link);
        //$osid = mysql_insert_id($db_link);
        //üreticiler fix 0000#0
        $query = mysql_query('SELECT manufacturers_id FROM `manufacturers` WHERE manufacturers_name=\''.tep_db_input($brandname).'\' LIMIT 1',$$link);
        if (!$query) {
            die('Could not query:' . mysql_error());
        }
        $osid =0;
        if(mysql_num_rows($query)<1)
        {
          mysql_query("INSERT INTO `manufacturers` (`manufacturers_name`) VALUES ('".tep_db_input($brandname)."')",$$link);
          $osid = mysql_insert_id($db_link);
        }
        else
        {
        $osid = mysql_result($query, 0, 0);
        }
        // fix end
        //mysql_query("INSERT INTO `manufacturers_info` (`manufacturers_id`  `languages_id`) VALUES ('".$osid."','".$languageid."')",$db_link);
        mysql_query("Update `d_brands` SET osid=".$osid." Where id=".$brand['id'],$$link);
        $arr_brand[$brand['id']]=$osid;
}
else
{
        $arr_brand[$brand['id']] = $brand['osid'];
}
}
}
}
}

function connector_transfer_category($languageid,$link = 'db_link')
{
global $$link,$refcategories;
$arr_parent = array();
//0020 parent_id eklentisi start
$os_parent = array();
//0020 parent_id eklentisi end
foreach($refcategories as $key=>$val){
if($val[0] != 1){
$result = mysql_query('SELECT osid,cname,ospi FROM `d_categories` WHERE id='.$key,$$link);
if (!$result) {
    die('Could not query:' . mysql_error());
}
if(mysql_num_rows($result)>0)
{
    $osid = mysql_result($result, 0, 0);
    $osname = mysql_result($result, 0, 1);
    //0020 parent_id eklentisi start
    $os_parent[$key]=tep_db_input(mysql_result($result, 0, 2));
    //0020 parent_id eklentisi end
    if($osid == 0){
        mysql_query("INSERT INTO `categories` (`parent_id`,`sort_order`)VALUES ('0', '0')",$$link);
        $osid = mysql_insert_id($db_link);
        if(strlen($osname)>32)$osname=substr($osname,0,32);
        $query = mysql_query("INSERT INTO `categories_description` ( `categories_id` , `language_id` , `categories_name` )VALUES ('".$osid."', '".$languageid."', '".tep_db_input($osname)."');",$$link);

        $refcategories[$key][3] = $osid;
        mysql_query("Update `d_categories` SET osid=".$osid." Where id=".$key,$$link);
        $arr_parent[$key]= $osid;
    }else
    {
    $refcategories[$key][3] = $osid;
    }
}
}
}
foreach($arr_parent as $key=>$val){
$osid = $refcategories[$key][3];
//0020 parent_id eklentisi start
if($os_parent[$key] != 0) $parentid = $os_parent[$key];
else
//0020 parent_id eklentisi end
$parentid = ($refcategories[$key][2] != 0) ? $refcategories[$refcategories[$key][2]][3]: 0;
if($parentid<0)$parentid=0; //0022 -1 osid kontrolü
mysql_query("Update `categories` SET parent_id=".$parentid." Where categories_id=".$osid,$$link);
}
}

function connector_defaultcurrency($link = 'db_link')
{
global $$link;
$defaultcurrency = 1;
$result = mysql_query('SELECT currencies_id FROM `currencies` WHERE `code` = (SELECT configuration_value FROM `configuration` WHERE `configuration_key` = \'DEFAULT_CURRENCY\' )',$$link);
if (!$result) {
    die('Could not query:' . mysql_error());
}
if(mysql_num_rows($result)>0)
{
$defaultcurrency = mysql_result($result, 0, 0);
}
return $defaultcurrency;
}

function connector_special($specid,$link = 'db_link')
{
global $$link;
$result = mysql_query("SELECT `vendor`,`pcode` FROM `d_special` where id=".formatnumber($specid).' Limit 1',$$link);
if (!$result) {
      die('Could not query:' . mysql_error());
}
if(mysql_num_rows($result)>0)
{
$resultv = mysql_query("SELECT osid FROM `d_products` Where vendor=".mysql_result($result, 0, 0)." and pcode='".mysql_result($result, 0, 1)."' Limit 1",$$link);
if (!$resultv) {
die('Could not query:' . mysql_error());
}
if(mysql_num_rows($resultv)>0)
{
mysql_query("DELETE FROM `specials` Where products_id=".formatnumber(mysql_result($resultv, 0, 0)),$$link);
}
}
mysql_query("DELETE FROM `d_special` Where id=".formatnumber($specid),$$link);
}

function connector_deleted($qtpro,$link = 'db_link')
{
global $$link,$image_directory,$PSUBIMAGELIMIT,$PIMAGEDELETE;
// 0012 remove deleted products's images
$pid=0;
if($PIMAGEDELETE!=0){
/*convert image format begin*/
$product = array();
$product['imagedir'] = '';
$result = mysql_query('SELECT i.imagedir as imagedir,i.proid as pid FROM `d_images` i, `d_products` p WHERE p.id = i.proid AND p.osid ='.formatnumber($_GET["id"]).' ORDER BY i.number LIMIT '.($PSUBIMAGELIMIT+1),$$link);
if (!$result) {
die('Could not query:' . mysql_error());
}
if(mysql_num_rows($result)>0)
{
$i=0;
while ($mem = tep_db_fetch_array($result)) {
if($i==0)
{
$pid=$mem['pid'];
$product['imagedir'] = $mem['imagedir'];
}
else
{
$product['subimagedir'.$i] = $mem['imagedir'];
}
$i++;
}
}
for ($i=count($subimages)+1; $i<=$PSUBIMAGELIMIT; $i++)  {
$product['subimagedir'.$i] = '';
}
/*convert image format end*/
$subimages = array();
for ($i=1; $i<=$PSUBIMAGELIMIT; $i++)  {
$subimages[] = '\''.$product['subimagedir'.$i].'\' as `subimagedir'.$i.'`,`products_subimage'.$i.'`';
}
$result = mysql_query("select '".$product['imagedir']."' as `imagedir`,`products_image`".(($PSUBIMAGELIMIT>0)?','.(implode(',',$subimages)):'')." FROM `products` WHERE products_id=".formatnumber($_GET["id"]),$$link);
if (!$result) {
die('Could not query:' . mysql_error());
}
if(mysql_num_rows($result)>0)
{
if(($PIMAGEDELETE!=0)&&(mysql_result($result, 0, 0) != ''))@unlink($image_directory.mysql_result($result, 0, 0));
if(($PIMAGEDELETE==2)&&(mysql_result($result, 0, 1) != '')&&(mysql_result($result, 0, 0) != mysql_result($result, 0, 1)))@unlink($image_directory.mysql_result($result, 0, 1));
for ($i=2;$i<=($PSUBIMAGELIMIT*2);$i=$i+2){
if(($PIMAGEDELETE!=0)&&(mysql_result($result, 0, $i) != ''))@unlink($image_directory.mysql_result($result, 0, $i));
if(($PIMAGEDELETE==2)&&(mysql_result($result, 0,($i+1)) != '')&&(mysql_result($result, 0, $i) != mysql_result($result, 0, ($i+1))))@unlink($image_directory.mysql_result($result, 0, ($i+1)));
}
}
}
// remove deleted products's images
if($pid!=0)mysql_query("DELETE FROM `d_images` Where proid=".$pid,$$link);
//mysql_query("DELETE FROM `d_products` Where osid=".formatnumber($_GET["id"]),$$link);
mysql_query("DELETE FROM `d_products` Where id=".formatnumber($pid),$$link);
mysql_query("DELETE FROM `d_tokeyvalues` Where proid=".formatnumber($pid),$$link);
mysql_query("DELETE FROM `d_attr` Where proid=".formatnumber($pid),$$link);
mysql_query("DELETE FROM `products` Where products_id=".formatnumber($_GET["id"]),$$link);
mysql_query("DELETE FROM `products_attributes` Where products_id=".formatnumber($_GET["id"]),$$link);
mysql_query("DELETE FROM `products_description` Where products_id=".formatnumber($_GET["id"]),$$link);
mysql_query("DELETE FROM `products_properties` Where products_id=".formatnumber($_GET["id"]),$$link);
mysql_query("DELETE FROM `products_to_categories` Where products_id=".formatnumber($_GET["id"]),$$link);
if($qtpro)mysql_query("DELETE FROM `products_stock` Where products_id=".formatnumber($_GET["id"]),$$link);
mysql_query("DELETE FROM `specials` Where products_id=".formatnumber($_GET["id"]),$$link);
}

function connector_alldeleted($pid,$osid,$qtpro,$link = 'db_link')
{
global $$link,$image_directory,$PSUBIMAGELIMIT,$PIMAGEDELETE;
//0012 remove deleted products's images
if($PIMAGEDELETE!=0){
/*convert image format begin*/
$product = array();
$product['imagedir'] = '';
$result = mysql_query('SELECT imagedir FROM `d_images` WHERE proid='.$pid.' Order by number Limit '.($PSUBIMAGELIMIT+1),$$link);
if (!$result) {
die('Could not query:' . mysql_error());
}
if(mysql_num_rows($result)>0)
{
$i=0;
while ($mem = tep_db_fetch_array($result)) {
if($i==0)$product['imagedir'] = $mem['imagedir'];
else
{
$product['subimagedir'.$i] = $mem['imagedir'];
}
$i++;
}
}
for ($i=count($subimages)+1; $i<=$PSUBIMAGELIMIT; $i++)  {
$product['subimagedir'.$i] = '';
}
/*convert image format end*/
$subimages = array();
for ($i=1; $i<=$PSUBIMAGELIMIT; $i++)  {
$subimages[] = '\''.$product['subimagedir'.$i].'\' as `subimagedir'.$i.'`,`products_subimage'.$i.'`';
}
$result_values = mysql_query("select '".$product['imagedir']."' as `imagedir`,`products_image`".(($PSUBIMAGELIMIT>0)?','.(implode(',',$subimages)):'')." FROM `products` WHERE products_id=".formatnumber($osid),$$link);
if (!$result_values) {
die('Could not query:' . mysql_error());
}
if(mysql_num_rows($result_values)>0)
{
if(($PIMAGEDELETE!=0)&&(mysql_result($result_values, 0, 0) != ''))@unlink($image_directory.mysql_result($result_values, 0, 0));
if(($PIMAGEDELETE==2)&&(mysql_result($result_values, 0, 1) != '')&&(mysql_result($result_values, 0, 0) != mysql_result($result_values, 0, 1)))@unlink($image_directory.mysql_result($result_values, 0, 1));
for ($i=2;$i<=($PSUBIMAGELIMIT*2);$i=$i+2){
if(($PIMAGEDELETE!=0)&&(mysql_result($result_values, 0, $i) != ''))@unlink($image_directory.mysql_result($result_values, 0, $i));
if(($PIMAGEDELETE==2)&&(mysql_result($result_values, 0,($i+1)) != '')&&(mysql_result($result_values, 0, $i) != mysql_result($result_values, 0, ($i+1))))@unlink($image_directory.mysql_result($result_values, 0, ($i+1)));
}
}
}
// remove deleted products's images
mysql_query("DELETE FROM `d_images` Where proid=".formatnumber($pid),$$link);
//mysql_query("DELETE FROM `d_products` Where osid=".formatnumber($osid),$$link);
mysql_query("DELETE FROM `d_products` Where id=".formatnumber($pid),$$link);
mysql_query("DELETE FROM `d_tokeyvalues` Where proid=".formatnumber($pid),$$link);
mysql_query("DELETE FROM `d_attr` Where proid=".formatnumber($pid),$$link);
mysql_query("DELETE FROM `products` Where products_id=".formatnumber($osid),$$link);
mysql_query("DELETE FROM `products_attributes` Where products_id=".formatnumber($osid),$$link);
mysql_query("DELETE FROM `products_description` Where products_id=".formatnumber($osid),$$link);
mysql_query("DELETE FROM `products_properties` Where products_id=".formatnumber($osid),$$link);
mysql_query("DELETE FROM `products_to_categories` Where products_id=".formatnumber($osid),$$link);
if($qtpro)mysql_query("DELETE FROM `products_stock` Where products_id=".formatnumber($osid),$$link);
mysql_query("DELETE FROM `specials` Where products_id=".formatnumber($osid),$$link);

}

function connector_language($link = 'db_link')
{
global $$link;
$result = mysql_query('SELECT languages_id, name FROM `languages` Order by sort_order',$$link);
if (!$result) {
    die('Could not query:' . mysql_error());
}
if(mysql_num_rows($result)>0)
{
echo '<table border=0 cellspacing=0 cellpadding=4><tr><td>Dil Id</td><td>Dil Adý</td></tr>'."\n";
while ($langs = tep_db_fetch_array($result)) {
echo '<tr>'."\n";
echo '<td><input type="text" value="'.$langs['languages_id'].'" size="5" disabled="disabled"/></td>'."\n";
echo '<td><input type="text" value="'.$langs['name'].'" size="10" disabled="disabled" /></td>'."\n";
echo '</tr>'."\n";
}
echo '</table>'."\n";
}
return true;
}

function connector_tax($link = 'db_link')
{
global $$link;
$result = mysql_query('SELECT tc.tax_class_id, tax_class_title, tax_rate, tax_description FROM `tax_class` tc LEFT JOIN `tax_rates` tr ON tc.tax_class_id = tr.tax_class_id',$$link);
if (!$result) {
    die('Could not query:' . mysql_error());
}
if(mysql_num_rows($result)>0)
{
echo '<table border=0 cellspacing=0 cellpadding=4><tr><td>Sistem Id</td><td>Vergi Adý</td><td>Vergi Oraný</td><td>Açýklama</td></tr>'."\n";
while ($taxs = tep_db_fetch_array($result)) {
echo '<tr>'."\n";
echo '<td><input type="text" value="'.$taxs['tax_class_id'].'" size="5" disabled="disabled"/></td>'."\n";
echo '<td><input type="text" value="'.$taxs['tax_class_title'].'" size="10" disabled="disabled" /></td>'."\n";
echo '<td><input type="text" value="'.$taxs['tax_rate'].'" size="8" disabled="disabled" /></td>'."\n";
echo '<td><input type="text" value="'.$taxs['tax_description'].'" size="8" disabled="disabled" /></td>'."\n";
echo '</tr>'."\n";
}
echo '</table>'."\n";
}
return true;
}

function connector_currency($link = 'db_link')
{
global $$link;
$result = mysql_query('SELECT `currencies_id`, `title`, `value` FROM `currencies`',$$link);
if (!$result) {
    die('Could not query:' . mysql_error());
}
if(mysql_num_rows($result)>0)
{
echo '<table border=0 cellspacing=0 cellpadding=4><tr><td>Sistem Id</td><td>Para Birimi Adý</td><td>Para Birimi Deðeri</td></tr>'."\n";
while ($currencys = tep_db_fetch_array($result)) {
echo '<tr>'."\n";
echo '<td><input type="text" value="'.$currencys['currencies_id'].'" size="5" disabled="disabled"/></td>'."\n";
echo '<td><input type="text" value="'.$currencys['title'].'" size="10" disabled="disabled" /></td>'."\n";
echo '<td><input type="text" value="'.$currencys['value'].'" size="8" disabled="disabled" /></td>'."\n";
echo '</tr>'."\n";
}
echo '</table>'."\n";
}
return true;
}

function connector_manufacturer($start,$startos,$ara,$araos,$limit,$limitos,$language,$link = 'db_link')
{
global $PHP_SELF,$$link;
$where = preparewhere($araos,'cs`.`manufacturers_name AS s ; cs`.`manufacturers_id AS n ; cs`.`manufacturers_name AS s ; cd`.`manufacturers_url AS s');

if($where == '')
$sql = 'SELECT count(*) FROM `manufacturers` cs LEFT JOIN `manufacturers_info` cd ON cs.manufacturers_id = cd.manufacturers_id '; //    Where cd.languages_id in ('.join(',',$language).')
else
$sql = 'SELECT count(*) FROM `manufacturers` cs LEFT JOIN `manufacturers_info` cd ON cs.manufacturers_id = cd.manufacturers_id Where '.$where.' ';  //  AND cd.languages_id in ('.join(',',$language).')


$result = mysql_query($sql,$$link);
if (!$result) {
    die('Could not query:' . mysql_error());
}
$nume = 0;
if(mysql_num_rows($result)>0)
{
$nume = mysql_result($result, 0, 0);
}

if($startos > $nume) {
$startos = $nume-$limitos;
}
if(!($limitos > 0)) {
$limitos = 30;
}
paging($nume,$startos,$limitos,'startos',$PHP_SELF.'?process=compare&action=brand&limit='.$limit.'&start='.$start.'&limitos='.$limitos.'&ara='.$ara.'&araos='.$araos);


echo '<table  width="100%" border="0" cellpadding="2" cellspacing="0">'."\n";
echo '<tr><td bgcolor="#9AC1E5"><table width="100%" border="0" cellpadding="3" cellspacing="1"><tr>'."\n";
echo '<td bgcolor="#FFFFFF" height="10"><font face="Georgia, Times New Roman, Times, serif" color="#0099FF">'.CONNECTOR.'</font></td>'."\n";
echo '</tr><tr><td bgcolor="#FFFFFF">'."\n";

if($where == '')
$sql = 'SELECT cs.manufacturers_id,cs.manufacturers_name,manufacturers_url FROM `manufacturers` cs LEFT JOIN `manufacturers_info` cd ON cs.manufacturers_id = cd.manufacturers_id   Limit '.$startos.','.$limitos; //  Where cd.languages_id in ('.join(',',$language).')
else
$sql = 'SELECT cs.manufacturers_id,cs.manufacturers_name,manufacturers_url FROM `manufacturers` cs LEFT JOIN `manufacturers_info` cd ON cs.manufacturers_id = cd.manufacturers_id Where '.$where.'  Limit '.$startos.','.$limitos;  // AND cd.languages_id in ('.join(',',$language).')

$result = mysql_query($sql,$$link);
if (!$result) {
    die('Could not query:' . mysql_error());
}
if(mysql_num_rows($result)>0)
{
echo '<table border=0 cellspacing=0 cellpadding=4><tr><td>Sistem Id</td><td>Marka Adý</td><td>Marka Url</td></tr>'."\n";
while ($categorys = tep_db_fetch_array($result)) {
echo '<tr>'."\n";
echo '<td><input type="text" value="'.$categorys['manufacturers_id'].'" size="5" disabled="disabled"/></td>'."\n";
echo '<td><input type="text" value="'.$categorys['manufacturers_name'].'" size="5" disabled="disabled" /></td>'."\n";
echo '<td><input type="text" value="'.$categorys['manufacturers_url'].'" size="12" disabled="disabled" /></td>'."\n";
echo '</tr>'."\n";
}
echo '</table>'."\n";
}

echo '</td></tr></table></td></tr></table>'."\n";
}

function connector_category($start,$startos,$ara,$araos,$limit,$limitos,$language,$link = 'db_link')
{
global $PHP_SELF,$$link;
$where = preparewhere($araos,'cd`.`categories_name AS s ; cs`.`categories_id AS n ; cs`.`parent_id AS n ; cd`.`categories_name AS s');

if($where == '')
$sql = 'SELECT count(*) FROM `categories` cs LEFT JOIN `categories_description` cd ON cs.categories_id = cd.categories_id Where cd.language_id in ('.join(',',$language).')';
else
$sql = 'SELECT count(*) FROM `categories` cs LEFT JOIN `categories_description` cd ON cs.categories_id = cd.categories_id Where '.$where.' AND cd.language_id in ('.join(',',$language).')';


$result = mysql_query($sql,$$link);
if (!$result) {
    die('Could not query:' . mysql_error());
}
$nume = 0;
if(mysql_num_rows($result)>0)
{
$nume = mysql_result($result, 0, 0);
}

if($startos > $nume) {
$startos = $nume-$limitos;
}
if(!($limitos > 0)) {
$limitos = 30;
}
paging($nume,$startos,$limitos,'startos',$PHP_SELF.'?process=compare&action=category&limit='.$limit.'&start='.$start.'&limitos='.$limitos.'&ara='.$ara.'&araos='.$araos);


echo '<table  width="100%" border="0" cellpadding="2" cellspacing="0">'."\n";
echo '<tr><td bgcolor="#9AC1E5"><table width="100%" border="0" cellpadding="3" cellspacing="1"><tr>'."\n";
echo '<td bgcolor="#FFFFFF" height="10"><font face="Georgia, Times New Roman, Times, serif" color="#0099FF">'.CONNECTOR.'</font></td>'."\n";
echo '</tr><tr><td bgcolor="#FFFFFF">'."\n";

if($where == '')
$sql = 'SELECT cs.categories_id,cs.parent_id,categories_name FROM `categories` cs LEFT JOIN `categories_description` cd ON cs.categories_id = cd.categories_id Where cd.language_id in ('.join(',',$language).')  Limit '.$startos.','.$limitos;
else
$sql = 'SELECT cs.categories_id,cs.parent_id,categories_name FROM `categories` cs LEFT JOIN `categories_description` cd ON cs.categories_id = cd.categories_id Where '.$where.' AND cd.language_id in ('.join(',',$language).')  Limit '.$startos.','.$limitos;

$result = mysql_query($sql,$$link);
if (!$result) {
    die('Could not query:' . mysql_error());
}
if(mysql_num_rows($result)>0)
{
echo '<table border=0 cellspacing=0 cellpadding=4><tr><td>Sistem Id</td><td>Parent Id</td><td>Kategori Adý</td></tr>'."\n";
while ($categorys = tep_db_fetch_array($result)) {
echo '<tr>'."\n";
echo '<td><input type="text" value="'.$categorys['categories_id'].'" size="5" disabled="disabled"/></td>'."\n";
echo '<td><input type="text" value="'.$categorys['parent_id'].'" size="5" disabled="disabled" /></td>'."\n";
echo '<td><input type="text" value="'.$categorys['categories_name'].'" size="12" disabled="disabled" /></td>'."\n";
echo '</tr>'."\n";
}
echo '</table>'."\n";
}

echo '</td></tr></table></td></tr></table>'."\n";
}

function connector_product($start,$startos,$ara,$araos,$limit,$limitos,$language,$link = 'db_link')
{
global $PHP_SELF,$$link;
$where = preparewhere($araos,'cd`.`products_name AS s ; cs`.`products_id AS n ; cs`.`products_model AS s ; cd`.`products_name AS s');

if($where == '')
$sql = 'SELECT count(*) FROM `products` cs LEFT JOIN `products_description` cd ON cs.products_id = cd.products_id Where cd.language_id in ('.join(',',$language).')';
else
$sql = 'SELECT count(*) FROM `products` cs LEFT JOIN `products_description` cd ON cs.products_id = cd.products_id Where '.$where.' AND cd.language_id in ('.join(',',$language).')';


$result = mysql_query($sql,$$link);
if (!$result) {
    die('Could not query:' . mysql_error());
}
$nume = 0;
if(mysql_num_rows($result)>0)
{
$nume = mysql_result($result, 0, 0);
}

if($startos > $nume) {
$startos = $nume-$limitos;
}
if(!($limitos > 0)) {
$limitos = 30;
}
paging($nume,$startos,$limitos,'startos',$PHP_SELF.'?process=compare&action=product&limit='.$limit.'&start='.$start.'&limitos='.$limitos.'&ara='.$ara.'&araos='.$araos);


echo '<table  width="100%" border="0" cellpadding="2" cellspacing="0">'."\n";
echo '<tr><td bgcolor="#9AC1E5"><table width="100%" border="0" cellpadding="3" cellspacing="1"><tr>'."\n";
echo '<td bgcolor="#FFFFFF" height="10"><font face="Georgia, Times New Roman, Times, serif" color="#0099FF">'.CONNECTOR.'</font></td>'."\n";
echo '</tr><tr><td bgcolor="#FFFFFF">'."\n";

if($where == '')
$sql = 'SELECT cs.products_id,cs.products_model,products_name FROM `products` cs LEFT JOIN `products_description` cd ON cs.products_id = cd.products_id Where cd.language_id in ('.join(',',$language).')  Limit '.$startos.','.$limitos;
else
$sql = 'SELECT cs.products_id,cs.products_model,products_name FROM `products` cs LEFT JOIN `products_description` cd ON cs.products_id = cd.products_id Where '.$where.' AND cd.language_id in ('.join(',',$language).')  Limit '.$startos.','.$limitos;

$result = mysql_query($sql,$$link);
if (!$result) {
    die('Could not query:' . mysql_error());
}
if(mysql_num_rows($result)>0)
{
echo '<table border=0 cellspacing=0 cellpadding=4><tr><td>Sistem Id</td><td>Ürün Kodu</td><td>Ürün Adý</td></tr>'."\n";
while ($categorys = tep_db_fetch_array($result)) {
echo '<tr>'."\n";
echo '<td><input type="text" value="'.$categorys['products_id'].'" size="5" disabled="disabled"/></td>'."\n";
echo '<td><input type="text" value="'.$categorys['products_model'].'" size="10" disabled="disabled" /></td>'."\n";
echo '<td><input type="text" value="'.$categorys['products_name'].'" size="20" disabled="disabled" /></td>'."\n";
echo '</tr>'."\n";
}
echo '</table>'."\n";
}

echo '</td></tr></table></td></tr></table>'."\n";
}

function connector_attribute($start,$startos,$ara,$araos,$limit,$limitos,$language,$link = 'db_link')
{
global $PHP_SELF,$$link;
$where = preparewhere($araos,'pv`.`products_options_values_name AS s ; p`.`products_id AS n ; pk`.`products_options_name AS s ; pk`.`products_options_id AS n ; pv`.`products_options_values_name AS s ; pv`.`products_options_values_id AS n');

if($where == '')
$sql = 'SELECT count(*) FROM `products_attributes` p LEFT JOIN `products_options` pk ON p.options_id=pk.products_options_id LEFT JOIN `products_options_values` pv ON p.options_values_id = pv.products_options_values_id  Where pk.language_id in ('.join(',',$language).') and pv.language_id in ('.join(',',$language).')';
else
$sql = 'SELECT count(*) FROM `products_attributes` p LEFT JOIN `products_options` pk ON p.options_id=pk.products_options_id LEFT JOIN `products_options_values` pv ON p.options_values_id = pv.products_options_values_id  Where '.$where.' AND pk.language_id in ('.join(',',$language).') and pv.language_id in ('.join(',',$language).')';


$result = mysql_query($sql,$$link);
if (!$result) {
    die('Could not query:' . mysql_error());
}
$nume = 0;
if(mysql_num_rows($result)>0)
{
$nume = mysql_result($result, 0, 0);
}

if($startos > $nume) {
$startos = $nume-$limitos;
}
if(!($limitos > 0)) {
$limitos = 30;
}
paging($nume,$startos,$limitos,'startos',$PHP_SELF.'?process=compare&action=option&limit='.$limit.'&start='.$start.'&limitos='.$limitos.'&ara='.$ara.'&araos='.$araos);


echo '<table  width="100%" border="0" cellpadding="2" cellspacing="0">'."\n";
echo '<tr><td bgcolor="#9AC1E5"><table width="100%" border="0" cellpadding="3" cellspacing="1"><tr>'."\n";
echo '<td bgcolor="#FFFFFF" height="10"><font face="Georgia, Times New Roman, Times, serif" color="#0099FF">'.CONNECTOR.'</font></td>'."\n";
echo '</tr><tr><td bgcolor="#FFFFFF">'."\n";

if($where == '')
$sql = 'SELECT p.products_id as pid,pk.products_options_id as kid,pk.products_options_name as kname, pv.products_options_values_id as vid,pv.products_options_values_name as vname FROM `products_attributes` p LEFT JOIN `products_options` pk ON p.options_id=pk.products_options_id LEFT JOIN `products_options_values` pv ON p.options_values_id = pv.products_options_values_id   Where pk.language_id in ('.join(',',$language).') and pv.language_id in ('.join(',',$language).')  Limit '.$startos.','.$limitos;
else
$sql = 'SELECT p.products_id as pid,pk.products_options_id as kid,pk.products_options_name as kname, pv.products_options_values_id as vid,pv.products_options_values_name as vname FROM `products_attributes` p LEFT JOIN `products_options` pk ON p.options_id=pk.products_options_id LEFT JOIN `products_options_values` pv ON p.options_values_id = pv.products_options_values_id   Where '.$where.' AND pk.language_id in ('.join(',',$language).') and pk.language_id in ('.join(',',$language).')  Limit '.$startos.','.$limitos;

$result = mysql_query($sql,$$link);
if (!$result) {
    die('Could not query:' . mysql_error());
}
if(mysql_num_rows($result)>0)
{
echo '<table border=0 cellspacing=0 cellpadding=4><tr><td>Ürün Id</td><td>Anahtar</td><td>Id#1</td><td>Deðer</td><td>Id#2</td></tr>'."\n";
while ($categorys = tep_db_fetch_array($result)) {
echo '<tr>'."\n";
echo '<td><input type="text" value="'.$categorys['pid'].'" size="3" disabled="disabled"/></td>'."\n";
echo '<td><input type="text" value="'.$categorys['kname'].'" size="6" disabled="disabled"/></td>'."\n";
echo '<td><input type="text" value="'.$categorys['kid'].'" size="3" disabled="disabled"/></td>'."\n";
echo '<td><input type="text" value="'.$categorys['vname'].'" size="6" disabled="disabled" /></td>'."\n";
echo '<td><input type="text" value="'.$categorys['vid'].'" size="3" disabled="disabled" /></td>'."\n";
echo '</tr>'."\n";
}
echo '</table>'."\n";
}

echo '</td></tr></table></td></tr></table>'."\n";
}

function connector_property($start,$startos,$ara,$araos,$limit,$limitos,$language,$link = 'db_link')
{
global $PHP_SELF,$$link;

$where = preparewhere($araos,'pv`.`products_options_values_name AS s ; pk`.`categories_options_id AS n ; pk`.`products_options_name AS s ; pk`.`products_options_id AS n ; pv`.`products_options_values_name AS s ; pv`.`products_options_values_id AS n');

if($where == '')
$sql = 'SELECT count(*) FROM `products_prop_options_values_to_products_prop_options` p LEFT JOIN `products_prop_options` pk ON p.products_options_id=pk.products_options_id LEFT JOIN `products_prop_options_values` pv ON p.products_options_values_id = pv.products_options_values_id  Where pk.language_id in ('.join(',',$language).') and pv.language_id in ('.join(',',$language).')';
else
$sql = 'SELECT count(*) FROM `products_prop_options_values_to_products_prop_options` p LEFT JOIN `products_prop_options` pk ON p.products_options_id=pk.products_options_id LEFT JOIN `products_prop_options_values` pv ON p.products_options_values_id = pv.products_options_values_id  Where '.$where.' AND pk.language_id in ('.join(',',$language).') AND pv.language_id in ('.join(',',$language).')';


$result = mysql_query($sql,$$link);
if (!$result) {
    die('Could not query:' . mysql_error());
}
$nume = 0;
if(mysql_num_rows($result)>0)
{
$nume = mysql_result($result, 0, 0);
}

if($startos > $nume) {
$startos = $nume-$limitos;
}
if(!($limitos > 0)) {
$limitos = 30;
}
paging($nume,$startos,$limitos,'startos',$PHP_SELF.'?process=compare&action=feature&limit='.$limit.'&start='.$start.'&limitos='.$limitos.'&ara='.$ara.'&araos='.$araos);


echo '<table  width="100%" border="0" cellpadding="2" cellspacing="0">'."\n";
echo '<tr><td bgcolor="#9AC1E5"><table width="100%" border="0" cellpadding="3" cellspacing="1"><tr>'."\n";
echo '<td bgcolor="#FFFFFF" height="10"><font face="Georgia, Times New Roman, Times, serif" color="#0099FF">'.CONNECTOR.'</font></td>'."\n";
echo '</tr><tr><td bgcolor="#FFFFFF">'."\n";

if($where == '')
$sql = 'SELECT pk.categories_options_id as cid,pk.products_options_id as kid,pk.products_options_name as kname, pv.products_options_values_id as vid,pv.products_options_values_name as vname FROM `products_prop_options_values_to_products_prop_options` p LEFT JOIN `products_prop_options` pk ON p.products_options_id=pk.products_options_id LEFT JOIN `products_prop_options_values` pv ON p.products_options_values_id = pv.products_options_values_id Where pk.language_id in ('.join(',',$language).') AND pv.language_id in ('.join(',',$language).')  Limit '.$startos.','.$limitos;
else
$sql = 'SELECT pk.categories_options_id as cid,pk.products_options_id as kid,pk.products_options_name as kname, pv.products_options_values_id as vid,pv.products_options_values_name as vname FROM `products_prop_options_values_to_products_prop_options` p LEFT JOIN `products_prop_options` pk ON p.products_options_id=pk.products_options_id LEFT JOIN `products_prop_options_values` pv ON p.products_options_values_id = pv.products_options_values_id Where '.$where.' AND pk.language_id in ('.join(',',$language).') AND pv.language_id in ('.join(',',$language).')  Limit '.$startos.','.$limitos;

$result = mysql_query($sql,$$link);
if (!$result) {
    die('Could not query:' . mysql_error());
}
if(mysql_num_rows($result)>0)
{
echo '<table border=0 cellspacing=0 cellpadding=4><tr><td>Kategori</td><td>Anahtar</td><td>Id#1</td><td>Deðer</td><td>Id#2</td></tr>'."\n";
while ($categorys = tep_db_fetch_array($result)) {
echo '<tr>'."\n";
echo '<td><input type="text" value="'.$categorys['cid'].'" size="3" disabled="disabled"/></td>'."\n";
echo '<td><input type="text" value="'.$categorys['kname'].'" size="6" disabled="disabled"/></td>'."\n";
echo '<td><input type="text" value="'.$categorys['kid'].'" size="3" disabled="disabled"/></td>'."\n";
echo '<td><input type="text" value="'.$categorys['vname'].'" size="6" disabled="disabled" /></td>'."\n";
echo '<td><input type="text" value="'.$categorys['vid'].'" size="3" disabled="disabled" /></td>'."\n";
echo '</tr>'."\n";
}
echo '</table>'."\n";
}

echo '</td></tr></table></td></tr></table>'."\n";
}
/*0016#1 eklenti end*/
/*0020#1 eklenti start*/
function connector_pairing_category($link = 'db_link'){
global $$link,$installed_modules;

echo '<table width="100%" border="0" cellspacing="0" cellpadding="10"><tr><td valign=top>'."\n";
if(isset($_POST["kaydet"]) && (!empty($_POST["categoryids"]))){
foreach(explode(',',$_POST["categoryids"]) as $categoryid){
mysql_query("UPDATE `d_categories` SET `osid`= '".tep_db_input($_POST["categoryosid_".$categoryid])."' Where id='".tep_db_input($categoryid)."'",$$link);
}
}

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

$querystr = $PHP_SELF.'?process=pairing&action=category'.((isset($_GET['module']))?'&module='.$_GET['module']:'').'&start='.$start.'&limit='.$limit.'&ara='.$ara;
$vendor = ((isset($_GET['module']))?' AND d.`vendor`='.vendor($_GET['module']):'');
search($querystr,'ara','search',$ara);

$where = preparewhere($ara,'d`.`cname AS s ; d`.`id AS n ; d`.`cname AS s ; d`.`osid AS n ; cd`.`categories_id AS n ; cd`.`categories_name AS s');

if ($where == '')
$sql = 'SELECT count(*) FROM `categories` c, `categories_description` cd, `d_categories` d WHERE (d.`cname` LIKE CONCAT( \'%\', cd.`categories_name` , \'%\' ) OR  cd.`categories_name` LIKE CONCAT( \'%\', d.`cname` , \'%\' ) )'.$vendor.' AND cd.`categories_id` = c.`categories_id`';
else
$sql = 'SELECT count(*) FROM `categories` c, `categories_description` cd, `d_categories` d WHERE (d.`cname` LIKE CONCAT( \'%\', cd.`categories_name` , \'%\' ) OR  cd.`categories_name` LIKE CONCAT( \'%\', d.`cname` , \'%\' ) )'.$vendor.' AND cd.`categories_id` = c.`categories_id` AND '.$where;

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
paging($nume,$start,$limit,'start',$PHP_SELF.'?process=pairing&action=category'.((isset($_GET['module']))?'&module='.$_GET['module']:'').'&limit='.$limit.'&ara='.$ara);
$currenyids = array();
if ($where == '')
$sql = '
SELECT d.`id`,
(SELECT `vdname` FROM `d_vendors` WHERE `id` = d.`vendor` LIMIT 1) AS vendor,
(SELECT `cname` FROM `d_categories` WHERE `code` = d.`parentcode` LIMIT 1) AS parentname,
d.`cname`,
d.`osid`,
cd.`categories_id`,
cd.`categories_name`,
(SELECT `categories_name` FROM `categories_description` WHERE `categories_id`=c.`parent_id` LIMIT 1) AS oscparentname,
d.`parentcode`,
c.`parent_id`
FROM `categories` c, `categories_description` cd, `d_categories` d
WHERE (d.`cname` LIKE CONCAT( \'%\', cd.`categories_name` , \'%\' ) OR  cd.`categories_name` LIKE CONCAT( \'%\', d.`cname` , \'%\' ) )'.$vendor.' AND cd.`categories_id` = c.`categories_id`
ORDER BY cd.`categories_name` , oscparentname ASC Limit '.$start.','.$limit;
else
$sql = '
SELECT d.`id`,
(SELECT `vdname` FROM `d_vendors` WHERE `id` = d.`vendor` LIMIT 1) AS vendor,
(SELECT `cname` FROM `d_categories` WHERE `code` = d.`parentcode` LIMIT 1) AS parentname,
d.`cname`,
d.`osid`,
cd.`categories_id`,
cd.`categories_name`,
(SELECT `categories_name` FROM `categories_description` WHERE `categories_id`=c.`parent_id` LIMIT 1) AS oscparentname,
d.`parentcode`,
c.`parent_id`
FROM `categories` c, `categories_description` cd, `d_categories` d
WHERE (d.`cname` LIKE CONCAT( \'%\', cd.`categories_name` , \'%\' ) OR  cd.`categories_name` LIKE CONCAT( \'%\', d.`cname` , \'%\' ) )'.$vendor.' AND cd.`categories_id` = c.`categories_id` AND '.$where.'
ORDER BY cd.`categories_name` , oscparentname ASC Limit '.$start.','.$limit;
$result = mysql_query($sql,$$link);
if (!$result) {
    die('Could not query:' . mysql_error());
}
if(mysql_num_rows($result)>0)
{
echo '<table  width="100%" border="0" cellpadding="0" cellspacing="0"><tr>'."\n";
echo '<td bgcolor="#9AC1E5"><table width="100%" border="0" cellpadding="3" cellspacing="1">'."\n";
echo '<tr><td bgcolor="#FFFFFF">'."\n";
echo '<table border=0 cellspacing=0 cellpadding=4>
<tr><td colspan="5" align="center" bgcolor="#EFEFEF"><strong>Connector</strong></td><td>&nbsp;</td><td colspan="3" align="center" bgcolor="#BEEBFF"><strong>Oscommerce</strong></td></tr>
<tr><td>Num#1</td><td>Tedarikci</td><td>Üst Kategori</td><td>Kategori Adý#2</td><td>->ID#3</td><td>&nbsp;</td><td>ID#4</td><td>Kategori Adý#5</td><td>Üst Kategori</td></tr>'."\n";
$valids = array();
while ($categorys = tep_db_fetch_array($result)) {
echo '<tr>'."\n";
echo '<td><input type="text" value="'.$categorys['id'].'" size="2" disabled="disabled"/></td>'."\n";
echo '<td><input type="text" value="'.$categorys['vendor'].'" size="3" disabled="disabled"/></td>'."\n";
echo '<td><input type="text" value="'.$categorys['parentname'].'" size="12" disabled="disabled"/></td>'."\n";
echo '<td><input type="text" value="'.$categorys['cname'].'" size="14" disabled="disabled"/></td>'."\n";
if(!in_array($categorys['id'],$valids)){
echo '<td><input name="categoryosid_'.$categorys['id'].'" type="text" value="'.$categorys['osid'].'" size="3" /></td>'."\n";
$valids[] = $categorys['id'];
$categoryids[] = $categorys['id'];
}else
echo '<td><input type="text" value="'.$categorys['osid'].'" size="3" disabled="disabled"/></td>'."\n";
echo '<td>&nbsp;</td>'."\n";
echo '<td><input type="text" value="'.$categorys['categories_id'].'" size="3" disabled="disabled"/></td>'."\n";
echo '<td><input type="text" value="'.$categorys['categories_name'].'" size="12" disabled="disabled"/></td>'."\n";
echo '<td><input type="text" value="'.$categorys['oscparentname'].'" size="12" disabled="disabled"/></td>'."\n";
echo '</tr>'."\n";
}
echo '</table>'."\n";

echo '</td></tr></table></td></tr></table><br/>'."\n";
}
if (count($categoryids)>0){
echo '<input name="categoryids" type="hidden" id="categoryids" value="'.join(',',$categoryids).'" size="15"/>'."\n";
echo '<input type="submit" name="kaydet" value="Kaydet">';
}
echo '</td></tr></tbody></form></table>'."\n";
echo '</td></tr></table>'."\n";
}
/*0020#1 eklenti end*/
?>