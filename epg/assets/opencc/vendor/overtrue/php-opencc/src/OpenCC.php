<?php

namespace Overtrue\PHPOpenCC;

/**
 * @method static string s2t(string $input)
 * @method static string s2hk(string $input)
 * @method static string s2jp(string $input)
 * @method static string s2tw(string $input)
 * @method static string s2twp(string $input)
 * @method static string hk2t(string $input)
 * @method static string hk2s(string $input)
 * @method static string tw2s(string $input)
 * @method static string tw2t(string $input)
 * @method static string tw2sp(string $input)
 * @method static string t2hk(string $input)
 * @method static string t2s(string $input)
 * @method static string t2tw(string $input)
 * @method static string t2jp(string $input)
 * @method static string jp2t(string $input)
 * @method static string jp2s(string $input)
 * @method static string simplifiedToTraditional(string $input)
 * @method static string simplifiedToHongkong(string $input)
 * @method static string simplifiedToJapanese(string $input)
 * @method static string simplifiedToTaiwan(string $input)
 * @method static string simplifiedToTaiwan_with_phrase(string $input)
 * @method static string hongkongToTraditional(string $input)
 * @method static string hongkongToSimplified(string $input)
 * @method static string taiwanToSimplified(string $input)
 * @method static string taiwanToTraditional(string $input)
 * @method static string taiwanToSimplified_with_phrase(string $input)
 * @method static string traditionalToHongkong(string $input)
 * @method static string traditionalToSimplified(string $input)
 * @method static string traditionalToTaiwan(string $input)
 * @method static string traditionalToJapanese(string $input)
 * @method static string japaneseToTraditional(string $input)
 * @method static string japaneseToSimplified(string $input)
 */
class OpenCC
{
    public static function convert(string $input, string $strategy = Strategy::SIMPLIFIED_TO_TRADITIONAL): string
    {
        $converter = new Converter();

        return $converter->convert($input, Dictionary::get($strategy));
    }

    public static function __callStatic(string $name, array $arguments)
    {
        // s2t() => Strategy::S2T => (), simplifiedToTraditional() -> Strategy::SIMPLIFIED_TO_TRADITIONAL
        $strategy = strtoupper(preg_replace_callback('/[A-Z]/', function ($matches) {
            return '_'.$matches[0];
        }, lcfirst($name)));

        if (! constant(Strategy::class.'::'.strtoupper($strategy))) {
            throw new \BadMethodCallException(sprintf('Method "%s" does not exist.', $strategy));
        }

        return static::convert($arguments[0], constant(Strategy::class.'::'.$strategy));
    }
}
