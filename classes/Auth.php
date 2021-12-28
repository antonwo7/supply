<?php

class Auth
{
    static public function get_header()
    {
        $header = array();
        $header[] = 'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9';
        //$header[] = 'Accept-Encoding: gzip, deflate';
        $header[] = 'Accept-Language: es-ES,es;q=0.9,ru-RU;q=0.8,ru;q=0.7,en-US;q=0.6,en;q=0.5';
        $header[] = 'Cache-Control: no-cache';
        $header[] = 'Connection: keep-alive';
        $header[] = 'Host: test.tgp.crs';
        $header[] = 'Pragma: no-cache';
        $header[] = 'Referer: http://test.tgp.crs/login/login.aspx';
        $header[] = 'Upgrade-Insecure-Requests: 1';
        $header[] = 'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/85.0.4183.121 Safari/537.36';

        return $header;
    }

    static public function get_data()
    {
        $data = array(
            'RadScriptManager' => 'SubmitLoginPanel|SubmitLogin',
            'RadStyleSheetManager_TSSM' => ';|637365503122388780:5e6a37b1:6b6fbbb2:a673acf:a98d9894;Telerik.Web.UI, Version=2018.3.910.45, Culture=neutral, PublicKeyToken=121fae78165ba3d4:en-US:f82d27b2-2e24-4637-ab87-c64fb8588ccb:45085116:1e75e40c:9a71aa6b:505983de:d7e35272:959c7879:ba1b8630;Telerik.Web.UI.Skins, Version=2018.3.910.45, Culture=neutral, PublicKeyToken=121fae78165ba3d4:en-US:416eee09-8c71-4598-ab9b-088260a7d88e:3675cefc:ccd8772c:962867ee:de5c6d36',
            '__EVENTTARGET' => 'SubmitLogin',
            '__EVENTARGUMENT' => '',
            '__VIEWSTATE' => self::get_var('__VIEWSTATE'),
            '__VIEWSTATEGENERATOR' => '1806D926',
            '__EVENTVALIDATION' => self::get_var('__EVENTVALIDATION'),
            'txtName' => '001550',
            'txtName_ClientState' => '{"enabled":true,"emptyMessage":"Enter Username","validationText":"001550","valueAsString":"001550","lastSetTextBoxValue":"001550"}',
            'txtPassword' => 'dixie',
            'txtPassword_ClientState' => '{"enabled":true,"emptyMessage":"","validationText":"dixie","valueAsString":"dixie","lastSetTextBoxValue":"dixie"}',
            'SubmitLogin_ClientState' => '{"text":"Login","value":"","checked":false,"target":"","navigateUrl":"","commandName":"","commandArgument":"","autoPostBack":true,"selectedToggleStateIndex":0,"validationGroup":null,"readOnly":false,"primary":false,"enabled":true}',
            // '__ASYNCPOST' => 'true',
            // 'RadAJAXControlID' => 'RadAjaxManager1',
        );

        return $data;
    }

    static public function login($url, $login, $password)
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, self::get_header());

        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, self::get_data());

        curl_setopt($ch, CURLOPT_COOKIEJAR, ROOT . '/cookie.txt');
        curl_setopt($ch, CURLOPT_COOKIEFILE, ROOT . '/cookie.txt');

        $data = curl_exec($ch);

        var_dump($data);

        if (strlen($data) > 50000) return true;
        return false;
    }

    static public function get_var($var_name){
        $html = file_get_html(AUTH_URL);
        $var = $html->find('#' . $var_name, 0)->getAttribute('value');
        return $var;
    }
}
