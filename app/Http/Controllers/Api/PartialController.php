<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PartialController extends Controller
{

    static function partial($bayar, $sisa)
    {
        $array = array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12);
        if ($bayar <= $sisa){
            return "true";
        } else { return "false"; }
    }

    static function partial2($bayar, $sisa)
    {
        $array = array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12);
        if ($bayar <= $sisa){
            $hitung = $sisa/$bayar;
            if (in_array($hitung, $array)){
                return "true";
            }
        } else { return "false"; }
    }

    static function partial3($bayar, $sisa)
    {
        $array = array(1, 2, 3);
        if ($bayar <= $sisa){
            $hitung = $sisa/$bayar;
            if (in_array($hitung, $array)){
                return "true";
            }
        } else { return "false"; }
    }

    static function partial4($bayar, $sisa)
    {
        $array = array(1, 2, 3, 4);
        if ($bayar <= $sisa){
            $hitung = $sisa/$bayar;
            if (in_array($hitung, $array)){
                return "true";
            }
        } else { return "false"; }
    }

    static function partial5($bayar, $sisa)
    {
        $array = array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12);
        if ($bayar <= $sisa){
            $hitung = $sisa/$bayar;
            if (in_array($hitung, $array)){
                return "true";
            }
        } else { return "false"; }
    }
    
    static function partial6($bayar, $sisa)
    {
        $array = array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12);
        if ($bayar <= $sisa){
            $hitung = $sisa/$bayar;
            if (in_array($hitung, $array)){
                return "true";
            }
        } else { return "false"; }
    }
    
    static function partial7($bayar, $sisa)
    {
        $array = array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12);
        if ($bayar <= $sisa){
            $hitung = $sisa/$bayar;
            if (in_array($hitung, $array)){
                return "true";
            }
        } else { return "false"; }
    }
    
    static function partial8($bayar, $sisa)
    {
        $array = array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12);
        if ($bayar <= $sisa){
            $hitung = $sisa/$bayar;
            if (in_array($hitung, $array)){
                return "true";
            }
        } else { return "false"; }
    }
    
    static function partial9($bayar, $sisa)
    {
        $array = array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12);
        if ($bayar <= $sisa){
            $hitung = $sisa/$bayar;
            if (in_array($hitung, $array)){
                return "true";
            }
        } else { return "false"; }
    }
    
    static function partial10($bayar, $sisa)
    {
        $array = array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12);
        if ($bayar <= $sisa){
            $hitung = $sisa/$bayar;
            if (in_array($hitung, $array)){
                return "true";
            }
        } else { return "false"; }
    }
    
    static function partial11($bayar, $sisa)
    {
        $array = array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12);
        if ($bayar <= $sisa){
            $hitung = $sisa/$bayar;
            if (in_array($hitung, $array)){
                return "true";
            }
        } else { return "false"; }
    }
    
    static function partial12($bayar, $sisa)
    {
        $array = array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12);
        if ($bayar <= $sisa){
            $hitung = $sisa/$bayar;
            if (in_array($hitung, $array)){
                return "true";
            }
        } else { return "false"; }
    }    
}