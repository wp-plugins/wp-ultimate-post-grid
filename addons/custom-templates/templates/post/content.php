<?php

class WPUPG_Template_Post_Content extends WPUPG_Template_Block {

    public $editorField = 'postContent';

    public function __construct( $type = 'post-content' )
    {
        parent::__construct( $type );
    }

    public function output( $post, $args = array() )
    {
        if( !$this->output_block( $post, $args ) ) return '';

        $output = $this->before_output();
        $output .= '<span' . $this->style() . '>' . $this->cut_off( $post->post_content ) . '</span>';

        return $this->after_output( $output, $post );
    }
}