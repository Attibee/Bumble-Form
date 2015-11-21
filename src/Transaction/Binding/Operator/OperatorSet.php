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

use Bumble\Database\Transaction\Binding\Operator\Exception\InvalidOperatorException;

/**
 * An operator set allows the creation of a set of operators separated by AND or OR
 * statements.
 */
class OperatorSet extends Operator {
    private $validOperators = array(
        'EQUAL'              => 'Equal',
        'NOTEQUAL'           => 'NotEqual',
        'GREATERTHAN'        => 'GreaterThan',
        'GREATERTHANOREQUAL' => 'GreaterThanOrEqual',
        'LESSTHAN'           => 'LessThan',
        'LESSTHANOREQUAL'    => 'LessThanOrEqual',
        'LIKE'               => 'Like'
    );

    protected $operators = array();
    
    const OPERATOR_OR  = 'OR';
    const OPERATOR_AND = 'AND';
    
    public function setOptions( $arr = array() ) {
        if( is_array( $arr ) ) {
            //by default we just do a bunch of equal
            foreach( $arr as $key=>$value ) {
                $this->addOperator( new Equal( $key, $value ) );
            }
        } else if( $arr instanceOf OperatorSet ) {
            //copy the operators
            $this->operators = $arr->getOperators();
            
            //refresh symbols, this will set all of the operators
            //symbols to the proper symbols
            $this->setSymbols( $this->getSymbols() );
        } else if( $arr instanceOf \Closure ) {
            $w = get_class( $this );
            $w = new $w();
            $w->setSymbols( $this->getSymbols() );
            $arr( $w );
            
            $this->operators = $w->getOperators();
        }
    }
    
    /**
     * Returns the array of operators.
     * 
     * @return Array The array of operators.
     */
    public function getOperators() {
        return $this->operators;
    }
    
    /**
     * Sets the symbols of the OperatorSet and all children Operators.
     * 
     * @param String|Symbols $symbols The symbols.
     */
    public function setSymbols( $symbols ) {
        parent::setSymbols( $symbols );
        
        //update each operator's symbol
        foreach( $this->operators as $op ) {
            if( $op instanceof Operator ) {
                $op->setSymbols( $this->getSymbols() );
            }
        }
    }
    
    /**
     * Adds an operator to the set.
     * 
     * Adds an operator to the set separated by OperatorSet::AND or OperatorSet::OR. The
     * boolean operators are prepended to the operator, so the first operator's boolean is
     * ignored.
     * 
     * @param \Bumble\Database\Operator\Operator $op The operator to add.
     * @param string $boolean The type of boolean separator.
     */
    public function addOperator( Operator $op, $boolean = self::OPERATOR_AND ) {
        $type = strtoupper( $boolean );

        //set symbols
        $op->setSymbols( $this->getSymbols() );

        //never start with an AND or OR
        if( count( $this->operators ) == 0 ) {
            $this->operators[] = $op;
        } else {
            $type = $this->isValidBooleanOperator( $type ) ? $type : self::OPERATOR_AND;
            
            $this->operators[] = $type;
            $this->operators[] = $op;
        }
    }
    
    /**
     * Returns true if the string is a valid boolean operator, else false.
     * 
     * @param string $str The operator string.
     * 
     * @return boolean True if valid, else false.
     */
    private function isValidBooleanOperator( $str ) {
        return in_array(strtoupper($str), array( self::OPERATOR_AND, self::OPERATOR_OR ) );
    }
    
    /**
     * The getter is used for the OR and AND operators. If OR or AND is get, it adds the
     * operator to our operator list.
     * 
     * @param string $name The name of the operator.
     * 
     * @return \Bumble\Database\Sql\Where Returns the where statement to allow chaining.
     * 
     * @throws Operator\Exception\InvalidOperatorException Thrown if an invalid operator is provided.
     */
    public function __get( $name ) {
        //first check if it's AND or OR
        $upper = strtoupper( $name );
        
        if( $upper == self::OPERATOR_AND || $upper == self::OPERATOR_OR ) {
            if( count( $this->operators ) == 0 ) {
                throw new InvalidOperatorException("You cannot start a WHERE statement with AND or OR.");
            }
            
            $this->operators[] = $upper;
            return $this;
        }
        
        throw new InvalidOperatorException("Operator must be AND or OR; '$name' provided.");
    }
    
    public function __call( $name, $arguments ) {         
        //map name
        $upper = strtoupper( $name );
        
        //check if it exists
        if( !isset($this->validOperators[$upper] ) ) {
            throw new InvalidOperatorException( "The SQL operatior '$name' does not exist." );
        }

        $class = __NAMESPACE__ . '\\' . $this->validOperators[$upper];
        
        $operator = new $class;
        
        call_user_func_array( array( $operator, 'setOperands' ), $arguments );

        $operator->setSymbols( $this->getSymbols() );
        
        $this->operators[] = $operator;

        return $this;
    }
    
    public function toString() {
        $str = '';
        
        //no operators, we return 1=1
        if( empty( $this->operators ) ) {
            return '1=1';
        }
        
        foreach( $this->operators as $operator ) {
            if( is_string( $operator ) ) {
                $str .= $operator . ' ';
            } else if( $operator instanceof OperatorSet ) {
                $operator->setSymbols( $this->getSymbols() );
                $str .= '(' . $operator->toString()  . ')';
            } else {
                //$operator->setIdentifierQuote( $this->identifierQuote );
                //$operator->setStringQuote( $this->stringQuote );
                $str .= $operator->toString() . ' ';
            }
        }
        
        return $str;
    }
}
