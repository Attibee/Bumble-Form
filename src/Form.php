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

namespace Bumble\Form;

/**
 * 
 */
class Form extends Element\Form {
    private $decorator;

    //holds a hash linking element name to its instance
    private $nameHash = array();

    //a list of element titles
    private $titles = array();

    /**
     * Initiates the decorator as the {@link Decorator\StandardDecorator}.
     */
    public function __construct() {
        $this->decorator = new Decorator\StandardDecorator;
    }

    /**
     * Adds a new form element given the config $config.
     * @param $config the element to add
     */
    public function add( array $config ) {
        $className = ucfirst( strtolower( $config['type'] ) );
        $class = "Bumble\\Form\\Element\\$className";

        //build the element
        $element = new $class();

        //special case of select boxes and datalist, we add the options
        if( $className == 'Select' || $className = 'Datalist' && isset( $config['options'] ) ) {
            $element->addOptions( $config['options'] );
        }

        //add attributes
        if( key_exists( 'attributes', $config ) ) {
            $element->setAttributes( $config['attributes'] );
        }
        
        $this->titles[] = isset( $config['title'] ) ? $config['title'] : null;

        if( isset( $config['attributes']['name'] ) ) {
            $this->nameHash[$config['attributes']['name']] = $element;
        }

        $this->addChild( $element );
    }

    /**
     * Set the form {@link \Bumble\Form\Decorator\Decorator}.
     * 
     * @param \Bumble\Form\Decorator\Decorator $dec The form decorator.
     */
    public function setDecorator( Decorator\Decorator $dec ) {
        $this->decorator = $dec;
    }

    /**
     * Returns the HTML string.
     * 
     * @return string The HTML string.
     */
    public function getHTML() {
        $tag = '<form';
        $q = $this->quoteType;

        //build attribute strings
        foreach( $this->attrs as $name=>$value ) {
            //if true, such as CHECKED, we just add the name
            if( $value === true )
                    $tag .= " $name";
            elseif( is_string( $value ) ) //if it's a string, we add attr="value"
                    $tag .= " {$name}={$q}{$value}{$q}";
        }

        //not a short tag, close it
        $tag .= '>';

        //add the children if they exist
        if( $this->hasChildren() ) {
            for( $i = 0; $i < count( $this->children ); $i++ ) {
                //datalists are hidden and should not be decorated
                if( $this->children[$i] instanceof Element\Datalist ) {
                    $tag .= $this->children[$i]->getHTML();
                } else {
                    $tag .= $this->decorator->element( $this->children[$i]->getHTML(), $this->titles[$i] );
                }
            }
        }

        //closing tag
        $tag .= "</form>";

        return $this->decorator->form( $tag );
    }

    /**
     * Sets the form elements values given an array of name=>value pairs.
     * 
     * @param $array the array of data to set
     */
    public function setData( array $array ) {
        foreach( $array as $name=>$value ) {
            if( !isset( $this->nameHash[$name] ) ) {
                continue;
            }

            $element = $this->nameHash[$name];

            $element->setAttribute( 'value', (string)$value );
        }
    }
}

