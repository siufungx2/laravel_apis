<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class FuelController extends Controller
{
    private $_fuel_api_url = "https://projectzerothree.info/api.php?format=json";
    private $fuel_type = array(
        'E10',
        'U91',
        'U95',
        'U98',
        'DIESEL',
        'LPG'
    );
    private $state = array(
        'ALL',
        'VIC',
        'NSW',
        'QLD',
        'WA'
    );

    public function getFuelList( Request $request, $search = "ALL" ){
        $search = strtoupper($search);

        $search_type = "";
        if (in_array($search, $this->fuel_type) ) $search_type = 'fuel';
        if (in_array($search, $this->state) ) $search_type = 'state';
        $fuel_list = $this->_filter_fuel_list($search_type, $search);

        return $fuel_list;
    }

    private function _filter_fuel_list( $key, $value = "ALL" ){
        $response = Http::get($this->_fuel_api_url);
        $fuel_list = $response->json();

        $filter_list = $fuel_list['regions'];
        $filter_list = array_column($filter_list, 'prices');
        $target_list = array_merge(...$filter_list);

        if ($value === "ALL")
            usort($target_list, fn($current, $next) => $current['price'] <=> $next['price'] );
            return $target_list;
        if( $key === 'fuel' ){
            $target_list = array_filter( $target_list, fn($list) => strtoupper($list['type']) === strtoupper($value) );
        }else if($key === 'state'){
            $target_list = array_filter( $target_list, fn($list) => str_contains(strtoupper($list['state']), strtoupper($value)) );
        }
        usort($target_list, fn($current, $next) => $current['price'] <=> $next['price'] );
        return $target_list;
    }
}
