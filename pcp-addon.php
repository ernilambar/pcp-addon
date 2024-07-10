<?php
      /**
      * Plugin Name: PCP Addon
      * Plugin URI: https://example.com
      * Description: Plugin Check addon.
      * Version: 0.1.0
      * Author: WordPress Performance Team
      * Author URI: https://make.wordpress.org/performance/
      * License: GPL-2.0+
      * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
      */

      use WordPress\Plugin_Check\Checker\Check_Result;
      use WordPress\Plugin_Check\Checker\Checks\Abstract_File_Check;
      use WordPress\Plugin_Check\Checker\Checks\Abstract_PHP_CodeSniffer_Check;
      use WordPress\Plugin_Check\Traits\Amend_Check_Result;
      use WordPress\Plugin_Check\Traits\Stable_Check;

      if ( ! class_exists( WordPress\Plugin_Check\Plugin_Main::class, false ) ) {
        require_once WP_PLUGIN_DIR . '/plugin-check/vendor/autoload.php';
      }

      class Prohibited_Text_Check extends Abstract_File_Check {

        use Amend_Check_Result;
        use Stable_Check;

        public function get_categories() {
          return array( 'general' );
        }

        protected function check_files( Check_Result $result, array $files ) {
          $php_files = self::filter_files_by_extension( $files, 'php' );
          $file      = self::file_preg_match( '#I\sam\sbad#', $php_files );
          if ( $file ) {
            $this->add_result_error_for_file(
              $result,
              __( 'Prohibited text found.', 'pcp-addon' ),
              'prohibited_text_detected',
              $file,
              0,
              0,
              8
            );
          }
        }
      }

      class PostsPerPage_Check extends Abstract_PHP_CodeSniffer_Check {

        use Stable_Check;

        public function get_categories() {
          return array( 'general' );
        }

        protected function get_args() {
          return array(
            'extensions' => 'php',
            'standard'   => plugin_dir_path( __FILE__ ) . 'postsperpage.xml',
          );
        }
      }

      add_filter(
        'wp_plugin_check_checks',
        function ( array $checks ) {
          return array_merge(
            $checks,
            array(
              'prohibited_text' => new Prohibited_Text_Check(),
              'postsperpage'    => new PostsPerPage_Check(),
            )
          );
        }
      );
