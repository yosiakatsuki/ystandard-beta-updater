<?php
/**
 * Plugin Name:     yStandard Beta Updater
 * Plugin URI:      https://github.com/yosiakatsuki/ystandard-beta-updater
 * Description:     yStandard開発版アップデートプラグイン
 * Author:          yosiakatsuki
 * Author URI:      https://yosiakatsuki.net/
 * Text Domain:     ystandard-beta-updater
 * Domain Path:     /languages
 * Version:         1.2.0
 *
 * @package         yStandard_Beta_Updater
 */

/*
	Copyright (c) 2019 Yoshiaki Ogata (https://yosiakatsuki.net/)
	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License, version 2, as
	published by the Free Software Foundation.
	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.
	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

define( 'YSTD_BETA_UPDATER_PATH', untrailingslashit( plugin_dir_path( __FILE__ ) ) );

require_once YSTD_BETA_UPDATER_PATH . '/class/class-ystandard-beta-updater.php';

new Ystandard_Beta_Updater();
