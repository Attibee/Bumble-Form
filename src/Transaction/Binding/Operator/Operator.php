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

namespace Bumble\Database\Transaction\Binding\Operator;

use \Bumble\Database\Transaction\Binding\Binding;

/**
 * Base operator extended by all operators.
 * 
 * An operator describes a SQL operator. Each operator requires a format string with the
 * bindings {column} and {value}.
 */
abstract class Operator extends Binding  {
    protected $column = '';
    protected $value = '';
    protected $format = '';
    
    public function __construct( $column=null, $value=null ) {
        $this->setOperands( $column, $value );
    }
    
    public function __invoke( $column=null, $value=null ) {
        $this->setOperands( $column, $value );
    }
    
    public function setOperands( $column=null, $value=null ) {
        $this->column = $column;
        $this->value = $value;
    }
    
    public function setOptions( $column=null, $value=null ){
        $this->setOperands( $column, $value );
    }
    
    public function toString() {
        $identifierQuote = $this->getSymbols()->IDENTIFIER_QUOTE;
        $stringQuote = $this->getSymbols()->STRING_QUOTE;
        
        //wrap column in identifier quotes
        $column = $identifierQuote . $this->column . $identifierQuote;
        $value = $this->value;
        
        //if a string, wrap string in string quotes, else leave as is
        if( is_string( $this->value ) ) {
            $value = $stringQuote . $this->value . $stringQuote;
        }
        
        return str_replace(
            array( '{column}', '{value}'),
            array( $column, $value ),
            $this->format
        );
    }
}
