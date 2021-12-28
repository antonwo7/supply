<?php

class Catalog
{
    private $ids;
    private $products = array();
    private $categories = array();
    private $base_temp;
    private $finished;

    public function __construct($ids, $base_temp)
    {
        $this->finished = false;
        $this->base_temp = $base_temp;
        $this->ids = $ids;
        $this->set_catalog();
    }

    public function get_finished()
    {
        return $this->finished;
    }

    private function set_catalog()
    {
        $count = 0;
        $i = 0;

        $products_temp_ids = $this->base_temp->get_products_temp_ids();

        foreach ($this->ids as $id => $title) {
            $i++;

            if ($count >= COUNT) {
                break;
            }

            if (array_search($id, $products_temp_ids) !== false) {
                continue;
            }

            if (!Filter::filter_by_words($title)) {
                continue;
            }

            $product_url = str_replace('%id%', $id, PRODUCT_URL_TEMPLATE);
            $product = new Product($product_url);

            if(!$product->error){
                $this->products[] = $product;
                $count++;

                if ($i >= count($this->ids)) {
                    $this->finished = true;
                }
            }
        }

        $categories_temp_array = $this->base_temp->get_categories_temp_array();

        foreach ($this->products as $product) {
            $category_array = [
                $product->major_department,
                $product->minor_department,
                $product->major_category,
                $product->minor_category,
            ];

            if ($this->check_category($category_array, $categories_temp_array)) {
                $this->categories[] = $category_array;
                $categories_temp_array[] = $category_array;
                // $this->categories = array_diff(array_unique($this->categories), array('', null, false));
            }

        }
    }

    private function check_category($category_array, $categories_temp_array)
    {
        if (empty($categories_temp_array)) {
            return true;
        }

        foreach ($categories_temp_array as $i => $category_temp) {
            if (($category_temp[0] === $category_array[0])
                && ($category_temp[1] === $category_array[1])
                && ($category_temp[2] === $category_array[2])
                && ($category_temp[3] === $category_array[3])) {
                return false;
            }

        }

        return true;
    }

    public function get_categories()
    {
        return $this->categories;
    }

    public function get_products()
    {
        return $this->products;
    }

}
