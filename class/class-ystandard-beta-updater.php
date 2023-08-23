<?php
/**
 * Ystandard_Beta_Updater
 *
 * @package yStandard_Beta_Updater
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

/**
 * Class Ystandard_Beta_Updater
 */
class Ystandard_Beta_Updater {

	/**
	 * constructor.
	 */
	public function __construct() {
		add_filter( 'ys_update_check_dir', [ $this, 'add_dev_dir' ] );
	}

	/**
	 * Add "/dev"
	 *
	 * @param string $dir Directory Name.
	 *
	 * @return string
	 */
	public function add_dev_dir( $dir ) {
		return $dir . '/dev';
	}
}
