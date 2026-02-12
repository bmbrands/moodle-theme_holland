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
 * Feature course toggle button.
 *
 * @module     theme_holland/featurecourse
 * @copyright  2026 Bas Brands <bas@sonsbeekmedia.nl>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

import Ajax from 'core/ajax';
import Notification from 'core/notification';
import Templates from 'core/templates';

class FavouriteCourse {
    constructor(courseid, rootElement) {
        this.courseid = courseid;
        this.rootElement = rootElement;
        this.isfavourite = false;
        this.hasAccess = false;
        this.waiting = false;
    }

    init() {
        if (!document.body.classList.contains('path-course-view') || !document.body.classList.contains('pagelayout-course')) {
            return;
        }
        this.courseHeader = this.rootElement.querySelector('#page-header-title');
        this.buttonContainer = document.createElement('div');
        this.buttonContainer.classList.add('d-inline-block', 'ml-4');
        // Insert the button container after the course title.
        this.courseHeader.parentNode.insertBefore(this.buttonContainer, this.courseHeader.nextSibling);
        this.render();
        this.addEventListeners();
    }

    async render() {
        this.buttonFeatured = await Templates.render('theme_holland/theme/featurebutton', {
            featured: true,
            featuring: false
        }).then((html) => {
            return html;
        }).catch(Notification.exception);
        this.buttonDefault = await Templates.render('theme_holland/theme/featurebutton', {
            featured: false,
            featuring: false
        }).then((html) => {
            return html;
        }).catch(Notification.exception);
        this.buttonWaiting = await Templates.render('theme_holland/theme/featurebutton', {
            featured: false,
            featuring: true
        }).then((html) => {
            return html;
        }).catch(Notification.exception);

        await this.favouriteCourse();
    }

    async renderFeatureButton() {
        if (!this.hasAccess) {
            return;
        }
        if (this.waiting) {
            this.buttonContainer.innerHTML = this.buttonWaiting;
        } else if (this.isfavourite) {
            this.buttonContainer.innerHTML = this.buttonFeatured;
        } else {
            this.buttonContainer.innerHTML = this.buttonDefault;
        }
    }

    async addEventListeners() {
        this.rootElement.addEventListener('click', async(event) => {
            if (event.target.closest('.btn-feature')) {
                this.favouriteCourse();
            }
        });
    }

    /**
     * Set the favourite state of a post.
     * @return {Promise}
     */
    favouriteCourse() {
        this.waiting = true;
        this.renderFeatureButton();
        const data = {
            courseid: this.courseid,
            togglestate: this.hasAccess
        };
        return Ajax.call([{
            methodname: 'theme_holland_favourite_course',
            args: data
        }])[0].done((response) => {
            this.isfavourite = response.isfavourite;
            this.hasAccess = response.hasaccess;
            this.waiting = false;
            this.renderFeatureButton();
            return '';
        }).catch(Notification.exception);
    }

}

const init = (courseid) => {
    const rootElement = document.querySelector('#page-header');
    const favouritecourse = new FavouriteCourse(courseid, rootElement);
    favouritecourse.init();
};

export default {
    init: init
};
