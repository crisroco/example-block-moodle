<?php

require_once(dirname(__FILE__) . '/../../config.php');
require_once($CFG->libdir.'/adminlib.php');
require_once($CFG->libdir.'/modinfolib.php');
require_once($CFG->libdir.'/formslib.php');
require_once("$CFG->dirroot/course/lib.php");

global $DB, $CFG, $PAGE, $OUTPUT, $USER;

require_login();

//$categoria = required_param('categoria', PARAM_INT);
//$section_course = required_param('section_course', PARAM_INT);

/**
*Retorna la lista de scrom de un curso
*/ 
function get_scorms($userId,$courseId){
	global $DB;
	//$list_scorms = get_course_mods($courseId); 
	/*$sql = "SELECT cm.id, cm.visible, cm.module, sc.id as scormid, sc.name, cm.instance, cm.section, cm.availability, cm.availability
        FROM {course} c
        INNER JOIN {course_modules} cm ON cm.course = c.id
        INNER JOIN {course_sections} cs ON cm.section = cs.id AND cm.course = cs.course
        INNER JOIN {scorm} sc on c.id = sc.course
		WHERE cm.module = 18 AND c.id = " . $courseId;*/

	$sql = "SELECT cm.id, c.id as courseid, c.shortname, cm.instance as scormid, sc.name, cm.availability
        FROM {course} c  
        INNER JOIN {course_modules} cm ON cm.course = c.id 
        INNER JOIN {course_sections} cs ON cm.section = cs.id AND cm.course = cs.course
        INNER JOIN {scorm} sc ON cm.instance = sc.id            
		WHERE  cm.module = 18 AND c.id = " . $courseId ." AND cm.idnumber LIKE '%scormucic%'";

    $list_scorms = $DB->get_records_sql($sql);  
    
    $scorm_names = array();

	foreach ($list_scorms as $key => $value) {

   		$scorm_name = $value->name;	
   		$scorm_id = $value->scormid;

   		$scorm_names[$scorm_id] = $scorm_name;
   		
    }  

	return $scorm_names;
}

/**
*retorna los roles de los usuarios logeados
*/
function get_user_rol($userId,$courseId){
	global $DB;

	$sql = "SELECT u.id, r.id FROM {course} as cr
                    JOIN {context} as cn ON cn.instanceid = cr.id
                    JOIN {role_assignments} as ra ON cn.id = ra.contextid
                    JOIN {role} as r ON r.id = ra.roleid
                    JOIN {user} as u ON u.id = ra.userid
                    WHERE cr.id = ? AND u.id = ? ";

    $params = array($courseId, $userId);


	$result = $DB->get_record_sql($sql, $params);
	
	return $result;
}

/**
*retorna los grupos que existen en el curso
*/
function get_course_groups($courseId){
	global $DB;

	$sql = "SELECT g.id, c.id as courseID, g.name FROM {course} as c
                    INNER JOIN {groups} as g ON c.id = g.courseid
                    WHERE c.id = ?";

    $params = array($courseId);
    $result = $DB->get_records_sql($sql, $params);

    $group_list = array();

    foreach ($result as $key => $value) {
		$group_list[$value->id] = $value->name;
    }
    return $group_list;
}

/**
*retorna el id del grupo y los scorm para los que esta habilitado
*/
function get_mod_availability($courseId){
	global $DB;

	//lista de scorm del curso
	$sql = "SELECT cm.id, c.id as courseid, c.shortname, cm.instance as scormid, sc.name, cm.availability
        FROM {course} c  
        INNER JOIN {course_modules} cm ON cm.course = c.id 
        INNER JOIN {course_sections} cs ON cm.section = cs.id AND cm.course = cs.course
        INNER JOIN {scorm} sc ON cm.instance = sc.id            
		WHERE  cm.module = 18 AND c.id = " . $courseId ." AND cm.idnumber LIKE '%scormucic%'";

    $list_scorms = $DB->get_records_sql($sql);  
    
    
    
    $availabilities = array();

	//recorre los los scorm
	foreach ($list_scorms as $key => $value) {

   		$scorm_name = $value->name;	
   		$modulo_id = $value->id;

   		//verifica si tiene restricciones
   		$availability = ($value->availability != null) ? json_decode($value->availability)->c : 'all';

   		$temp_scorm = array();


   		if ($availability != 'all') {   			
   			//recorre las restricciones y obtiene los id de los grupos
	   		foreach ($availability as $ke => $valu) {
	   			if ($valu->type == 'group') {

	   				//array_push($temp_scorm, $valu->id);
	   				$temp_scorm[$valu->id] =  $valu->id;
	   			}
	   		}
   		}elseif ($availability == 'all') {
   		   		
   				//array_push($temp_scorm, 'all');
   				$temp_scorm['all'] =  'all';
   		}
   		
   		$availabilities[$value->scormid] = $temp_scorm;
   		
    }


    $gruposAll = array();

    foreach ($availabilities as $key => $value) {
    	foreach ($value as $ke => $valu) {
    		if(empty($gruposAll[$valu])){
    			$gruposAll[$valu] = array();
    		}

    		if(!is_null($gruposAll[$valu])){
    			array_push($gruposAll[$valu], $key);
    		}

    	}
    }

    return $gruposAll;
}

function get_report_data($groupId, $scormId){
	global $DB;

	//lista alumnos por grupo
	/*$sql = "SELECT gm.id, gm.userid
        FROM {groups_members} gm
		WHERE gm.groupid = " . $groupId;

    $user_lis = $DB->get_records_sql($sql);

    $user_list = array();
    foreach ($user_lis as $key => $value) {
    	$user_list[$key] = $value->userid;
    }

    $sql = "SELECT sct.id, sct.userid, sct.value
        FROM {scorm_scoes_track} sct
		WHERE sct.scormid = " . $scormId . " AND sct.element = 'cmi.suspend_data'";

    $user_data_scorm = $DB->get_records_sql($sql);*/

    $sql = "SELECT sct.id, gm.id as curosmod_id, gm.userid, sct.value, u.username, u.lastname, u.firstname, u.institution, u.email
        FROM {groups_members} gm
        INNER JOIN {scorm_scoes_track} sct ON sct.userid = gm.userid
        INNER JOIN {user} u ON sct.userid = u.id
		WHERE gm.groupid = " . $groupId . " AND sct.scormid = " . $scormId. " AND sct.element = 'cmi.suspend_data'";

    $user_lis = $DB->get_records_sql($sql);

    return   $user_lis;


}

