<?php
      use WordPress\Plugin_Check\Checker\Checks\Abstract_PHP_CodeSniffer_Check;
      use WordPress\Plugin_Check\Traits\Stable_Check;

      class PostsPerPage_Check extends Abstract_PHP_CodeSniffer_Check {

        use Stable_Check;

        public function get_categories() {
          return array( 'new_category' );
        }

        protected function get_args() {
          return array(
            'extensions' => 'php',
            'standard'   => plugin_dir_path( __FILE__ ) . 'postsperpage.xml',
          );
        }
      }
