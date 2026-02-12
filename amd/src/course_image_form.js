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
 * Course image edit form handler.
 *
 * Opens a modal form for uploading a course header image.
 *
 * @module     theme_holland/course_image_form
 * @copyright  2026 Bas Brands <bas@sonsbeekmedia.nl>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

import ModalForm from 'core_form/modalform';
import {get_string as getString} from 'core/str';

export const init = () => {
    document.addEventListener('click', (event) => {
        const button = event.target.closest('[data-action="course-image-edit-form"]');
        if (!button) {
            return;
        }
        event.preventDefault();

        const modalForm = new ModalForm({
            modalConfig: {
                title: getString('uploadimage', 'theme_holland'),
            },
            formClass: '\\theme_holland\\form\\course_image_edit',
            args: {
                ...button.dataset,
            },
            saveButtonText: getString('save'),
        });

        modalForm.addEventListener(modalForm.events.FORM_SUBMITTED, (event) => {
            if (event.detail && event.detail.returnurl) {
                window.location.href = event.detail.returnurl;
            } else {
                window.location.reload();
            }
        });

        modalForm.show();
    });
};
