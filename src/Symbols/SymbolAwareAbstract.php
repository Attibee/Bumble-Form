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

namespace Attibee\Database\Symbols;

/**
 * Includes functions to set symbols and 
 */
class SymbolAwareAbstract {
    protected $symbols = null;
    
    const MYSQL = 'MYSQL';
    const ANSI = 'ANSI';
    
    /**
     * Returns the {@link Symbols} object. If it has not been set, it uses ANSI symbols.
     * 
     * @return The {@link Symbols} object.
     */
    public function getSymbols() {
        if( $this->symbols == null ) {
            $this->setSymbols( self::ANSI );
        }
        
        return $this->symbols;
    }
    
    /**
     * Set the symbols.
     * 
     * @param string $symbols The symbol identifier, such as self::MYSQL or self::ANSI.
     */
    public function setSymbols( $symbols ) {
        //symbols object? just set it
        if( $symbols instanceof Symbols ) {
            $this->symbols = $symbols;
            return;
        }
        
        //not a string, throw exception
        if( !is_string( $symbols ) ) {
            throw new \Exception( 'The symbol must be an identifier or instance of Symbols.' );
        }
        
        $symbols = strtoupper( $symbols );
        
        switch( $symbols ) {
            case self::MYSQL:
                $this->symbols = new Mysql;
                break;
            case self::ANSI;
            default:
                $this->symbols = new Symbols;
        }
    }
    
    /**
     * Get a symbol by its name.
     * 
     * @param string $name The name of the symbol.
     * 
     * @return string The symbol's string value.
     */
    public function getSymbol( $name ) {
        return $this->getSymbols()->$name;
    }
}
