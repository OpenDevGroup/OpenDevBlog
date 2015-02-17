<?php
/**
* @author Steve King
* @team Open Dev
* @package Open Dev Blog
* @version 1.0-alpha
* @license GNU
*/

namespace OpenDev;

class Util {
    
    const SECONDS_IN_A_MINUTE = 60;
    const SECONDS_IN_A_HOUR = 3600;
    const SECONDS_IN_AN_HOUR = 3600;
    const SECONDS_IN_A_DAY = 86400;
    const SECONDS_IN_A_WEEK = 604800;
    const SECONDS_IN_A_MONTH = 2592000;
    const SECONDS_IN_A_YEAR = 31536000;
   
    public static function wordCount($str) {
        return count(preg_split('/\s+/', strip_tags($str), null, PREG_SPLIT_NO_EMPTY));
    }

    public static function readingTime($content) {
        $word = self::wordCount($content);
        $min = floor($word / 175);
        $sec = floor($word % 175 / (175 / 60));
        if($min > 1) {
            return $time = $min . ' min' . ($min == 1 ? '' : 's');
        } elseif($sec <= 59) {
            return $time = $sec . ' second' . ($sec == 1 ? '' : 's');
        }
    }

    public static function slugify($string, $css_mode = FALSE) {
        $slug = preg_replace('/([^a-z0-9]+)/', '-', strtolower(self::remove_accents($string)));
        if ($css_mode) {
            $digits = ['zero', 'one', 'two', 'three', 'four', 'five', 'six', 'seven', 'eight', 'nine'];
            
            if (is_numeric(substr($slug, 0, 1))) {
                $slug = $digits[substr($slug, 0, 1)] . substr($slug, 1);
            }
        }
        return $slug;
    }
            
    public static function humanTimeDiff($from, $to = '', $as_text = FALSE, $suffix = ' ago') {
        if ($to == '') {
            $to = time();
        }
        
        $from = new \DateTime(date('Y-m-d H:i:s', $from));
        $to   = new \DateTime(date('Y-m-d H:i:s', $to));
        $diff = $from->diff($to);
        
        if ($diff->y > 1) {
            $text = $diff->y . ' years';
        } else if ($diff->y == 1) {
            $text = '1 year';
        } else if ($diff->m > 1) {
            $text = $diff->m . ' months';
        } else if ($diff->m == 1) {
            $text = '1 month';
        } else if ($diff->d > 7) {
            $text = ceil($diff->d / 7) . ' weeks';
        } else if ($diff->d == 7) {
            $text = '1 week';
        } else if ($diff->d > 1) {
            $text = $diff->d . ' days';
        } else if ($diff->d == 1) {
            $text = '1 day';
        } else if ($diff->h > 1) {
            $text = $diff->h . ' hours';
        } else if ($diff->h == 1) {
            $text = ' 1 hour';
        } else if ($diff->i > 1) {
            $text = $diff->i . ' minutes';
        } else if ($diff->i == 1) {
            $text = '1 minute';
        } else if ($diff->s > 1) {
            $text = $diff->s . ' seconds';
        } else {
            $text = '1 second';
        }
        
        if ($as_text) {
            $text = explode(' ', $text, 2);
            $text = self::numberToWord($text[0]) . ' ' . $text[1];
        }
        
        return trim($text) . $suffix;
    }
    
    public static function numberToWord($number) {
        $number = (string) $number;
        
        if (strpos($number, '.') !== FALSE) {
            list($number, $decimal) = explode('.', $number);
        } else {
            $number  = $number;
            $decimal = FALSE;
        }
        
        $output = '';
        
        if ($number[0] == '-') {
            $output = 'negative ';
            $number = ltrim($number, '-');
        } else if ($number[0] == '+') {
            $output = 'positive ';
            $number = ltrim($number, '+');
        }
        
        if ($number[0] == '0') {
            $output .= 'zero';
        } else {
            $number = str_pad($number, 36, '0', STR_PAD_LEFT);
            $group  = rtrim(chunk_split($number, 3, ' '), ' ');
            $groups = explode(' ', $group);
            
            $groups2 = array();
            
            foreach ($groups as $group) {
                $groups2[] = self::numberToWordThreeDigit($group[0], $group[1], $group[2]);
            }
            
            for ($z = 0; $z < count($groups2); $z++) {
                if ($groups2[$z] != '') {
                    $output .= $groups2[$z] . self::numberToWordConvertGroup(11 - $z);
                    $output .= ($z < 11 && !array_search('', array_slice($groups2, $z + 1, -1)) && $groups2[11] != '' && $groups[11][0] == '0' ? ' and ' : ', ');
                }
            }
            
            $output = rtrim($output, ', ');
        }
        
        if ($decimal > 0) {
            $output .= ' point';
            
            for ($i = 0; $i < strlen($decimal); $i++) {
                $output .= ' ' . self::numberToWordConvertDigit($decimal[$i]);
            }
        }
        
        return $output;
    }
    
    protected static function numberToWordConvertGroup($index) {
        switch ($index) {
            case 11:
                return ' decillion';
            case 10:
                return ' nonillion';
            case 9:
                return ' octillion';
            case 8:
                return ' septillion';
            case 7:
                return ' sextillion';
            case 6:
                return ' quintrillion';
            case 5:
                return ' quadrillion';
            case 4:
                return ' trillion';
            case 3:
                return ' billion';
            case 2:
                return ' million';
            case 1:
                return ' thousand';
            case 0:
                return '';
        }
    }
    
    protected static function numberToWordThreeDigit($digit1, $digit2, $digit3) {
        $output = '';
        
        if ($digit1 == '0' && $digit2 == '0' && $digit3 == '0') {
            return '';
        }
        
        if ($digit1 != '0') {
            $output .= self::numberToWordConvertDigit($digit1) . ' hundred';
            
            if ($digit2 != '0' || $digit3 != '0') {
                $output .= ' and ';
            }
        }
        if ($digit2 != '0') {
            $output .= self::numberToWordTwoDigit($digit2, $digit3);
        } else if ($digit3 != '0') {
            $output .= self::numberToWordConvertDigit($digit3);
        }
        
        return $output;
    }
    
    protected static function numberToWordTwoDigit($digit1, $digit2) {
        if ($digit2 == '0') {
            switch ($digit1) {
                case '1':
                    return 'ten';
                case '2':
                    return 'twenty';
                case '3':
                    return 'thirty';
                case '4':
                    return 'forty';
                case '5':
                    return 'fifty';
                case '6':
                    return 'sixty';
                case '7':
                    return 'seventy';
                case '8':
                    return 'eighty';
                case '9':
                    return 'ninety';
            }
        } else if ($digit1 == '1') {
            switch ($digit2) {
                case '1':
                    return 'eleven';
                case '2':
                    return 'twelve';
                case '3':
                    return 'thirteen';
                case '4':
                    return 'fourteen';
                case '5':
                    return 'fifteen';
                case '6':
                    return 'sixteen';
                case '7':
                    return 'seventeen';
                case '8':
                    return 'eighteen';
                case '9':
                    return 'nineteen';
            }
        } else {
            $second_digit = self::numberToWordConvertDigit($digit2);
            
            switch ($digit1) {
                case '2':
                    return "twenty-{$second_digit}";
                case '3':
                    return "thirty-{$second_digit}";
                case '4':
                    return "forty-{$second_digit}";
                case '5':
                    return "fifty-{$second_digit}";
                case '6':
                    return "sixty-{$second_digit}";
                case '7':
                    return "seventy-{$second_digit}";
                case '8':
                    return "eighty-{$second_digit}";
                case '9':
                    return "ninety-{$second_digit}";
            }
        }
    }
    
    protected static function numberToWordConvertDigit($digit) {
        switch ($digit) {
            case '0':
                return 'zero';
            case '1':
                return 'one';
            case '2':
                return 'two';
            case '3':
                return 'three';
            case '4':
                return 'four';
            case '5':
                return 'five';
            case '6':
                return 'six';
            case '7':
                return 'seven';
            case '8':
                return 'eight';
            case '9':
                return 'nine';
        }
    }

    /**
     * Converts all accent characters to ASCII characters.
     *
     * If there are no accent characters, then the string given is just
     * returned.
     *
     * @param  string $string  Text that might have accent characters
     * @return string Filtered string with replaced "nice" characters
     */
    public static function remove_accents($string) {
        if (!preg_match('/[\x80-\xff]/', $string)) {
            return $string;
        }
        
        if (self::seems_utf8($string)) {
            $chars = array(
                
                // Decompositions for Latin-1 Supplement
                chr(194) . chr(170) => 'a',
                chr(194) . chr(186) => 'o',
                chr(195) . chr(128) => 'A',
                chr(195) . chr(129) => 'A',
                chr(195) . chr(130) => 'A',
                chr(195) . chr(131) => 'A',
                chr(195) . chr(132) => 'A',
                chr(195) . chr(133) => 'A',
                chr(195) . chr(134) => 'AE',
                chr(195) . chr(135) => 'C',
                chr(195) . chr(136) => 'E',
                chr(195) . chr(137) => 'E',
                chr(195) . chr(138) => 'E',
                chr(195) . chr(139) => 'E',
                chr(195) . chr(140) => 'I',
                chr(195) . chr(141) => 'I',
                chr(195) . chr(142) => 'I',
                chr(195) . chr(143) => 'I',
                chr(195) . chr(144) => 'D',
                chr(195) . chr(145) => 'N',
                chr(195) . chr(146) => 'O',
                chr(195) . chr(147) => 'O',
                chr(195) . chr(148) => 'O',
                chr(195) . chr(149) => 'O',
                chr(195) . chr(150) => 'O',
                chr(195) . chr(153) => 'U',
                chr(195) . chr(154) => 'U',
                chr(195) . chr(155) => 'U',
                chr(195) . chr(156) => 'U',
                chr(195) . chr(157) => 'Y',
                chr(195) . chr(158) => 'TH',
                chr(195) . chr(159) => 's',
                chr(195) . chr(160) => 'a',
                chr(195) . chr(161) => 'a',
                chr(195) . chr(162) => 'a',
                chr(195) . chr(163) => 'a',
                chr(195) . chr(164) => 'a',
                chr(195) . chr(165) => 'a',
                chr(195) . chr(166) => 'ae',
                chr(195) . chr(167) => 'c',
                chr(195) . chr(168) => 'e',
                chr(195) . chr(169) => 'e',
                chr(195) . chr(170) => 'e',
                chr(195) . chr(171) => 'e',
                chr(195) . chr(172) => 'i',
                chr(195) . chr(173) => 'i',
                chr(195) . chr(174) => 'i',
                chr(195) . chr(175) => 'i',
                chr(195) . chr(176) => 'd',
                chr(195) . chr(177) => 'n',
                chr(195) . chr(178) => 'o',
                chr(195) . chr(179) => 'o',
                chr(195) . chr(180) => 'o',
                chr(195) . chr(181) => 'o',
                chr(195) . chr(182) => 'o',
                chr(195) . chr(184) => 'o',
                chr(195) . chr(185) => 'u',
                chr(195) . chr(186) => 'u',
                chr(195) . chr(187) => 'u',
                chr(195) . chr(188) => 'u',
                chr(195) . chr(189) => 'y',
                chr(195) . chr(190) => 'th',
                chr(195) . chr(191) => 'y',
                chr(195) . chr(152) => 'O',
                
                // Decompositions for Latin Extended-A
                chr(196) . chr(128) => 'A',
                chr(196) . chr(129) => 'a',
                chr(196) . chr(130) => 'A',
                chr(196) . chr(131) => 'a',
                chr(196) . chr(132) => 'A',
                chr(196) . chr(133) => 'a',
                chr(196) . chr(134) => 'C',
                chr(196) . chr(135) => 'c',
                chr(196) . chr(136) => 'C',
                chr(196) . chr(137) => 'c',
                chr(196) . chr(138) => 'C',
                chr(196) . chr(139) => 'c',
                chr(196) . chr(140) => 'C',
                chr(196) . chr(141) => 'c',
                chr(196) . chr(142) => 'D',
                chr(196) . chr(143) => 'd',
                chr(196) . chr(144) => 'D',
                chr(196) . chr(145) => 'd',
                chr(196) . chr(146) => 'E',
                chr(196) . chr(147) => 'e',
                chr(196) . chr(148) => 'E',
                chr(196) . chr(149) => 'e',
                chr(196) . chr(150) => 'E',
                chr(196) . chr(151) => 'e',
                chr(196) . chr(152) => 'E',
                chr(196) . chr(153) => 'e',
                chr(196) . chr(154) => 'E',
                chr(196) . chr(155) => 'e',
                chr(196) . chr(156) => 'G',
                chr(196) . chr(157) => 'g',
                chr(196) . chr(158) => 'G',
                chr(196) . chr(159) => 'g',
                chr(196) . chr(160) => 'G',
                chr(196) . chr(161) => 'g',
                chr(196) . chr(162) => 'G',
                chr(196) . chr(163) => 'g',
                chr(196) . chr(164) => 'H',
                chr(196) . chr(165) => 'h',
                chr(196) . chr(166) => 'H',
                chr(196) . chr(167) => 'h',
                chr(196) . chr(168) => 'I',
                chr(196) . chr(169) => 'i',
                chr(196) . chr(170) => 'I',
                chr(196) . chr(171) => 'i',
                chr(196) . chr(172) => 'I',
                chr(196) . chr(173) => 'i',
                chr(196) . chr(174) => 'I',
                chr(196) . chr(175) => 'i',
                chr(196) . chr(176) => 'I',
                chr(196) . chr(177) => 'i',
                chr(196) . chr(178) => 'IJ',
                chr(196) . chr(179) => 'ij',
                chr(196) . chr(180) => 'J',
                chr(196) . chr(181) => 'j',
                chr(196) . chr(182) => 'K',
                chr(196) . chr(183) => 'k',
                chr(196) . chr(184) => 'k',
                chr(196) . chr(185) => 'L',
                chr(196) . chr(186) => 'l',
                chr(196) . chr(187) => 'L',
                chr(196) . chr(188) => 'l',
                chr(196) . chr(189) => 'L',
                chr(196) . chr(190) => 'l',
                chr(196) . chr(191) => 'L',
                chr(197) . chr(128) => 'l',
                chr(197) . chr(129) => 'L',
                chr(197) . chr(130) => 'l',
                chr(197) . chr(131) => 'N',
                chr(197) . chr(132) => 'n',
                chr(197) . chr(133) => 'N',
                chr(197) . chr(134) => 'n',
                chr(197) . chr(135) => 'N',
                chr(197) . chr(136) => 'n',
                chr(197) . chr(137) => 'N',
                chr(197) . chr(138) => 'n',
                chr(197) . chr(139) => 'N',
                chr(197) . chr(140) => 'O',
                chr(197) . chr(141) => 'o',
                chr(197) . chr(142) => 'O',
                chr(197) . chr(143) => 'o',
                chr(197) . chr(144) => 'O',
                chr(197) . chr(145) => 'o',
                chr(197) . chr(146) => 'OE',
                chr(197) . chr(147) => 'oe',
                chr(197) . chr(148) => 'R',
                chr(197) . chr(149) => 'r',
                chr(197) . chr(150) => 'R',
                chr(197) . chr(151) => 'r',
                chr(197) . chr(152) => 'R',
                chr(197) . chr(153) => 'r',
                chr(197) . chr(154) => 'S',
                chr(197) . chr(155) => 's',
                chr(197) . chr(156) => 'S',
                chr(197) . chr(157) => 's',
                chr(197) . chr(158) => 'S',
                chr(197) . chr(159) => 's',
                chr(197) . chr(160) => 'S',
                chr(197) . chr(161) => 's',
                chr(197) . chr(162) => 'T',
                chr(197) . chr(163) => 't',
                chr(197) . chr(164) => 'T',
                chr(197) . chr(165) => 't',
                chr(197) . chr(166) => 'T',
                chr(197) . chr(167) => 't',
                chr(197) . chr(168) => 'U',
                chr(197) . chr(169) => 'u',
                chr(197) . chr(170) => 'U',
                chr(197) . chr(171) => 'u',
                chr(197) . chr(172) => 'U',
                chr(197) . chr(173) => 'u',
                chr(197) . chr(174) => 'U',
                chr(197) . chr(175) => 'u',
                chr(197) . chr(176) => 'U',
                chr(197) . chr(177) => 'u',
                chr(197) . chr(178) => 'U',
                chr(197) . chr(179) => 'u',
                chr(197) . chr(180) => 'W',
                chr(197) . chr(181) => 'w',
                chr(197) . chr(182) => 'Y',
                chr(197) . chr(183) => 'y',
                chr(197) . chr(184) => 'Y',
                chr(197) . chr(185) => 'Z',
                chr(197) . chr(186) => 'z',
                chr(197) . chr(187) => 'Z',
                chr(197) . chr(188) => 'z',
                chr(197) . chr(189) => 'Z',
                chr(197) . chr(190) => 'z',
                chr(197) . chr(191) => 's',
                
                // Decompositions for Latin Extended-B
                chr(200) . chr(152) => 'S',
                chr(200) . chr(153) => 's',
                chr(200) . chr(154) => 'T',
                chr(200) . chr(155) => 't',
                
                // Euro Sign
                chr(226) . chr(130) . chr(172) => 'E',
                // GBP (Pound) Sign
                chr(194) . chr(163) => ''
            );
            
            $string = strtr($string, $chars);
        } else {
            
            // Assume ISO-8859-1 if not UTF-8
            $chars['in'] = chr(128) . chr(131) . chr(138) . chr(142) . chr(154) . chr(158) . chr(159) . chr(162) . chr(165) . chr(181) . chr(192) . chr(193) . chr(194) . chr(195) . chr(196) . chr(197) . chr(199) . chr(200) . chr(201) . chr(202) . chr(203) . chr(204) . chr(205) . chr(206) . chr(207) . chr(209) . chr(210) . chr(211) . chr(212) . chr(213) . chr(214) . chr(216) . chr(217) . chr(218) . chr(219) . chr(220) . chr(221) . chr(224) . chr(225) . chr(226) . chr(227) . chr(228) . chr(229) . chr(231) . chr(232) . chr(233) . chr(234) . chr(235) . chr(236) . chr(237) . chr(238) . chr(239) . chr(241) . chr(242) . chr(243) . chr(244) . chr(245) . chr(246) . chr(248) . chr(249) . chr(250) . chr(251) . chr(252) . chr(253) . chr(255);
            
            $chars['out'] = 'EfSZszYcYuAAAAAACEEEEIIIINOOOOOOUUUUYaaaaaaceeeeiiiinoooooouuuuyy';
            
            $string = strtr($string, $chars['in'], $chars['out']);
            $double_chars['in']  = array(
                chr(140),
                chr(156),
                chr(198),
                chr(208),
                chr(222),
                chr(223),
                chr(230),
                chr(240),
                chr(254)
            );
            $double_chars['out'] = array(
                'OE',
                'oe',
                'AE',
                'DH',
                'TH',
                'ss',
                'ae',
                'dh',
                'th'
            );
            $string = str_replace($double_chars['in'], $double_chars['out'], $string);
        }
        
        return $string;
    }

    /**
     * Checks to see if a string is utf8 encoded.
     *
     * NOTE: This function checks for 5-Byte sequences, UTF8
     *       has Bytes Sequences with a maximum length of 4.
     *
     * @param  string $string The string to be checked
     * @return boolean
     */
    public static function seems_utf8($string) {
        if (function_exists('mb_check_encoding')) {
            // If mbstring is available, this is significantly faster than
            // using PHP regexps.
            return mb_check_encoding($string, 'UTF-8');
        }
        
        $regex = '/(
| [\xF8-\xFF] # Invalid UTF-8 Bytes
| [\xC0-\xDF](?![\x80-\xBF]) # Invalid UTF-8 Sequence Start
| [\xE0-\xEF](?![\x80-\xBF]{2}) # Invalid UTF-8 Sequence Start
| [\xF0-\xF7](?![\x80-\xBF]{3}) # Invalid UTF-8 Sequence Start
| (?<=[\x0-\x7F\xF8-\xFF])[\x80-\xBF] # Invalid UTF-8 Sequence Middle
| (?<![\xC0-\xDF]|[\xE0-\xEF]|[\xE0-\xEF][\x80-\xBF]|[\xF0-\xF7]|[\xF0-\xF7][\x80-\xBF]|[\xF0-\xF7][\x80-\xBF]{2})[\x80-\xBF] # Overlong Sequence
| (?<=[\xE0-\xEF])[\x80-\xBF](?![\x80-\xBF]) # Short 3 byte sequence
| (?<=[\xF0-\xF7])[\x80-\xBF](?![\x80-\xBF]{2}) # Short 4 byte sequence
| (?<=[\xF0-\xF7][\x80-\xBF])[\x80-\xBF](?![\x80-\xBF]) # Short 4 byte sequence (2)
)/x';
        
        return !preg_match($regex, $string);
    }

}

?>

