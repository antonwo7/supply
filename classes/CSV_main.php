<?php

class CSV_main
{
    private $excel;
    private $csv_temp_handle;

    public function __construct($categories, $products, $finished)
    {
        if ($finished) {
            $this->update_temp_products_csv($products);
            $this->update_products_csv();
            $this->set_temp_products_csv_default();

            $this->update_temp_categories_csv($categories);
            $this->update_categories_csv();
            $this->set_temp_categories_csv_default();
        } else {
            $this->update_temp_products_csv($products);
            $this->update_temp_categories_csv($categories);
        }

    }

    private function update_products_csv()
    {
        unlink(CSV_PRODUCTS_PATH);
        copy(CSV_PRODUCTS_TEMP_PATH, CSV_PRODUCTS_PATH);
    }

    private function update_temp_products_csv($products)
    {
		$csv_temp_handle = fopen(CSV_PRODUCTS_TEMP_PATH, 'a+');
	
        foreach ($products as $i => $product) {

            fputcsv($csv_temp_handle, [
                $product->major_department,
                $product->minor_department,
                $product->major_category,
                $product->minor_category,
                $product->item,
                $product->upc,
                $product->upc_fixed,
                $product->description,
                $product->case,
                $product->landed_cost,
                $product->sale_price,
                $product->regular_price,
                $product->code,
                $product->stock,
                $product->sku,
                $product->category,
                $product->images,
                $product->l,
                $product->w,
                $product->h,
                $product->pack,
                $product->size,
                $product->measure,
                $product->flags
            ]);
        }

        fclose($csv_temp_handle);
    }

    private function update_categories_csv()
    {
        unlink(CSV_CATEGORIES_PATH);
        copy(CSV_CATEGORIES_TEMP_PATH, CSV_CATEGORIES_PATH);
    }

    private function update_temp_categories_csv($categories)
    {
        $csv_categories_handle = fopen(CSV_CATEGORIES_TEMP_PATH, 'a');

        foreach ($categories as $i => $category) {

            fputcsv($csv_categories_handle, [
                $category[0],
                $category[1],
                $category[2],
                $category[3],
            ]);
        }

        fclose($csv_categories_handle);
    }

    private function get_categories_csv_header()
    {
        return [
            'Major Department',
            'Minor Department',
            'Major Category',
            'Minor Category',
        ];
    }

    private function get_products_csv_header()
    {
        return [
            'Major Department',
            'Minor Department',
            'Major Category',
            'Minor Category',
            'Item',
            'UPC',
            'UPC Fixed',
            'Description',
            'Case (LBS)',
            'Landed Cost',
            'Sale Price',
            'Regular Price',
            'Code',
            'Stock',
            'SKU',
            'Category',
            'Images',
            'l',
            'w',
            'h',
            'Pack',
            'Size',
            'Measure',
            'Flags'
        ];
    }

    private function set_temp_categories_csv_default()
    {
        fputcsv(fopen(CSV_CATEGORIES_TEMP_PATH, 'w'), $this->get_categories_csv_header());
    }

    private function set_temp_products_csv_default()
    {
        fputcsv(fopen(CSV_PRODUCTS_TEMP_PATH, 'w'), $this->get_products_csv_header());
    }
}
