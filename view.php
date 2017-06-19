<?php
global $DB, $PAGE, $OUTPUT,$CFG,$USER, $COURSE;

require_once("../../config.php");
require_once($CFG->dirroot.'/course/lib.php');
require_once($CFG->dirroot.'/course/modlib.php');
require_once($CFG->libdir.'/completionlib.php');
require_once('locallib.php');


$courseId = $_GET['courseid'];
$userId = $_GET['userid'];

$params = array();
$params = array('id' => $courseId );
$course = $DB->get_record('course', $params, '*', MUST_EXIST);

// Prevent caching of this page to stop confusion when changing page after making AJAX changes
$PAGE->set_cacheable(false);
$urlparams = array('courseid' => $_GET['courseid'], 'userid' => $_GET['userid'] );
$PAGE->set_url('/view.php', $urlparams);


$PAGE->set_pagelayout('course');
$PAGE->set_heading($course->fullname);

// Get information 
$list_scorms = get_scorms($courseId);
$courseGroup = get_course_groups($courseId);
$list_scorms_enabled = get_mod_availability($courseId);
$user_list = get_report_data('64', '22');
//$userRol = get_user_rol($userId,$courseId);


echo "<pre>";
   print_r($courseGroup);
echo "</pre>";

echo "<pre>";
print_r($list_scorms_enabled);
echo "</pre>";

echo "<pre>";
print_r($list_scorms);
echo "</pre>";

echo "<pre>";
print_r($user_list);
echo "</pre>";

print $OUTPUT->header();
$PAGE->requires->js_call_amd('block_scorm_report/module', 'init');
   

	
  echo '<form id="searchform" action="search.php" method="get">';
	print(html_writer::select($courseGroup , 'group', 'group'));
   print(html_writer::select($list_scorms , 'scorm', 'scorm'));	
   echo '<input id="courseidd" type="hidden" name="courseid"  value="'. $courseId .'"> ' ;
   echo '<input type="button"  onclick="" value="Descargar Reporte"/>';
   // print(add_action_buttons(false, 'Reporte'));
print $OUTPUT->footer();
