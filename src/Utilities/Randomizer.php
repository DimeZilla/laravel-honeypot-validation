<?php

namespace DiamondHoneyPot\Utilities;


class Randomizer
{
     /**
     * Generates a random string that we'll use as a name for our HoneyPot field
     *
     * @see  https://stackoverflow.com/questions/4356289/php-random-string-generator
     * @param  integer $length  how long we want the name to be
     * @return string
     */
    public static function realRandomNameGenerator($length = 10)
    {
        $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
            // after the first run, lets append numbers and update
            // our string length. We don't want the name
            // to start with a number
            if ($i == 0) {
                $characters .= '0123456789';
                $charactersLength = strlen($characters);
            }
        }

        return $randomString;
    }

    /**
     * Generates a random name that used fake strings that sound like words
     * and peppers in real words
     * @param  integer $length The number of words to use in the string
     * @param  string  $delim  the delimiter to use
     * @return string
     */
    public static function randomNameGenerator($length = 2, $delim = '_')
    {
        $ret = [];
        $usedReal = false;
        $usedFake = false;
        for ($i = 0; $i < $length; $i++) {
            $word = '';
            if (rand(0,1) || ($i == $length - 1 && !$usedReal)) {
                $word = self::randomRealWord();
                $usedReal = true;
            }
            else {
                $word = self::randomWordlikeString(rand(1,7));
                $usedFake = true;
            }

            // lets enforce that even if we are asking for 1 name,
            // then lets at least ensure its fake
            if ($i == $length - 1 && !$usedFake) {
                $word = self::randomWordlikeString(rand(1,7));
                $usedFake = true;
            }

            // if we at the end of the loop and we haven't used a real word yet
            // lets enforce a real word;
            $ret[] = $word;
        }

        return join($delim, $ret);
    }

    /**
     * Gets a word that sounds like a string
     * @param  integer $length the length of the word
     * @return string
     */
    public static function randomWordlikeString($length = 6)
    {
        $string = '';
        $vowels = ['a', 'e', 'i', 'o', 'u'];
        $letters =[
            $vowels,
            array_values(
                array_diff(range('a', 'z'), $vowels)
            )
        ];
        unset($vowels);

        for ($i = 0; $i < $length; $i++) {
            if ($i === 0) {
                // randomly start with a consonant or a vowel
                $pool = $letters[rand(0, count($letters) - 1)];
            }
            else {
                $pool = $letters[0];
                if (in_array(substr($string, -1), $pool)) {
                    $pool = $letters[1];
                }
            }
            $string .= $pool[rand(0,count($pool) - 1)];
        }
        return $string;
    }

    /**
     * Retrieves a random real word from this small pool of words
     * @return string
     */
    public static function randomRealWord()
    {
        $words = [
            'web', 'internet', 'name', 'email', 'phone','password',
            'config', 'url', 'browser', 'type', 'minimum', 'maximum',
            'favorite', 'username', 'github', 'facebook', 'google', 'yahoo',
            'microsoft', 'chrome', 'mozilla', 'website'
        ];

        return $words[rand(0, count($words) - 1)];
    }
}
