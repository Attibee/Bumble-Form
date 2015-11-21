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

namespace Bumble\Database\Transaction\Binding;

/**
 * Binds a list of columns to a SELECT statement.
 * 
 * Columns excepts an array or list of strings as its parameter. Eeach string should be
 * the name of the table's column.
 * 
 * Columns may be called with an array:
 *      Columns::setOptions(array( 'columnA', 'columnB', 'ColumnC' ))
 * 
 * Or using a list of parameters:
 *      Columns::setOptions( 'columnA', 'columnB', 'columnC' )
 */
class Columns extends Binding {
    private $columns = array();
    
    /**
     * Sets the options.
     * 
     * The options may be an array of columns or a list of parameters.
     * 
     * @param Array|Parameters $columns An array of columsn or list of parameters.
     */
    public function setOptions( $columns = array() ) {
        $this->columns = is_array( $columns ) ? $columns : func_get_args();
    }
    
    public function toString() {
        if( count( $this->columns ) == 0 ) {
            throw new \Exception('No columns selected.');
        }
        
        $str = '';
        
        //identifier quote
        $q = $this->getSymbols()->IDENTIFIER_QUOTE;

        foreach( $this->columns as $column ) {
            $str .= "$q$column$q, ";
        }
        
        return rtrim( $str, ' ,' );
    }
}