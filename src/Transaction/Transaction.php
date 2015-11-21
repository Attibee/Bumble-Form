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

namespace Bumble\Database\Transaction;

use \Bumble\Database\Symbols\SymbolAwareAbstract;

/**
 * A database transaction provides an interface for configurable SQL statements. For example,
 * we can have the format:
 * 
 * SELECT {columns} FROM books
 * 
 * The binding {columns} allows the application programmer to configure the SELECT
 * statement without ever knowing its implementation.
 * 
 * For example, we can configure the method's columns in the follow way:
 * 
 * $transaction->columns('author', 'title', 'isbn')
 *
 * @author Attibee <support@attibee.com>
 */
class Transaction extends SymbolAwareAbstract {
    /**
     * The method's format.
     */
    protected $format = '';
    
    /**
     * A list of binding identifiers used by the transaction.
     */
    protected $bindings = array();
    
    /**
     * Instantiated bindings are stored in this array.
     */
    protected $abstractBindings = array();

    const SET     = 'SET';
    const WHERE   = 'WHERE';
    const COLUMNS = 'COLUMNS';
    const VALUES  = 'VALUES';
    const LIMIT   = 'LIMIT';
    const OFFSET  = 'OFFSET';
    
    public function __construct() {
        foreach( $this->bindings as $name ) {
            $this->addBinding( $name );
        }
    }
    
    /**
     * Sets the query format.
     * @param string $format The query format.
     */
    public function setFormat( $format ) {
        $this->format = (string)$format;
    }
    
    /**
     * When an unknown method is called, we assume a binding is being called. We first
     * check if the binding exists. If the binding exists, the binding is instantiated
     * with the $parameters.
     * @param string $name The name of the binding.
     * @param array $parameters The parameters of the binding.
     */
    public function __call( $name, $parameters ) {
        $name = strtoupper( $name );

        if( !isset( $this->abstractBindings[$name] ) ) {
            throw new \Exception("The binding '$name' does not exist.");
        }

        //get the class name from the binding list
        $className = get_class( $this->abstractBindings[$name] );
        
        //instantiate the binding and set options
        $class = new $className();
        
        //pass in the symbols
        $class->setSymbols( $this->getSymbols() );
        
        //!IMPORTANT make sure we set options AFTER symbols
        //else the Transaction may send the wrong symbols
        call_user_func_array( array( $class, 'setOptions' ), $parameters );
        
        //add to abstract bindings
        $this->abstractBindings[$name] = $class;

        return $this;
    }
    
    /**
     * Returns the query string. Converts are bindings to string and replaces their
     * bindings in the format string.
     * @return string The query string.
     */
    public function toString() {
        $searches = array();
        $replacements = array();

        foreach( $this->abstractBindings as $name=>$binding ) {
            $searches[] = '{' . strtoupper( $name ) . '}';
            $replacements[] = $binding->toString();
        }
        
        return str_replace( $searches, $replacements, $this->format );
    }
    
    /**
     * Adds a new binding to the transaction.
     *
     * @param type $hook    The hook's value. Either the name of the binding or a built-in
     *                      binding such as self::WHERE.
     * @param type $class   The FQN of the class. If not set, the method assumes $class is
     *                      a default binding (self::WHERE, self::COLUMNS, etc.) Optional.
     */
    public function addBinding( $hook, $class = null ) {
        if( $class === null ) {
            switch( $hook ) {
                case self::WHERE:
                    $class = new Binding\Where;
                    break;
                case self::COLUMNS:
                    $class = new Binding\Columns;
                    break;
                case self::SET:
                    $class = new Binding\Set;
                    break;
                case self::LIMIT:
                    $class = new Binding\Limit;
                    break;
                case self::OFFSET:
                    $class = new Binding\Offset;
                    break;
            }
        }
        
        if( $class ) {
            $this->abstractBindings[$hook] = new $class;
        }
    }
}
