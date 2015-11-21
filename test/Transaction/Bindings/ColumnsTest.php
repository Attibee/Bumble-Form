<?php

/*
 * Copyright 2015 Attibee (http://attibee.com)

 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 * 
 *     http://www.apache.org/licenses/LICENSE-2.0
 * 
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace Attibee\Database\Transaction\Binding;

class ColumnsTest extends \PHPUnit_Framework_TestCase {
    public function testCreation() {
        $columns = new Columns();
        
        //set options both ways
        $columns->setOptions('a','b','c');
        $columns->setOptions(array('a','b','c'));
        
        return $columns;
    }
    
    /**
     * @depends testCreation
     */
    public function testCorrectAnsi( Columns $columns ) {   
        $this->assertEquals(
            $columns->toString(),
            '"a", "b", "c"'
        );
    }
    
    /**
     * @depends testCreation
     */
    public function testCorrectMysql( Columns $columns ) {
        $columns->setSymbols( Columns::MYSQL );
        
        $this->assertEquals(
            $columns->toString(),
            '`a`, `b`, `c`'
        );
    }
}
