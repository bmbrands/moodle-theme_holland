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
 * Adding resize option to theme holland.
 *
 * @module     theme_holland/imageeditable
 * @copyright  2026 Bas Brands <bas@sonsbeekmedia.nl>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

import {get_string as getString} from 'core/str';
import Notification from 'core/notification';
import imageHandler from 'theme_holland/imagehandler';


/**
 * The init function for the image editable.
 *
 * Editable images are wrapped in a div with region="imageeditable" and data attributes.
 * Supported data attributes:
 *   - data-contextid: The context id (required if data-courseid is not set)
 *   - data-courseid: The course id (alternative to contextid, resolved server-side)
 *   - data-itemid: The item id, e.g. section id (optional, defaults to 0)
 *   - data-filearea: The file area (optional, defaults to 'overviewfiles')
 *
 * If the user is editing the page, a file upload button is added to the div.
 * On click the user can browse for an image and upload it.
 * The image is then displayed in the div (supports both img tags and background-image).
 */
export const init = () => {
    const editableImages = document.querySelectorAll('[data-region="imageeditable"]');
    editableImages.forEach((editableImage) => {
        // Skip if already initialized.
        if (editableImage.dataset.initialized) {
            return;
        }
        editableImage.dataset.initialized = '1';

        const contextId = parseInt(editableImage.getAttribute('data-contextid') || '0', 10);
        const courseId = parseInt(editableImage.getAttribute('data-courseid') || '0', 10);
        const itemId = parseInt(editableImage.getAttribute('data-itemid') || '0', 10);
        const fileArea = editableImage.getAttribute('data-filearea') || 'overviewfiles';

        // Find target for image display: an <img> tag or a background-image element.
        const image = editableImage.querySelector('img');
        const bgElement = editableImage.querySelector('[data-region="background-image"]');

        const fileInput = document.createElement('input');
        fileInput.setAttribute('type', 'file');
        fileInput.setAttribute('accept', 'image/*');
        fileInput.setAttribute('class', 'hidden');

        const imageUploadButton = document.createElement('button');
        imageUploadButton.setAttribute('type', 'button');
        imageUploadButton.classList.add('btn', 'btn-secondary', 'uploadbutton');

        const imageUploadIcon = document.createElement('i');
        imageUploadIcon.classList.add('fa', 'fa-upload');
        imageUploadButton.appendChild(imageUploadIcon);

        getString('uploadimage', 'theme_holland').then((string) => {
            const imageUploadText = document.createElement('span');
            imageUploadText.classList.add('sr-only');
            imageUploadText.innerHTML = string;
            imageUploadButton.appendChild(imageUploadText);
            return '';
        }).catch(Notification.exception);

        editableImage.appendChild(imageUploadButton);

        imageUploadButton.addEventListener('click', (e) => {
            e.preventDefault();
            e.stopPropagation();
            fileInput.click();
        });

        fileInput.addEventListener('change', (e) => {
            const file = e.target.files[0];
            if (!file) {
                return;
            }
            const reader = new FileReader();
            reader.addEventListener('load', () => {
                imageHandler.saveImage(contextId, fileArea, file, reader.result, itemId, courseId).then((result) => {
                    if (result.success) {
                        if (image) {
                            image.setAttribute('src', result.fileurl);
                        }
                        if (bgElement) {
                            bgElement.style.backgroundImage = `url("${result.fileurl}")`;
                        }
                    }
                    return '';
                }).catch(Notification.exception);
            });
            reader.readAsDataURL(file);
        });
    });
};
