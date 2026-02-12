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

namespace theme_holland\form;

use context;
use context_course;
use core_form\dynamic_form;
use moodle_url;
use theme_holland\imagehandler;

/**
 * Dynamic form for editing the course header image.
 *
 * @package    theme_holland
 * @copyright  2026 Bas Brands <bas@sonsbeekmedia.nl>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class course_image_edit extends dynamic_form {

    /**
     * Define form elements.
     */
    protected function definition() {
        $mform = $this->_form;

        $courseid = $this->optional_param('courseid', null, PARAM_INT);

        $mform->addElement('hidden', 'courseid', $courseid);
        $mform->setType('courseid', PARAM_INT);

        $mform->addElement('filepicker', 'courseimage', get_string('uploadimage', 'theme_holland'), null, [
            'maxbytes' => 0,
            'accepted_types' => ['image'],
        ]);
    }

    /**
     * Process dynamic submission.
     *
     * @return array
     */
    public function process_dynamic_submission() {
        try {
            $data = $this->get_data();
            $courseid = $data->courseid;
            $context = context_course::instance($courseid);
            $contextid = $context->id;
            $filearea = 'overviewfiles';

            // Get the file from the draft area.
            $fs = get_file_storage();
            $usercontext = \context_user::instance($data->usermodified ?? $GLOBALS['USER']->id);
            $draftfiles = $fs->get_area_files(
                $usercontext->id,
                'user',
                'draft',
                $data->courseimage,
                'id DESC',
                false
            );

            if (!empty($draftfiles)) {
                $file = reset($draftfiles);
                $binary = $file->get_content();
                $filename = $file->get_filename();
                imagehandler::store($contextid, $filearea, $filename, $binary);
            }

            return [
                'result' => true,
                'returnurl' => ($this->get_page_url_for_dynamic_submission())->out(),
            ];
        } catch (\Exception $e) {
            return [
                'result' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Get context for dynamic submission.
     *
     * @return context
     */
    protected function get_context_for_dynamic_submission(): context {
        $courseid = $this->optional_param('courseid', null, PARAM_INT);
        return context_course::instance($courseid);
    }

    /**
     * Check access for dynamic submission.
     */
    protected function check_access_for_dynamic_submission(): void {
        $context = $this->get_context_for_dynamic_submission();
        if (!has_capability('moodle/course:update', $context)) {
            throw new \Exception(get_string('accessdenied', 'admin'));
        }
    }

    /**
     * Set data for dynamic submission.
     */
    public function set_data_for_dynamic_submission(): void {
        $data = [
            'courseid' => $this->optional_param('courseid', null, PARAM_INT),
        ];
        parent::set_data((object) $data);
    }

    /**
     * Get page URL for dynamic submission.
     *
     * @return moodle_url
     */
    protected function get_page_url_for_dynamic_submission(): moodle_url {
        $courseid = $this->optional_param('courseid', null, PARAM_INT);
        return new moodle_url('/course/view.php', ['id' => $courseid]);
    }
}
