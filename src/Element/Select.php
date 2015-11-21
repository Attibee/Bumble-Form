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
 * A select box that includes methods to add and select options.
 */
class Select extends Element {
    protected $name = 'select';
    protected $isShortTag = false;

    function __construct() {
        $this->addValidAttribute( 'multiple' );
        $this->setAttribute( 'multiple', false );
    }

    /**
     * Adds an option given the config array.
     * @param $config an array of config
     */
    public function addOption( array $config ) {
        $opt = new Option;

        $opt->setAttributes( $config );

        $this->addChild( $opt );
    }

    /**
     * Adds a list of options given an array of option configs. See addOption() method.
     * @param $config an array of arrays of config
     */
    public function addOptions( array $options ) {
        foreach( $options as $config ) {
            $this->addOption( $config );
        }
    }

    /**
     * Handles the value attribute. If the "value" attribute is set,
     * the option with the attribute's value is then selected.
     * @param $name the name of the attribute
     * @param $value the value of the attribute
     */
    public function setAttribute( $name, $value ) {
        if( strtolower( $name ) == 'value' ) {
            $this->setSelectedOption( $value );
        } else {
            parent::setAttribute( $name, $value );
        }
    }

    /**
     * Sets the option as selected given the value attribute of the <option> tag.
     * @param $value The value of the tag to select.
     */
    public function setSelectedOption( $value ) {
        foreach( $this->children as $option ) {
            if( $option->getAttribute( 'value' ) == $value ) {
                $option->setAttribute( 'selected', true );
            }
        }
    }
}