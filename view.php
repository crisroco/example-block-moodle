<?php
global $DB, $PAGE, $OUTPUT,$CFG,$USER;

require_once("../../config.php");
require_once($CFG->dirroot.'/course/lib.php');
require_once($CFG->dirroot.'/course/modlib.php');
require_once($CFG->libdir.'/completionlib.php');

$params = array();
$params = array('id' => $_GET['courseid']);

// Prevent caching of this page to stop confusion when changing page after making AJAX changes
$PAGE->set_cacheable(false);

$course = $DB->get_record('course', $params, '*', MUST_EXIST);

$PAGE->set_pagelayout('course');
$PAGE->set_heading($course->fullname);

print $OUTPUT->header();
  echo '<br><br><br><br><br><br>';
  echo 'reporte';
print $OUTPUT->footer();