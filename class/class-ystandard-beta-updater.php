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
	 * constructor.
	 */
	public function __construct() {
		/**
		 * プラグインパスの取得
		 */
		$this->plugin_path = YSTD_BETA_UPDATER_PATH;

		add_action( 'plugins_loaded', [ $this, 'plugins_loaded' ] );
		add_action( 'after_setup_theme', [ $this, 'update_check' ] );
		add_filter( 'ys_update_check_url', [ $this, 'check_url' ] );
		add_filter( 'ys_update_check_dir', [ $this, 'add_dev_dir' ] );
		add_action( 'admin_menu', [ $this, 'add_admin_menu' ] );
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
	 * Add "/dev"
	 *
	 * @param string $dir Directory Name.
	 *
	 * @return string
	 */
	public function add_dev_dir( $dir ) {
		return $dir . '/dev';
	}

	/**
	 * チェックするURLを変更する
	 *
	 * @param string $url URL.
	 *
	 * @return string
	 */
	public function check_url( $url ) {
		// v3までの機能
		if ( false === strpos( $url, 'ystandard/v3' ) ) {
			return $url;
		}

		$url = 'https://wp-ystandard.com/download/ystandard/v3/dev/ystandard-info.json';

		$v4_update = get_option( 'ys_beta_updater_v4', '0' );
		if ( '1' === $v4_update || 1 === $v4_update || true === $v4_update ) {
			$url = 'https://wp-ystandard.com/download/ystandard/v4/ystandard-info.json';
		}

		return $url;
	}

	/**
	 * テーマのバージョンアップチェック
	 */
	public function update_check() {
		if ( ! is_admin() ) {
			return;
		}
		require_once $this->plugin_path . '/library/plugin-update-checker/plugin-update-checker.php';
		/**
		 * アップデートチェック
		 */
		$myUpdateChecker = Puc_v4_Factory::buildUpdateChecker(
			self::UPDATE_META_DATA_URL,
			$this->plugin_path . '/' . self::PLUGIN_MAIN_FILE,
			self::UPDATE_SLUG
		);
	}

	/**
	 * 設定画面追加
	 */
	public function add_admin_menu() {
		if ( ! function_exists( 'ys_get_theme_version' ) ) {
			return;
		}
		$ver = ys_get_theme_version( true );
		if ( version_compare( $ver, '4.0.0-alpha-1', '>=' ) ) {
			return;
		}
		add_options_page(
			'[ys]Beta Updater',
			'[ys]Beta Updater',
			'manage_options',
			'ys-beta-updater',
			[ $this, 'option_page' ]
		);
	}

	public function option_page() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
		}

		if ( isset( $_POST['ys_beta_updater_nonce'] ) ) {
			if ( wp_verify_nonce( $_POST['ys_beta_updater_nonce'], 'ys_beta_updater' ) ) {
				if ( isset( $_POST['ys_beta_updater_v4'] ) && $_POST['ys_beta_updater_v4'] ) {
					update_option( 'ys_beta_updater_v4', '1' );
				} else {
					delete_option( 'ys_beta_updater_v4' );
				}
				?>
				<div class="notice notice-success is-dismissible">
					<p>設定を更新しました。</p>
				</div>
				<?php
			}
		}
		$v4_update = get_option( 'ys_beta_updater_v4', '0' );
		?>
		<div class="wrap">
			<h2>yStandard v4へのバージョンアップ</h2>
			<form method="post" action="" id="v4-update">
				<?php wp_nonce_field( 'ys_beta_updater', 'ys_beta_updater_nonce' ); ?>
				<table class="form-table">
					<tbody>
					<tr>
						<th>v4へのアップデート</th>
						<td>
							<label>
								<input type="checkbox" value="1" name="ys_beta_updater_v4" <?php checked( $v4_update, '1' ); ?> >v4へのアップデートを有効化する</label>
						</td>
					</tr>
					</tbody>
				</table>
				<?php submit_button(); ?>
			</form>
		</div>
		<?php
	}
}
