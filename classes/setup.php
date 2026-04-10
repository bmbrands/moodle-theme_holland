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

namespace theme_holland;

use core_customfield\category_controller;
use core_customfield\field_controller;
use core_customfield\handler;
use core_course\customfield\course_handler;

/**
 * Setup routines
 *
 * @package   theme_holland
 * @copyright 2026 Bas Brands <bas@sonsbeekmedia.nl>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class setup {

    /**
     * Create the course custom fields required by the Holland theme.
     *
     * Creates a hidden text field with shortname 'coursedesign' used to store
     * per-course styling variables consumed by the theme.
     *
     * @return void
     */
    public static function create_course_custom_fields(): void {
        $handler = course_handler::create();

        // Avoid creating duplicates if the field already exists.
        foreach ($handler->get_categories_with_fields() as $category) {
            foreach ($category->get_fields() as $field) {
                if ($field->get('shortname') === 'coursedesign') {
                    return;
                }
            }
        }

        // Create a dedicated category for Holland theme fields.
        $categoryid = $handler->create_category('Holland Theme');
        $category = category_controller::create($categoryid);

        // Build the field configuration: text type, hidden from everyone.
        $configdata = json_encode([
            'required'            => 0,
            'uniquevalues'        => 0,
            'locked'              => 0,
            'visibility'          => course_handler::NOTVISIBLE,
            'defaultvalue'        => '',
            'defaultvalueformat'  => FORMAT_MOODLE,
            'displaysize'         => 50,
            'maxlength'           => 0,
            'ispassword'          => 0,
            'link'                => '',
            'linktarget'          => '',
        ]);

        $fieldrecord = (object) [
            'name'              => 'Course design',
            'shortname'         => 'coursedesign',
            'description'       => 'Styling variables used by the Holland theme to style the course.',
            'descriptionformat' => FORMAT_HTML,
            'type'              => 'text',
            'sortorder'         => 0,
            'configdata'        => $configdata,
        ];

        $field = field_controller::create(0, (object) ['type' => 'text'], $category);
        $handler->save_field_configuration($field, $fieldrecord);
    }
}
