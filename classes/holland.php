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
 * The holland class controlling various outputs.
 *
 * @package   theme_holland
 * @copyright 2026 Bas Brands <bas@sonsbeekmedia.nl>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace theme_holland;

use context_user;
use core_course\external\course_summary_exporter;
use core_favourites\service_factory;
use moodle_url;

/**
 * The holland class controlling various outputs.
 *
 * @package   theme_holland
 * @copyright 2026 Bas Brands <bas@sonsbeekmedia.nl>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class holland {

    /**
     * @var array Body classes.
     */
    public $bodyclasses = [];

    /**
     * @var bool $editor
     */
    public $editor;

    /**
     * holland constructor.
     */
    public function __construct() {
        global $PAGE;
        $this->editor = $PAGE->user_allowed_editing();
        if ($this->editor) {
            $this->bodyclasses[] = 'pageeditor';
        }
    }

    /**
     * Get the number of block columns for the side-pre region.
     *
     * @return int Number of columns (1-6).
     */
    public function numblockssidepre(): int {
        $value = get_config('theme_holland', 'numblockssidepre');
        return ($value !== false) ? (int) $value : 4;
    }

    /**
     * Get the number of block columns for the side-post region.
     *
     * @return int Number of columns (1-6).
     */
    public function numblockssidepost(): int {
        $value = get_config('theme_holland', 'numblockssidepost');
        return ($value !== false) ? (int) $value : 4;
    }

    /**
     * Check if the featured courses are enabled.
     *
     * @return bool True if the featured courses are enabled.
     */
    public function hasfeaturedcourses(): bool {
        $settings = get_config('theme_holland');
        if (empty($settings->featuredcoursesenabled)) {
            return false;
        }
        return true;
    }

    /**
     * Get the featured courses (admin-favourited courses).
     *
     * @return array The list of featured courses.
     */
    public function featuredcourses(): array {
        global $OUTPUT;
        $featured = [];
        $siteadmin = get_admin();
        $usercontext = context_user::instance($siteadmin->id);
        $ufservice = service_factory::get_service_for_user_context($usercontext);
        $favourites = $ufservice->find_favourites_by_type('core_course', 'course');

        foreach ($favourites as $favourite) {
            $course = get_course($favourite->itemid);
            $courseimage = course_summary_exporter::get_course_image($course);
            if (!$courseimage) {
                $courseimage = $OUTPUT->get_generated_image_for_id($course->id);
            }
            $coursecategory = \core_course_category::get($course->category, MUST_EXIST, true);
            $featured[] = [
                'fullname' => $course->fullname,
                'summary' => format_text($course->summary, $course->summaryformat),
                'courseimage' => $courseimage,
                'viewurl' => (new moodle_url('/course/view.php', ['id' => $course->id]))->out(false),
                'coursecategory' => $coursecategory->name,
            ];
        }
        return $featured;
    }

    /**
     * Get the theme context for use in templates
     * @return bool Context data for templates
     */
    public function hasadminbar(): bool {
        global $PAGE;
        return $PAGE->user_allowed_editing();
    }

    /**
     * Render the user menu.
     *
     */
    public function user_menu() {
        global $USER, $CFG, $OUTPUT, $PAGE, $DB;
        require_once($CFG->dirroot . '/user/lib.php');

        $opts = user_get_user_navigation_info($USER, $PAGE, ['avatarsize' => '100']);
        $opts->loginurl = get_login_url();
        if (isset($opts->metadata['userid'])) {
            $opts->user = $DB->get_record('user', ['id' => $opts->metadata['userid']], '*', MUST_EXIST);
            $courses = enrol_get_my_courses(['summary', 'summaryformat']);
            $mycourses = (object)[
                'itemtype' => 'link',
                'title' => get_string('mycourses'),
                'badge' => count($courses),
                'url' => new \moodle_url('/my/courses.php'),
            ];
            // Push the mycourses navitem to the front of the array.
            array_unshift($opts->navitems, $mycourses);

        }
        return $OUTPUT->render_from_template('theme_holland/theme/usermenu', $opts);
    }
}
