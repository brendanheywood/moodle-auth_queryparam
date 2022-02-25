<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Authenticate using query params for internal accountsi
 *
 * @package   auth_queryparam
 * @copyright Brendan Heywood <brendan@catalyst-au.net>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();
require_once($CFG->libdir.'/authlib.php');

/**
 * Plugin for query param based auth.
 *
 * @copyright  Brendan Heywood <brendan@catalyst-au.net>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class auth_plugin_queryparam extends auth_plugin_base {

    public $defaults = array(
    );

    /**
     * Constructor.
     */
    public function __construct() {
        $this->authtype = 'queryparam';
        $this->config = (object) array_merge($this->defaults, (array) get_config('auth_queryparam') );
    }

    /**
     * Confirm the new user as registered.
     *
     * @param string $username
     * @param string $confirmsecret
     */
    public function user_confirm($username, $confirmsecret) {
        global $DB;
        $user = get_complete_user_data('username', $username);

        if (!empty($user)) {
            if ($user->auth != $this->authtype) {
                return AUTH_CONFIRM_ERROR;

            } else if ($user->secret === $confirmsecret && $user->confirmed) {
                return AUTH_CONFIRM_ALREADY;

            } else if ($user->secret === $confirmsecret) { // They have provided the secret key to get in.
                $DB->set_field('user', 'confirmed', 1, ['id' => $user->id]);
                return AUTH_CONFIRM_OK;
            }
        }
    }

}
