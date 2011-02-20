<?php
/*
  $Id$

  Tedarikçi Entegrasyonu, Açýk Kaynak Entegrasyon Çözümüdür
  http://www.duzgun.com

  Released under the GNU General Public License
*/

class tedarikcim
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

    function tedarikcim( )
    {
        $this->code = "tedarikcim";
        $this->title = "Tedarikcim DEMO Entegrasyonu Modülü";
        $this->description = "";
        $this->enabled     = TEDARIKCIM_STATUS == "True" ? true : false;
        $this->sort_order  = 100;   //bu deger her modülde farkli olmak zorundadir
        $this->product     = true;
        $this->category    = false;
        $this->feature     = false;
        $this->option      = false;
        $this->brand       = false;
        $this->price       = false;
        $this->stock       = false;
        $this->xml         = array();
        $this->xmlperbyte  = 0;
        $this->version     = '1.0.0';
    }
    function check( )
    {
        return true;
    }
    function keys( )
    {
        return "TEDARIKCIM_STATUS";
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

    $baslangicetiketi   = 'urun';
    $urun_kodu          = 'ID';
    $urun_adi           = 'Adi';
    $kategori_kodu      = 'KategoriID';
    $kategori_adi       = 'KategoriAdi';
    $urun_fiyati        = 'Fiyat';
    $parabirimi         = 'kur';
    $urun_stogu         = 'Stok';
    $urun_aciklamasi    = 'Aciklama';
    $urun_resmi         = 'Resim';

    $xmlData = simplexml_load_file($xml_directory.$this->code."/urunler.xml");
    foreach($xmlData->{$baslangicetiketi} as $product)
    {
        $this->category = array('code' => $product->{$kategori_kodu},
                                 'cname' => $product->{$kategori_adi},
                                 'parentcode' => '');
        core_category();
        $this->product = array('pcode' => $product->{$urun_kodu},
                                'pname' => $product->{$urun_adi},
                                'price1' => $product->{$urun_fiyati},
                                'currency' => $product->{$parabirimi},
                                'tax' => 'KDV DAHIL',
                                'stock' => $product->{$urun_stogu},
                                'catid' => $product->{$kategori_kodu},
                                'brand' => 'Oscommerce.TC',
                                'desc' => $product->{$urun_aciklamasi});
        $pid = core_product();

        $this->image = array(  'pid' => $pid,
                                        'image'=>$product->{$urun_resmi},
                                        'thumb'=>'',
                                        'number' =>0
                                        );
        core_image_setopt(true,$pid);
        core_image();
        core_image_setopt(false,$pid); 
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
    global $xml_directory;

    }
    function stock( )
    {
    global $xml_directory;

    }
} 
?>
