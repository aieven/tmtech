<?php
	namespace Cerceau\I;

	interface IHtmlView extends \Cerceau\I\IView {
        /**
         * Set theme
         *
         * @abstract
         * @param string $name
         */
        public function theme( $name );

        /**
         * Set template
         *
         * @abstract
         * @param string $name
         */
        public function template( $name );

        /**
         * Set layout
         *
         * @abstract
         * @param string $name
         */
        public function layout( $name );

        /**
         * Set globals data param
         *
         * @abstract
         * @param string $name
         * @param mixed $value
         */
        public function globals( $name, $value );

        /**
         * Set layout data param
         *
         * @abstract
         * @param string $name
         * @param mixed $value
         * @return mixed
         */
        public function page( $name, $value = null );
	}
	