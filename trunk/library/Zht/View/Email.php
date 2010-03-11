<?php
/**
 * @package zfhrtool
 * @subpackage library
 */


/**
 * Вид почтовых сообщений
 *
 * @package zfhrtool
 * @subpackage library
 */
class Zht_View_Email extends Zend_View {


   public function __construct($config = array()) {
      if (array_key_exists( 'basePath', $config ) === false) {
         $basePath = APPLICATION_PATH . '/views/';
         $config['basePath'] = $basePath;
      }
      parent::__construct( $config );
   }

}
