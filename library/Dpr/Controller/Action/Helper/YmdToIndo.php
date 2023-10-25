<?php

class Dpr_Controller_Action_Helper_YmdToIndo extends Zend_Controller_Action_Helper_Abstract
{
    public function direct($dt)
    {
        $year = substr($dt, 0, 4);
        $month = substr($dt, 5, 2);
        $date = substr($dt, 8, 2);

        $full = "$year-$month-$date";

        $day = $this->getDay($full);

        switch ($day){
            case 'Monday' : $day = 'Senin'; break;
            case 'Tuesday' : $day = 'Selasa'; break;
            case 'Wednesday' : $day = 'Rabu'; break;
            case 'Thursday' : $day = 'Kamis'; break;
            case 'Friday' : $day = "Jum'at"; break;
            case 'Saturday' : $day = 'Sabtu'; break;
            case 'Sunday' : $day = 'Minggu'; break;
        }

        switch ($date) {
            case '01':
                $date = '1';
                break;
            case '02':
                $date = '2';
                break;
            case '03':
                $date = '3';
                break;
            case '04':
                $date = '4';
                break;
            case '05':
                $date = '5';
                break;
            case '06':
                $date = '6';
                break;
            case '07':
                $date = '7';
                break;
            case '08':
                $date = '8';
                break;
            case '09':
                $date = '9';
                break;
        }

        switch ($month) {
            case '01' :
                $month = 'Januari';
                break;
            case '02' :
                $month = 'Februari';
                break;
            case '03' :
                $month = 'Maret';
                break;
            case '04' :
                $month = 'April';
                break;
            case '05' :
                $month = 'Mai';
                break;
            case '06' :
                $month = 'Juni';
                break;
            case '07' :
                $month = 'Juli';
                break;
            case '08' :
                $month = 'Agustus';
                break;
            case '09' :
                $month = 'September';
                break;
            case '10' :
                $month = 'Oktober';
                break;
            case '11' :
                $month = 'November';
                break;
            case '12' :
                $month = 'Desember';
                break;
        }
        $df = $date . " " . $month . " " . $year;
        $df_indo = "$day $month $year";

        return [$df, $df_indo];
    }

    public function directAngkaFUll($date)
    {
        $date = explode(' ', $date);

        $year = substr($date[0], 0, 4);
        $month = substr($date[0], 5, 2);
        $tanggal = substr($date[0], 8, 2);

        return "$date[1] $tanggal-$month-$year";
    }

    public function directAngka($date)
    {
        $year = substr($date, 0, 4);
        $month = substr($date, 5, 2);
        $date = substr($date, 8, 2);

        return "$date-$month-$year";
    }

    public function getDay($date)
    {
        $datetime = DateTime::createFromFormat('Y-m-d', $date);
        return $datetime->format('l');
    }
}