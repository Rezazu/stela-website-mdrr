<?php

class Dpr_PersentaseService
{

    public function getPersentase($data)
    {
        $total = array_values($data[0]);
        $selesai = array_values($data[1]);

        $persen = 100;
        for ($i = 0; $i <= count($total); $i++) {
            if ($total[$i] != 0) {
                $hitung = ($selesai[$i] / $total[$i]) * 100;
            } else {
                $hitung = 0;
            }

            $persen += $hitung;
        }

        return ((round($persen / 6) - 17) / 83) * 100 ;
    }

}