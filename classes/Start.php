<?php

class Start
{
    public static function run()
    {
        if (!Auth::login(AUTH_URL, LOGIN, PASSWORD)) {
            die('невозможно залогиниться');
        }

        $ids = (new CSV_base())->get_ids();

        $base_temp = new CSV_temp();

        $catalog = new Catalog($ids, $base_temp);
        new CSV_main($catalog->get_categories(), $catalog->get_products(), $catalog->get_finished());
    }
}
