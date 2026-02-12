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
 * External API.
 *
 * @package    theme_holland
 * @copyright  2026 Bas Brands <bas@sonsbeekmedia.nl>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace theme_holland;

defined('MOODLE_INTERNAL') || die();

require_once("$CFG->libdir/externallib.php");

use core_external\external_api;
use core_external\external_function_parameters;
use core_external\external_value;
use core_external\external_single_structure;
use context;
use context_system;

/**
 * External API class.
 *
 * @package    theme_holland
 * @copyright  2026 Bas Brands <bas@sonsbeekmedia.nl>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class external extends external_api {

    /**
     * Description of the parameters suitable for the `update_image` function.
     *
     * @return external_function_parameters
     */
    public static function update_image_parameters(): external_function_parameters {
        $parameters = [
            'params' => new external_single_structure([
                'imagedata' => new external_value(PARAM_TEXT, 'Image data', VALUE_REQUIRED),
                'imagefilename' => new external_value(PARAM_TEXT, 'Image filename', VALUE_REQUIRED),
                'filearea' => new external_value(PARAM_AREA, 'File area', VALUE_REQUIRED),
                'contextid' => new external_value(PARAM_INT, 'Contextid', VALUE_REQUIRED),
            ], 'Params wrapper - just here to accommodate optional values', VALUE_REQUIRED),
        ];
        return new external_function_parameters($parameters);
    }

    /**
     * Save the image and return any warnings and the new image url.
     *
     * @param array $params parameters for saving the image
     * @return array the save image return values
     */
    public static function update_image($params): array {
        $params = self::validate_parameters(self::update_image_parameters(), ['params' => $params])['params'];

        $filearea = $params['filearea'];
        $contextid = $params['contextid'];
        $filename = $params['imagefilename'];

        $context = context::instance_by_id($contextid);
        self::validate_context($context);
        $binary = base64_decode($params['imagedata']);

        $success = false;
        $fileurl = false;
        $warning = false;

        \theme_holland\imagehandler::store($contextid, $filearea, $filename, $binary);
        $fileurl = \theme_holland\imagehandler::get_image_url($contextid, $filearea);

        if (!empty($fileurl)) {
            $success = true;
        }

        return ['success' => $success, 'fileurl' => $fileurl, 'warning' => $warning];
    }

    /**
     * Description of the return value for the `update_image` function.
     *
     * @return external_single_structure
     */
    public static function update_image_returns(): external_single_structure {
        $keys = [
            'success' => new external_value(PARAM_BOOL, 'Was the image successfully changed', VALUE_REQUIRED),
            'warning' => new external_value(PARAM_TEXT, 'Warning', VALUE_OPTIONAL),
            'fileurl' => new external_value(PARAM_URL, 'New file url', VALUE_REQUIRED),
        ];

        return new external_single_structure($keys, 'coverimage');
    }

    /**
     * Description of the parameters suitable for the `delete_image` function.
     *
     * @return external_function_parameters
     */
    public static function delete_image_parameters(): external_function_parameters {
        $parameters = [
            'params' => new external_single_structure([
                'contextid' => new external_value(PARAM_INT, 'Contextid', VALUE_REQUIRED),
                'filearea' => new external_value(PARAM_AREA, 'File area', VALUE_REQUIRED),
            ], 'Params wrapper - just here to accommodate optional values', VALUE_REQUIRED),
        ];
        return new external_function_parameters($parameters);
    }

    /**
     * Delete the image and return any warnings.
     *
     * @param array $params parameters for deleting the image
     * @return array the delete image return values
     */
    public static function delete_image($params): array {
        $params = self::validate_parameters(self::delete_image_parameters(), ['params' => $params])['params'];

        $contextid = $params['contextid'];
        $filearea = $params['filearea'];

        $context = context::instance_by_id($contextid);
        self::validate_context($context);

        $success = false;
        $warning = false;

        \theme_holland\imagehandler::delete($contextid, $filearea);
        $success = true;

        return ['success' => $success, 'warning' => $warning];
    }

    /**
     * Description of the return value for the `delete_image` function.
     *
     * @return external_single_structure
     */
    public static function delete_image_returns(): external_single_structure {
        $keys = [
            'success' => new external_value(PARAM_BOOL, 'Was the image successfully deleted', VALUE_REQUIRED),
            'warning' => new external_value(PARAM_TEXT, 'Warning', VALUE_OPTIONAL),
        ];
        return new external_single_structure($keys, 'coverimage');
    }

    /**
     * Defines the parameters for the favouritecourse method.
     *
     * @return external_function_parameters
     */
    public static function favouritecourse_parameters(): external_function_parameters {
        return new external_function_parameters(
            [
                'courseid' => new external_value(PARAM_INT, 'The course to toggle the favourite state on', VALUE_REQUIRED),
                'togglestate' => new external_value(PARAM_BOOL, 'False to check state', VALUE_REQUIRED),
            ]
        );
    }

    /**
     * Toggle the favourite state of a course.
     *
     * @param int $courseid the course to toggle the favourite state on
     * @param bool $togglestate false to check state
     * @return array
     */
    public static function favouritecourse($courseid, $togglestate): array {
        $params = self::validate_parameters(
            self::favouritecourse_parameters(),
            ['courseid' => $courseid, 'togglestate' => $togglestate]
        );
        $settings = get_config('theme_holland');
        $hasaccess = (is_siteadmin() && !empty($settings->featuredcoursesenabled));
        if (!$hasaccess) {
            return ['warnings' => null, 'isfavourite' => false, 'hasaccess' => false];
        }

        $course = get_course($params['courseid']);
        if (empty($course)) {
            throw new \moodle_exception('invalidcourseid', 'core_course');
        }
        $course->context = \context_course::instance($course->id);

        $siteadmin = get_admin();
        $usercontext = \context_user::instance($siteadmin->id);
        $ufservice = \core_favourites\service_factory::get_service_for_user_context($usercontext);

        $isfavourite = !empty($ufservice) ? $ufservice->favourite_exists(
            'core_course',
            'course',
            $course->id,
            $course->context
        ) : false;

        if ($togglestate) {
            if (!$isfavourite) {
                $ufservice->create_favourite('core_course', 'course', $course->id, $course->context);
                $isfavourite = true;
            } else {
                $ufservice->delete_favourite('core_course', 'course', $course->id, $course->context);
                $isfavourite = false;
            }
        }

        $warnings = null;

        return ['warnings' => $warnings, 'isfavourite' => $isfavourite, 'hasaccess' => $hasaccess];
    }

    /**
     * Description of the return value for the `favouritecourse` function.
     *
     * @return external_single_structure
     */
    public static function favouritecourse_returns(): external_single_structure {
        $keys = [
            'warnings' => new external_value(PARAM_TEXT, 'Warning', VALUE_OPTIONAL),
            'isfavourite' => new external_value(PARAM_BOOL, 'Is the course a favourite', VALUE_REQUIRED),
            'hasaccess' => new external_value(PARAM_BOOL, 'Does the user have access to favourite', VALUE_REQUIRED),
        ];
        return new external_single_structure($keys);
    }
}
