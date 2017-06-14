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

$urlparams = array('courseid' => $_GET['courseid'], 'userid' => $_GET['userid'] );
$PAGE->set_url('/view.php', $urlparams);

$course = $DB->get_record('course', $params, '*', MUST_EXIST);

$PAGE->set_pagelayout('course');
$PAGE->set_heading($course->fullname);

print $OUTPUT->header();
  echo '<br><br><br><br><br><br>';
  echo 'reporte';
	$semana  = array('hola'=>'hola2');
	print(html_writer::select($semana , 'choosenumber', 'hola'));

   // print(add_action_buttons(false, 'Reporte'));
print $OUTPUT->footer();