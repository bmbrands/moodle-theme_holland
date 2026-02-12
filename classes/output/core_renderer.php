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
 * Renderers to align Moodle's HTML with that expected by Bootstrap
 *
 * @package    theme_holland
 * @copyright  2026 Bas Brands <bas@sonsbeekmedia.nl>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace theme_holland\output;

use html_writer;
use moodle_url;
use core_course_list_element;
use theme_config;

/**
 * Theme renderer
 *
 * @package    theme_holland
 * @copyright  2026 Bas Brands <bas@sonsbeekmedia.nl>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class core_renderer extends \theme_boost\output\core_renderer {

    /**
     * Wrapper for header elements.
     *
     * @return string HTML to display the main header.
     */
    public function full_header(): string {
        $noheaderpages = ['frontpage', 'course'];
        if (in_array($this->page->pagelayout, $noheaderpages)) {
            if ($this->page->pagelayout == 'course') {
                return $this->course_header();
            }
            return '';
        }
        return parent::full_header();
    }

    /**
     * Returns course-specific information to be output on any course page in the header area
     * (for the current course)
     *
     * @return string
     */
    public function course_header() {
        global $COURSE;
        $content = parent::course_header();
        $header = new \stdClass();

        if ($this->page->pagelayout == 'course') {
            $header->pagename = format_string($COURSE->fullname);
            $header->courseimage = $this->get_course_header_image_url($COURSE);
            $header->navbar = $this->navbar();
            $header->contextid = $this->page->context->id;
            $header->courseid = $COURSE->id;
            $header->coursesummary = format_text($COURSE->summary, $COURSE->summaryformat);
            $header->headeractions = $this->page->get_header_actions();
            $header->canedit = has_capability('moodle/course:update', $this->page->context);
        }
        $content .= $this->render_from_template('theme_holland/theme/course_header', $header);
        return $content;
    }

    /**
     * Return the url for course header image.
     *
     * @param  Object $course  - optional course, otherwise, this course.
     * @return string header image url.
     */
    public function get_course_header_image_url($course = false): string {
        global $CFG, $COURSE;

        if (!$course) {
            $course = $COURSE;
        }

        $course = new core_course_list_element($course);
        $courseimage = '';
        foreach ($course->get_course_overviewfiles() as $file) {
            $courseimage = moodle_url::make_pluginfile_url(
                $file->get_contextid(),
                $file->get_component(),
                $file->get_filearea(),
                '',
                $file->get_filepath(),
                $file->get_filename()
            );
        }

        if (empty($courseimage)) {
            $courseimage = $this->get_generated_image_for_id($course->id);
        }

        return $courseimage;
    }

    /**
     * Add 'holland' class to body for design system styling
     *
     * @param array $additionalclasses An array of additional classes to add
     * @return string Space-separated string of CSS classes for the body tag
     */
    public function body_css_classes(array $additionalclasses = []): string {
        $additionalclasses[] = 'holland';
        return parent::body_css_classes($additionalclasses);
    }

    /**
     * Get the list of available Google Fonts.
     *
     * @return array Font name => Google Fonts URL parameter
     */
    public static function get_google_fonts(): array {
        return [
            'Space Grotesk' => 'Space+Grotesk:wght@400;500;600;700',
            'Work Sans'     => 'Work+Sans:wght@400;500;600;700',
            'Inter'         => 'Inter:wght@400;500;600;700',
            'DM Sans'       => 'DM+Sans:wght@400;500;600;700',
            'Outfit'        => 'Outfit:wght@400;500;600;700',
            'Plus Jakarta Sans' => 'Plus+Jakarta+Sans:wght@400;500;600;700',
        ];
    }

    /**
     * Output a Google Fonts link tag based on the theme setting.
     *
     * @return string HTML link element for the selected Google Font
     */
    public function fontcss(): string {
        $fontname = get_config('theme_holland', 'googlefont');
        if (empty($fontname)) {
            $fontname = 'Space Grotesk';
        }

        $fonts = self::get_google_fonts();
        if (!isset($fonts[$fontname])) {
            $fontname = 'Space Grotesk';
        }

        $param = $fonts[$fontname];
        $url = 'https://fonts.googleapis.com/css2?family=' . $param . '&display=swap';

        $output = '<link rel="preconnect" href="https://fonts.googleapis.com">';
        $output .= '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>';
        $output .= '<link rel="stylesheet" href="' . $url . '">';

        // Inject a CSS variable override so holland.scss picks up the selected font.
        $output .= '<style>:root { --ho-font-modern: \'' . $fontname . '\', \'Helvetica Neue\', sans-serif; }</style>';

        return $output;
    }

    /**
     * Output dev CSS for theme designer mode
     *
     * @return string CSS link tag for dev server or empty string
     */
    public function devcss(): string {
        global $CFG;

        if (empty($CFG->themedesignermode)) {
            return '';
        }

        // Check if Node.js dev server is running on port 3001
        $devserver_url = 'http://localhost:3001/css/compiled.css';

        // Add timestamp to force reload
        $timestamp = time();
        $css_url = $devserver_url . '?t=' . $timestamp;

        // Configure the dev server with wwwroot on first load
        $this->configure_dev_server();

        $output = '<link rel="stylesheet" type="text/css" href="' . $css_url . '" id="theme-dev-css">';

        // Add live reload script for automatic CSS updates
        $output .= $this->get_live_reload_script();

        return $output;
    }

    /**
     * Configure the development server with Moodle settings
     *
     * @return void
     */
    private function configure_dev_server(): void {
        static $configured = false;

        if ($configured) {
            return;
        }

        // Trigger the dev server to refresh its configuration from config.php
        $ch = curl_init('http://localhost:3001/config');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, '{}');
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 2);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 1);

        curl_exec($ch);
        curl_close($ch);

        $configured = true;
    }

    /**
     * Get live reload script for automatic CSS updates
     *
     * @return string JavaScript code for live reload
     */
    private function get_live_reload_script(): string {
        return '
        <script>
        (function() {
            if (!window.hollandDevMode) {
                window.hollandDevMode = true;

                // Connect to the dev server for live reload
                const eventSource = new EventSource("http://localhost:3001/events");

                eventSource.onopen = function() {
                    console.log("[Holland Theme] 🔗 Connected to dev server");
                };

                eventSource.onmessage = function(event) {
                    try {
                        const data = JSON.parse(event.data);

                        if (data.type === "reload" || data.type === "connected") {
                            console.log("[Holland Theme] 🔄 Reloading CSS...");

                            // Find the dev CSS link
                            const devCssLink = document.getElementById("theme-dev-css");
                            if (devCssLink) {
                                // Create new link with updated timestamp
                                const newLink = devCssLink.cloneNode();
                                newLink.href = "http://localhost:3001/css/compiled.css?t=" + Date.now();

                                // Replace old link with new one
                                newLink.onload = function() {
                                    devCssLink.remove();
                                    console.log("[Holland Theme] ✅ CSS updated");
                                };

                                devCssLink.parentNode.insertBefore(newLink, devCssLink.nextSibling);
                            }
                        }
                    } catch (e) {
                        console.error("[Holland Theme] Error processing SSE message:", e);
                    }
                };

                eventSource.onerror = function(error) {
                    console.warn("[Holland Theme] ⚠️ Dev server connection lost. Attempting to reconnect...");
                    // The EventSource will automatically attempt to reconnect
                };

                // Cleanup on page unload
                window.addEventListener("beforeunload", function() {
                    eventSource.close();
                });
            }
        })();
        </script>';
    }
}
