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
 * TextNode defines simple text. Methods are provided to set the text. It overrides much of
 * the {@link Element} functionality, since no attributes may be set and it's not a tag.
 * Since it may be set as a child, the textnode is still an {@link Element}.
 */
class TextNode extends Element {
    protected $text;

    /**
     * Sets the text optionally.
     * 
     * @param $text Sets the text of the node. Optional.
     */
    public function __construct( $text = null ) {
        if( $text ) {
            $this->setText( $text );
        }
    }

    /**
     * Sets the text.
     * 
     * @param $text the text
     */
    public function setText( $text ) {
        $this->text = $text;
    }

    /**
     * Gets the text. Alias of TextNode::getHTML().
     * 
     * @return the text
     */
    public function getText() {
        return $this->text;
    }

    /**
     * Gets the text. Alias of TextNode::getText().
     * 
     * @return the text
     */
    public function getHTML() {
        return $this->getText();
    }
}