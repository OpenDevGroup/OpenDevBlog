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
    

    public static function debugToConsole($data) {
    	if(is_array($data) || is_object($data)) {
		echo("<script>console.log('PHP: ".json_encode($data)."');</script>");
	} else {
		echo("<script>console.log('PHP: ".$data."');</script>");
	}
    }

   
    public static function wordCount($str) {
        return count(preg_split('/\s+/', strip_tags($str), null, PREG_SPLIT_NO_EMPTY));
    }
    
    public static function readingTime($str) {
        $mycontent = $str;
        $word      = self::wordCount(strip_tags($mycontent));
        $m         = floor($word / 175);
        $s         = floor($word % 175 / (175 / 60));
        
        if ($m >= 1) {
            return $est = $m . ' min' . ($m == 1 ? '' : 's');
        } elseif ($s <= 59) {
            return $est = $s . ' second' . ($s == 1 ? '' : 's');
        }
    }
    
    public static function slugify($string, $css_mode = FALSE) {
        $slug = preg_replace('/([^a-z0-9]+)/', '-', strtolower(self::remove_accents($string)));
        if ($css_mode) {
            $digits = array(
                'zero',
                'one',
                'two',
                'three',
                'four',
                'five',
                'six',
                'seven',
                'eight',
                'nine'
            );
            
            if (is_numeric(substr($slug, 0, 1))) {
                $slug = $digits[substr($slug, 0, 1)] . substr($slug, 1);
            }
        }
        
        return $slug;
    }
    
    public static function sizeFormat($bytes, $decimals = 0) {
        $bytes = floatval($bytes);
        
        if ($bytes < 1024) {
            return $bytes . ' B';
        } else if ($bytes < pow(1024, 2)) {
            return number_format($bytes / 1024, $decimals, '.', '') . ' KiB';
        } else if ($bytes < pow(1024, 3)) {
            return number_format($bytes / pow(1024, 2), $decimals, '.', '') . ' MiB';
        } else if ($bytes < pow(1024, 4)) {
            return number_format($bytes / pow(1024, 3), $decimals, '.', '') . ' GiB';
        } else if ($bytes < pow(1024, 5)) {
            return number_format($bytes / pow(1024, 4), $decimals, '.', '') . ' TiB';
        } else if ($bytes < pow(1024, 6)) {
            return number_format($bytes / pow(1024, 5), $decimals, '.', '') . ' PiB';
        } else {
            return number_format($bytes / pow(1024, 5), $decimals, '.', '') . ' PiB';
        }
    }
    
    public static function startsWith($string, $starts_with) {
        return (strpos($string, $starts_with) === 0);
    }
    
    public static function endsWith($string, $ends_with) {
        return substr($string, -strlen($ends_with)) === $ends_with;
    }
    
    public static function stringContains($haystack, $needle) {
        return (strpos($haystack, $needle) !== false);
    }
    
    public static function getFileExt($filename) {
        return pathinfo($filename, PATHINFO_EXTENSION);
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
    
    public static function force_download($filename, $content = FALSE) {
        if (!headers_sent()) {
            // Required for some browsers
            if (ini_get('zlib.output_compression')) {
                @ini_set('zlib.output_compression', 'Off');
            }
            
            header('Pragma: public');
            header('Expires: 0');
            header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
            
            // Required for certain browsers
            header('Cache-Control: private', FALSE);
            
            header('Content-Disposition: attachment; filename="' . basename(str_replace('"', '', $filename)) . '";');
            header('Content-Type: application/force-download');
            header('Content-Transfer-Encoding: binary');
            
            if ($content) {
                header('Content-Length: ' . strlen($content));
            }
            
            ob_clean();
            flush();
            
            if ($content) {
                echo $content;
            }
            
            return TRUE;
        } else {
            return FALSE;
        }
    }
    
    public static function validate_email($possible_email) {
        return (bool) filter_var($possible_email, FILTER_VALIDATE_EMAIL);
    }
    
    public static function safeTruncate($string, $length, $append = '...') {
        $ret        = substr($string, 0, $length);
        $last_space = strrpos($ret, ' ');
        
        if ($last_space !== FALSE && $string != $ret) {
            $ret = substr($ret, 0, $last_space);
        }
        
        if ($ret != $string) {
            $ret .= $append;
        }
        
        return $ret;
    }
    
    public static function ordinal($number) {
        $test_c = abs($number) % 10;
        
        $ext = ((abs($number) % 100 < 21 && abs($number) % 100 > 4) ? 'th' : (($test_c < 4) ? ($test_c < 3) ? ($test_c < 2) ? ($test_c < 1) ? 'th' : 'st' : 'nd' : 'rd' : 'th'));
        
        return $number . $ext;
    }
    
    public static function xap($in, $html = 'text', $iframe = false) {
        if (is_array($in)) {
            foreach ($in as &$item) {
                $item = xap($item, $html);
            }
            return $in;
            /**
             * Make safe HTML
             */
        } elseif ($html === true) {
            $in = preg_replace('/
<[^a-z=>]*(link|script|object|applet|embed)[^>]*>? # Open tag
(
.* # Some content
<\/[^>]*\\1[^>]*> # Close tag (with reference for tag name to open tag)
)? # Section is optional
/xims', '', $in);
            /**
             * Remove iframes (regular expression the same as previous)
             */
            if (!$iframe) {
                $in = preg_replace('/
<[^a-z=>]*iframe[^>]*>? # Open tag
(
.* # Some content
<\/[^>]*iframe[^>]*> # Close tag
)? # Section is optional
/xims', '', $in);
                /**
                 * Allow iframes without inner content (for example, video from youtube)
                 */
            } else {
                $in = preg_replace('/
(<[^a-z=>]*iframe[^>]*>\s*) # Open tag
[^<\s]+ # Search if there something that is not space or < character
(<\/[^>]*iframe[^>]*>)? # Optional close tag
/xims', '', $in);
            }
            $in = preg_replace('/(script|data|vbscript):/i', '\\1&#58;', $in);
            $in = preg_replace('/(expression[\s]*)\(/i', '\\1&#40;', $in);
            $in = preg_replace('/<[^>]*\s(on[a-z]+|dynsrc|lowsrc|formaction)=[^>]*>?/ims', '', $in);
            $in = preg_replace('/(href[\s\t\r\n]*=[\s\t\r\n]*["\'])((?:http|https|ftp)\:\/\/.*?["\'])/ims', '\\1redirect/\\2', $in);
            return $in;
        } elseif ($html === false) {
            return strip_tags($in);
        } else {
            return htmlspecialchars($in, ENT_NOQUOTES | ENT_HTML5 | ENT_DISALLOWED | ENT_SUBSTITUTE | ENT_HTML5);
        }
    }
    
}

?>

