<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HolidayController extends Controller
{
    private $_holiday_file_name = "public_holiday-2019-2023.csv";
    private $_school_holiday_file_name = "school_day-2019-2025.csv";

    private function _getPublicHolidayList( $withHeader = false )
    {
        return $this->_getHolidayList($this->_holiday_file_name, $withHeader);
    }

    private function _getSchoolHolidayList( $withHeader = false )
    {
        return $this->_getHolidayList($this->_school_holiday_file_name, $withHeader);
    }

    private function _getHolidayList( $filename, $withHeader = false )
    {
        if(!$filename)
            return false;
        $holidays = array();
        if (($open = fopen(storage_path() . "/holiday_file/" . $filename, "r")) !== FALSE) {

            while (($data = fgetcsv($open, 1000, ",")) !== FALSE) {
                $holidays[] = array(
                    'date' => $data[0],
                    'significance' => $data[1]
                );
            }

            // Edit keys when return school holiday
            if(  $this->_school_holiday_file_name === $filename )
                $holidays = array_map(function($holiday) {
                    return array(
                        'start' => $holiday['date'],
                        'end' => $holiday['significance']
                    );
                }, $holidays);

            if( count($holidays) > 0 && !$withHeader)
                array_shift($holidays);
            fclose($open);
        }
        return $holidays;
    }

    public function getPublicHoliday(Request $request)
    {
        return response()->json([
            'success' => true,
            'holiday' => $this->_getPublicHolidayList()
        ], 200);
    }

    public function getSchoolHoliday(Request $request)
    {
        return response()->json([
            'success' => true,
            'holiday' => $this->_getSchoolHolidayList()
        ], 200);
    }
}

