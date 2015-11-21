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
 * Option element used in {@link Select} elements.
 */
class Option extends Element {
    protected $name = 'option';
    protected $isShortTag = false;

    /**
     * Adds the selected attribute and sets it to false by default.
     */
    function __construct() {
        $this->addValidAttributes(array(
            'selected',
            'value',
            'label'
        ));

        $this->setAttribute( 'selected', false );
    }

    /**
     * Sets the label of the option. This is the inner text of the element.
     * @param $text The text of the label.
     */
    public function setLabel( $text ) {
        $node = new TextNode( $text );
        $this->addChild( $node );
    }

    /**
     * Handles the special case of label. The label is the inner text of the <option> tag.
     * @param $name The name of the attribute.
     * @param $value The value of the attribute.
     */
    public function setAttribute( $name, $value ) {
        if( $name == 'label' ) {
            $this->setLabel( (string)$value );
        } else {
            parent::setAttribute( $name, $value );
        }
    }
}