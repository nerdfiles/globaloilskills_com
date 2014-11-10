<?php
/**
 * Fired when the plugin is uninstalled.
 *
 * @package   stripe-market
 * @author    nerdfiles <hello@nerdfiles.net>
 * @license   GPL-2.0+
 * @link      http://nerdfiles.net
 * @copyright 10-7-2014 Company Name
 */

// If uninstall, not called from WordPress, then exit
if (!defined("WP_UNINSTALL_PLUGIN")) {
	exit;
}

// TODO: Define uninstall functionality here