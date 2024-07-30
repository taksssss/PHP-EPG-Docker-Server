<?php

namespace Overtrue\PHPOpenCC\Contracts;

interface ConverterInterface
{
    /**
     * @param  array<array<string, string>>  $dictionaries
     *
     *@example
     *         $string = '一口吃個胖子';
     *         $dictionaries = [
     *                 ['HKVariants' => ['一' => '壹', '個' => '個', '胖' => '胖', '子' => '子']],
     *                 ['STPhrases' => ['壹個' => '一個']],
     *                  // 可能同时包含多组词典
     *                 [
     *                      [
     *                          'HKVariantsRevPhrases' => ['一個' => '壹個'],
     *                          'HKVariantsRev' => ['壹' => '一', '個' => '個', '胖' => '胖', '子' => '子'],
     *                      ]
     *                 ]
     *         ]
     */
    public function convert(string $string, array $dictionaries): string;
}
