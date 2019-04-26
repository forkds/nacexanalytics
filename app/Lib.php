<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Lib extends Model
{
    static function ExcelDateToUnixtimestamp ($dateXls)
    {
        # Convert Excel Date to Unixstamp
        return ( intval($dateXls) - 25569) * 86400;

    }

    static function getMaxValue ($billings)
    {
        $values = [];

        foreach ($billings as $billing)
        {
            $values[] = $billing->amount;
        }

        return $values ? max($values) : 0;
 
    }

    static function getGraphScale ($value)
    {
        $scale = 1;

        for ($x=0; $x<10; $x++)
        {
            $scale*=10;

            if ($scale > $value)
            {
                $scale/=10;
                break;
            }
        }

        $scale_div = $scale;
        $scale     = 0;


        for ($x=0; $x<10; $x++)
        {
            if ($scale < $value)
            {
                $scale+=$scale_div;
            }
        }

        return $scale;

    }

    static function getGraphDataByMonth ($items, $defaultItems = null)
    {
        # Get years
        $arrayYears = [];

        if ($defaultItems)
        {
            foreach ($defaultItems as $item)
            {
                if (!isset($arrayYears[$item->year])) $arrayYears[$item->year] = $item->year;
            }
        }
        else
        {
            foreach ($items as $item)
            {
                if (!isset($arrayYears[$item->year])) $arrayYears[$item->year] = $item->year;
            }
        }

        $arrayGraph = array();

        $cnt_color = 0;

        if ($arrayYears)
        {
            foreach ($arrayYears as $key => $year)
            {
                $arrayMonths = array();

                $yearPattern = $key;

                for ($x=0; $x<12; $x++) 
                {
                    $arrayMonths[$x] =  ['x' => $x, 'y' => 0];
                }

                foreach ($items as $item)
                {
                    if ($item->year == $yearPattern)
                    {
                        $arrayMonths[$item->month-1] = ['x' => $item->month-1, 'y' => $item->amount];
                    }
                }

                $arrayGraph[] = array('values' => $arrayMonths, 'key' => 'Año ' . $yearPattern, 'color' => self::getGraphColors($cnt_color));

                $cnt_color++;
            }

        }

        return $arrayGraph;
    }


    static function getGraphDataByYear ($items, $defaultItems = null)
    {
        # Get default years
        $arrayYears = [];

        if ($defaultItems)
        {
            foreach ($defaultItems as $item)
            {
                if (!isset($arrayYears[$item->year])) $arrayYears[$item->year] = $item->year;
            }
        }

        $values = [];
        $defaultValues = [];

        $cnt_color = 0;

        if ($arrayYears)
        {
            foreach ($arrayYears as $year)
            {
                $defaultValues[$year] = ['label' => $year, 
                                         'value' => 0,0,
                                         'color' => self::getGraphColors($cnt_color++)];
            }

            foreach ($items as $item)
            {
                if (isset($defaultValues[$item->year]))
                {
                    $defaultValues[$item->year]['value'] = $item->amount;
                }
            }

            foreach ($defaultValues as $defaultValue)
            {
                $values[] = $defaultValue;
            }
        }
        else
        {
            foreach ($items as $item)
            {
                $values[] = ['label' => $item->year, 
                             'value' => $item->amount,
                             'color' => self::getGraphColors($cnt_color++)];
            }
        }

        return ['key'=>'Evolución anual', 'values'=>$values];

    }

    static function getGraphColors($number) 
    {
        $colors = array();

        $colors[] = '#039cfd';
        $colors[] = '#f76397';
        $colors[] = '#52bb56';
        $colors[] = '#ffaa00';
        $colors[] = '#dcdcdc';
        $colors[] = '#e6194B';
        $colors[] = '#f58231';
        $colors[] = '#ffe119';
        $colors[] = '#bfef45';
        $colors[] = '#67d1f8';
        $colors[] = '#42d4f4';
        $colors[] = '#911eb4';
        $colors[] = '#f032e6';
        $colors[] = '#a9a9a9';
        $colors[] = '#800000';
        $colors[] = '#9A6324';
        $colors[] = '#469990';
        $colors[] = '#000075';
        $colors[] = '#000000';
        $colors[] = '#039cfd';
        $colors[] = '#7266ba';
        $colors[] = '#fabebe';
        $colors[] = '#ffd8b1';
        $colors[] = '#fffac8';
        $colors[] = '#aaffc3';
        $colors[] = '#e6beff';
        $colors[] = '#2E8B57';
        $colors[] = '#DB7093';
        $colors[] = '#228B22';
        $colors[] = '#00FF7F';
        $colors[] = '#ADFF2F';
        $colors[] = '#B22222';
        $colors[] = '#FFB6C1';
        $colors[] = '#7FFF00';
        $colors[] = '#BA55D3';
        $colors[] = '#66CDAA';
        $colors[] = '#F5F5DC';
        $colors[] = '#778899';
        $colors[] = '#FFA07A';
        $colors[] = '#FF00FF';
        $colors[] = '#3CB371';
        $colors[] = '#48D1CC';
        $colors[] = '#5F9EA0';
        $colors[] = '#D2691E';
        $colors[] = '#FF7F50';
        $colors[] = '#696969';
        $colors[] = '#FF00FF';
        $colors[] = '#4169E1';
        $colors[] = '#8B4513';
        $colors[] = '#F4A460';
        $colors[] = '#FFD700';
        $colors[] = '#DAA520';
        $colors[] = '#F0E68C';
        $colors[] = '#4682B4';
        $colors[] = '#00FF00';
        $colors[] = '#B8860B';


        return (($number > count($colors)-1) ? '#67d1f8' : $colors[$number]); 
    }

}
