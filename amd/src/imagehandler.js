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
 * Image handler for the theme.
 *
 * @module     theme_holland/imagehandler
 * @copyright  2026 Bas Brands <bas@sonsbeekmedia.nl>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

import Ajax from 'core/ajax';
import Notification from 'core/notification';

const saveImage = (contextid, filearea, file, data) => {
    let ajaxParams = {
        imagefilename: file.name,
        imagedata: data.split('base64,')[1],
        filearea: filearea,
        contextid: contextid,
    };

    return store({params: ajaxParams});
};


/**
 * Store an image from the image handler
 * @param {Object} args The request arguments
 * @return {Promise} Resolved with an array file the stored file url.
 */
const store = args => {
    const request = {
        methodname: 'theme_holland_update_image',
        args: args
    };

    let promise = Ajax.call([request])[0]
        .fail(Notification.exception);

    return promise;
};

/**
 * Get an image from the image handler
 * @param {Object} args The request arguments
 * @return {Promise} Resolved with an file url.
 */
const getImage = args => {
    const request = {
        methodname: 'theme_holland_get_image',
        args: args
    };

    let promise = Ajax.call([request])[0]
        .fail(Notification.exception);

    return promise;
};

export default {
    saveImage: saveImage,
    getImage: getImage
};
