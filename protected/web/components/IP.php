<?php

    /**
     * ip_in_range.php - function to determine if an IP is located in a
     *                   specific range as specified via several alternative
     *                   formats.
     *
     * Network ranges can be specified as:
     * 1. Wildcard format:     1.2.3.*
     * 2. CIDR format:         1.2.3/24  OR  1.2.3.4/255.255.255.0
     * 3. Start-End IP format: 1.2.3.0-1.2.3.255
     *
     * Return value BOOLEAN : ip_in_range($ip, $range);
     *
     * Copyright 2008: Paul Gregg <pgregg@pgregg.com>
     * 10 January 2008
     * Version: 1.2
     *
     * Source website: http://www.pgregg.com/projects/php/ip_in_range/
     * Version 1.2
     *
     * This software is Donationware - if you feel you have benefited from
     * the use of this tool then please consider a donation. The value of
     * which is entirely left up to your discretion.
     * http://www.pgregg.com/donate/
     *
     * Please do not remove this header, or source attibution from this file.
     */
    class IP
    {

        /**
         * decbin32
         * In order to simplify working with IP addresses (in binary) and their
         * netmasks, it is easier to ensure that the binary strings are padded
         * with zeros out to 32 characters - IP addresses are 32 bit numbers
         */
        function decbin32($dec)
        {
            return str_pad(decbin($dec), 32, '0', STR_PAD_LEFT);
        }

        /**
         * ip_in_range
         * This function takes 2 arguments, an IP address and a "range" in several
         * different formats.
         * Network ranges can be specified as:
         * 1. Wildcard format:     1.2.3.*
         * 2. CIDR format:         1.2.3/24  OR  1.2.3.4/255.255.255.0
         * 3. Start-End IP format: 1.2.3.0-1.2.3.255
         * The function will return true if the supplied IP is within the range.
         * Note little validation is done on the range inputs - it expects you to
         * use one of the above 3 formats.
         */
        static function ip_in_range($ip, $range)
        {
            if (strpos($range, '/') !== FALSE) {
                // $range is in IP/NETMASK format
                list($range, $netmask) = explode('/', $range, 2);
                if (strpos($netmask, '.') !== FALSE) {
                    // $netmask is a 255.255.0.0 format
                    $netmask     = str_replace('*', '0', $netmask);
                    $netmask_dec = ip2long($netmask);

                    return ((ip2long($ip) & $netmask_dec) == (ip2long($range) & $netmask_dec));
                } else {
                    // $netmask is a CIDR size block
                    // fix the range argument
                    $x = explode('.', $range);
                    while (count($x) < 4) $x[] = '0';
                    list($a, $b, $c, $d) = $x;
                    $range     = sprintf("%u.%u.%u.%u", empty($a) ? '0' : $a, empty($b) ? '0' : $b, empty($c) ? '0' : $c, empty($d) ? '0' : $d);
                    $range_dec = ip2long($range);
                    $ip_dec    = ip2long($ip);

                    # Strategy 1 - Create the netmask with 'netmask' 1s and then fill it to 32 with 0s
                    #$netmask_dec = bindec(str_pad('', $netmask, '1') . str_pad('', 32-$netmask, '0'));

                    # Strategy 2 - Use math to create it
                    $wildcard_dec = pow(2, (32 - $netmask)) - 1;
                    $netmask_dec  = ~$wildcard_dec;

                    return (($ip_dec & $netmask_dec) == ($range_dec & $netmask_dec));
                }
            } else {
                // range might be 255.255.*.* or 1.2.3.0-1.2.3.255
                if (strpos($range, '*') !== FALSE) { // a.b.*.* format
                    // Just convert to A-B format by setting * to 0 for A and 255 for B
                    $lower = str_replace('*', '0', $range);
                    $upper = str_replace('*', '255', $range);
                    $range = "$lower-$upper";
                }

                if (strpos($range, '-') !== FALSE) { // A-B format
                    list($lower, $upper) = explode('-', $range, 2);
                    $lower_dec = (float)sprintf("%u", ip2long($lower));
                    $upper_dec = (float)sprintf("%u", ip2long($upper));
                    $ip_dec    = (float)sprintf("%u", ip2long($ip));

                    return (($ip_dec >= $lower_dec) && ($ip_dec <= $upper_dec));
                }

                echo 'Range argument is not in 1.2.3.4/24 or 1.2.3.4/255.255.255.0 format';

                return FALSE;
            }

        }

        /**
         * Detect Telco
         * @param $ip
         * @param $ip_map_3G
         *
         * @return bool|int|string
         */
        public static function detectTelco($ip, $ip_map_3G)
        {
            foreach ($ip_map_3G as $telco => $ary_ip_range) {
                foreach ($ary_ip_range as $ip_range) {
                    if (self::ip_in_range($ip, $ip_range)) {
                        return $telco;
                    }
                }
            }

            return 'UNKNOWN_TELCO';
        }

    }