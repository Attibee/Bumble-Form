<?php

/* Copyright 2015 Attibee (http://attibee.com)
 *
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

namespace Bumble\Form\Element;

/**
 * Base class for an element. Note all elements are in the format:
 * <name attr1="val1" attr2="val2" ... ></name>
 * The class will automatically output the html given this format.
 */
class Element {
    const DOUBLE_QUOTE = '"';
    const SINGLE_QUOTE = '\'';

    //name of the element, input, textarea, etc
    protected $name;

    //list of the attributes attr->value
    protected $attrs = array();

    //the type of quote to use, defaults to double quotes
    protected $quoteType = self::DOUBLE_QUOTE;

    //short tag flag marks if an end tag should be used or not
    protected $isShortTag = false;

    //an array of valid attributes that can be set
    protected $validAttributes = array(
        'id', 'name'
    );

    //list of children elements
    protected $children = array();

    /**
     * Sets an attribute given the name and value.
     * 
     * @param $attr the name of the attribute
     * @param $value the value of the attribute
     * 
     * @throws Exception\InvalidAttributeException if an invalid attribute is provided
     */
    public function setAttribute( $attr, $value ) {
        //data-* attribute or in valid attribute array
        if( strpos( $attr, 'data-' ) === 0 || in_array( $attr, $this->validAttributes ) ) {
            $this->attrs[$attr] = $value;
        } else {
            throw new Exception\InvalidAttributeException( $attr );
        }
    }

    /**
     * Sets an array of attribute given the name=>value pair.
     * 
     * @param $attrs the array of attrbutes
     */
    public function setAttributes( $attributes ) {
        foreach( $attributes as $name=>$value ) {
            $this->setAttribute( $name, $value );
        }
    }


    /**
     * Gets an attribute given the attribute name.
     * 
     * @param $name The name of the attribute.
     */
    public function getAttribute( $name ) {
        if( key_exists( $name, $this->attrs ) ) {
            return $this->attrs[$name];
        } else {
            return null;
        }
    }

    /**
     * Parses the class data and outputs the HTML.
     * 
     * @return the HTML string of the element
     */
    public function getHTML() {
        $tag = "<" . $this->name;
        $q = $this->quoteType;

        //build attribute strings
        foreach( $this->attrs as $name=>$value ) {
            //if true, such as CHECKED, we just add the name
            if( $value === true ) {
                $tag .= " $name";
            } else if( is_string( $value ) ) { //if it's a string, we add attr="value"
                $tag .= " {$name}={$q}{$value}{$q}";
            }
        }

        //short tag? close it and return
        if( $this->isShortTag ) {
            $tag .= ' />';
            
            return $tag;
        }

        //not a short tag, close it
        $tag .= '>';

        //add the children if they exist
        if( $this->hasChildren() ) {
            foreach( $this->getChildren() as $child ) {
                $tag .= $child->getHTML();
            }
        }

        //closing tag
        $tag .= "</{$this->name}>";

        return $tag;
    }

    /**
     * Sets the type of quote to use. Default quote type is double quotes.
     * @param $type Element::DOUBLE_QUOTE or Element::SINGLE_QUOTE
     */
    public function setQuote( $type ) {
        $this->quoteType = $quoteType == self::SINGLE_QUOTE ? self::SINGLE_QUOTE : self::DOUBLE_QUOTE;
    }

    /**
     * Adds a new valid attribute.
     * 
     * @param $name the name of the attribute
     */
    protected function addValidAttribute( $name ) {
        $this->validAttributes[] = $name;
    }

    /**
     * Adds array of attributes to the valid attributes.
     * 
     * @param $attrs an array of attributes
     */
    protected function addValidAttributes( $attrs ) {
        foreach( $attrs as $attr ) {
            //add if string
            if( is_string( $attr ) ) {
                $this->validAttributes[] = $attr;
            }
        }
    }

    /**
     * Returns true if the element has children, else false.
     * 
     * @return true if the element has children, else false
     */
    public function hasChildren() {
        return count( $this->children ) > 0;
    }

    /**
     * Returns an array of children elements.
     * 
     * @return an array of children
     */
    public function getChildren() {
        return $this->children;
    }

    /**
     * Adds a child to the element.
     * 
     * @param $child the child element
     */
    public function addChild( Element $child ) {
        $this->children[] = $child;
    }

    public function __toString() {
        return $this->getHTML();
    }
}