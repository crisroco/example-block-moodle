<?php
class block_scorm_report extends block_base {
	function init() {
    	$this->title = get_string('pluginname', 'block_scorm_report');
	}

	function has_config() {
		return true;
	}

	function get_content() {
		global $OUTPUT, $USER , $DB , $COURSE;
      
	    $courseid = $this->page->course->id;
	    $userid = $USER->id;

	    if ($this->content !== null) {
	      return $this->content;
	    }

	    if (empty($this->instance)) {
	      $this->content = '';
	      return $this->content;
	    }

	    $this->content = new stdClass();
	    $this->content->text = ''; //translate this

	    $url = new moodle_url('/blocks/scorm_report/view.php', array('courseid' =>$courseid, 'userid' => $userid));

	    $text = 'Generar Reporte de Scorm'; //Translate this
	    //$this->content->text = '<div>Este es un curso hijo</div>';
	    $this->content->text .= html_writer::link($url,$text,array('class'=>'btn btn-danger' , 'target' => '_blank'));
	}

}