<?php declare(strict_types=1);
namespace Boxes\GoldPriceLive\Block;

use Magento\Framework\View\Element\Template;

class Index extends Template {


    //--------START GOLD CHART------------
    public function get_gold_chart() {
        $goldchart = file_get_contents('http://gold-feed.com/charts/plugin3823832/goldchart.php');
        return $goldchart;
    }

    //--------START Silver CHART------------
    public function get_silver_chart() {
        $a=file_get_contents('http://gold-feed.com/charts/plugin3823832/silverchart.php');

        return $a;
    }



}
