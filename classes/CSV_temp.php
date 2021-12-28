<?php

class CSV_temp
{
    private $products_temp_ids = [];
    private $categories_temp_array = [];

    public function __construct()
    {
        $this->set_products_temp_ids();
        $this->set_categories_temp_array();
    }

    private function set_products_temp_ids()
    {
        $products_temp_array = array_map('str_getcsv', file(CSV_PRODUCTS_TEMP_PATH));

        array_shift($products_temp_array);

        $this->products_temp_ids = array_column($products_temp_array, 4);
    }

    private function set_categories_temp_array()
    {
        $this->categories_temp_array = array_map('str_getcsv', file(CSV_CATEGORIES_TEMP_PATH));

        array_shift($this->categories_temp_array);
    }

    public function get_products_temp_ids()
    {
        return $this->products_temp_ids;
    }

    public function get_categories_temp_array()
    {
        return $this->categories_temp_array;
    }
}
