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
	 * アップデート確認用jsonURL
	 */
	const UPDATE_META_DATA_URL = 'https://wp-ystandard.com/download/ystandard/plugin/ystandard-beta-updater/ystandard-beta-updater.json';
	/**
	 * スラッグ
	 */
	const UPDATE_SLUG = 'yStandard Beta Updater';

	/**
	 * プラグインのメインファイル名
	 */
	const PLUGIN_MAIN_FILE = 'ystandard-beta-updater.php';

	/**
	 * プラグインのパス
	 *
	 * @var string
	 */
	private $plugin_path = '';

	/**
	 * プラグイン情報
	 *
	 * @var array
	 */
	private $plugin_data = array();

	/**
	 * constructor.
	 */
	public function __construct() {
		/**
		 * プラグインパスの取得
		 */
		$this->plugin_path = YSTD_BETA_UPDATER_PATH;
		/**
		 * プラグイン情報の取得
		 */
		$this->plugin_data = get_plugin_data( $this->plugin_path . self::PLUGIN_MAIN_FILE );

		add_action( 'plugins_loaded', array( $this, 'plugins_loaded' ) );
		add_action( 'after_setup_theme', array( $this, 'update_check' ) );
		add_filter( 'ys_update_check_url', array( $this, 'check_url' ) );
	}

	/**
	 * プラグインロード
	 */
	public function plugins_loaded() {
		if ( isset( $this->plugin_data['TextDomain'] ) ) {
			load_plugin_textdomain(
				'ystandard-beta-updater',
				false,
				basename( dirname( __FILE__ ) ) . '/languages/'
			);
		}
	}

	/**
	 * チェックするURLを変更する
	 *
	 * @param string $url URL.
	 *
	 * @return string
	 */
	public function check_url( $url ) {
		$url = 'https://wp-ystandard.com/download/ystandard/v3/dev/ystandard-info.json';

		return $url;
	}

	/**
	 * テーマのバージョンアップチェック
	 */
	public function update_check() {
		if ( ! is_admin() ) {
			return;
		}
		require_once $this->plugin_path . 'library/plugin-update-checker/plugin-update-checker.php';
		/**
		 * アップデートチェック
		 */
		$myUpdateChecker = Puc_v4_Factory::buildUpdateChecker(
			self::UPDATE_META_DATA_URL,
			$this->plugin_path . self::PLUGIN_MAIN_FILE,
			self::UPDATE_SLUG
		);
	}
}