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
 * Frontpage layout for the Holland theme.
 *
 * A simplified layout without course index or block drawers.
 * Uses two inline block regions: fp-before (before main) and fp-after (after main).
 *
 * @package   theme_holland
 * @copyright 2026 Bas Brands <bas@sonsbeekmedia.nl>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$holland = new \theme_holland\holland();

$bodyattributes = $OUTPUT->body_attributes($holland->bodyclasses);

$primary = new core\navigation\output\primary($PAGE);
$renderer = $PAGE->get_renderer('core');
$primarymenu = $primary->export_for_template($renderer);

$header = $PAGE->activityheader;
$headercontent = $header->export_for_template($renderer);

// Render the two block regions using the proper API to detect content.
$sidepreblocks = $OUTPUT->blocks_for_region('side-pre');
$hassidepre = $PAGE->blocks->region_has_content('side-pre', $OUTPUT);

$sidepostblocks = $OUTPUT->blocks_for_region('side-post');
$hassidepost = $PAGE->blocks->region_has_content('side-post', $OUTPUT);

$addpreblockbutton = $OUTPUT->addblockbutton('side-pre');
$addpostblockbutton = $OUTPUT->addblockbutton('side-post');

$numblockssidepre = $holland->numblockssidepre();
$numblockssidepost = $holland->numblockssidepost();
$numblockssidepresm = min(2, $numblockssidepre);
$numblockssidepostsm = min(2, $numblockssidepost);

$templatecontext = [
    'sitename' => format_string(
        $SITE->fullname,
        true,
        ['context' => context_course::instance(SITEID), 'escape' => false]
    ),
    'output' => $OUTPUT,
    'bodyattributes' => $bodyattributes,
    'numblockssidepre' => $numblockssidepre,
    'numblockssidepost' => $numblockssidepost,
    'numblockssidepresm' => $numblockssidepresm,
    'numblockssidepostsm' => $numblockssidepostsm,
    'secondarymoremenu' => $primarymenu['moremenu'],
    'mobileprimarynav' => $primarymenu['mobileprimarynav'],
    'usermenu' => $primarymenu['user'],
    'langmenu' => $primarymenu['lang'],
    'headercontent' => $headercontent,
    'addpreblockbutton' => $addpreblockbutton,
    'addpostblockbutton' => $addpostblockbutton,
    'sidepreblocks' => $sidepreblocks,
    'hassidepre' => $hassidepre,
    'sidepostblocks' => $sidepostblocks,
    'hassidepost' => $hassidepost,
    'holland' => $holland,
    'loggedin' => isloggedin(),
    'loginurl' => new moodle_url('/login/index.php'),
];

echo $OUTPUT->render_from_template('theme_holland/theme/frontpage', $templatecontext);
