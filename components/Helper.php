<?php
/**
 * Created by PhpStorm.
 * User: slashman
 * Date: 02.03.15
 * Time: 19:42
 */

namespace app\components;

class Helper
{

    public static function shortLine($line, $chars)
    {
        if (mb_strlen($line, 'UTF-8') > $chars + 1) {
            return mb_substr($line, 0, $chars, 'UTF-8') . '&hellip;';
        } else {
            return $line;
        }
    }

    public static function clearPhone($phone)
    {
        return str_replace(')', '', str_replace(' ', '', str_replace('-', '', str_replace('(', '', $phone))));
    }

    public static function rusDate()
    {
        $months = ['Января', 'Февраля', 'Марта', 'Апреля', 'Мая', 'Июня', 'Июля', 'Августа', 'Сентября', 'Октября', 'Ноября', 'Декабря'];

        $day = intval(date('d'));
        $month = intval(date('m'));
        $year = intval(date('Y'));

        return $day . ' ' . $months[$month - 1] . ' ' . $year . ' г.';
    }

    public static function num2str($num)
    {
        $nul = 'ноль';

        $ten = array(
          array('', 'один', 'два', 'три', 'четыре', 'пять', 'шесть', 'семь', 'восемь', 'девять'),
          array('', 'одна', 'две', 'три', 'четыре', 'пять', 'шесть', 'семь', 'восемь', 'девять'),
        );

        $a20 = array('десять', 'одиннадцать', 'двенадцать', 'тринадцать', 'четырнадцать', 'пятнадцать', 'шестнадцать', 'семнадцать', 'восемнадцать', 'девятнадцать');
        $tens = array(2 => 'двадцать', 'тридцать', 'сорок', 'пятьдесят', 'шестьдесят', 'семьдесят', 'восемьдесят', 'девяносто');
        $hundred = array('', 'сто', 'двести', 'триста', 'четыреста', 'пятьсот', 'шестьсот', 'семьсот', 'восемьсот', 'девятьсот');

        $unit = array( // Units
                       array('копейка', 'копейки', 'копеек', 1),
                       array('рубль', 'рубля', 'рублей', 0),
                       array('тысяча', 'тысячи', 'тысяч', 1),
                       array('миллион', 'миллиона', 'миллионов', 0),
                       array('миллиард', 'милиарда', 'миллиардов', 0),
        );

        //
        list($rub, $kop) = explode('.', sprintf("%015.2f", floatval($num)));

        $out = array();
        if (intval($rub) > 0) {
            foreach (str_split($rub, 3) as $uk => $v) { // by 3 symbols
                if (!intval($v)) {
                    continue;
                }
                $uk = sizeof($unit) - $uk - 1; // unit key
                $gender = $unit[$uk][3];
                list($i1, $i2, $i3) = array_map('intval', str_split($v, 1));
                // mega-logic
                $out[] = $hundred[$i1]; # 1xx-9xx
                if ($i2 > 1) {
                    $out[] = $tens[$i2] . ' ' . $ten[$gender][$i3];
                } # 20-99
                else {
                    $out[] = $i2 > 0 ? $a20[$i3] : $ten[$gender][$i3];
                } # 10-19 | 1-9
                // units without rub & kop
                if ($uk > 1) {
                    $out[] = static::morph($v, $unit[$uk][0], $unit[$uk][1], $unit[$uk][2]);
                }
            } //foreach
        } else {
            $out[] = $nul;
        }

        $out[] = static::morph(intval($rub), $unit[1][0], $unit[1][1], $unit[1][2]); // rub
        $out[] = $kop . ' ' . static::morph($kop, $unit[0][0], $unit[0][1], $unit[0][2]); // kop

        return trim(preg_replace('/ {2,}/', ' ', join(' ', $out)));
    }

    private static function morph($n, $f1, $f2, $f5)
    {
        $n = abs(intval($n)) % 100;
        if ($n > 10 && $n < 20) {
            return $f5;
        }
        $n = $n % 10;
        if ($n > 1 && $n < 5) {
            return $f2;
        }
        if ($n == 1) {
            return $f1;
        }

        return $f5;
    }

    public static function randomPassword($password_length = 8, $strong = false)
    {
        $alphabet = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789";
        if ($strong) {
            $alphabet .= '~!@#$%^&*()_+';
        }
        $pass = array(); //remember to declare $pass as an array
        $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
        for ($i = 0; $i < $password_length; $i++) {
            $n = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }

        return implode($pass); //turn the array into a string
    }

}