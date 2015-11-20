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

namespace Attibee\Database\Transaction;

use Attibee\Database\Symbols\SymbolAwareAbstract;

/**
 * A transaction provides a configurable interface to perform commons transactions on a
 * database table. The transaction allows basic methods to select, insert, update, and
 * delete from the table.
 * 
 * Typically, the transaction would be extended and a new class created for each table in
 * the database.
 */
class TransactionCollection extends SymbolAwareAbstract {
    /**
     * The array of formats.
     */
    protected $formats = array();
    
    /**
     * Indicate the default symbol set.
     */
    protected $symbols = self::ANSI;
    
    //CRUD constants
    const INSERT = "INSERT";
    const SELECT = "SELECT";
    const UPDATE = "UPDATE";
    const DELETE = "DELETE";
    
    /**
     * Constructor sets the symbols.
     */
    public function __construct() {
        $this->setSymbols( $this->symbols );
    }
    
    /**
     * Returns the transaction object given the string name.
     * @param string $name The name of the transaction.
     * @return \Attibee\Database\Transaction\BaseTransaction The transaction object.
     */
    protected function getTransaction( $name ) {
        $transaction = null;
        
        switch( $name ) {
            case self::SELECT:
                $transaction = new Select();
                break;
            case self::INSERT:
                $transaction = new Insert();
                break;
            case self::UPDATE:
                $transaction = new Update();
                break;
            case self::DELETE:
                $transaction = new Delete();
                break;
        }
        
        return $transaction;
    }
    
    /**
     * Returns the {@link Transaction} object by name.
     * 
     * A Transaction is accessed by calling it via method. For example, to get the INSERT
     * Transaction, we can do TransactionCollection::insert() to get the {@link Insert}
     * transaction. The name are case insensitive.
     * 
     * @param string $name The name of the transaction.

     * @return Attibee\Database\Transaction\Transaction The transaction.
     * 
     * @throws \Exception If the transaction $name does not exist.
     */
    public function __call( $name, $args ) {
        $name = strtoupper( $name );
                
        //is it a default transaction name?
        if( is_array( $this->formats[$name] ) ) {
            $transaction = $this->getTransaction( $this->formats[$name]['type'] );
            $transaction->setFormat( $this->formats[$name]['format'] );
        } else {
            $transaction = $this->getTransaction( $name );
            $transaction->setFormat( $this->formats[$name] );
        }
        
        //pass symbols
        $transaction->setSymbols( $this->getSymbols() );
        
        if( $transaction == null || !isset( $this->formats[$name] ) ) {
            throw new \Exception("The transaction '$name' does not exist for this table.");
        }

        return $transaction;
    }
}
