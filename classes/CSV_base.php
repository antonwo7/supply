<?php

class CSV_base
{
    private $ids = array();

    public function __construct()
    {
        $links = $this->get_links();

        foreach($links as $link){
            $this->get_csv(trim($link));
        }
    }

    private function get_links(){
        return file(CSV_LINKS);
    }

    private function get_csv($link)
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $link);
        curl_setopt($ch, CURLOPT_HTTPHEADER, self::get_header());

        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 600);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($ch, CURLOPT_POST, 1);

        curl_setopt($ch, CURLOPT_COOKIEJAR, ROOT . '/cookie.txt');
        curl_setopt($ch, CURLOPT_COOKIEFILE, ROOT . '/cookie.txt');

        $data = curl_exec($ch);

        if (!$data) {
            die('csv не доступен');
        }else{

            $temp = fopen(TEMP_XLSX, 'w');
            fwrite($temp, $data);

            $array = Functions::getXLS(TEMP_XLSX);
            $array = $this->clean_xslx($array);
            $array = array_filter($array, array($this, 'filter_array_callback'));
            $this->get_ids_from_csv($array);

            fclose($temp);
            //unlink(TEMP_XLSX);
        }
    }

    private function clean_xslx($array){
        $new = [];

        foreach($array as $i => $row){
            if($i > 2)
                $new[] = $row;
        }

        return $new;
    }

    private function get_header()
    {
        $header = array();
        $header[] = 'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9';
        //$header[] = 'Accept-Encoding: gzip, deflate';
        $header[] = 'Accept-Language: es-ES,es;q=0.9,ru-RU;q=0.8,ru;q=0.7,en-US;q=0.6,en;q=0.5';
        $header[] = 'Cache-Control: no-cache';
        //$header[] = 'sec-fetch-dest: document';
        //$header[] = 'sec-fetch-mode: navigate';
        //$header[] = 'sec-fetch-site: none';
        //$header[] = 'sec-fetch-user: ?1';
        $header[] = 'Upgrade-Insecure-Requests: 1';
        $header[] = 'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/85.0.4183.121 Safari/537.36';

        return $header;
    }

    private function get_ids_from_csv($csv_catalog)
    {
        foreach ($csv_catalog as $i => $row) {
            $this->ids[$row[5]] = $row[7];
        }
    }

    public function get_ids()
    {
        return $this->ids;
    }

    private function filter_callback($elem)
    {
        if (MAJOR_DEPARTMENT != '') {
            if ($elem[0] != MAJOR_DEPARTMENT) {
                return false;
            }

        }
        if (MINOR_DEPARTMENT != '') {
            if ($elem[1] != MINOR_DEPARTMENT) {
                return false;
            }

        }
        if (MAJOR_CATEGORY != '') {
            if ($elem[2] != MAJOR_CATEGORY) {
                return false;
            }

        }
        if (MINOR_CATEGORY != '') {
            if ($elem[3] != MINOR_CATEGORY) {
                return false;
            }

        }
        return true;
    }

    private function filter_array_callback($elem)
    {

//        if (MAJOR_DEPARTMENT != '') {
//            if (!in_array($elem[1], MAJOR_DEPARTMENT)) {
//                return false;
//            }
//        }

        if (array_search($elem[2], array_keys(CATEGORIES_BY_MINOR_DEPARTMENTS)) === false) {
            return false;
        }

        return true;
    }
}
