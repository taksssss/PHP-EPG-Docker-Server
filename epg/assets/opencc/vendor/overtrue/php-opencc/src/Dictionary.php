<?php

namespace Overtrue\PHPOpenCC;

class Dictionary
{
    const SETS_MAP = [
        Strategy::SIMPLIFIED_TO_TRADITIONAL => [['STPhrases', 'STCharacters']], // S2T
        Strategy::SIMPLIFIED_TO_HONGKONG => [['STPhrases', 'STCharacters'], 'HKVariants'], // S2HK
        Strategy::SIMPLIFIED_TO_JAPANESE => [['STPhrases', 'STCharacters'], 'JPVariants'], // S2JP
        Strategy::SIMPLIFIED_TO_TAIWAN => [['STPhrases', 'STCharacters'], 'TWVariants'], // S2TW
        Strategy::SIMPLIFIED_TO_TAIWAN_WITH_PHRASE => [['STPhrases', 'STCharacters'], ['TWPhrases', 'TWVariants']], // S2TWP
        Strategy::HONGKONG_TO_TRADITIONAL => [['HKVariantsRevPhrases', 'HKVariantsRev']], // HK2T
        Strategy::HONGKONG_TO_SIMPLIFIED => [['HKVariantsRevPhrases', 'HKVariantsRev'], ['TSPhrases', 'TSCharacters']], // HK2S
        Strategy::TAIWAN_TO_SIMPLIFIED => [['TWVariantsRevPhrases', 'TWVariantsRev'], ['TSPhrases', 'TSCharacters']], // TW2S
        Strategy::TAIWAN_TO_TRADITIONAL => [['TWVariantsRevPhrases', 'TWVariantsRev']], // TW2T
        Strategy::TAIWAN_TO_SIMPLIFIED_WITH_PHRASE => [['TWPhrasesRev', 'TWVariantsRevPhrases', 'TWVariantsRev'], ['TSPhrases', 'TSCharacters']], // TW2SP
        Strategy::TRADITIONAL_TO_HONGKONG => ['HKVariants'], // T2HK
        Strategy::TRADITIONAL_TO_SIMPLIFIED => [['TSPhrases', 'TSCharacters']], // T2S
        Strategy::TRADITIONAL_TO_TAIWAN => ['TWVariants'], // T2TW
        Strategy::TRADITIONAL_TO_JAPANESE => ['JPVariants'], // T2JP
        Strategy::JAPANESE_TO_TRADITIONAL => [['JPShinjitaiPhrases', 'JPShinjitaiCharacters', 'JPVariantsRev']], // JP2T
        Strategy::JAPANESE_TO_SIMPLIFIED => [['JPShinjitaiPhrases', 'JPShinjitaiCharacters', 'JPVariantsRev'], ['TSPhrases', 'TSCharacters']], // JP2S
    ];

    const PARSED_DIR = __DIR__.'/../data/parsed';

    /**
     * @return array<string, array<string, string>>
     */
    public static function get(string $set): array
    {
        $set = constant(Strategy::class.'::'.strtoupper($set));

        if (! array_key_exists($set, self::SETS_MAP)) {
            throw new \InvalidArgumentException("Dictionary set [{$set}] does not exists.");
        }

        $dictionaries = [];
        foreach (self::SETS_MAP[$set] as $dictionary) {
            if (is_array($dictionary)) {
                $group = [];
                foreach ($dictionary as $dict) {
                    $group[$dict] = self::loadDictionary($dict);
                }
                $dictionaries[] = $group;

                continue;
            }
            $dictionaries[$dictionary] = self::loadDictionary($dictionary);
        }

        return $dictionaries;
    }

    protected static function loadDictionary(string $dictionary)
    {
        $dictionary = sprintf('%s/%s.php', self::PARSED_DIR, $dictionary);

        if (! file_exists($dictionary)) {
            throw new \InvalidArgumentException("Dictionary [{$dictionary}] does not exists.");
        }

        return require $dictionary;
    }
}
