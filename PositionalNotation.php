<?php
/**
 * Copyright (c) 2007 Martin Jansen
 * 
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions
 * are met:
 * 1. Redistributions of source code must retain the above copyright
 *    notice, this list of conditions and the following disclaimer.
 * 2. Redistributions in binary form must reproduce the above copyright
 *    notice, this list of conditions and the following disclaimer in the
 *    documentation and/or other materials provided with the distribution.
 * 3. The name of the author may not be used to endorse or promote products
 *    derived from this software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE AUTHOR ``AS IS'' AND ANY EXPRESS OR
 * IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES
 * OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED.
 * IN NO EVENT SHALL THE AUTHOR BE LIABLE FOR ANY DIRECT, INDIRECT,
 * INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT
 * NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF
 * THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
*/

class Math_PositionalNotation_Exception extends Exception {}

/**
 * Implements what is know as Positional Notation in mathematics
 *
 * This class is capable of converting numbers from decimal notation in
 * representations in systems with the bases 2 (binary), 3, 4, 8, 9, 10 (decimal),
 * 12, 16, 24, 30, 32, 36, 60, and 64.
 *
 * The code was originally intended to be publish in PEAR, but as it turns out
 * there is already a package called Math_Basex that does the same.
 *
 * @author Martin Jansen <martin@divbyzero.net>
 * @license http://www.opensource.org/licenses/bsd-license.php BSD
 */
class Math_PositionalNotation {

    const ALPHABET = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    private static $SUPPORTED_BASES = array(2, 4, 8, 16, 32, 64, 3, 9, 10, 12, 24, 30, 36, 60);
    
    /**
     * Convenience method to convert the given number to the binary representation.
     *
     * Calling this method is equivalent to calling $this->toBase2($input).
     *
     * @param int Number to convert
     * @return string Binary representation of the input
     */
    public function toBinary($input) {
        return self::toBase($input, 2);
    }

    /**
     * Convenience method to convert the given binray number to the decimal representation.
     *
     * Calling this method is equivalent to calling $this->fromBase2($input).
     *
     * @param int Binary number to convert
     * @return string Decimal number equivalent to the input
     */
    public function fromBinary($input) {
        return self::fromBase($input, 2);
    }

    /**
     * Captures invocations of toBase* and fromBase* method calls.
     *
     * This method captures calls like $this->toBase36(122) or $this->fromBase36("AB")
     * and passes them to self::toBase() resp. self::fromBase().  If the 
     * "virtual method" is not valid, a Math_PositionalNotation_Exception is
     * thrown.
     *
     * @throws Math_PositionalNotation_Exception
     */
    public function __call($name, $args) {
        if (substr($name, 0, 6) == "toBase") {
            $base = substr($name, 6, 2);

            if (!in_array($base, self::$SUPPORTED_BASES)) {
                throw new Math_PositionalNotation_Exception("Unsupported base " . $base);
            }
            
            return self::toBase($args[0], $base);
        }  else if (substr($name, 0, 8) == "fromBase") {
            $base = substr($name, 8, 2);

            if (!in_array($base, self::$SUPPORTED_BASES)) {
                throw new Math_PositionalNotation_Exception("Unsupported base " . $base);
            }
            
            return self::fromBase($args[0], $base);
        }
        
        throw new Math_PositionalNotation_Exception("Call to undefined method " . $name . " in " . __FILE__ . " in line " . __LINE__);
    }

    /**
     * Converts an integer in decimal notation into some positional notation
     *
     * @param int Integer value in decimal notation
     * @param int Base of the positional notation
     * @return string
     */
    private static function toBase($input, $base) {
        $result = "";
        
        $alphabet = substr(self::ALPHABET, 0, $base);
        do {
            $remainder = bcmod($input, $base);
            $input = bcdiv(bcsub($input, $remainder), $base);

            $result = $alphabet{$remainder} . $result;
        } while ($input > 0);
        
        return $result;
    }
    
    /**
     * Converts a number in some positional notation into an integer in decimal notation
     *
     * @param string Representation of a number in a positional notation
     * @param int Base of the positional notation
     * @throws Math_PositionalNotation_Exception
     * @return int
     */
    private static function fromBase($input, $base) {
        $result = $i = 0;
        
        $alphabet = substr(self::ALPHABET, 0, $base);
        do {
            $first = $input{0};
            $input = substr($input, 1);
            
            $key = strpos($alphabet, $first);
            if ($key === false) {
                throw new Math_PositionalNotation_Exception("Illegal character in input at column " . $i);
            }
            
            $result = bcadd(bcmul($result, $base), $key);
            $i++;
        } while ($input != "");
        
        return $result;
    }
}
