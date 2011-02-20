<?php
/*
  $Id$ Yavuz Yasin D�zg�n

  Tedarik�i Entegrasyonu, A��k Kaynak Entegrasyon ��z�m�d�r
  http://www.duzgun.com

  Copyright (c) 2008 Duzgun.com

  Released under the GNU General Public License
*/

if(!isset($_GET['process'])){
/**/

$input = <<< EOPA
<?xml version="1.0" encoding="ISO-8859-9"?>
<urun_listesi>
<urun>
<kategori id=""></kategori>
<urun_kodu></urun_kodu>
<urun_adi></urun_adi>
<fiyat></fiyat>
<pb></pb>
<stok></stok>
<marka></marka>
<resim></resim>
</urun>
</urun_listesi>
EOPA;

/**/

$ArrayKeys = array();
$ArrayValues = array();
$ArrayAttributes = array();
$DataStruct = simplexml_load_string(trim($input));
$DataArray  = array();
$DataDegisken = "";
convertXmlObjToArr($DataStruct,$DataArray);
CreateModuleRoot();
echo "<br><br>";
echo "<b>Simple XML Taglar�n� �fade Eden Php De�i�kenleri</b>";
echo "<table cellpadding=\"0\" cellspacing=\"0\" border=\"0\">\n";
foreach($ArrayKeys as $key=>$val)
{
$DataDegisken .= "        ".$key . "=" . $val . "
";
echo "<tr><td>".$key." </td><td>= ".$val."</td></tr>\n";
}
echo "</table>";
echo "<br>";
echo "<b>Php Dilinin sundu�u bilgilerin okunmas� i�in gerekli Simple XML de�i�kenleri</b>";
echo "<table cellpadding=\"0\" cellspacing=\"0\" border=\"0\">\n";
$ArrayValuesSample = array();
foreach($ArrayValues as $key=>$val)
{
$ArrayValuesSample[$val]=1;
}
foreach($ArrayValuesSample as $key=>$val)
{
echo "<tr><td>".$key."</td></tr>\n";

}
/*
foreach($ArrayValues as $key=>$val)
{
echo "<tr><td>".$val."</td></tr>\n";

}
*/
echo "</table>";
echo "<br>";
echo "<b>Mod�l�n olu�turulmas� i�in yap�lmas� gereken de�i�ken tan�mlar�</b><br>";
echo "XML bulunmayan etiketleri l�tfen bo� b�rak�n�z.";
?>
<form id="form1" name="form1" method="post" action="module_wizard.php?process=2">
<table width="935" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td colspan="2">L�tfen ba�lang�� etiketini yaz�n�z. Bu etiket XML d�k�manlar�nda Herbir �r�n�n bulundu�u etikettir.  Bu etiket <?php echo $ArrayValues[0];?> olabilir. Yukar�da yaz�l� olan simple XML de�i�kenleri listesinde yaz�ld��� gibi yazmal�s�n�z. Bu de�i�kenler $product-&gt; s�z dizimi ile ba�lamaktad�r. </td>
  </tr>
  <tr>
    <td width="168">Bailang�� Etiketi</td>
    <td width="767"><label for="starttag"></label>
    <input type="text" name="starttag" size="50" id="starttag" /></td>
  </tr>
  <tr>
    <td colspan="2">A�a��daki tan�mlar i�in yukar�daki simple XML de�i�kenleri listesinden uygun olanlar� yaz�n�z. Bu de�i�kenler $product-&gt; s�z dizimi ile ba�lamaktad�r. <br />
    Uygun yoksa bo� b�rak�n�z.</td>
  </tr>
  <tr>
    <td>Kategori ID</td>
    <td><input type="text" name="catidtag" size="50" id="catidtag" /></td>
  </tr>
  <tr>
    <td>Kategori Ad�</td>
    <td><input type="text" name="catnametag" size="50" id="catnametag" /></td>
  </tr>
  <tr>
    <td>Alt Kategori IDsi </td>
    <td><input type="text" name="subcatidtag" size="50" id="subcatidtag" /></td>
  </tr>
  <tr>
    <td>Alt Kategori Ad�</td>
    <td><input type="text" name="subcatnametag" size="50" id="subcatnametag" /></td>
  </tr>
  <tr>
    <td>�r�n Kodu</td>
    <td><input type="text" name="productcode" size="50" id="productcode" /></td>
  </tr>
  <tr>
    <td>�r�n Ad�</td>
    <td><input type="text" name="productname" size="50" id="productname" /></td>
  </tr>
  <tr>
    <td>�r�n Fiyat�</td>
    <td><input type="text" name="productprice" size="50" id="productprice" /></td>
  </tr>
  <tr>
    <td>�r�n�n Para birimi</td>
    <td><input type="text" name="productcurry" size="50" id="productcurry" /></td>
  </tr>
  <tr>
    <td>�r�n�n Vergi T�r�</td>
    <td><input type="text" name="producttax" size="50" id="producttax" /></td>
  </tr>
  <tr>
    <td>�r�n Sto�u</td>
    <td><input type="text" name="productstock" size="50" id="productstock" /></td>
  </tr>
  <tr>
    <td>�r�n Markas�</td>
    <td><input type="text" name="productbrand" size="50" id="productbrand" /></td>
  </tr>
  <tr>
    <td>�r�n A��klamas�</td>
    <td><input type="text" name="productdesc" size="50" id="productdesc" /></td>
  </tr>
  <tr>
    <td>�r�n Resmi</td>
    <td><input type="text" name="productimage" size="50" id="productimage" /></td>
  </tr>
  <tr>
    <td colspan="2">Mod�l ad� olarak tedarik�inizin ad�n� yazabilirsiniz. Mod�l isminde bo�luk,say�sal ifade ve alfan�merik karakter kullanmay�n�z. sadece k���k harf ve a-z aras� kullan�n�z. �rn:duzgunbilisim</td>
  </tr>
  <tr>
    <td>Mod�l Ad�n� Yaz�n�z</td>
    <td><input type="text" name="modulename" id="modulename" value="demo" /></td>
  </tr>
</table>
<p>
<input type="hidden" name="degiskenler" id="degiskenler" value="<?php echo $DataDegisken; ?>" />
  <input name="Submit" type="submit" id="Submit" value="Mod�l Olu�tur" />
</p>
</form>
<?php
}else if($_GET['process']==2)
{
$starttag = isset($_POST['starttag'])?$_POST['starttag']:'';
$catidtag = isset($_POST['catidtag'])?str_replace($starttag,'$product',$_POST['catidtag']):'';
$catnametag = isset($_POST['catnametag'])?str_replace($starttag,'$product',$_POST['catnametag']):'';
$subcatidtag = isset($_POST['subcatidtag'])?str_replace($starttag,'$product',$_POST['subcatidtag']):'';
$subcatnametag = isset($_POST['subcatnametag'])?str_replace($starttag,'$product',$_POST['subcatnametag']):'';
$productcode = isset($_POST['productcode'])?str_replace($starttag,'$product',$_POST['productcode']):'';
$productname = isset($_POST['productname'])?str_replace($starttag,'$product',$_POST['productname']):'';
$productprice = isset($_POST['productprice'])?str_replace($starttag,'$product',$_POST['productprice']):'';
$productcurry = isset($_POST['productcurry'])?str_replace($starttag,'$product',$_POST['productcurry']):'';
$producttax = isset($_POST['producttax'])?str_replace($starttag,'$product',$_POST['producttax']):'';
$productstock = isset($_POST['productstock'])?str_replace($starttag,'$product',$_POST['productstock']):'';
$productbrand = isset($_POST['productbrand'])?str_replace($starttag,'$product',$_POST['productbrand']):'';
$productdesc = isset($_POST['productdesc'])?str_replace($starttag,'$product',$_POST['productdesc']):'';
$productimage = isset($_POST['productimage'])?str_replace($starttag,'$product',$_POST['productimage']):'';

if($catnametag=='')$catnametag="''";

$catid = "";
if($catidtag!='')$catid=$catidtag;
else
$catid=$catnametag;


$subcatid = "";
if($subcatidtag!='')$subcatid=$subcatidtag;
else
$subcatid=$subcatnametag;



$baslangictagi = substr($starttag,10);

$degiskenler = isset($_POST['degiskenler'])?stripslashes($_POST['degiskenler']):'';
$ModuleName = isset($_POST['modulename'])?$_POST['modulename']:'';
$ModuleName = strtolower($ModuleName);
if(!preg_match('/^([A-Za-z])+$/',$ModuleName))
{
$ModuleName = "Demo";
}
$ModuleName1 = ucfirst($ModuleName);
$ModuleName2 = strtoupper($ModuleName);
?>
A�a��da olu�turdu�unuz &quot;<?php echo $ModuleName1;?> Entegrasyon Mod�l�&quot; n� <?php echo $ModuleName;?>.php ad�yla modules klas�r�ne kopyalayarak kullanabilirsiniz. Mod�l�n T.E yaz�l�m�n�zda �al��abilmesi i�in aktivasyon kodunu almal�s�n�z. �cretsiz ve Demo s�r�mlerinde aktivasyon kodu �cretsizdir. �cretsiz s�r�mlerde T.E ile birlikte sadece 1 mod�l kullan�labilir. �cretli T.E yaz�l�m� ise s�n�rs�z mod�l eklenebilir. Herhangi bir sorunla kar��la�t���n�zda <a href="http://www.osommerce.tc/ModulKlavuzu.rtf">mod�l yaz�m klavuzu</a>'ndan faydalanabilirsiniz.
<table width="0" border="1">
  <tr>
    <td width="799" height="433" valign="top">
<?php
ob_start();
?>
&lt;?php
//Released under the GNU Lesser General Public License
//Mod�l i�indeki kodlar�n t�m�nden kullan�c�s� sorumludur. Hukuka ayk�r� bi�imde kullan�l�mas� yasakt�r.
//Generated time is <?php echo date("F j, Y, g:i a");?>

//<?php echo 'oscommerce.tc, '.$_SERVER["REMOTE_ADDR"].' IP li ziyaret�i taraf�ndan otomatik olu�turulmu� 
//&quot;'.$ModuleName1.' Entegrasyon Mod�l�&quot; php s�n�f�ndan olu�abilecek b�t�n telif haklar�ndan feragat eder.' ;?>

class <?php echo $ModuleName;?>

      {
      var $code;
      var $title;
      var $description;
      var $enabled;
      var $product;
      var $category;
      var $feature;
      var $option;
      var $brand;
      var $price;
      var $stock;
      var $xml;
      var $xmlperbyte;
      function <?php echo $ModuleName;?>( )
        {
        $this-&gt;code = &quot;<?php echo $ModuleName;?>&quot;;
        $this-&gt;title = &quot;<?php echo $ModuleName1;?> Entegrasyon Mod�l�&quot;;
        $this-&gt;description = &quot;&quot;;
        $this-&gt;enabled = <?php echo $ModuleName2;?>_STATUS == &quot;True&quot; ? true : false;
        $this-&gt;sort_order = 100;
        $this-&gt;product  = true;
        $this-&gt;category = false;
        $this-&gt;feature  = false;
        $this-&gt;option   = false;
        $this-&gt;brand    = false;
        $this-&gt;price    = false;
        $this-&gt;stock    = false;
        $this-&gt;xml      = array('' =&gt; 'urunler.xml'); //L�tfen mod�l yazma klavuzuna bak�n�z.
        $this-&gt;xmlperbyte = 0;
        $this-&gt;version = '1.0.0';
        }
        function check( )
        {
        return true;
        }
        function keys( )
        {
        return &quot;<?php echo $ModuleName2;?>_STATUS&quot;;
        }
        function install( )
        {
        }
        function feature( )
        {
        global $xml_directory;
        }
        function product( )
        {
        global $xml_directory;

<?php
echo $degiskenler;
?>

        $xmlData = simplexml_load_file($xml_directory.$this->code."/urunler.xml");
        foreach($xmlData-><?php echo $baslangictagi;?> as $product)
        {
        $catid = <?php echo $catid;?>;
        $this->category = array('code' => $catid,
                                 'cname' => <?php echo $catnametag;?>,
                                 'parentcode' => '');
        core_category();

<?php
if($subcatnametag!=""){
?>
        $subcatid = <?php echo $subcatidtag;?>;
        $this->category = array('code' => $subcatid,
                                 'cname' => <?php echo $subcatnametag;?>,
                                 'parentcode' => $catid
                                 );
        $catid = <?php echo $catid;?>;

        core_category();
<?php } ?>

        $this->product = array();
<?php if($productcode!=''){?>
        $this->product['pcode']     = <?php echo $productcode; ?>;
<?php } ?>
<?php if($productname!=''){?>
        $this->product['pname']     = <?php echo $productname; ?>;
<?php } ?>
<?php if($productprice!=''){?>
        $this->product['price1']    = <?php echo $productprice; ?>;
<?php } ?>
<?php if($productcurry!=''){?>
        $this->product['currency']  = <?php echo $productcurry; ?>;
<?php } ?>
<?php if($producttax!=''){?>
        $this->product['tax']       = <?php echo $producttax; ?>;
<?php } ?>
<?php if($productstock!=''){?>
        $this->product['stock']     = <?php echo $productstock; ?>;
<?php } ?>
<?php if($productbrand!=''){?>
        $this->product['brand']     = <?php echo $productbrand; ?>;
<?php } ?>
<?php if($productdesc!=''){?>
        $this->product['desc']      = <?php echo $productdesc; ?>;
<?php } ?>
<?php if($productcode!=''){?>
        $this->product['catid']     = $catid;
<?php } ?>

        $pid = core_product();
<?php if($productimage!=''){?>
        $this->image = array(   'pid' => $pid,
                        'image'=><?php echo $productimage; ?>,
                        'thumb'=>'',
                        'number' =>0
        );
        core_image_setopt(true,$pid);
        core_image();
        core_image_setopt(false,$pid);
<?php } ?>
        }
        }

        function category( )
        {
        global $xml_directory;
        }

        function brand( )
        {
        global $xml_directory;
        }

        function option( )
        {
        global $xml_directory;
        }

        function price( )
        {
        global $xml_directory;>
        }

        function stock( )
        {
        global $xml_directory;
        }
        }

        function geturlencode($str) {
        $tr =array(
                  ' '    => "%20",
                  "\xC4\xB1"    => '�',
                  "\xC4\xB0"    => '�',
                  "\xC4\x9F"    => '�',
                  "\xC4\x9E"    => '�',
                  "\xC3\x9C"    => '�',
                  "\xC3\xBC"    => '�',
                  "\xC3\x87"    => '�',
                  "\xC3\xA7"    => '�',
                  "\xC5\x9E"    => '�',
                  "\xC5\x9F"    => '�',
                  "\xC3\x96"    => '�',
                  "\xC3\xB6"    => '�'
        );
        return strtr($str,$tr);
        }

        function getutf8($str)
        {
        $tr =array(
                  '�'    => "\xC4\xB1",
                  '�'    => "\xC4\xB0",
                  '�'    => "\xC4\x9F",
                  '�'    => "\xC4\x9E",
                  '�'    => "\xC3\x9C",
                  '�'    => "\xC3\xBC",
                  '�'    => "\xC3\x87",
                  '�'    => "\xC3\xA7",
                  '�'    => "\xC5\x9E",
                  '�'    => "\xC5\x9F",
                  '�'    => "\xC3\x96",
                  '�'    => "\xC3\xB6",
                  '�'    => "\xC2\xAE"
         );
         return strtr($str,$tr);
     }
?&gt;
<?php
$content = ob_get_contents();
ob_end_clean();
highlight_string(htmlspecialchars_decode($content));
?>
</p>
</table>
<?php
}
$i=0;

function convertXmlObjToArr($obj, &$arr)
{
$children = $obj->children();
foreach ($children as $elementName => $node)
{
$nextIdx = count($arr);
$arr[$nextIdx] = array();
$arr[$nextIdx]['@name'] = strtolower((string)$elementName);
$arr[$nextIdx]['@attributes'] = array();
$attributes = $node->attributes();
foreach ($attributes as $attributeName => $attributeValue)
{
$attribName = strtolower(trim((string)$attributeName));
$attribVal = trim((string)$attributeValue);
$arr[$nextIdx]['@attributes'][$attribName] = $attribVal;
}
$text = (string)$node;
$text = trim($text);
if (strlen($text) > 0)
{
$arr[$nextIdx]['@text'] = $text;
}
$arr[$nextIdx]['@children'] = array();
convertXmlObjToArr($node, $arr[$nextIdx]['@children']);
}
return;
}
function CreateModuleRoot()
{
global $ArrayKeys,$ArrayValues,$DataArray;
foreach($DataArray as $DataArray1)
{
$i=0;
$name="";
$text="";
$attr=array();
$child=array();
if(isset($DataArray1["@name"]))$name=$DataArray1["@name"];
if(isset($DataArray1["@text"]))$text=$DataArray1["@text"];
if(isset($DataArray1["@attributes"]))$attr=$DataArray1["@attributes"];
if(isset($DataArray1["@children"]))$child=$DataArray1["@children"];
$ArrayKeys['$T'.$i.'_'.$name] = "'".$name."';";
$ArrayValues[] = '$product->{'.'$T'.$i.'_'.$name.'}';
foreach($attr as $attrkey1=>$attrval1)
{
$ArrayKeys['$T'.$i.'_'.$name.'_A_'.$attrkey1] = "'".$attrkey1."';";
$ArrayValues[] = '$product->{'.'$T'.$i.'_'.$name.'}'.'->attributes()->{'.'$T'.$i.'_'.$name.'_A_'.$attrkey1.'}';
}
$names = 'T'.$i.'_'.$name;
$names2 = '{$T'.$i.'_'.$name.'}';
CreateModuleSubs($names,$names2,$child,$i);
}
}
function CreateModuleSubs($names,$names2,$child,$i)
{
global $ArrayKeys,$ArrayValues;
$i++;
$namestr = '';
$names2tr = '';
foreach($child as $DataArray2)
{
$name="";
$text="";
$attr=array();
$child=array();
if(isset($DataArray2["@name"]))$name=$DataArray2["@name"];
if(isset($DataArray2["@text"]))$text=$DataArray2["@text"];
if(isset($DataArray2["@attributes"]))$attr=$DataArray2["@attributes"];
if(isset($DataArray2["@children"]))$child=$DataArray2["@children"];
$namestr = $names.'_T'.$i.'_'.$name;
$names2tr = $names2.'->{$' .$namestr."}";
$ArrayKeys['$'.$namestr] = "'".$name."';";
$ArrayValues[] = '$product->'.$names2tr;
foreach($attr as $attrkey1=>$attrval1)
{
$ArrayKeys['$'.$namestr.'_A_'.$attrkey1] = "'".$attrkey1."';";
$ArrayValues[] = '$product->'.$names2tr.'->attributes()->{'.'$'.$namestr.'_A_'.$attrkey1.'}';
}
CreateModuleSubs($namestr,$names2tr,$child,$i);
}
}


?>
