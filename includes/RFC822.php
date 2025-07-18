<?php
/**
* RFC 822 Email address list validation Utility
*
* What is it?
*
* This class will take an address string, and parse it into it's consituent
* parts, be that either addresses, groups, or combinations. Nested groups
* are not supported. The structure it returns is pretty straight forward,
* and is similar to that provided by the imap_rfc822_parse_adrlist(). Use
* print_r() to view the structure.
*
* How do I use it?
*
* $Address_string = 'My Group: "Richard Heyes" <richard@localhost> (A comment), ted@example.com (Ted Bloggs), Barney;';
* $structure = Mail_RFC822::parseAddressList($Address_string, 'example.com', TRUE)
* print_r($structure);
*
* @author  Richard Heyes <richard@phpguru.org>
* @author  Chuck Hagenbuch <chuck@horde.org>
* @version $Revision: 1.4 $
* @package Mail
*/

class Mail_RFC822
{
    /**
     * The address being parsed by the RFC822 object.
     * @var string $Address
     */
    var $Address = '';

    /**
     * The default domain to use for unqualified addresses.
     * @var string $default_domain
     */
    var $default_domain = 'localhost';

    /**
     * Should we return a nested array showing groups, or flatten everything?
     * @var boolean $nestGroups
     */
    var $nestGroups = true;

    /**
     * Whether or not to validate atoms for non-ascii characters.
     * @var boolean $Validate
     */
    var $Validate = true;

    /**
     * The array of raw addresses built up as we parse.
     * @var array $Addresses
     */
    var $Addresses = array();

    /**
     * The final array of parsed address information that we build up.
     * @var array $structure
     */
    var $structure = array();

    /**
     * The current error message, if any.
     * @var string $error
     */
    var $error = null;

    /**
     * An internal counter/pointer.
     * @var integer $index
     */
    var $index = null;

    /**
     * The number of groups that have been found in the address list.
     * @var integer $num_groups
     * @access public
     */
    var $num_groups = 0;

    /**
     * A variable so that we can tell whether or not we're inside a
     * Mail_RFC822 object.
     * @var boolean $mailRFC822
     */
    var $mailRFC822 = true;

    /**
    * A limit after which processing stops
    * @var int $limit
    */
    var $limit = null;


    /**
     * Sets up the object. The address must either be set here or when
     * calling parseAddressList(). One or the other.
     *
     * @access public
     * @param string  $Address         The address(es) to validate.
     * @param string  $default_domain  Default domain/host etc. If not supplied, will be set to localhost.
     * @param boolean $nest_groups     Whether to return the structure with groups nested for easier viewing.
     * @param boolean $Validate        Whether to validate atoms. Turn this off if you need to run addresses through before encoding the personal names, for instance.
     *
     * @return object Mail_RFC822 A new Mail_RFC822 object.
     */
    function __construct($Address = null, $default_domain = null, $nest_groups = null, $Validate = null, $limit = null)
    {
        if (isset($Address))        $this->address        = $Address;
        if (isset($default_domain)) $this->default_domain = $default_domain;
        if (isset($nest_groups))    $this->nestGroups     = $nest_groups;
        if (isset($Validate))       $this->validate       = $Validate;
        if (isset($limit))          $this->limit          = $limit;
    }


    /**
     * Starts the whole process. The address must either be set here
     * or when creating the object. One or the other.
     *
     * @access public
     * @param string  $Address         The address(es) to validate.
     * @param string  $default_domain  Default domain/host etc.
     * @param boolean $nest_groups     Whether to return the structure with groups nested for easier viewing.
     * @param boolean $Validate        Whether to validate atoms. Turn this off if you need to run addresses through before encoding the personal names, for instance.
     *
     * @return array A structured array of addresses.
     */
    function parseAddressList($Address = null, $default_domain = null, $nest_groups = null, $Validate = null, $limit = null)
    {

        if (!isset($this->mailRFC822)) {
            $obj = new Mail_RFC822($Address, $default_domain, $nest_groups, $Validate, $limit);
            return $obj->parseAddressList();
        }

        if (isset($Address))        $this->address        = $Address;
        if (isset($default_domain)) $this->default_domain = $default_domain;
        if (isset($nest_groups))    $this->nestGroups     = $nest_groups;
        if (isset($Validate))       $this->validate       = $Validate;
        if (isset($limit))          $this->limit          = $limit;

        $this->structure  = array();
        $this->addresses  = array();
        $this->error      = null;
        $this->index      = null;

        while ($this->address = $this->_splitAddresses($this->address)) {
            continue;
        }

        if ($this->address === false || isset($this->error)) {
            return false;
        }

        // Reset timer since large amounts of addresses can take a long time to
        // get here
        set_time_limit(30);

        // Loop through all the addresses
        for ($i = 0; $i < count($this->addresses); $i++){

            if (($Return = $this->_validateAddress($this->addresses[$i])) === false
                || isset($this->error)) {
                return false;
            }

            if (!$this->nestGroups) {
                $this->structure = array_merge($this->structure, $Return);
            } else {
                $this->structure[] = $Return;
            }
        }

        return $this->structure;
    }

    /**
     * Splits an address into seperate addresses.
     *
     * @access private
     * @param string $Address The addresses to split.
     * @return boolean Success or failure.
     */
    function _splitAddresses($Address)
    {

        if (!empty($this->limit) AND count($this->addresses) == $this->limit) {
            return '';
        }

        if ($this->_isGroup($Address) && !isset($this->error)) {
            $split_char = ';';
            $is_group   = true;
        } elseif (!isset($this->error)) {
            $split_char = ',';
            $is_group   = false;
        } elseif (isset($this->error)) {
            return false;
        }

        // Split the string based on the above ten or so lines.
        $parts  = explode($split_char, $Address);
        $string = $this->_splitCheck($parts, $split_char);

        // If a group...
        if ($is_group) {
            // If $string does not contain a colon outside of
            // brackets/quotes etc then something's fubar.

            // First check there's a colon at all:
            if (mb_strpos($string, ':') === false) {
                $this->error = 'Invalid address: ' . $string;
                return false;
            }

            // Now check it's outside of brackets/quotes:
            if (!$this->_splitCheck(explode(':', $string), ':'))
                return false;

            // We must have a group at this point, so increase the counter:
            $this->num_groups++;
        }

        // $string now contains the first full address/group.
        // Add to the addresses array.
        $this->addresses[] = array(
                                   'address' => trim($string),
                                   'group'   => $is_group
                                   );

        // Remove the now stored address from the initial line, the +1
        // is to account for the explode character.
        $Address = trim(mb_substr($Address, mb_strlen($string) + 1));

        // If the next char is a comma and this was a group, then
        // there are more addresses, otherwise, if there are any more
        // chars, then there is another address.
        if ($is_group && mb_substr($Address, 0, 1) == ','){
            $Address = trim(mb_substr($Address, 1));
            return $Address;

        } elseif (mb_strlen($Address) > 0) {
            return $Address;

        } else {
            return '';
        }

        // If you got here then something's off
        return false;
    }

    /**
     * Checks for a group at the start of the string.
     *
     * @access private
     * @param string $Address The address to check.
     * @return boolean Whether or not there is a group at the start of the string.
     */
    function _isGroup($Address)
    {
        // First comma not in quotes, angles or escaped:
        $parts  = explode(',', $Address);
        $string = $this->_splitCheck($parts, ',');

        // Now we have the first address, we can reliably check for a
        // group by searching for a colon that's not escaped or in
        // quotes or angle brackets.
        if (count($parts = explode(':', $string)) > 1) {
            $string2 = $this->_splitCheck($parts, ':');
            return ($string2 !== $string);
        } else {
            return false;
        }
    }

    /**
     * A common function that will check an exploded string.
     *
     * @access private
     * @param array $parts The exloded string.
     * @param string $char  The char that was exploded on.
     * @return mixed False if the string contains unclosed quotes/brackets, or the string on success.
     */
    function _splitCheck($parts, $char)
    {
        $string = $parts[0];

        for ($i = 0; $i < count($parts); $i++) {
            if ($this->_hasUnclosedQuotes($string)
                || $this->_hasUnclosedBrackets($string, '<>')
                || $this->_hasUnclosedBrackets($string, '[]')
                || $this->_hasUnclosedBrackets($string, '()')
                || mb_substr($string, -1) == '\\') {
                if (isset($parts[$i + 1])) {
                    $string = $string . $char . $parts[$i + 1];
                } else {
                    $this->error = 'Invalid address spec. Unclosed bracket or quotes';
                    return false;
                }
            } else {
                $this->index = $i;
                break;
            }
        }

        return $string;
    }

    /**
     * Checks if a string has an unclosed quotes or not.
     *
     * @access private
     * @param string $string The string to check.
     * @return boolean True if there are unclosed quotes inside the string, false otherwise.
     */
    function _hasUnclosedQuotes($string)
    {
        $string     = explode('"', $string);
        $string_cnt = count($string);

        for ($i = 0; $i < (count($string) - 1); $i++)
            if (mb_substr($string[$i], -1) == '\\')
                $string_cnt--;

        return ($string_cnt % 2 === 0);
    }

    /**
     * Checks if a string has an unclosed brackets or not. IMPORTANT:
     * This function handles both angle brackets and square brackets;
     *
     * @access private
     * @param string $string The string to check.
     * @param string $chars  The characters to check for.
     * @return boolean True if there are unclosed brackets inside the string, false otherwise.
     */
    function _hasUnclosedBrackets($string, $chars)
    {
        $num_angle_start = substr_count($string, $chars[0]);
        $num_angle_end   = substr_count($string, $chars[1]);

        $this->_hasUnclosedBracketsSub($string, $num_angle_start, $chars[0]);
        $this->_hasUnclosedBracketsSub($string, $num_angle_end, $chars[1]);

        if ($num_angle_start < $num_angle_end) {
            $this->error = 'Invalid address spec. Unmatched quote or bracket (' . $chars . ')';
            return false;
        } else {
            return ($num_angle_start > $num_angle_end);
        }
    }

    /**
     * Sub function that is used only by hasUnclosedBrackets().
     *
     * @access private
     * @param string $string The string to check.
     * @param integer &$num    The number of occurrences.
     * @param string $char   The character to count.
     * @return integer The number of occurrences of $char in $string, adjusted for backslashes.
     */
    function _hasUnclosedBracketsSub($string, &$num, $char)
    {
        $parts = explode($char, $string);
        for ($i = 0; $i < count($parts); $i++){
            if (mb_substr($parts[$i], -1) == '\\' || $this->_hasUnclosedQuotes($parts[$i]))
                $num--;
            if (isset($parts[$i + 1]))
                $parts[$i + 1] = $parts[$i] . $char . $parts[$i + 1];
        }

        return $num;
    }

    /**
     * Function to begin checking the address.
     *
     * @access private
     * @param string $Address The address to validate.
     * @return mixed False on failure, or a structured array of address information on success.
     */
    function _validateAddress($Address)
    {
        $is_group = false;

        if ($Address['group']) {
            $is_group = true;

            // Get the group part of the name
            $parts     = explode(':', $Address['address']);
            $groupname = $this->_splitCheck($parts, ':');
            $structure = array();

            // And validate the group part of the name.
            if (!$this->_validatePhrase($groupname)){
                $this->error = 'Group name did not validate.';
                return false;
            } else {
                // Don't include groups if we are not nesting
                // them. This avoids returning invalid addresses.
                if ($this->nestGroups) {
                    $structure = new stdClass;
                    $structure->groupname = $groupname;
                }
            }

            $Address['address'] = ltrim(mb_substr($Address['address'], mb_strlen($groupname . ':')));
        }

        // If a group then split on comma and put into an array.
        // Otherwise, Just put the whole address in an array.
        if ($is_group) {
            while (mb_strlen($Address['address']) > 0) {
                $parts       = explode(',', $Address['address']);
                $Addresses[] = $this->_splitCheck($parts, ',');
                $Address['address'] = trim(mb_substr($Address['address'], mb_strlen(end($Addresses) . ',')));
            }
        } else {
            $Addresses[] = $Address['address'];
        }

        // Check that $Addresses is set, if address like this:
        // Groupname:;
        // Then errors were appearing.
        if (!isset($Addresses)){
            $this->error = 'Empty group.';
            return false;
        }

        for ($i = 0; $i < count($Addresses); $i++) {
            $Addresses[$i] = trim($Addresses[$i]);
        }

        // Validate each mailbox.
        // Format could be one of: name <geezer@domain.com>
        //                         geezer@domain.com
        //                         geezer
        // ... or any other format valid by RFC 822.
        array_walk($Addresses, array($this, 'validateMailbox'));

        // Nested format
        if ($this->nestGroups) {
            if ($is_group) {
                $structure->addresses = $Addresses;
            } else {
                $structure = $Addresses[0];
            }

        // Flat format
        } else {
            if ($is_group) {
                $structure = array_merge($structure, $Addresses);
            } else {
                $structure = $Addresses;
            }
        }

        return $structure;
    }

    /**
     * Function to validate a phrase.
     *
     * @access private
     * @param string $phrase The phrase to check.
     * @return boolean Success or failure.
     */
    function _validatePhrase($phrase)
    {
        // Splits on one or more Tab or space.
        $parts = preg_split('/[ \\x09]+/', $phrase, -1, PREG_SPLIT_NO_EMPTY);

        $phrase_parts = array();
        while (count($parts) > 0){
            $phrase_parts[] = $this->_splitCheck($parts, ' ');
            for ($i = 0; $i < $this->index + 1; $i++)
                array_shift($parts);
        }

        for ($i = 0; $i < count($phrase_parts); $i++) {
            // If quoted string:
            if (mb_substr($phrase_parts[$i], 0, 1) == '"') {
                if (!$this->_validateQuotedString($phrase_parts[$i]))
                    return false;
                continue;
            }

            // Otherwise it's an atom:
            if (!$this->_validateAtom($phrase_parts[$i])) return false;
        }

        return true;
    }

    /**
     * Function to validate an atom which from rfc822 is:
     * atom = 1*<any CHAR except specials, SPACE and CTLs>
     *
     * If validation ($this->validate) has been turned off, then
     * validateAtom() doesn't actually check anything. This is so that you
     * can split a list of addresses up before encoding personal names
     * (umlauts, etc.), for example.
     *
     * @access private
     * @param string $atom The string to check.
     * @return boolean Success or failure.
     */
    function _validateAtom($atom)
    {
        if (!$this->validate) {
            // Validation has been turned off; assume the atom is okay.
            return true;
        }

        // Check for any char from ASCII 0 - ASCII 127
        if (!preg_match('/^[\\x00-\\x7E]+$/i', $atom, $matches)) {
            return false;
        }

        // Check for specials:
        if (preg_match('/[][()<>@,;\\:". ]/', $atom)) {
            return false;
        }

        // Check for control characters (ASCII 0-31):
        if (preg_match('/[\\x00-\\x1F]+/', $atom)) {
            return false;
        }

        return true;
    }

    /**
     * Function to validate quoted string, which is:
     * quoted-string = <"> *(qtext/quoted-pair) <">
     *
     * @access private
     * @param string $qstring The string to check
     * @return boolean Success or failure.
     */
    function _validateQuotedString($qstring)
    {
        // Leading and trailing "
        $qstring = mb_substr($qstring, 1, -1);

        // Perform check.
        return !(preg_match('/(.)[\x0D\\\\"]/', $qstring, $matches) && $matches[1] != '\\');
    }

    /**
     * Function to validate a mailbox, which is:
     * mailbox =   addr-spec         ; simple address
     *           / phrase route-addr ; name and route-addr
     *
     * @access public
     * @param string &$mailbox The string to check.
     * @return boolean Success or failure.
     */
    function validateMailbox(&$mailbox)
    {
        // A couple of defaults.
        $phrase  = '';
        $comment = '';

        // Catch any RFC822 comments and store them separately
        $_mailbox = $mailbox;
        while (mb_strlen(trim($_mailbox)) > 0) {
            $parts = explode('(', $_mailbox);
            $before_comment = $this->_splitCheck($parts, '(');
            if ($before_comment != $_mailbox) {
                // First char should be a (
                $comment    = mb_substr(str_replace($before_comment, '', $_mailbox), 1);
                $parts      = explode(')', $comment);
                $comment    = $this->_splitCheck($parts, ')');
                $comments[] = $comment;

                // +1 is for the trailing )
                $_mailbox   = mb_substr($_mailbox, mb_strpos($_mailbox, $comment)+mb_strlen($comment)+1);
            } else {
                break;
            }
        }

        for($i=0; $i<count(@$comments); $i++){
            $mailbox = str_replace('('.$comments[$i].')', '', $mailbox);
        }
        $mailbox = trim($mailbox);

        // Check for name + route-addr
        if (mb_substr($mailbox, -1) == '>' && mb_substr($mailbox, 0, 1) != '<') {
            $parts  = explode('<', $mailbox);
            $name   = $this->_splitCheck($parts, '<');

            $phrase     = trim($name);
            $route_addr = trim(mb_substr($mailbox, mb_strlen($name . '<'), -1));

            if ($this->_validatePhrase($phrase) === false || ($route_addr = $this->_validateRouteAddr($route_addr)) === false)
                return false;

        // Only got addr-spec
        } else {
            // First snip angle brackets if present.
            if (mb_substr($mailbox,0,1) == '<' && mb_substr($mailbox,-1) == '>')
                $addr_spec = mb_substr($mailbox,1,-1);
            else
                $addr_spec = $mailbox;

            if (($addr_spec = $this->_validateAddrSpec($addr_spec)) === false)
                return false;
        }

        // Construct the object that will be returned.
        $mbox = new stdClass();

        // Add the phrase (even if empty) and comments
        $mbox->personal = $phrase;
        $mbox->comment  = isset($comments) ? $comments : array();

        if (isset($route_addr)) {
            $mbox->mailbox = $route_addr['local_part'];
            $mbox->host    = $route_addr['domain'];
            $route_addr['adl'] !== '' ? $mbox->adl = $route_addr['adl'] : '';
        } else {
            $mbox->mailbox = $addr_spec['local_part'];
            $mbox->host    = $addr_spec['domain'];
        }

        $mailbox = $mbox;
        return true;
    }

    /**
     * This function validates a route-addr which is:
     * route-addr = "<" [route] addr-spec ">"
     *
     * Angle brackets have already been removed at the point of
     * getting to this function.
     *
     * @access private
     * @param string $route_addr The string to check.
     * @return mixed False on failure, or an array containing validated address/route information on success.
     */
    function _validateRouteAddr($route_addr)
    {
        // Check for colon.
        if (mb_strpos($route_addr, ':') !== false) {
            $parts = explode(':', $route_addr);
            $route = $this->_splitCheck($parts, ':');
        } else {
            $route = $route_addr;
        }

        // If $route is same as $route_addr then the colon was in
        // quotes or brackets or, of course, non existent.
        if ($route === $route_addr){
            unset($route);
            $addr_spec = $route_addr;
            if (($addr_spec = $this->_validateAddrSpec($addr_spec)) === false) {
                return false;
            }
        } else {
            // Validate route part.
            if (($route = $this->_validateRoute($route)) === false) {
                return false;
            }

            $addr_spec = mb_substr($route_addr, mb_strlen($route . ':'));

            // Validate addr-spec part.
            if (($addr_spec = $this->_validateAddrSpec($addr_spec)) === false) {
                return false;
            }
        }

        if (isset($route)) {
            $Return['adl'] = $route;
        } else {
            $Return['adl'] = '';
        }

        $Return = array_merge($Return, $addr_spec);
        return $Return;
    }

    /**
     * Function to validate a route, which is:
     * route = 1#("@" domain) ":"
     *
     * @access private
     * @param string $route The string to check.
     * @return mixed False on failure, or the validated $route on success.
     */
    function _validateRoute($route)
    {
        // Split on comma.
        $domains = explode(',', trim($route));

        for ($i = 0; $i < count($domains); $i++) {
            $domains[$i] = str_replace('@', '', trim($domains[$i]));
            if (!$this->_validateDomain($domains[$i])) return false;
        }

        return $route;
    }

    /**
     * Function to validate a domain, though this is not quite what
     * you expect of a strict internet domain.
     *
     * domain = sub-domain *("." sub-domain)
     *
     * @access private
     * @param string $domain The string to check.
     * @return mixed False on failure, or the validated domain on success.
     */
    function _validateDomain($domain)
    {
        // Note the different use of $subdomains and $sub_domains
        $subdomains = explode('.', $domain);

        while (count($subdomains) > 0) {
            $sub_domains[] = $this->_splitCheck($subdomains, '.');
            for ($i = 0; $i < $this->index + 1; $i++)
                array_shift($subdomains);
        }

        for ($i = 0; $i < count($sub_domains); $i++) {
            if (!$this->_validateSubdomain(trim($sub_domains[$i])))
                return false;
        }

        // Managed to get here, so return input.
        return $domain;
    }

    /**
     * Function to validate a subdomain:
     *   subdomain = domain-ref / domain-literal
     *
     * @access private
     * @param string $subdomain The string to check.
     * @return boolean Success or failure.
     */
    function _validateSubdomain($subdomain)
    {
        if (preg_match('|^\[(.*)]$|', $subdomain, $arr)){
            if (!$this->_validateDliteral($arr[1])) return false;
        } else {
            if (!$this->_validateAtom($subdomain)) return false;
        }

        // Got here, so return successful.
        return true;
    }

    /**
     * Function to validate a domain literal:
     *   domain-literal =  "[" *(dtext / quoted-pair) "]"
     *
     * @access private
     * @param string $dliteral The string to check.
     * @return boolean Success or failure.
     */
    function _validateDliteral($dliteral)
    {
        return !preg_match('/(.)[][\x0D\\\\]/', $dliteral, $matches) && $matches[1] != '\\';
    }

    /**
     * Function to validate an addr-spec.
     *
     * addr-spec = local-part "@" domain
     *
     * @access private
     * @param string $addr_spec The string to check.
     * @return mixed False on failure, or the validated addr-spec on success.
     */
    function _validateAddrSpec($addr_spec)
    {
        $addr_spec = trim($addr_spec);

        // Split on @ sign if there is one.
        if (mb_strpos($addr_spec, '@') !== false) {
            $parts      = explode('@', $addr_spec);
            $local_part = $this->_splitCheck($parts, '@');
            $domain     = mb_substr($addr_spec, mb_strlen($local_part . '@'));

        // No @ sign so assume the default domain.
        } else {
            $local_part = $addr_spec;
            $domain     = $this->default_domain;
        }

        if (($local_part = $this->_validateLocalPart($local_part)) === false) return false;
        if (($domain     = $this->_validateDomain($domain)) === false) return false;

        // Got here so return successful.
        return array('local_part' => $local_part, 'domain' => $domain);
    }

    /**
     * Function to validate the local part of an address:
     *   local-part = word *("." word)
     *
     * @access private
     * @param string $local_part
     * @return mixed False on failure, or the validated local part on success.
     */
    function _validateLocalPart($local_part)
    {
        $parts = explode('.', $local_part);

        // Split the local_part into words.
        while (count($parts) > 0){
            $words[] = $this->_splitCheck($parts, '.');
            for ($i = 0; $i < $this->index + 1; $i++) {
                array_shift($parts);
            }
        }

        // Validate each word.
        for ($i = 0; $i < count($words); $i++) {
            if ($this->_validatePhrase(trim($words[$i])) === false) return false;
        }

        // Managed to get here, so return the input.
        return $local_part;
    }

    /**
    * Returns an approximate count of how many addresses are
    * in the given string. This is APPROXIMATE as it only splits
    * based on a comma which has no preceding backslash. Could be
    * useful as large amounts of addresses will end up producing
    * *large* structures when used with parseAddressList().
    *
    * @param  string $data Addresses to count
    * @return int          Approximate count
    */
    function approximateCount($data)
    {
        return count(preg_split('/(?<!\\\\),/', $data));
    }

    /**
    * This is a email validating function seperate to the rest
    * of the class. It simply validates whether an email is of
    * the common internet form: <user>@<domain>. This can be
    * sufficient for most people. Optional stricter mode can
    * be utilised which restricts mailbox characters allowed
    * to alphanumeric, full stop, hyphen and underscore.
    *
    * @param  string  $data   Address to check
    * @param  boolean $strict Optional stricter mode
    * @return mixed           False if it fails, an indexed array
    *                         username/domain if it matches
    */
    function isValidInetAddress($data, $strict = false)
    {
        $regex = $strict ? '/^([.0-9a-z_-]+)@(([0-9a-z-]+\.)+[0-9a-z]{2,4})$/i' : '/^([*+!.&#$|\'\\%\/0-9a-z^_`{}=?~:-]+)@(([0-9a-z-]+\.)+[0-9a-z]{2,4})$/i';
        if (preg_match($regex, trim($data), $matches)) {
            return array($matches[1], $matches[2]);
        } else {
            return false;
        }
    }
}
