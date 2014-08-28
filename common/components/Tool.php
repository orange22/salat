<?php
class Tool
{
    /**
     * Prepare insert/delete array for junction table update
     *
     * @static
     * @param array $old Array of old(current) ID's
     * @param array $new Array of new ID's
     * @return array Array with appropriate insert/delete arrays
     */
    public static function prepareJunction($old, $new)
    {
        // collect ID's to delete
        $delete = array_diff($old, $new);

        // collect ID's to insert
        $insert = array_diff($new, $old);

        return array('insert' => $insert, 'delete' => $delete);
    }

    /**
     * Replaces double line-breaks with paragraph elements.
     * From Wordpress formatting.php wpautop() function
     *
     * A group of regex replaces used to identify text formatted with newlines and
     * replace double line-breaks with HTML paragraph tags. The remaining
     * line-breaks after conversion become <<br />> tags, unless $br is set to '0'
     * or 'false'.
     *
     * @param string   $pee The text which has to be formatted.
     * @param int|bool $br  Optional. If set, this will convert all remaining line-breaks after paragraphing. Default true.
     * @return string Text which has been converted into correct paragraph tags.
     */
    public static function autop($pee, $br = true)
    {
        if(trim($pee) === '')
            return '';
        $pee = $pee."\n"; // just to make things a little easier, pad the end
        $pee = preg_replace('|<br />\s*<br />|', "\n\n", $pee);
        // Space things out a little
        $allblocks = '(?:table|thead|tfoot|caption|col|colgroup|tbody|tr|td|th|div|dl|dd|dt|ul|ol|li|pre|select|option|form|map|area|blockquote|address|math|style|input|p|h[1-6]|hr|fieldset|legend|section|article|aside|hgroup|header|footer|nav|figure|figcaption|details|menu|summary)';
        $pee       = preg_replace('!(<'.$allblocks.'[^>]*>)!', "\n$1", $pee);
        $pee       = preg_replace('!(</'.$allblocks.'>)!', "$1\n\n", $pee);
        $pee       = str_replace(array("\r\n", "\r"), "\n", $pee); // cross-platform newlines
        if(strpos($pee, '<object') !== false)
        {
            $pee = preg_replace('|\s*<param([^>]*)>\s*|', "<param$1>", $pee); // no pee inside object/embed
            $pee = preg_replace('|\s*</embed>\s*|', '</embed>', $pee);
        }
        $pee = preg_replace("/\n\n+/", "\n\n", $pee); // take care of duplicates
        // make paragraphs, including one at the end
        $pees = preg_split('/\n\s*\n/', $pee, -1, PREG_SPLIT_NO_EMPTY);
        $pee  = '';
        foreach($pees as $tinkle)
            $pee .= '<p>'.trim($tinkle, "\n")."</p>\n";
        $pee = preg_replace('|<p>\s*</p>|', '',
            $pee); // under certain strange conditions it could create a P of entirely whitespace
        $pee = preg_replace('!<p>([^<]+)</(div|address|form)>!', "<p>$1</p></$2>", $pee);
        $pee = preg_replace('!<p>\s*(</?'.$allblocks.'[^>]*>)\s*</p>!', "$1", $pee); // don't pee all over a tag
        $pee = preg_replace("|<p>(<li.+?)</p>|", "$1", $pee); // problem with nested lists
        $pee = preg_replace('|<p><blockquote([^>]*)>|i', "<blockquote$1><p>", $pee);
        $pee = str_replace('</blockquote></p>', '</p></blockquote>', $pee);
        $pee = preg_replace('!<p>\s*(</?'.$allblocks.'[^>]*>)!', "$1", $pee);
        $pee = preg_replace('!(</?'.$allblocks.'[^>]*>)\s*</p>!', "$1", $pee);
        if($br)
        {
            $pee = preg_replace_callback('/<(script|style).*?<\/\\1>/s', 'Tool::_autopNewlinePreservationHelper',
                $pee);
            $pee = preg_replace('|(?<!<br />)\s*\n|', "<br />\n", $pee); // optionally make line breaks
            $pee = str_replace('<PreserveNewline />', "\n", $pee);
        }
        $pee = preg_replace('!(</?'.$allblocks.'[^>]*>)\s*<br />!', "$1", $pee);
        $pee = preg_replace('!<br />(\s*</?(?:p|li|div|dl|dd|dt|th|pre|td|ul|ol)[^>]*>)!', '$1', $pee);
        if(strpos($pee, '<pre') !== false)
            $pee = preg_replace_callback('!(<pre[^>]*>)(.*?)</pre>!is', 'Tool::cleanPre', $pee);
        $pee = preg_replace("|\n</p>$|", '</p>', $pee);

        return $pee;
    }

    /**
     * Accepts matches array from preg_replace_callback in autop() or a string.
     * From Wordpress formatting.php clean_pre() function
     *
     * Ensures that the contents of a <<pre>>...<</pre>> HTML block are not
     * converted into paragraphs or line-breaks.
     *
     *
     * @param array|string $matches The array or string
     * @return string The pre block without paragraph/line-break conversion.
     */
    public static function cleanPre($matches)
    {
        if(is_array($matches))
            $text = $matches[1].$matches[2]."</pre>";
        else
            $text = $matches;

        $text = str_replace('<br />', '', $text);
        $text = str_replace('<p>', "\n", $text);
        $text = str_replace('</p>', '', $text);

        return $text;
    }



    /**
     * Returns a formatted descriptive date string for given datetime string.
     * If the given date is today, the returned string could be "Today, 06:54".
     * If the given date was yesterday, the returned string could be "Yesterday, 06:54".
     * If $dateString's year is the current year, the returned string does not
     * include mention of the year.
     *
     * Part of {@link http://www.yiiframework.com/extension/time/}
     *
     * @param string $dateString Datetime string or Unix timestamp
     * @return string Described, relative date string
     */
    public static function niceShortTime($dateString = null)
    {
        $date = ($dateString == null) ? time() : strtotime($dateString);

        if(date('Y-m-d', $date) == date('Y-m-d', time()))
            $ret = sprintf(Yii::t('frontend', 'Today, %s'), date('G:i', $date));
        elseif(date('Y-m-d', $date) == date('Y-m-d', strtotime('yesterday')))
            $ret = sprintf(Yii::t('frontend', 'Yesterday, %s'), date('G:i', $date));
        else
            $ret = date('d.m.y H:i', $date);

        return $ret;
    }

    /**
     * Parse time for template
     *
     * @param string $time
     * @return array {"{from}", "{to}"}
     */
    public static function parseTime($time)
    {
        $time = explode(' - ', $time);
        if(count($time) != 2)
            return '';

        return array_combine(array('{from}', '{to}'), $time);
    }

    /**
     * Resolve tabular attribute name
     * Get attribute original name
     *
     * @param string $attribute
     * @return string
     */
    public static function resolveAttribute($attribute)
    {
        if(($pos = strpos($attribute, '[')) !== false)
        {
            if($pos === 0) // [a]name[b][c], should ignore [a]
            {
                if(preg_match('/\](\w+(\[.+)?)/', $attribute, $matches))
                    $attribute = $matches[1]; // we get: name[b][c]
                if(($pos = strpos($attribute, '[')) === false)
                    return $attribute;
            }

            return substr($attribute, 0, $pos);
        }
        else
            return $attribute;
    }

    /**
     * Email obfuscation
     *
     * @static
     * @param string $email
     * @param bool $raw Return just email itself (no <a>)
     * @return string
     */
    public static function obfuscateEmail($email, $raw = false)
    {
        if(!$email)
            return '';

        $length = strlen($email);
        $obfuscatedEmail = '';
        for($i = 0; $i < $length; $i++)
            $obfuscatedEmail .= '&#'.ord($email[$i]).';';

        if($raw)
            return $obfuscatedEmail;

        return '<a href="mailto:'.$obfuscatedEmail.'">'.$obfuscatedEmail.'</a>';
    }

    /**
     * Email obfuscator script 2.1 by Tim Williams, University of Arizona.
     * Random encryption key feature by Andrew Moulden, Site Engineering Ltd
     * PHP version coded by Ross Killen, Celtic Productions Ltd
     * This code is freeware provided these six comment lines remain intact
     * A wizard to generate this code is at http://www.jottings.com/obfuscator/
     * The PHP code may be obtained from http://www.celticproductions.net/\n\n";
     *
     * @param string $address the email address to obfuscate
     * @return string
     */
    function obfuscateEmailJs($address)
    {
        $address = strtolower($address);
        $coded = "";
        $unmixedkey = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789.@";
        $inprogresskey = $unmixedkey;
        $mixedkey = "";
        $unshuffled = strlen($unmixedkey);
        for ($i = 0; $i <= strlen($unmixedkey); $i++)
        {
            $ranpos = rand(0, $unshuffled - 1);
            $nextchar = substr($inprogresskey, $ranpos, 1);
            $mixedkey .= $nextchar;
            $before = substr($inprogresskey, 0, $ranpos);
            $after = substr($inprogresskey, $ranpos + 1, $unshuffled - ($ranpos + 1));
            $inprogresskey = $before . '' . $after;
            $unshuffled -= 1;
        }
        $cipher = $mixedkey;

        $shift = strlen($address);

        $txt = "<script type=\"text/javascript\" language=\"javascript\">\n" .
            "<!-" . "-\n";

        for ($j = 0; $j < strlen($address); $j++)
        {
            if (strpos($cipher, $address{$j}) == -1)
            {
                $chr = $address{$j};
                $coded .= $chr;
            }
            else
            {
                $chr = (strpos($cipher, $address{$j}) + $shift) % strlen($cipher);
                $coded .= $cipher{$chr};
            }
        }

        $txt .= "\ncoded = \"" . $coded . "\"\n" .
            "  key = \"" . $cipher . "\"\n" .
            "  shift=coded.length\n" .
            "  link=\"\"\n" .
            "  for (i=0; i<coded.length; i++) {\n" .
            "    if (key.indexOf(coded.charAt(i))==-1) {\n" .
            "      ltr = coded.charAt(i)\n" .
            "      link += (ltr)\n" .
            "    }\n" .
            "    else {     \n" .
            "      ltr = (key.indexOf(coded.charAt(i))-shift+key.length) % key.length\n" .
            "      link += (key.charAt(ltr))\n" .
            "    }\n" .
            "  }\n" .
            "document.write(\"<a href='mailto:\"+link+\"'>\"+link+\"</a>\")\n" .
            "\n" .
            "//-" . "->\n" .
            "<" . "/script><noscript>N/A" .
            "<" . "/noscript>";
        return $txt;
    }

    /**
     * Ellipsize String
     * This public static function will strip tags from a string, split it at its max_length and ellipsize
     * From CodeIgniter TextHelper
     *
     * @param string string to ellipsize
     * @param integer max length of string
     * @param mixed int (1|0) or float, .5, .2, etc for position to split
     * @param string ellipsis ; Default '...'
     * @return string ellipsized string
     */
    public static function ellipsize($str, $max_length, $position = 1, $ellipsis = '&hellip;')
    {
        // Strip tags
        $str = trim(strip_tags($str));

        // Is the string long enough to ellipsize?
        if(mb_strlen($str) <= $max_length)
        {
            return $str;
        }

        $beg = mb_substr($str, 0, floor($max_length * $position));

        $position = ($position > 1) ? 1 : $position;

        if($position === 1)
        {
            $end = mb_substr($str, 0, -($max_length - mb_strlen($beg)));
        }
        else
        {
            $end = mb_substr($str, -($max_length - mb_strlen($beg)));
        }

        return $beg.$ellipsis.$end;
    }

    /**
     * Newline preservation help function for autop
     * From Wordpress formatting.php _autop_newline_preservation_helper() function
     *
     * @access protected
     * @param array $matches preg_replace_callback matches array
     * @return string
     */
    public static function _autopNewlinePreservationHelper($matches)
    {
        return str_replace("\n", "<PreserveNewline />", $matches[0]);
    }

    /**
     * Converts a word to its plural form.
     * Note that this is for English only!
     * For example, 'apple' will become 'apples', and 'child' will become 'children'.
     *
     * @param string $name the word to be pluralized
     * @return string the pluralized word
     */
    public static function pluralize($name)
    {
        $rules = array(
            '/(m)ove$/i' => '\1oves',
            '/(f)oot$/i' => '\1eet',
            '/(c)hild$/i' => '\1hildren',
            '/(h)uman$/i' => '\1umans',
            '/(m)an$/i' => '\1en',
            '/(t)ooth$/i' => '\1eeth',
            '/(p)erson$/i' => '\1eople',
            '/([m|l])ouse$/i' => '\1ice',
            '/(x|ch|ss|sh|us|as|is|os)$/i' => '\1es',
            '/([^aeiouy]|qu)y$/i' => '\1ies',
            '/(?:([^f])fe|([lr])f)$/i' => '\1\2ves',
            '/(shea|lea|loa|thie)f$/i' => '\1ves',
            '/([ti])um$/i' => '\1a',
            '/(tomat|potat|ech|her|vet)o$/i' => '\1oes',
            '/(bu)s$/i' => '\1ses',
            '/(ax|test)is$/i' => '\1es',
            '/s$/' => 's',
        );
        foreach($rules as $rule => $replacement)
        {
            if(preg_match($rule, $name))
                return preg_replace($rule, $replacement, $name);
        }
        return $name.'s';
    }
}