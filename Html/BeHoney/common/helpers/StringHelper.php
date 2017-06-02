<?php

namespace common\helpers;

use Yii;
use yii\base\InvalidParamException;
use yii\helpers\Json;

/**
 * StringHelper
 *
 * @author SeynovAM <sejnovalexey@gmail.com>
 */
class StringHelper extends \yii\helpers\StringHelper
{
    /**
     * @param string $separator
     * @param array $array
     * @return string
     * @throws InvalidParamException
     */
    public static function implodeRecursive($separator, $array)
    {
        if (!is_array($array)) {
            throw new InvalidParamException('Param $array must be an array.');
        }
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $array[$key] = static::implodeRecursive($separator, $value);
            }
        }
        return implode($separator, $array);
    }

    /**
     * @param string $term
     * @return array
     */
    public static function explodeByWords($term)
    {
        $term = trim($term);
        if ($term === '' || $term === null) {
            return [];
        }
        return preg_split('/(\s+)/', $term, null, PREG_SPLIT_NO_EMPTY);
    }

    /**
     * Changes case to upper for the first char.
     * @param string $str the input string.
     * @return string input string with converted to upper the first char.
     */
    public static function ucfirst($str)
    {
        if (!static::strlen($str)) {
            return '';
        }
        return static::strtoupper(static::substr($str, 0, 1)) .
            static::substr($str, 1);
    }

    /**
     * Changes case to lower for the first char.
     * @param string $str the input string.
     * @return string input string with converted to lower the first char.
     */
    public static function lcfirst($str)
    {
        if (!static::strlen($str)) {
            return '';
        }
        return static::strtolower(static::substr($str, 0, 1)) .
            static::substr($str, 1);
    }

    /**
     * Returns substring of input string.
     * @param string $str the input string.
     * @param integer $start start position.
     * @param integer $length length of result string.
     * @return string substring.
     */
    public static function substr($str, $start, $length = null)
    {
        if (!Yii::$app) {
            if ($length === null) {
                return substr($str, $start);
            }
            return substr($str, $start, $length);
        }

        if ($start < 0) {
            $strlen = static::strlen($str);
            $start = $strlen + $start;
            if ($start < 0) {
                $start = 0;
            }
        }
        if ($length === null) {
            $strlen = isset($strlen) ? $strlen : static::strlen($str);
            if ($strlen - 1 < $start) {
                return false;
            }
            $length = $strlen - $start;
        } elseif ($length == 0) {
            return '';
        } elseif ($length < 0) {
            $strlen = isset($strlen) ? $strlen : static::strlen($str);
            $length = $strlen - $start - $length;
            if ($length <= 0) {
                return false;
            }
        }
        return mb_substr($str, $start, $length, Yii::$app->charset);
    }

    /**
     * @param string $str
     * @return integer
     */
    public static function strlen($str)
    {
        if (Yii::$app) {
            return mb_strlen($str, Yii::$app->charset);
        } else {
            return strlen($str);
        }
    }

    /**
     * @param string $str
     * @return string
     */
    public static function strtolower($str)
    {
        if (Yii::$app) {
            return mb_strtolower($str, Yii::$app->charset);
        } else {
            return strtolower($str);
        }
    }

    /**
     * @param string $str
     * @return string
     */
    public static function strtoupper($str)
    {
        if (Yii::$app) {
            return mb_strtoupper($str, Yii::$app->charset);
        } else {
            return strtoupper($str);
        }
    }

    /**
     * Find the position of the first occurrence of a substring in a string
     * @param string $string The string to search in.
     * @param string $substr If `$needle` is not a string, it is converted
     * to an integer and applied as the ordinal value of a character.
     * @param integer $offset If specified, search will start this number of characters counted from
     * the beginning of the string.
     * @return mixed the position of where the needle exists relative to the beginning of
     * the `$haystack` string (independent of offset). False means the needle was not found.
     */
    public static function strpos($string, $substr, $offset = 0)
    {
        if (Yii::$app) {
            $substr = is_string($substr) ? $substr : chr($substr);
            return mb_strpos($string, $substr, $offset, Yii::$app->charset);
        }
        return strpos($string, $substr, $offset);
    }

    /**
     * Find the position of the last occurrence of a substring in a string
     * @param string $string The string to search in.
     * @param string $substr If `$needle` is not a string, it is converted
     * to an integer and applied as the ordinal value of a character.
     * @param integer $offset If specified, search will start this number of characters counted from
     * the beginning of the string.
     * @return mixed the position of where the needle exists relative to the beginning of
     * the `$haystack` string (independent of offset). False means the needle was not found.
     */
    public static function strrpos($string, $substr, $offset = 0)
    {
        if (Yii::$app) {
            $substr = is_string($substr) ? $substr : chr($substr);
            return mb_strrpos($string, $substr, $offset, Yii::$app->charset);
        }
        return strrpos($string, $substr, $offset);
    }

    /**
     * Strips all tags and converts html to text. All br tags will be as \n in result.
     * @param string $content
     * @return string
     */
    public static function html2text($content)
    {
        $content = preg_replace('/\s/u', ' ', $content);
        $content = preg_replace('#<\s*br[^/>]*/?>#iu', "\n", $content);
        $content = preg_replace('/(<p[^>]*>([^<]*)<\/p[^>]*>)/iu', "$2\n", $content);
        $content = strip_tags($content);

        $entities = get_html_translation_table(HTML_ENTITIES, ENT_QUOTES, Yii::$app ? Yii::$app->charset : 'UTF-8');
        $content = str_ireplace(array_values($entities), array_keys($entities), $content);
        if (preg_match_all('/&#(\d{2,4});/u', $content, $matches, PREG_SET_ORDER | PREG_OFFSET_CAPTURE)) {
            foreach ($matches as $symbolMatches) {
                $char = '"\u' . str_pad(base_convert($symbolMatches[1][0], 10, 16), 4, '0', STR_PAD_LEFT) . '"';
                try {
                    $utf8Char = @Json::decode($char);
                } catch (\Exception $ex) {
                    $utf8Char = null;
                }
                if (is_string($utf8Char)) {
                    $content = substr_replace($content, $utf8Char, $symbolMatches[0][1], strlen($symbolMatches[0][0]));
                }
            }
        }

        $content = preg_replace('/\h*(\v)\h*/u', "\n", $content);
        $content = preg_replace('/\h{2,}/u', ' ', $content);

        $content = trim($content);
        return $content;
    }

    /**
     * Generates a random password of specified length.
     * The string generated matches [A-Za-z0-9]+ and is transparent to URL-encoding.
     * @return string generated password.
     */
    public static function generatePassword($length = 16)
    {
        $result = Yii::$app->getSecurity()->generateRandomString($length);

        static $alphabet = null;
        if ($alphabet === null) {
            $alphabet = '';
            for ($ascii = ord('a'), $zCode = ord('z'); $ascii <= $zCode; ++$ascii) {
                $char = chr($ascii);
                $alphabet .= ucfirst("$char$char");
            }
        }

        $alphabet = str_shuffle($alphabet);
        return strtr($result, [
            '-' => $alphabet[0],
            '_' => $alphabet[1],
        ]);
    }
}
