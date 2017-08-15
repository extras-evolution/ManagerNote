<?php
if(!defined('MODX_BASE_PATH')){die('What are you doing? Get out of here!');}
$plugin_path = $modx->config['base_path'] . "assets/plugins/managernote/";
include($plugin_path.'lang/english.php');
if (file_exists($plugin_path.'lang/' . $modx->config['manager_language'] . '.php')) {
    include($plugin_path.'lang/' . $modx->config['manager_language'] . '.php');
}
$e = &$modx->Event;
if($e->name == 'OnManagerWelcomeHome'){
	$managerId = $_SESSION['mgrInternalKey'];
	$res = $modx->db->select("note_text, time_add", $modx->getFullTableName('manager_note'),  "manager = $managerId", '', '1');  
    if($modx->db->getRecordCount($res) >= 1) {
		$row = $modx->db->getRow($res);	
		$oldText = $row['note_text']; 
		$lastTime = '';		
		if ($row['time_add'] > 0) {
			$lastTime = $_lang['last_time'].date("d.m.Y H:i:s", $row['time_add'] + ($modx->config['server_offset_time']));  
		}
	}
	else {
		$oldText = '';
	}
	
	 $widgets['managernote_widget'] = array(
                                        'menuindex' =>'20',
                                        'id' => 'managernote_widget',
                                        'cols' => 'col-sm-12',
                                        'icon' => 'fa-sticky-note',
                                        'title' => $_lang['pluginname'],
                                        'body' => '<script>
		function saveNote() {
			var noteText = $("#managernoteContent").val();
			$("#managernoteContent").attr("disabled", "disabled");
			$("#saveManagerNoteBtn").attr("disabled", "disabled");
			$.ajax({
			  type: "POST",
			  dataType: "JSON",
			  url: "../assets/plugins/managernote/managernote_write.php",
			  data: "action=saveNote&noteText=" + noteText,
			  success: function(data){
				$(".lastTime").empty();
				$(".lastTime").html(data["lastTime"]);
				$("#managernoteContent").val(data["managernoteContent"]);
			  },
			  error: function() {
				$(".lastTime").empty();
				$(".lastTime").html("'.$_lang['error_save'].'");
			  },
			  complete: function() {
				$("#managernoteContent").removeAttr("disabled");
				$("#saveManagerNoteBtn").removeAttr("disabled");  
			  }
			});

		}
	</script>
	
              <textarea id="managernoteContent" rows="9" style="width: 100%; margin-bottom: 10px; line-height: 16px;">'.$oldText.'</textarea>
			  <a onclick="saveNote();" class="btn btn-default" style="cursor: pointer;" id="saveManagerNoteBtn"><i class="fa fa-save"></i> '.$_lang['btn_save'].'</a> <em class="lastTime">'.$lastTime.'</em> 
         ');
                $e->output(serialize($widgets));

	
}
