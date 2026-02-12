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
            $featured[] = [
                'title' => $course->fullname,
                'description' => format_text($course->summary, $course->summaryformat),
                'imgurl' => $OUTPUT->get_course_header_image_url($course),
                'url' => new moodle_url('/course/view.php', ['id' => $course->id]),
            ];
        }
        return $featured;
    }
}
