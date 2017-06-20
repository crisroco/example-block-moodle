<?php
global $DB, $PAGE, $OUTPUT,$CFG,$USER, $COURSE;

require_once("../../config.php");
require_once($CFG->dirroot.'/course/lib.php');
require_once($CFG->dirroot.'/course/modlib.php');
require_once($CFG->libdir.'/completionlib.php');
require_once('locallib.php');


//Get parameter
$courseid = $_POST['courseid'];
$groupid = $_POST['groupid'];

$list_scorms = get_mod_availability($courseid);
$allscorms = get_scorms($courseid);

$options='';

if ($groupid == 'todos') {
    foreach ($list_scorms['all'] as $key => $value) {
       $options .= '<option value="'.$value.'">'.$allscorms[$value].'</option>';
    }   
} else {
    foreach ($list_scorms[$groupid] as $key => $value) {
       $options .= '<option value="'.$value.'">'.$allscorms[$value].'</option>';
    }

}

print_r($options);


