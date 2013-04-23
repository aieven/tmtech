<?php
	namespace Cerceau\View;

	class Blitz extends \Cerceau\View\HtmlBase {
        protected function html(){
            $Template = new \Cerceau\Api\Blitz( $this->template, $this->pathTemplate, self::TEMPLATE_EXT );
            $Template->set( $this->data );
            $this->page['yield'] = $Template->parse();
            $this->page['globals'] = $this->globals;

            if( $this->pathLayout ){
                $Layout = new \Cerceau\Api\Blitz( $this->layout, $this->pathLayout, self::TEMPLATE_EXT );
                $Layout->set( $this->page );
                return $Layout->parse();
            }
            else
                return $this->page['yield'];
        }
    }
	