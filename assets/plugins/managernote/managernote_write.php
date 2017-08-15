<?php
if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
	global $modx;
    define('MODX_API_MODE', true);
    include_once("../../../index.php");
    $modx->db->connect();
    if (empty($modx->config)) {
        $modx->getSettings();
    }
	$plugin_path = $modx->config['base_path'] . "assets/plugins/managernote/";
	include($plugin_path.'lang/english.php');
	if (file_exists($plugin_path.'lang/' . $modx->config['manager_language'] . '.php')) {
		include($plugin_path.'lang/' . $modx->config['manager_language'] . '.php');
	}
    $output = '';
    if (isset($_POST['action'])) {
        $action = $modx->db->escape($_POST['action']);
        switch ($action) {
            case saveNote:
				$nowTime = time();
				$noteText = trim($_POST['noteText']);
				$managerId = $_SESSION['mgrInternalKey'];
				$output = '';
				$table = $modx->getFullTableName('manager_note');
				$modx->db->escape($noteText);
				$fields = array('note_text'  => $noteText, 'time_add' => $nowTime, 'manager' => $managerId);
				$res = $modx->db->select("note_text", $table,  "manager = $managerId");  
				if($modx->db->getRecordCount($res)) { 
					if ($modx->db->update($fields, $table, "manager = $managerId"))
					{
						$res = $modx->db->select("note_text, time_add", $table,  "manager = $managerId", '', '1');  
						if($modx->db->getRecordCount($res) >= 1) {
							$row = $modx->db->getRow($res);	
							$oldText = $row['note_text']; 	
						}
						$output = array("lastTime" => $_lang['last_time'].date("d.m.Y H:i:s", $nowTime + ($modx->config['server_offset_time'])), "managernoteContent" => $oldText);
					}
				}
				else {
					if ($modx->db->insert($fields, $table)) {
						$res = $modx->db->select("note_text, time_add", $table,  "manager = $managerId", '', '1');  
						if($modx->db->getRecordCount($res) >= 1) {
							$row = $modx->db->getRow($res);	
							$oldText = $row['note_text']; 	
						}
						$output = array("lastTime" => $_lang['last_time'].date("d.m.Y H:i:s", $nowTime + ($modx->config['server_offset_time'])), "managernoteContent" => $oldText);
					}
				}
				 
			break;
            default:
            break;
        }
        echo json_encode($output);
    }
    exit;
}
exit;
