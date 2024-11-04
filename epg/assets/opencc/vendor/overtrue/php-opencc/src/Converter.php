<?php

namespace Overtrue\PHPOpenCC;

use Overtrue\PHPOpenCC\Contracts\ConverterInterface;

class Converter implements ConverterInterface
{
    public function convert(string $string, array $dictionaries): string
    {
        foreach ($dictionaries as $dictionary) {
            // [['f1' => 't1'], ['f2' => 't2'], ...]
            if (is_array(reset($dictionary))) {
                $tmp = [];
                foreach ($dictionary as $dict) {
                    $tmp = array_merge($tmp, $dict);
                }
                $dictionary = $tmp;
            }

            uksort($dictionary, function ($a, $b) {
                return mb_strlen($b) <=> mb_strlen($a);
            });

            $string = strtr($string, $dictionary);
        }

        return $string;
    }
}
