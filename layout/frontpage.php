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

$bodyattributes = $OUTPUT->body_attributes([]);

$primary = new core\navigation\output\primary($PAGE);
$renderer = $PAGE->get_renderer('core');
$primarymenu = $primary->export_for_template($renderer);

$header = $PAGE->activityheader;
$headercontent = $header->export_for_template($renderer);

// Render the two block regions.
$fpbeforeblocks = $OUTPUT->blocks('fp-before');
$hasfpbeforeblocks = (strpos($fpbeforeblocks, 'data-block=') !== false);

$fpafterblocks = $OUTPUT->blocks('fp-after');
$hasfpafterblocks = (strpos($fpafterblocks, 'data-block=') !== false);

$addblockbutton = $OUTPUT->addblockbutton();

// Featured courses from the holland helper class.
$holland = new \theme_holland\holland();

$templatecontext = [
    'sitename' => format_string(
        $SITE->shortname,
        true,
        ['context' => context_course::instance(SITEID), 'escape' => false]
    ),
    'output' => $OUTPUT,
    'bodyattributes' => $bodyattributes,
    'primarymoremenu' => $primarymenu['moremenu'],
    'mobileprimarynav' => $primarymenu['mobileprimarynav'],
    'usermenu' => $primarymenu['user'],
    'langmenu' => $primarymenu['lang'],
    'headercontent' => $headercontent,
    'addblockbutton' => $addblockbutton,
    'fpbeforeblocks' => $fpbeforeblocks,
    'hasfpbeforeblocks' => $hasfpbeforeblocks,
    'fpafterblocks' => $fpafterblocks,
    'hasfpafterblocks' => $hasfpafterblocks,
    'holland' => [
        'hasfeaturedcourses' => $holland->hasfeaturedcourses(),
        'featuredcourses' => $holland->featuredcourses(),
    ],
];

echo $OUTPUT->render_from_template('theme_holland/frontpage', $templatecontext);
