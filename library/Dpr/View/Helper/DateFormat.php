<?php

class Dpr_View_Helper_DateFormat
{
	public function dateFormat($dt)
	{	
				$year = substr($dt, 0, 4);
        $month = substr ($dt, 5, 2);
        $date = substr ($dt, 8, 2);
        switch($month)
        {
          case '01' : $month = 'Januari'; break;
          case '02' : $month = 'Februari'; break;
          case '03' : $month = 'Maret'; break;
          case '04' : $month = 'April'; break;
          case '05' : $month = 'Mai'; break;
          case '06' : $month = 'Juni'; break;
          case '07' : $month = 'Juli'; break;
          case '08' : $month = 'Agustus'; break;
          case '09' : $month = 'September'; break;
          case '10' : $month = 'Oktober'; break;
          case '11' : $month = 'November'; break;
          case '12' : $month = 'Desember'; break;

        }
		$df = $date." ".$month." ".$year; 
	
		return $df;
	
	}
}