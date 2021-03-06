<?php

/*
 *	Copyright 2015 RhubarbPHP
 *
 *  Licensed under the Apache License, Version 2.0 (the "License");
 *  you may not use this file except in compliance with the License.
 *  You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 *  Unless required by applicable law or agreed to in writing, software
 *  distributed under the License is distributed on an "AS IS" BASIS,
 *  WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 *  See the License for the specific language governing permissions and
 *  limitations under the License.
 */

namespace Rhubarb\Crown\String;

/**
 * A utility class for manipulating strings
 */
class StringTools
{
    /**
     * Removes all blanks in an array and merges them into a string
     *
     * @param string $glue String to include between each element
     * @param array $array Array containing elements to be imploded
     * @param string $itemCallback A transformation closure to call on each element of the array
     * @param string $keysToInclude,... Keys of the array elements to include - if not supplied, all elements will be used
     * @return string String of concatenated elements
     */
    public static function implodeIgnoringBlanks($glue, $array, $itemCallback = null, $keysToInclude = null)
    {
        $array = array_filter($array);

        if (!empty($itemCallback)) {
            foreach ($array as $key => $value) {
                $array[$key] = $itemCallback($value);
            }
        }

        $keys = array($keysToInclude);

        for ($i = 4; $i < func_num_args(); $i++) {
            $keys[] = func_get_arg($i);
        }

        $keys = array_filter($keys);

        $string = "";

        if (count($keys)) {
            foreach ($keys as $key) {
                if (!empty($array[$key])) {
                    $string .= $array[$key] . $glue;
                }
            }
            if (strlen($string) >= strlen($glue)) {
                $string = substr($string, 0, strlen($string) - strlen($glue));
            }
        } else {
            $string = implode($glue, $array);
        }

        return $string;
    }

    /**
     * Turns StringLikeThis into String Like This
     */
    public static function wordifyStringByUpperCase($string)
    {
        return implode(
            " ",
            preg_split("#([A-Z][a-z]+)#", ucwords($string), -1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE)
        );
    }

    /**
     * Returns the English singular form of a plural word.
     *
     * @param $plural
     *
     * @return string
     */
    public static function makeSingular($plural)
    {
        $plurals = ["ies" => "y", "es" => "", "s" => ""];

        foreach ($plurals as $pluralEnding => $singularEnding) {
            $plural = preg_replace("/" . $pluralEnding . "$/i", $singularEnding, $plural);
        }

        return $plural;
    }

    /**
     * Returns the English plural form of a singular word.
     *
     * @param $singular
     *
     * @return mixed|string
     */
    public static function makePlural($singular)
    {
        $singulars = [
            "ch" => "ches",
            "s" => "ses",
            "x" => "xes",
            "y" => "ies"
        ];

        foreach ($singulars as $singularEnding => $pluralEnding) {
            if (preg_match("/" . $singularEnding . "$/i", $singular)) {
                return preg_replace("/" . $singularEnding . "$/i", $pluralEnding, $singular);
            }
        }

        return $singular . "s";
    }

    /**
     * Returns the singular form of a word if $quantity is 1, otherwise the plural form
     *
     * @param string $singular The singular form of the word
     * @param int|float $quantity The quantity to check
     * @param bool $includeCount If true, the quantity will be prepended to the return string
     * @param int $decimalPlaces Used to format the quantity if it is included
     *
     * @return string
     */
    public static function makePluralWithQuantity($singular, $quantity, $includeCount = false, $decimalPlaces = 0)
    {
        $return = "";
        if ($includeCount) {
            $return = number_format($quantity, $decimalPlaces) . " ";
        }

        if (round($quantity, $decimalPlaces) == 1) {
            return $return . $singular;
        }

        return $return . self::makePlural($singular);
    }

    /**
     * Returns true if $needle is found in $haystack, false otherwise
     *
     * @param string $haystack
     * @param string $needle
     * @param bool $caseSensitive
     *
     * @return bool
     */
    public static function contains($haystack, $needle, $caseSensitive = true)
    {
        return ($caseSensitive ? strpos($haystack, $needle) : stripos($haystack, $needle)) !== false;
    }

    /**
     * Returns true if $haystack starts with $needle, false otherwise
     *
     * @param string $haystack
     * @param string $needle
     * @param bool $caseSensitive
     *
     * @return bool
     */
    public static function startsWith($haystack, $needle, $caseSensitive = true)
    {
        return ($caseSensitive ? strpos($haystack, $needle) : stripos($haystack, $needle)) === 0;
    }

    /**
     * Returns true if $haystack ends with $needle, false otherwise
     *
     * @param string $haystack
     * @param string $needle
     * @param bool $caseSensitive
     *
     * @return bool
     */
    public static function endsWith($haystack, $needle, $caseSensitive = true)
    {
        $haystackLength = strlen($haystack);
        $needleLength = strlen($needle);
        if ($haystackLength < $needleLength) {
            return false;
        }

        $haystack = substr($haystack, -$needleLength);
        if (!$caseSensitive) {
            $haystack = strtolower($haystack);
            $needle = strtolower($needle);
        }
        return $haystack == $needle;
    }

    /**
     * Returns $string with $numberOfChars characters removed from its end
     *
     * @param string $string
     * @param int $numberOfChars
     *
     * @return string
     */
    public static function removeCharsFromEnd($string, $numberOfChars)
    {
        $length = strlen($string);
        if ($numberOfChars >= $length) {
            return $string;
        }
        return substr($string, 0, $length - $numberOfChars);
    }

    /**
     * Returns all characters in $string after the first (or last, depending on $firstOccurrence switch) match of $search
     *
     * @param string $string
     * @param string $search
     * @param bool $firstOccurrence True to start from first match, false to start from the last match
     * @param int|null $maxChars If null, all characters after the match will be returned. If specified, no more than this will be returned.
     * @param bool $caseSensitive
     * @param bool $includeSearch If true, the returned value will include the matched occurrence of $search.
     * @param mixed $returnIfNoMatch This value will be returned if $search is not found in $string
     *
     * @return string
     */
    public static function getCharsAfterMatch(
        $string,
        $search,
        $firstOccurrence = false,
        $maxChars = null,
        $caseSensitive = true,
        $includeSearch = false,
        $returnIfNoMatch = false
    ) {
        $posFunction = "str" . ($firstOccurrence ? "" : "r") . ($caseSensitive ? "" : "i") . "pos";
        $start = $posFunction($string, $search);
        if ($start === false) {
            return $returnIfNoMatch;
        }
        if (!$includeSearch) {
            $start += strlen($search);
        }
        if ($maxChars === null) {
            return substr($string, $start);
        }
        return substr($string, $start, $maxChars);
    }

    /**
     * Scan the $string for {placeholders} that can be used as key names.
     * Keys found will be replaced with a value from supplied $data source. Multiple $data sources can be supplied,
     * each passed as an additional parameter. The first not-empty value matching the key in the data sources will be used.
     *
     * @param string $string
     * @param array $data
     *
     * @return string
     */
    public static function parseTemplateString($string, $data)
    {
        $args = func_get_args();
        $dataSources = [];
        for ($i = 1; $i < count($args); $i++) {
            $dataSources[] = $args[$i];
        }
        $t = $html = $string;

        while (preg_match("/[{]([^{}]+)[}]/", $t, $regs)) {
            $t = str_replace($regs[0], "", $t);

            $value = "";
            foreach ($dataSources as $data) {
                if (is_object($data)) {
                    $value = @$data->$regs[1];
                } else {
                    if (is_array($data)) {
                        $value = @$data[$regs[1]];
                    }
                }

                if (!empty($value)) {
                    break;
                }
            }

            $html = str_replace($regs[0], (string)$value, $html);
        }

        return $html;
    }

    /**
     * @param string $string Word that you wish to pluralise
     * @param int $number Quantity to determine whether the string should be pluralised
     *
     * @return string Pluralised string
     */
    public static function pluralise($string, $number)
    {
        return $number != 1 ? $string . "s" : $string;
    }

    /**
     * Alias of Pluralise.
     *
     * @param string $string Word that you wish to pluralise
     * @param int $number Quantity to determine whether the string should be pluralised
     *
     * @return string Pluralised string
     */
    public static function pluralize($string, $number)
    {
        return self::pluralise($string, $number);
    }

    /**
     * Returns a sentence with each word in $wordList concatenated with commas between each,
     * and "and" between the last 2 words. Skips any empty values in the list.
     *
     * @param string[] $wordList List of words to convert to sentence
     *
     * @return string Sentence
     */
    public static function listToSentence($wordList)
    {
        $wordList = array_filter($wordList);
        $sentence = implode("< ", $wordList);

        return preg_replace('/^(.+)(, )([^,]+)$/', '$1 and $3', $sentence);
    }
}