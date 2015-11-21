<?php
namespace Bumble\Form\Element;

/**
 * A <textarea> element. Includes the methods to add a {@link TextNode} element.
 */
class Textarea extends Element {
    //the text node
    private $textNode;

    /**
     * Initiates the textarea and create the {@link TextNode}.
     */
    public function __construct() {
        $this->name = 'textarea'; //name of the tag
        $this->isShortTag = false; //not a short tag!

        //create and add the text node
        $this->textNode = new TextNode();
        $this->addChild( $this->textNode );
    }

    /**
     * Adds the functionality of setting the 'value' to the textarea's inner {@link TextNode}.
     * @param $attr the attribute name
     * @param $value the attribute value
     */
    public function setAttribute( $attr, $value ) {
        //value is the text node
        //we add this for the sake of consistency amongst other form elements
        if( strtolower( $attr ) == 'value' ) {
           $this->setText( $value );
        } else {
            parent::setAttribute( $attr, $value );
        }
    }

    /**
     * Sets the textarea text.
     * @param $text the text inside the textarea
     */
    public function setText( $text ) {
        $this->textNode->setText( $text );
    }
}