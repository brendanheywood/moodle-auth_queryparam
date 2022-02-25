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

function auth_queryparam_after_config() {
    global $DB;
    if (!is_enabled_auth('queryparam')) {
        return;
    }

    // If we are not logged in?
    if (isloggedin() && !isguestuser()) {
        return;
    }

    // Do we have login params?
    $username = optional_param('autologinas', '', PARAM_ALPHANUMEXT);
    $password = optional_param('password', '', PARAM_ALPHANUMEXT);

    if (empty($username) || empty($password)) {
        return;
    }

    // and we satisfy the iprange
    // then query password
    // and login!
    $user = $DB->get_record('user', ['username' => $username]);
    if (empty($user)) {
        debugging("auth_queryparam did not find user for $username", DEBUG_DEVELOPER);
        return;
    }

    if ($user->auth !== 'queryparam') {
        debugging("auth_queryparam user $username is not type 'queryparam'", DEBUG_DEVELOPER);
        return;
    }

    if (!validate_internal_user_password($user, $password)) {
        debugging("auth_queryparam password did not match $username", DEBUG_DEVELOPER);
        return;
    }

    complete_user_login($user);
}

