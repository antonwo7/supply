<?php

class Product
{
    public $major_department;
    public $minor_department;
    public $major_category;
    public $minor_category;
    public $item;
    public $upc, $upc_fixed;
    public $description;
    public $case;
    public $landed_cost, $sale_price, $regular_price;
    public $code, $sku, $stock;
    public $images;
    public $l, $w, $h;
    public $category;
    public $pack, $size, $measure;
    public $error = false;
    public $flags;

    public function __construct($url)
    {
        $this->set_product($url);
    }

    private function set_product($url)
    {
        $product_html = $this->get_product_node($url);


        if ($product_html) {
            $categories_array = explode('>', $product_html->find('#ItemsView_ctrl0_lblDepartments', 0)->innertext);

            $this->major_department = trim($categories_array[0]);
            $this->minor_department = trim($categories_array[1]);
            $this->major_category = trim($categories_array[2]);
            $this->minor_category = trim($categories_array[3]);

            $this->item = trim($product_html->find('#ItemsView_ctrl0_OrderCode', 0)->innertext);
            $this->upc = $this->upc_short(trim($product_html->find('#ItemsView_ctrl0_ctl16', 0)->innertext));
            $this->upc_fixed = $this->upc_convert($this->upc);
            $this->description = trim(strip_tags($product_html->find('.ImageTitle strong', 0)->innertext));

            $details_html = $product_html->find('#ItemsView_ctrl0_DetailsPane', 0)->innertext;
            $this->case = trim(preg_replace('/.*<strong>Case Weight:<\/strong>(.*?)<span.*/si', '$1', $details_html));

            if($product_html->find('#ItemsView_ctrl0_colLandedCost #ItemsView_ctrl0_lblLandedCostLabelAmount', 0)){
                $this->landed_cost = trim(str_replace('$', '', $product_html->find('#ItemsView_ctrl0_colLandedCost #ItemsView_ctrl0_lblLandedCostLabelAmount', 0)->innertext));
            }
            else{
                $this->landed_cost = trim(str_replace('$', '', $product_html->find('#ItemsView_ctrl0_colCost #ItemsView_ctrl0_lblCostLabelAmount', 0)->innertext));
            }
            //$this->landed_cost = trim(str_replace('$', '', $product_html->find('#ItemsView_ctrl0_colLandedCost #ItemsView_ctrl0_lblLandedCostLabelAmount', 0)->innertext));
            $this->sale_price = round($this->landed_cost * 1.35, 2);
            $this->regular_price = round($this->landed_cost * 1.58, 2);

            if ($product_html->find('#lblE', 0)) {
                $this->code = trim($product_html->find('#lblE', 0)->innertext);
                $this->stock = $product_html->find('#qtyEAmount span', 0)->innertext;
            } elseif ($product_html->find('#lblC', 0)) {
                $this->code = trim($product_html->find('#lblC', 0)->innertext);
                $this->stock = $product_html->find('#qtyCAmount span', 0)->innertext;
            }

            $this->sku = $this->code . $this->item;

            //$this->images = $this->image_filter(SITE_URL . trim($product_html->find('#catImg', 0)->src));
			$this->images = $this->image_filter(SITE_URL . trim($product_html->find('#catImg', 0)->src));

            $sizes_html = strip_tags(preg_replace('/.*<strong>Cube:<\/strong>(.*?)<span.*/si', '$1', $details_html));
            $sizes_html = str_replace(array('(L)', '(W)', '(H)'), '', $sizes_html);
            $sizes_array = explode('x', $sizes_html);

            $this->l = trim($sizes_array[0]);
            $this->w = trim($sizes_array[1]);
            $this->h = trim($sizes_array[2]);

            $this->category = $this->set_category($this->minor_department);
            $this->minor_department = trim($this->clean_category($categories_array[1]));

            $html = preg_replace('/[\x00-\x1F\x7F]/', '', $product_html->innertext);

            $this->pack = $this->get_preg( '/<div class="t-row">\s*<span id=\"ItemsView_ctrl0_lblPack\".+?\s*Pack\:\s*<\/span>\s*([a-zA-Z0-9.,]*?)\s*<\/div>/si', $this->clean($html) );
            $this->size = $this->get_preg( '/<div class=\"t-row\">\s*<strong>\s*Size\:\s*<\/strong>\s*([a-zA-Z0-9.,]*?)\s*<\/div>/si', $this->clean($html) );
            $this->measure = $this->get_preg( '/<div class=\"t-row\">\s*<strong>\s*Measure\:\s*<\/strong>\s*([a-zA-Z0-9.,]*?)\s*<\/div>/si', $this->clean($html) );

            $flags = $product_html->find('#ItemsView_ctrl0_lblFlags', 0)->innertext;
            $this->flags = strpos($flags, 'GST') !== false ? 'G' : '';

        } else {
            $this->error = true;
        }
    }

    function clean($html){
        return preg_replace('/[\x00-\x1F\x7F]/', '', $html);
    }

    function get_preg($pattern, $s){
        preg_match($pattern, $s, $matches);
        if(isset($matches[1])) return $matches[1];
        return '';
    }

    function clean_category($cat){
        $cat = preg_replace("/(\d{1,3}\s?-\s?)/i", '', $cat);
        $cat = str_replace('&amp;', '&', $cat);
        return $cat;
    }

    function upc_short($upc)
    {
        $len = strlen($upc);
        if ($len < 1) {
            return '';
        }

        for($i = 0; $i < $len; $i++){
            if ($upc[0] === '0'){
                $upc = substr($upc, 1);
            }
        }

        return $upc;
    }

    private function upc_convert($upc)
    {

        $short_upc = $this->upc_short($upc);
        $short_len = strlen($short_upc);
        $upc_temp = $short_upc;
        $zeros = '';

        if ($short_len < 1) {
            return '000000000000';
        }

        if($short_len <= 11){
            $d = 11 - $short_len;
            for($i = 0; $i < $d; $i++){
                $zeros = '0' . $zeros;
            }
        }elseif($short_len == 12){
            return $short_upc;
        }else{
            return substr($upc_temp, -12);
        }

        $a = str_split($upc_temp);

        $even_sum = 0;
        $odd_sum = 0;

        foreach ($a as $i => $v) {
            $v = intval($v);
            if ((($i + 1) % 2) == 0) {
                $even_sum += $v;
            } else {
                $odd_sum += $v;
            }
        }

        if (($short_len % 2) == 0) {
            $even_sum *= 3;
        } else {
            $odd_sum *= 3;
        }

        $sum = $even_sum + $odd_sum;
        $upc_add = (10 - ($sum % 10)) % 10;

        //if($upc_add == 10) $upc_add = 0;

        return $zeros . $short_upc . $upc_add;
    }

    private function get_product_node($url)
    {
        $ch = curl_init();

        $header = Auth::get_header();
        $header[] = 'Content-Length: 0';

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 600);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($ch, CURLOPT_POST, 1);

        curl_setopt($ch, CURLOPT_COOKIEJAR, ROOT . '/cookie.txt');
        curl_setopt($ch, CURLOPT_COOKIEFILE, ROOT . '/cookie.txt');

        $data_html = curl_exec($ch);

        if (strlen($data_html) < 20000) {
            return false;
        }

        return str_get_html($data_html);
    }

    private function set_category($minor_department)
    {
        if (!empty(CATEGORIES_BY_MINOR_DEPARTMENTS[$minor_department])) {
            $category = CATEGORIES_BY_MINOR_DEPARTMENTS[$minor_department];
            if (is_array($category['category'])) {
                foreach($category['category'] as $i => $c){
                    //if(in_array($category['category'][$i], MAJOR_DEPARTMENT_SUPPLY))
                        $category['category'][$i] = $category['category'][$i] . '>' . $category['subcategory'];
                }
                $category = implode(SEP, $category['category']);
            }else{
                $category = $category['category'] . '>' . $category['subcategory'];
            }

            return $category;
        }
        return '';
    }

    private function image_filter($image)
    {
        if (strpos($image, 'soon.png') !== false) {
            return '';
        }
        return $image;
    }
}