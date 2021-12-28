<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


include_once 'vendor/autoload.php';
include_once 'include/simple_html_dom.php';
include_once 'include/phpexcel/PHPExcel.php';
include_once 'classes/Autoloader.php';


const DS = DIRECTORY_SEPARATOR;
const ROOT = __DIR__;
const CLASSES = ROOT . DS . 'classes';
// const SYSTEM = ROOT . DS . 'system';

const SITE_URL = 'https://test.tgp.crs';
const AUTH_URL = 'https://test.tgp.crs/login/login.aspx';
const CATALOG_URL = 'https://test.tgp.crs/Content/Catalog/Catalog.aspx';
const PRODUCT_URL_TEMPLATE = 'https://test.tgp.crs/Content/Item/Item.aspx?ItemId=%id%&ProvinceId=AB&AssociateId=109028&ShowSpecialCost=9999';

const CSV_URL = 'https://test.tgp.crs/Handlers/DownloadFile.ashx?requestingPage=Catalog&filePath=Content/Catalog/Download/AB.CSV';
const CSV_PATH = ROOT . DS . 'system/csv/AB.CSV';

const CSV_CATEGORIES_PATH = ROOT . DS . 'system/csv/tgpcategories.csv';
const CSV_CATEGORIES_TEMP_PATH = ROOT . DS . 'system/csv/tgpcategoriestemp.csv';

const CSV_PRODUCTS_PATH = ROOT . DS . 'system/csv/tgpproducts.csv';
const CSV_PRODUCTS_TEMP_PATH = ROOT . DS . 'system/csv/tgpproductstemp.csv';

const CSV_LINKS = ROOT . DS . 'system/csv/links.txt';
const TEMP_XLSX = ROOT . '/temp.xlsx';

const CONFIG_FILE = ROOT . '/config.cnf';
const LOG_FILE = ROOT . '/time.txt';


const SEP = '::';
const SUBSEP = ':';

const LOGIN = '001550';
const PASSWORD = 'dixie';

define('COUNT', (!empty($_GET['count'])) ? intval($_GET['count']) : 100);
//define('MAJOR_DEPARTMENT', (!empty($_GET['made'])) ? urldecode($_GET['made']) : '');

define('MINOR_DEPARTMENT', (!empty($_GET['mide'])) ? urldecode($_GET['mide']) : '');
define('MAJOR_CATEGORY', (!empty($_GET['maca'])) ? urldecode($_GET['maca']) : '');
define('MINOR_CATEGORY', (!empty($_GET['mica'])) ? urldecode($_GET['mica']) : '');

const MAJOR_DEPARTMENT_SUPPLY = array(
    'Bulk Foods & Wholesale Grocery',
    'Health & Beauty'
);

const MAJOR_DEPARTMENT = array(
    '1 - GROCERY',
    '50 - HBC'
);

//const PERMITTED_MINOR_DEPARTMENTS = array(
//    '1 - CONDIMENTS',
//    '2 - SALAD DRESS PICKLES & CROUTONS',
//    '3 - SPREADS & SYRUP',
//    '4 - HOT BEVERAGES',
//    '5 - COLD CEREAL',
//    '6 - TISSUE',
//    '7 - PAPER',
//    '8 - WRAPS',
//    '9 - LAUNDRY',
//    '10 - CLEANING',
//    '11 - SOUP',
//    '12 - JUICES, DRINKS & POWDERS',
//    '13 - PET FOOD',
//    '14 - CANNED FISH & MEAT',
//    '15 - CANNED VEGETABLES',
//    '16 - CANNED FRUIT',
//    '17 - RICE & GRAINS',
//    '18 - PASTA & SAUCES',
//    '19 - HOT CEREAL',
//    '20 - PORTABLE SNACKS',
//    '21 - BAKING PRODUCTS',
//    '22 - OIL VINEGAR STUFFING MIXES',
//    '24 - FLOUR & CAKE MIX',
//    '25 - DESSERTS',
//    '26 - SUGAR & SPICES',
//    '27 - ETHNIC FOODS',
//    '28 - SOFT DRINKS',
//    '29 - SNACK FOODS',
//    '43 - COOKIES',
//    '44 - SNACK CRACKERS',
//    '45 - SOUP CRACKERS',
//    '46 - SEASONAL CONFECTIONERY',
//    '47 - CONFECTIONERY',
//    '48 - CONES/TOPPINGS/FREEZE POPS',
//    '92 - INSECTICIDES',
//    '93 - CANNING/PICKLING',
//    '691 - ANALGESICS',
//    '692 - COUGH & COLD',
//    '701 - STOMACH PREPARATIONS',
//    '711 - DIAPERS & TRAINING PANTS',
//    '712 - BABY CARE',
//    '713 - INFANT FEEDING',
//    '714 - BABY FOOD & CEREAL',
//    '721 - SKINCARE',
//    '723 - BAR & LIQUID SOAPS',
//    '724 - MEDICATED SKIN CARE',
//    '725 - SUNCARE',
//    '731 - DENTAL CARE',
//    '741 - DEODORANTS',
//    '751 - FEMININE HYGIENE',
//    '752 - FAMILY PLANNING',
//    '753 - INCONTINENCE PRODUCTS',
//    '761 - FIRST AID PRODUCTS',
//    '762 - FOOT CARE',
//    '763 - ATHLETIC SUPPORTS',
//    '771 - HAIR CARE',
//    '773 - HAIR COLORING PRODUCTS',
//    '774 - HAIR ACCESSORIES',
//    '775 - HBC - TRAVEL SIZES',
//    '781 - SHAVE & DEPILATORY PRODUCTS',
//    '791 - VITAMINS',
//    '792 - HERBALS',
//    '793 - NUTRITIONAL SUPPLEMENTS',
//    '794 - EYE CARE',
//    '811 - MEDICAL PRODUCTS',
//    '821 - COSMETICS',
//    '822 - IMPLEMENTS',
//    '903 - EYE GLASS WEAR'
//);

const CATEGORIES_BY_MINOR_DEPARTMENTS = array(
    '1 - CONDIMENTS' => ['category' => 'Bulk Foods & Wholesale Grocery', 'subcategory' => 'CONDIMENTS'],
    '2 - SALAD DRESS PICKLES & CROUTONS' => ['category' => 'Bulk Foods & Wholesale Grocery', 'subcategory' => 'SALAD DRESS PICKLES & CROUTONS'],
    '3 - SPREADS & SYRUP' => ['category' => 'Bulk Foods & Wholesale Grocery', 'subcategory' => 'SPREADS & SYRUP'],
    '4 - HOT BEVERAGES' => ['category' => ['Bulk Foods & Wholesale Grocery', 'Coffee & Coffee Supplies', 'Office & Breakroom'], 'subcategory' => 'HOT BEVERAGES'],
    '5 - COLD CEREAL' => ['category' => 'Bulk Foods & Wholesale Grocery', 'subcategory' => 'COLD CEREAL'],
    '6 - TISSUE' => ['category' => ['Health & Beauty', 'Restaurant & Commercial'], 'subcategory' => 'TISSUE'],
    '7 - PAPER' => ['category' => ['Health & Beauty', 'Sanitation and janitorial'], 'subcategory' => 'PAPER'],
    '8 - WRAPS' => ['category' => 'Bulk Foods & Wholesale Grocery', 'subcategory' => 'WRAPS'],
    '9 - LAUNDRY' => ['category' => 'Sanitation and janitorial', 'subcategory' => 'LAUNDRY'],
    '10 - CLEANING' => ['category' => ['Sanitation and janitorial', 'Restaurant & Commercial'], 'subcategory' => 'CLEANING'],
    '11 - SOUP' => ['category' => 'Bulk Foods & Wholesale Grocery', 'subcategory' => 'SOUP'],
    '12 - JUICES, DRINKS & POWDERS' => ['category' => 'Bulk Foods & Wholesale Grocery', 'subcategory' => 'JUICES, DRINKS & POWDERS'],
    '13 - PET FOOD' => ['category' => 'Pet Food and Supplies', 'subcategory' => 'PET FOOD'],
    '14 - CANNED FISH & MEAT' => ['category' => 'Bulk Foods & Wholesale Grocery', 'subcategory' => 'CANNED FISH & MEAT'],
    '15 - CANNED VEGETABLES' => ['category' => 'Bulk Foods & Wholesale Grocery', 'subcategory' => 'CANNED VEGETABLES'],
    '16 - CANNED FRUIT' => ['category' => 'Bulk Foods & Wholesale Grocery', 'subcategory' => 'CANNED FRUIT'],
    '17 - RICE & GRAINS' => ['category' => 'Bulk Foods & Wholesale Grocery', 'subcategory' => 'RICE & GRAINS'],
    '18 - PASTA & SAUCES' => ['category' => 'Bulk Foods & Wholesale Grocery', 'subcategory' => 'PASTA & SAUCES'],
    '19 - HOT CEREAL' => ['category' => 'Bulk Foods & Wholesale Grocery', 'subcategory' => 'HOT CEREAL'],
    '20 - PORTABLE SNACKS' => ['category' => 'Bulk Foods & Wholesale Grocery', 'subcategory' => 'PORTABLE SNACKS'],
    '21 - BAKING PRODUCTS' => ['category' => 'Bulk Foods & Wholesale Grocery', 'subcategory' => 'BAKING PRODUCTS'],
    '22 - OIL VINEGAR STUFFING MIXES' => ['category' => 'Bulk Foods & Wholesale Grocery', 'subcategory' => 'OIL VINEGAR STUFFING MIXES'],
    '24 - FLOUR & CAKE MIX' => ['category' => 'Bulk Foods & Wholesale Grocery', 'subcategory' => 'FLOUR & CAKE MIX'],
    '25 - DESSERTS' => ['category' => 'Bulk Foods & Wholesale Grocery', 'subcategory' => 'DESSERTS'],
    '26 - SUGAR & SPICES' => ['category' => 'Bulk Foods & Wholesale Grocery', 'subcategory' => 'SUGAR & SPICES'],
    '27 - ETHNIC FOODS' => ['category' => 'Bulk Foods & Wholesale Grocery', 'subcategory' => 'ETHNIC FOODS'],
    '28 - SOFT DRINKS' => ['category' => 'Bulk Foods & Wholesale Grocery', 'subcategory' => 'SOFT DRINKS'],
    '29 - SNACK FOODS' => ['category' => 'Bulk Foods & Wholesale Grocery', 'subcategory' => 'SNACK FOODS'],
    '43 - COOKIES' => ['category' => 'Bulk Foods & Wholesale Grocery', 'subcategory' => 'COOKIES'],
    '44 - SNACK CRACKERS' => ['category' => 'Bulk Foods & Wholesale Grocery', 'subcategory' => 'SNACK CRACKERS'],
    '45 - SOUP CRACKERS' => ['category' => 'Bulk Foods & Wholesale Grocery', 'subcategory' => 'SOUP CRACKERS'],
    '46 - SEASONAL CONFECTIONERY' => ['category' => 'Bulk Foods & Wholesale Grocery', 'subcategory' => 'SEASONAL CONFECTIONERY'],
    '47 - CONFECTIONERY' => ['category' => 'Bulk Foods & Wholesale Grocery', 'subcategory' => 'CONFECTIONERY'],
    '48 - CONES/TOPPINGS/FREEZE POPS' => ['category' => 'Bulk Foods & Wholesale Grocery', 'subcategory' => 'CONES/TOPPINGS/FREEZE POPS'],
    '92 - INSECTICIDES' => ['category' => 'Outdoors and camping', 'subcategory' => 'INSECTICIDES'],
    '93 - CANNING/PICKLING' => ['category' => 'Farm & Gardening', 'subcategory' => 'CANNING/PICKLING'],
    '691 - ANALGESICS' => ['category' => 'Health & Beauty', 'subcategory' => 'OVER THE COUNTER MEDICATION'],
    '692 - COUGH & COLD' => ['category' => 'Health & Beauty', 'subcategory' => 'COUGH & COLD'],
    '701 - STOMACH PREPARATIONS' => ['category' => 'Health & Beauty', 'subcategory' => 'STOMACH & DIGESTION'],
    '711 - DIAPERS & TRAINING PANTS' => ['category' => 'Health & Beauty', 'subcategory' => 'DIAPERS & TRAINING PANTS'],
    '712 - BABY CARE' => ['category' => 'Health & Beauty', 'subcategory' => 'BABY CARE'],
    '713 - INFANT FEEDING' => ['category' => 'Health & Beauty', 'subcategory' => 'INFANT FEEDING'],
    '714 - BABY FOOD & CEREAL' => ['category' => 'Health & Beauty', 'subcategory' => 'BABY FOOD & CEREAL'],
    '721 - SKINCARE' => ['category' => 'Health & Beauty', 'subcategory' => 'SKINCARE'],
    '723 - BAR & LIQUID SOAPS' => ['category' => 'Health & Beauty', 'subcategory' => 'BAR & LIQUID SOAPS'],
    '724 - MEDICATED SKIN CARE' => ['category' => 'Health & Beauty', 'subcategory' => 'MEDICATED SKIN CARE'],
    '725 - SUNCARE' => ['category' => 'Health & Beauty', 'subcategory' => 'SUNCARE'],
    '731 - DENTAL CARE' => ['category' => 'Health & Beauty', 'subcategory' => 'DENTAL CARE'],
    '741 - DEODORANTS' => ['category' => 'Health & Beauty', 'subcategory' => 'DEODORANTS'],
    '751 - FEMININE HYGIENE' => ['category' => 'Health & Beauty', 'subcategory' => 'FEMININE HYGIENE'],
    '752 - FAMILY PLANNING' => ['category' => 'Health & Beauty', 'subcategory' => 'FAMILY PLANNING'],
    '753 - INCONTINENCE PRODUCTS' => ['category' => 'Health & Beauty', 'subcategory' => 'INCONTINENCE PRODUCTS'],
    '761 - FIRST AID PRODUCTS' => ['category' => 'Health & Beauty', 'subcategory' => 'FIRST AID'],
    '762 - FOOT CARE' => ['category' => 'Health & Beauty', 'subcategory' => 'FOOT CARE'],
    '763 - ATHLETIC SUPPORTS' => ['category' => 'Health & Beauty', 'subcategory' => 'ATHLETIC SUPPORTS'],
    '771 - HAIR CARE' => ['category' => 'Health & Beauty', 'subcategory' => 'HAIR CARE'],
    '773 - HAIR COLORING PRODUCTS' => ['category' => 'Health & Beauty', 'subcategory' => 'HAIR COLORING'],
    '774 - HAIR ACCESSORIES' => ['category' => 'Health & Beauty', 'subcategory' => 'HAIR ACCESSORIES'],
    '775 - HBC - TRAVEL SIZES' => ['category' => 'Health & Beauty', 'subcategory' => 'TRAVEL SIZE HBC'],
    '781 - SHAVE & DEPILATORY PRODUCTS' => ['category' => 'Health & Beauty', 'subcategory' => 'SHAVE & GROOMING'],
    '791 - VITAMINS' => ['category' => 'Health & Beauty', 'subcategory' => 'VITAMINS'],
    '792 - HERBALS' => ['category' => 'Health & Beauty', 'subcategory' => 'HERBAL SUPPLEMENTS'],
    '793 - NUTRITIONAL SUPPLEMENTS' => ['category' => 'Health & Beauty', 'subcategory' => 'NUTRITIONAL SUPPLEMENTS'],
    '794 - EYE CARE' => ['category' => 'Health & Beauty', 'subcategory' => 'EYE CARE'],
    '811 - MEDICAL PRODUCTS' => ['category' => 'Health & Beauty', 'subcategory' => 'MEDICAL & CARE'],
    //'821 - COSMETICS' => ['category' => 'Health & Beauty', 'subcategory' => 'COSMETICS'],
    '822 - IMPLEMENTS' => ['category' => 'Health & Beauty', 'subcategory' => 'MEDICAL & CARE'],
    //'903 - EYE GLASS WEAR' => ['category' => 'Health & Beauty', 'subcategory' => 'EYE GLASS WEAR'],
);

use PidHelper\PidHelper;
$pidHelper = new PidHelper('/var/www/html/supplybeaver.ca/parsing/', 'process.pid');

if (!$pidHelper->lock()) {
    exit("Script Running\n");
}

Autoloader::register();


$timer = new Time();
$timer->begin();

Start::run();

$timer->end();

$pidHelper->unlock();



////$started = Config::get_config('started');
//
//if(!$started || $started !== '1'){
////    Config::start();
//    Start::run();
////    Config::stop();
//}
//
//$timer->end();