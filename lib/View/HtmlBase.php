<?php
	namespace Cerceau\View;

	abstract class HtmlBase implements \Cerceau\I\IHtmlView {
        const
            THEME_DIR = 'themes/',
            TEMPLATE_EXT = '.tpl',
            TEMPLATE_DIR = '/templates/',
            TEMPLATE_AJAX_DIR = '/templates/ajax/',
            LAYOUT_DIR = '/layouts/',

            DEFAULT_TEMPLATE = 'empty',
            DEFAULT_LAYOUT = 'index',
            DEFAULT_AJAX_LAYOUT = 'ajax',
            DEFAULT_THEME = 'default';

        protected
            $ajax,

            $theme,
            $template,
            $layout,

            $globals = array(),
            $json = array(),
            $page = array(),
            $data = array(),

            $pathTemplate = '',
            $pathLayout = '';

        public function __construct( $ajax = false ){
            $this->ajax = $ajax;
            if( $ajax )
                $this->layout = static::DEFAULT_AJAX_LAYOUT;
        }

        public function theme( $name ){
            $this->theme = $name;
        }

        public function template( $name ){
            $this->template = $name;
        }

        public function layout( $name ){
            $this->layout = $name;
        }

        public function globals( $name, $value ){
            $this->globals[$name] = $value;
        }

        public function json( $name, $value ){
            $this->json[$name] = $value;
        }

        public function page( $name, $value = null ){
            if( is_null( $value ))
                return isset( $this->page[$name] ) ? $this->page[$name] : null;

            return $this->page[$name] = $value;
        }

        public function set( $name, $value = null ){
            if( is_array( $name ))
                $this->data += $name;
            else
                $this->data[$name] = $value;
        }

        /**
         * Render with layout template or yield template only
         *
         * @param bool $layout
         * @return string
         * @throws \Exception
         * @throws \UnexpectedValueException
         */
        final public function render( $layout = true ){
            if(!$this->theme )
                $this->theme = static::DEFAULT_THEME;

            if(!file_exists( ROOT . static::THEME_DIR . $this->theme ))
                throw new \UnexpectedValueException( 'Theme '. $this->theme .' not exists' );

            if(!$this->template )
                $this->template = static::DEFAULT_TEMPLATE;

            if( $this->ajax ){
                $this->pathTemplate = ROOT . static::THEME_DIR . $this->theme . static::TEMPLATE_AJAX_DIR;
                if(!file_exists( $this->pathTemplate . $this->template . static::TEMPLATE_EXT ))
                    $this->pathTemplate = '';
            }

            if(!$this->pathTemplate ){
                $this->pathTemplate = ROOT . static::THEME_DIR . $this->theme . static::TEMPLATE_DIR;
                if(!file_exists( $this->pathTemplate . $this->template . static::TEMPLATE_EXT )){
                    $this->pathTemplate = ROOT . static::THEME_DIR . static::DEFAULT_THEME . static::TEMPLATE_DIR;
                    if(!file_exists( $this->pathTemplate . $this->template . static::TEMPLATE_EXT ))
                        throw new \UnexpectedValueException( 'Template '. $this->template .' not exists' );
                }
            }

            if( $layout ){
                if(!$this->layout )
                    $this->layout = static::DEFAULT_LAYOUT;

                $this->pathLayout = ROOT . static::THEME_DIR . $this->theme . static::LAYOUT_DIR;
                if(!file_exists( $this->pathLayout . $this->layout . static::TEMPLATE_EXT )){
                    $this->pathLayout = ROOT . static::THEME_DIR . static::DEFAULT_THEME . static::LAYOUT_DIR;
                    if(!file_exists( $this->pathLayout . $this->layout . static::TEMPLATE_EXT ))
                        $this->pathLayout = '';
                }
            }
            else
                $this->pathLayout = '';

            try {
                $html = $this->html();
            }
            catch(\Exception $e){
                throw $e;
            }

            return $html;
        }

        /**
         * @abstract
         * @return string
         */
        abstract protected function html();
    }
	