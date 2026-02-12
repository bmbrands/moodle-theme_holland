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
 * The configuration for Holland theme is defined here.
 *
 * @package    theme_holland
 * @copyright  2026 Bas Brands <bas@sonsbeekmedia.nl>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$THEME->name = 'holland';
$THEME->sheets = [];
$THEME->editor_sheets = [];
$THEME->usefallback = true;
$THEME->parents = ['boost'];
$THEME->scss = function ($theme) {
    return theme_holland_get_main_scss_content($theme);
};

$THEME->layouts = [
    'frontpage' => [
        'file' => 'frontpage.php',
        'regions' => ['fp-before', 'fp-after'],
        'defaultregion' => 'fp-after',
        'options' => ['nonavbar' => true],
    ],
    'course' => [
        'file' => 'course.php',
        'regions' => ['side-pre'],
        'defaultregion' => 'side-pre',
        'options' => ['langmenu' => true],
    ],
    'incourse' => [
        'file' => 'course.php',
        'regions' => ['side-pre'],
        'defaultregion' => 'side-pre',
    ],
];

$THEME->enable_dock = false;
$THEME->extrascsscallback = 'theme_holland_get_extra_scss';
$THEME->prescsscallback = 'theme_holland_get_pre_scss';
$THEME->precompiledcsscallback = 'theme_holland_get_precompiled_css';
$THEME->yuicssmodules = [];
$THEME->rendererfactory = theme_overridden_renderer_factory::class;
$THEME->requiredblocks = '';
$THEME->addblockposition = BLOCK_ADDBLOCK_POSITION_FLATNAV;
$THEME->iconsystem = \core\output\icon_system::FONTAWESOME;
$THEME->haseditswitch = true;
$THEME->usescourseindex = true;
$THEME->activityheaderconfig = [
    'notitle' => true,
];
