<?php
global $DB, $PAGE, $OUTPUT,$CFG,$USER, $COURSE;

require_once("../../config.php");
require_once($CFG->dirroot.'/course/lib.php');
require_once($CFG->dirroot.'/course/modlib.php');
require_once($CFG->libdir.'/completionlib.php');
require_once('locallib.php');


$courseid = $_POST['courseid'];
$groupid = $_POST['groupid'];

$list_scorms = get_mod_availability($courseid);
$allscorms = get_scorms($courseid);

$temparrayu = array();
$options='';

foreach ($list_scorms[$groupid] as $key => $value) {
   $temparrayu[$key] = $allscorms[$value];
   $options .= '<option value="'.$key.'">'.$allscorms[$value].'</option>';
}


   

   print_r($options);

