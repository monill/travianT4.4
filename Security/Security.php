<?php

class CI_Security
{
    var $ip_address = FALSE;
    var $user_agent = FALSE;
    var $_allow_get_array = TRUE;  // Two hours (in seconds)
    var $_standardize_newlines = TRUE;
    var $_enable_xss = FALSE;
    var $_enable_csrf = FALSE;
    protected $_xss_hash = '';
    protected $_csrf_hash = '';
    protected $_csrf_expire = 7200;
    protected $_csrf_token_name = 'ci_csrf_token'; // Set automatically based on config setting
    protected $_csrf_cookie_name = 'ci_csrf_token'; // Set automatically based on config setting
    protected $headers = array();

    /* never allowed, string replacement */
    protected $_never_allowed_str = array(
        'document.cookie' => '[removed]',
        'document.write' => '[removed]',
        '.parentNode' => '[removed]',
        '.innerHTML' => '[removed]',
        'window.location' => '[removed]',
        '-moz-binding' => '[removed]',
        '<!--' => '&lt;!--',
        '-->' => '--&gt;',
        '<![CDATA[' => '&lt;![CDATA['
    );
    /* never allowed, regex replacement */
    protected $_never_allowed_regex = array(
        "javascript\s*:" => '[removed]',
        "expression\s*(\(|&\#40;)" => '[removed]', // CSS and IE
        "vbscript\s*:" => '[removed]', // IE, surprise!
        "Redirect\s+302" => '[removed]'
    );

    // --------------------------------------------------------------------
    public function __construct()
    {
        // Sanitize global arrays
        $this->_sanitize_globals();
    }

    /**
     * Sanitize Globals
     *
     * This function does the following:
     *
     * Unsets $_GET data (if query strings are not enabled)
     *
     * Unsets all globals if register_globals is enabled
     *
     * Standardizes newline characters to \n
     *
     * @access    private
     * @return    void
     */
    public function _sanitize_globals()
    {
        // It would be "wrong" to unset any of these GLOBALS.
        $protected = array('_SERVER', '_GET', '_POST', '_FILES', '_REQUEST',
            '_SESSION', '_ENV', 'GLOBALS', 'HTTP_RAW_POST_DATA',
            'system_folder', 'application_folder', 'BM', 'EXT',
            'CFG', 'URI', 'RTR', 'OUT', 'IN');

        // Unset globals for securiy.
        // This is effectively the same as register_globals = off
        foreach (array($_GET, $_POST, $_COOKIE) as $global) {
            if (!is_array($global)) {
                if (!in_array($global, $protected)) {
                    global $$global;
                    $$global = NULL;
                }
            } else {
                foreach ($global as $key => $val) {
                    if (!in_array($key, $protected)) {
                        global $$key;
                        $$key = NULL;
                    }
                }
            }
        }

        // Is $_GET data allowed? If not we'll set the $_GET to an empty array
        if ($this->_allow_get_array == FALSE) {
            $_GET = array();
        } else {
            if (is_array($_GET) AND count($_GET) > 0) {
                foreach ($_GET as $key => $val) {
                    $_GET[$this->_clean_input_keys($key)] = $this->_clean_input_data($val);
                }
            }
        }

        // Clean $_POST Data
        if (is_array($_POST) AND count($_POST) > 0) {
            foreach ($_POST as $key => $val) {
                $_POST[$this->_clean_input_keys($key)] = $this->_clean_input_data($val);
            }
        }

        // Clean $_COOKIE Data
        if (is_array($_COOKIE) AND count($_COOKIE) > 0) {
            // Also get rid of specially treated cookies that might be set by a server
            // or silly application, that are of no use to a CI application anyway
            // but that when present will trip our 'Disallowed Key Characters' alarm
            // http://www.ietf.org/rfc/rfc2109.txt
            // note that the key names below are single quoted strings, and are not PHP variables
            unset($_COOKIE['$Version']);
            unset($_COOKIE['$Path']);
            unset($_COOKIE['$Domain']);

            foreach ($_COOKIE as $key => $val) {
                $_COOKIE[$this->_clean_input_keys($key)] = $this->_clean_input_data($val);
            }
        }

        // Sanitize PHP_SELF
        $_SERVER['PHP_SELF'] = strip_tags($_SERVER['PHP_SELF']);


        // CSRF Protection check
        // if ($this->_enable_csrf == TRUE)
        // {
        // $this->security->csrf_verify();
        // }

        // log_message('debug', "Global POST and COOKIE data sanitized");
    }

    /**
     * Clean Keys
     *
     * This is a helper function. To prevent malicious users
     * from trying to exploit keys we make sure that keys are
     * only named with alpha-numeric text and a few other items.
     *
     * @access    private
     * @param string
     * @return    string
     */
    function _clean_input_keys($str)
    {
        if (!preg_match("/^[a-z0-9:_\/-]+$/i", $str)) {
            exit('Disallowed Key Characters.');
        }

        return $str;
    }

    // --------------------------------------------------------------------

    /**
     * Clean Input Data
     *
     * This is a helper function. It escapes data and
     * standardizes newline characters to \n
     *
     * @access    private
     * @param string
     * @return    string
     */
    function _clean_input_data($str)
    {
        if (is_array($str)) {
            $new_array = array();
            foreach ($str as $key => $val) {
                $new_array[$this->_clean_input_keys($key)] = $this->_clean_input_data($val);
            }
            return $new_array;
        }

        // We strip slashes if magic quotes is on to keep things consistent
        if (function_exists('get_magic_quotes_gpc') AND get_magic_quotes_gpc()) {
            $str = stripslashes($str);
        }


        // Remove control characters
        $str = $this->remove_invisible_characters($str);

        // Should we filter the input data?
        if ($this->_enable_xss === TRUE) {
            $str = $this->security->xss_clean($str);
        }

        // Standardize newlines if needed
        if ($this->_standardize_newlines == TRUE) {
            if (strpos($str, "\r") !== FALSE) {
                $str = str_replace(array("\r\n", "\r", "\r\n\n"), PHP_EOL, $str);
            }
        }

        return $str;
    }

    // --------------------------------------------------------------------

    /**
     * XSS Clean
     *
     * Sanitizes data so that Cross Site Scripting Hacks can be
     * prevented.  This function does a fair amount of work but
     * it is extremely thorough, designed to prevent even the
     * most obscure XSS attempts.  Nothing is ever 100% foolproof,
     * of course, but I haven't been able to get anything passed
     * the filter.
     *
     * Note: This function should only be used to deal with data
     * upon submission.  It's not something that should
     * be used for general runtime processing.
     *
     * This function was based in part on some code and ideas I
     * got from Bitflux: http://channel.bitflux.ch/wiki/XSS_Prevention
     *
     * To help develop this script I used this great list of
     * vulnerabilities along with a few other hacks I've
     * harvested from examining vulnerabilities in other programs:
     * http://ha.ckers.org/xss.html
     *
     * @param mixed    string or array
     * @return    string
     */
    public function remove_invisible_characters($str, $url_encoded = TRUE)
    {
        $non_displayables = array();

        // every control character except newline (dec 10)
        // carriage return (dec 13), and horizontal tab (dec 09)

        if ($url_encoded) {
            $non_displayables[] = '/%0[0-8bcef]/';    // url encoded 00-08, 11, 12, 14, 15
            $non_displayables[] = '/%1[0-9a-f]/';    // url encoded 16-31
        }

        $non_displayables[] = '/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]+/S';    // 00-08, 11, 12, 14-31, 127

        do {
            $str = preg_replace($non_displayables, '', $str, -1, $count);
        } while ($count);

        return $str;
    }

    // --------------------------------------------------------------------

    public function xss_clean($str, $is_image = FALSE)
    {
        /*
         * Is the string an array?
         *
         */
        if (is_array($str)) {
            while (list($key) = each($str)) {
                $str[$key] = $this->xss_clean($str[$key]);
            }

            return $str;
        }

        /*
         * Remove Invisible Characters
         */
        $str = $this->remove_invisible_characters($str);

        // Validate Entities in URLs
        $str = $this->_validate_entities($str);

        /*
         * URL Decode
         *
         * Just in case stuff like this is submitted:
         *
         * <a href="http://%77%77%77%2E%67%6F%6F%67%6C%65%2E%63%6F%6D">Google</a>
         *
         * Note: Use rawurldecode() so it does not remove plus signs
         *
         */
        $str = rawurldecode($str);

        /*
         * Convert character entities to ASCII
         *
         * This permits our tests below to work reliably.
         * We only convert entities that are within tags since
         * these are the ones that will pose security problems.
         *
         */

        $str = preg_replace_callback("/[a-z]+=([\'\"]).*?\\1/si", array($this, '_convert_attribute'), $str);

        $str = preg_replace_callback("/<\w+.*?(?=>|<|$)/si", array($this, '_decode_entity'), $str);

        /*
         * Remove Invisible Characters Again!
         */
        $str = $this->remove_invisible_characters($str);

        /*
         * Convert all tabs to spaces
         *
         * This prevents strings like this: ja	vascript
         * NOTE: we deal with spaces between characters later.
         * NOTE: preg_replace was found to be amazingly slow here on
         * large blocks of data, so we use str_replace.
         */

        if (strpos($str, "\t") !== FALSE) {
            $str = str_replace("\t", ' ', $str);
        }

        /*
         * Capture converted string for later comparison
         */
        $converted_string = $str;

        // Remove Strings that are never allowed
        $str = $this->_do_never_allowed($str);

        /*
         * Makes PHP tags safe
         *
         * Note: XML tags are inadvertently replaced too:
         *
         * <?xml
         *
         * But it doesn't seem to pose a problem.
         */
        if ($is_image === TRUE) {
            // Images have a tendency to have the PHP short opening and
            // closing tags every so often so we skip those and only
            // do the long opening tags.
            $str = preg_replace('/<\?(php)/i', "&lt;?\\1", $str);
        } else {
            $str = str_replace(array('<?', '?' . '>'), array('&lt;?', '?&gt;'), $str);
        }

        /*
         * Compact any exploded words
         *
         * This corrects words like:  j a v a s c r i p t
         * These words are compacted back to their correct state.
         */
        $words = array(
            'javascript', 'expression', 'vbscript', 'script',
            'applet', 'alert', 'document', 'write', 'cookie', 'window'
        );

        foreach ($words as $word) {
            $temp = '';

            for ($i = 0, $wordlen = strlen($word); $i < $wordlen; $i++) {
                $temp .= substr($word, $i, 1) . "\s*";
            }

            // We only want to do this when it is followed by a non-word character
            // That way valid stuff like "dealer to" does not become "dealerto"
            $str = preg_replace_callback('#(' . substr($temp, 0, -3) . ')(\W)#is', array($this, '_compact_exploded_words'), $str);
        }

        /*
         * Remove disallowed Javascript in links or img tags
         * We used to do some version comparisons and use of stripos for PHP5,
         * but it is dog slow compared to these simplified non-capturing
         * preg_match(), especially if the pattern exists in the string
         */
        do {
            $original = $str;

            if (preg_match("/<a/i", $str)) {
                $str = preg_replace_callback("#<a\s+([^>]*?)(>|$)#si", array($this, '_js_link_removal'), $str);
            }

            if (preg_match("/<img/i", $str)) {
                $str = preg_replace_callback("#<img\s+([^>]*?)(\s?/?>|$)#si", array($this, '_js_img_removal'), $str);
            }

            if (preg_match("/script/i", $str) OR preg_match("/xss/i", $str)) {
                $str = preg_replace("#<( /*)(script|xss)(.*?)\>#si", '[removed]', $str);
            }
        } while ($original != $str);

        unset($original);

        // Remove evil attributes such as style, onclick and xmlns
        $str = $this->_remove_evil_attributes($str, $is_image);

        /*
        * Sanitize naughty HTML elements
        *
        * If a tag containing any of the words in the list
        * below is found, the tag gets converted to entities.
        *
        * So this: <blink>
            * Becomes: &lt;blink&gt;
            */
        $naughty =
            'alert|applet|audio|basefont|base|behavior|bgsound|blink|body|embed|expression|form|frameset|frame|head|html|ilayer|iframe|input|isindex|layer|link|meta|object|plaintext|style|script|textarea|title|video|xml|xss';
        $str = preg_replace_callback('#<( /*\s*)(' . $naughty . ')([^><]*)([><]*)#is',
            array($this, '_sanitize_naughty_html'), $str); /* * Sanitize naughty scripting elements * * Similar to
            above, only instead of looking for * tags it looks for PHP and JavaScript commands * that are disallowed.
            Rather than removing the * code, it simply converts the parenthesis to entities * rendering the code
            un-executable. * * For example: eval('some code') * Becomes: eval&#40;'some code'&#41; */
        $str = preg_replace('#(alert|cmd|passthru|eval|exec|expression|system|fopen|fsockopen|file|file_get_contents|readfile|unlink)(\s*)\((.*?)\)#si', "\\1\\2&#40;\\3&#41;", $str);
        // Final clean up // This adds a bit of extra precaution in case // something got through the above filters
        $str = $this->_do_never_allowed($str);

        /*
        * Images are Handled in a Special Way
        * - Essentially, we want to know that after all of the character
        * conversion is done whether any unwanted, likely XSS, code was found.
        * If not, we return TRUE, as the image is clean.
        * However, if the string post-conversion does not matched the
        * string post-removal of XSS, then it fails, as there was unwanted XSS
        * code found and removed/changed during processing.
        */

        if ($is_image === TRUE) {
            return ($str == $converted_string) ? TRUE : FALSE;
        }

        // log_message('debug', "XSS Filtering completed");
        return $str;
    }

    // ----------------------------------------------------------------

    /**
     * Validate URL entities
     *
     * Called by xss_clean()
     *
     * @param string
     * @return string
     */
    protected function _validate_entities($str)
    {
        /*
        * Protect GET variables in URLs
        */

        // 901119URL5918AMP18930PROTECT8198

        $str = preg_replace('|\&([a-z\_0-9\-]+)\=([a-z\_0-9\-]+)|i', $this->xss_hash() . "\\1=\\2", $str);

        /*
        * Validate standard character entities
        *
        * Add a semicolon if missing. We do this to enable
        * the conversion of entities to ASCII later.
        *
        */
        $str = preg_replace('#(&\#?[0-9a-z]{2,})([\x00-\x20])*;?#i', "\\1;\\2", $str);

        /*
        * Validate UTF16 two byte encoding (x00)
        *
        * Just as above, adds a semicolon if missing.
        *
        */
        $str = preg_replace('#(&\#x?)([0-9A-F]+);?#i', "\\1\\2;", $str);

        /*
        * Un-Protect GET variables in URLs
        */
        $str = str_replace($this->xss_hash(), '&', $str);

        return $str;
    }

    // --------------------------------------------------------------------

    /*
    * Remove Evil HTML Attributes (like evenhandlers and style)
    *
    * It removes the evil attribute and either:
    * - Everything up until a space
    * For example, everything between the pipes:
    * <a |style=document.write('hello');alert('world');| class=link>
        * - Everything inside the quotes
        * For example, everything between the pipes:
        * <a |style="document.write('hello'); alert('world');" | class="link">
            *
            * @param string $str The string to check
            * @param boolean $is_image TRUE if this is an image
            * @return string The string with the evil attributes removed
            */

    /**
     * Random Hash for protecting URLs
     *
     * @return string
     */
    public function xss_hash()
    {
        if ($this->_xss_hash == '') {
            if (phpversion() >= 4.2) {
                mt_srand();
            } else {
                mt_srand(hexdec(substr(md5(microtime()), -8)) & 0x7fffffff);
            }

            $this->_xss_hash = md5(time() + mt_rand(0, 1999999999));
        }

        return $this->_xss_hash;
    }

    // --------------------------------------------------------------------

    /**
     * Do Never Allowed
     *
     * A utility function for xss_clean()
     *
     * @param string
     * @return string
     */
    protected function _do_never_allowed($str)
    {
        foreach ($this->_never_allowed_str as $key => $val) {
            $str = str_replace($key, $val, $str);
        }

        foreach ($this->_never_allowed_regex as $key => $val) {
            $str = preg_replace("#" . $key . "#i", $val, $str);
        }

        return $str;
    }

    // --------------------------------------------------------------------

    protected function _remove_evil_attributes($str, $is_image)
    {
        // All javascript event handlers (e.g. onload, onclick, onmouseover), style, and xmlns
        $evil_attributes = array('on\w*', 'style', 'xmlns');

        if ($is_image === TRUE) {
            /*
            * Adobe Photoshop puts XML metadata into JFIF images,
            * including namespacing, so we have to allow this for images.
            */
            unset($evil_attributes[array_search('xmlns', $evil_attributes)]);
        }

        do {
            $str = preg_replace(
                "#<( /?[^>
                        <]+?)([^A-Za-z\-])(" . implode('|', $evil_attributes) . ")(\s*=\s*)([\" ][^>
                            ]*?[\"]|[\'][^>]*?[\']|[^>]*?)([\s><])([>
                                <]*)#i", "<$1$6", $str, -1, $count);
        } while ($count);
        return $str;
    }
    // --------------------------------------------------------------------

    /**
     * Filename
     * Security *
     * @param string
     * @return string
     */
    public function sanitize_filename($str, $relative_path = FALSE)
    {
        $bad = array("../", "<!--", "-->"
        , "<", ">", "'", '"', '&', '$', '#', '{', '}', '[', ']', '=', ';', '?'
        , "%20", "%22", "%3c", // < "%253c" , // < "%3e" , //>
            "%0e", // >
            "%28", // (
            "%29", // )
            "%2528", // (
            "%26", // &
            "%24", // $
            "%3f", // ?
            "%3b", // ;
            "%3d" // =
        );

        if (!$relative_path) {
            $bad[] = './';
            $bad[] = '/';
        }

        $str = $this->remove_invisible_characters($str, FALSE);
        return stripslashes(str_replace($bad, '', $str));
    }

    // --------------------------------------------------------------------

    /**
     * Compact Exploded Words
     *
     * Callback function for xss_clean() to remove whitespace from
     * things like j a v a s c r i p t
     *
     * @param $matches
     * @return string
     */
    protected function _compact_exploded_words($matches)
    {
        return preg_replace('/\s+/s', '', $matches[1]) . $matches[2];
    }

    // --------------------------------------------------------------------

    /**
     * Sanitize Naughty HTML
     *
     * Callback function for xss_clean() to remove naughty HTML elements
     *
     * @param array
     * @return string
     */
    protected function _sanitize_naughty_html($matches)
    {
        // encode opening brace
        $str = '&lt;' . $matches[1] . $matches[2] . $matches[3];

        // encode captured opening or closing brace to prevent recursive vectors
        $str .= str_replace(array('>', '<'), array('&gt;', '&lt;'), $matches[4]);
        return $str;
    }

    /** * JS Link Removal * * Callback function for xss_clean() to sanitize links *
     * This limits the PCRE backtracks, making it more performance friendly * and
     * prevents PREG_BACKTRACK_LIMIT_ERROR from being triggered in * PHP 5.2+ on
     * link-heavy strings * *
     * @param array * @return string
     * @return string|string[]
     */

    protected function _js_link_removal(array $)
    {
        $attributes = $this->_filter_attributes(str_replace(array('<', '>'), '', $match[1]));

        return str_replace($match[1],
            preg_replace("#href=.*?(alert\(|alert&\#40;|javascript\:|livescript\:|mocha\:|charset\=|window\.|document\.|\.cookie|<script|<xss|base64\s*,)#si", ""
                , $attributes), $match[0]);
    }

    /**
     * Filter Attributes * * Filters tag attributes for consistency and safety
     *
     * @param string
     * @return string
     */
    protected function _filter_attributes($str)
    {
        $out = '';
        if (preg_match_all('#\s*[a-z\-]+\s*=\s*(\042|\047)([^\1]*?)\\1#is', $str, $matches)) {
            foreach ($matches[0] as $match) {
                $out .= preg_replace("#/\*.*?\*/#s", '', $match);
            }
        }
        return $out;
    }

    /**
     * JS Image Removal *
     * Callback function for xss_clean() to sanitize image tags
     *
     * This limits the PCRE backtracks, making it more performance friendly
     * and prevents PREG_BACKTRACK_LIMIT_ERROR from being triggered in
     * PHP 5.2+ on image tag heavy strings *
     *
     * @param array
     * @return string
     */
    protected function _js_img_removal($match)
    {
        $attributes = $this->_filter_attributes(str_replace(array('<', '>'), '', $match[1]));

        return str_replace($match[1],
            preg_replace("#src=.*?(alert\(|alert&\#40;|javascript\:|livescript\:|mocha\:|charset\=|window\.|document\.|\.cookie|<script|<xss|base64\s*,)#si", ""
                , $attributes), $match[0]);
    }

    /**
     * Attribute Conversion
     * Used as a callback for XSS Clean
     *
     * @param array
     * @return string
     */
    protected function _convert_attribute($match)
    {
        return str_replace(array('>', '<', '\\'), array('&gt;', '&lt;', '\\\\'), $match[0]);
    }

    /**
     * HTML
     * Entity Decode Callback * * Used as a callback for XSS Clean
     * @param array
     * @return string
     */
    protected function _decode_entity($match)
    {
        return $this->entity_decode($match[0], strtoupper('UTF-8'));
    }

    /**
     * HTML Entities Decode
     *
     * This function is a replacement for html_entity_decode()
     *
     * In some versions of PHP the native function does not work
     * when UTF-8 is the specified character set, so this gives us
     * a work-around. More info here:
     * http://bugs.php.net/bug.php?id=25670
     *
     * NOTE: html_entity_decode() has a bug in some PHP versions when
     * UTF-8 is the
     * character set, and the PHP developers said they were not back
     * porting the
     * fix to versions other than PHP 5.x.
     *
     * @param string
     * @param string
     * @return string
     */
    public function entity_decode($str, $charset = 'UTF-8')
    {
        if (stristr($str, '&') === FALSE) return $str;

        // The reason we are not using html_entity_decode() by itself is because
        // while it is not technically correct to leave out the semicolon
        // at the end of an entity most browsers will still interpret the entity
        // correctly. html_entity_decode() does not convert entities without
        // semicolons, so we are left with our own little solution here. Bummer .

        if (function_exists('html_entity_decode') && (strtolower($charset) != 'utf-8')) {
            $str = html_entity_decode($str, ENT_COMPAT, $charset);
            $str = preg_replace('~&#x(0*[0-9a-f]{2,5})~ei',
                'chr(hexdec("\\1"))', $str);
            return preg_replace('~&#([0-9]{2,4})~e', 'chr(\\1)', $str);
        }

        // Numeric Entities
        $str = preg_replace('~&#x(0*[0-9a-f]{2,5});{0,1}~ei',
            'chr(hexdec("\\1"))', $str);
        $str = preg_replace('~&#([0-9]{2,4});{0,1}~e', 'chr(\\1)', $str);

        // Literal Entities - Slightly slow so we do another check
        if (stristr($str, '&') === FALSE) {
            $str = strtr($str,
                array_flip(get_html_translation_table(HTML_ENTITIES)));
        }

        return $str;
    }
}

// END Security Class

$security = new CI_Security();
/* End of file Security.php */
/* Location: ./system/libraries/Security.php */