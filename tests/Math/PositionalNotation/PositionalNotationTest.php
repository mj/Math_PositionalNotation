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

/**
 * Test suite for the Math_PositionalNotation class
 *
 * @author Martin Jansen <martin@divbyzero.net>
 * @license http://www.opensource.org/licenses/bsd-license.php BSD
 */

// Call Math_PositionalNotationTest::main() if executed directly.
if (!defined("PHPUnit_MAIN_METHOD")) {
    define("PHPUnit_MAIN_METHOD", "Math_PositionalNotationTest::main");
}

require_once "PHPUnit/Framework/TestCase.php";
require_once "PHPUnit/Framework/TestSuite.php";

require_once "Math/PositionalNotation.php";

/**
 * Test class for Math_PositionalNotation.
 *
 * @author Martin Jansen <martin@divbyzero.net>
 * @license http://www.opensource.org/licenses/bsd-license.php BSD
 */
class Math_PositionalNotationTest extends PHPUnit_Framework_TestCase {

    protected static $supportedBases = array(2, 4, 8, 16, 32, 64, 3, 9, 10, 12, 24, 30, 36, 60);

    /**
     * Runs the test methods of this class.
     */
    public static function main() {
        include_once "PHPUnit/TextUI/TestRunner.php";

        $suite  = new PHPUnit_Framework_TestSuite("Math_PositionalNotationTest");
        $result = PHPUnit_TextUI_TestRunner::run($suite);
    }
    
    protected function setUp() {
        $this->obj = new Math_PositionalNotation;
    }
    
    public function testIdentity() {
        $result = $this->obj->fromBinary($this->obj->toBinary(12));
        self::assertEquals(12, $result);

        foreach (self::$supportedBases as $base) {
            $result = $this->obj->{"fromBase" . $base}($this->obj->{"toBase" . $base}(12));
            self::assertEquals(12, $result);
        }
    }
    
    public function testToBinaryNotation() {
        $result1 = $this->obj->toBinary(1);
        $result2 = $this->obj->toBase2(1);
        $result3 = decbin(1);

        self::assertEquals("00000001", $result1);
        self::assertEquals($result1, $result2);
        self::assertEquals($result1, $result3);
    }

    public function testFromBinaryNotation() {
        $result1 = $this->obj->fromBinary("00000001");
        $result2 = $this->obj->fromBase2("00000001");
        $result3 = bindec("00000001");
        
        self::assertEquals(1, $result1);
        self::assertEquals($result1, $result2);
        self::assertEquals($result1, $result3);
    }
    
    public function testMethodCallExceptions1() {
        $this->setExpectedException("Math_PositionalNotation_Exception");
        $this->obj->toBase99(1);
    }

    public function testMethodCallExceptions2() {
        $this->setExpectedException("Math_PositionalNotation_Exception");
        $this->obj->fromBase99(1);
    }

    public function testMethodCallExceptions3() {
        $this->setExpectedException("Math_PositionalNotation_Exception");
        $this->obj->foofoo(1);
    }
}
