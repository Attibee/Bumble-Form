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

/**
 * Binds a map of column to value to an UPDATE's SET statement.
 * 
 * The binding accepts an associative array of column=>value.
 */
class Set extends Binding {
    protected $sets = array();

    public function setOptions( $options = array() ) {
        //array, we copy it to our sets
        if( is_array( $options ) ) {
            foreach( $options as $name=>$value ) {
                $this->sets[$name] = $value;
            }
        } else if( count( func_get_args() ) == 2 ) {
            $args = func_get_args();
            
            $this->sets[$args[0]] = $args[1];
        }
    }
    
    public function toString() {
        if( count( $this->sets ) == 0 ) {
            throw new \Exception('The set statement cannot be empty.');
        } 
        
        $str = '';
        
        foreach( $this->sets as $name=>$value ) {
            $str .= "$name=$value, ";
        }
        
        return rtrim( $str, ' ,' );
    }
}
