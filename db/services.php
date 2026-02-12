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
 * Theme Holland external functions and service definitions.
 *
 * @package    theme_holland
 * @copyright  2026 Bas Brands <bas@sonsbeekmedia.nl>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$functions = [
    'theme_holland_update_image' => [
        'classname'     => 'theme_holland\external',
        'methodname'    => 'update_image',
        'description'   => 'Update image',
        'type'          => 'write',
        'loginrequired' => true,
        'ajax'          => true,
    ],
    'theme_holland_delete_image' => [
        'classname'     => 'theme_holland\external',
        'methodname'    => 'delete_image',
        'description'   => 'Delete image',
        'type'          => 'write',
        'loginrequired' => true,
        'ajax'          => true,
    ],
    'theme_holland_favourite_course' => [
        'classname'     => 'theme_holland\external',
        'methodname'    => 'favouritecourse',
        'description'   => 'Favourite course',
        'type'          => 'write',
        'loginrequired' => true,
        'ajax'          => true,
    ],
];
