<?php

include_once 'GenyWebConfig.php';

class GenyActivityReport {
	private $updates = array();
	public function __construct($id = -1){
		$this->config = new GenyWebConfig();
		$this->handle = mysql_connect($this->config->db_host,$this->config->db_user,$this->config->db_password);
		mysql_select_db("GYMActivity");
		mysql_query("SET NAMES 'utf8'");
		$this->id = -1;
		$this->checksum = '';
		$this->invoice_reference = '';
		$this->activity_id = -1;
		$this->profile_id = -1;
		$this->status_id = -1;
		if($id > -1)
			$this->loadActivityReportById($id);
	}
	public function insertNewActivityReport($id,$checksum,$invoice_reference,$activity_id,$profile_id,$status_id){
		if( is_numeric($id) && is_numeric($activity_id) && is_numeric($profile_id) && is_numeric($status_id) ){
			$query = "INSERT INTO ActivityReports VALUES($id,'".mysql_real_escape_string($checksum)."','".mysql_real_escape_string($invoice_reference)."',$activity_id,$profile_id,$status_id)";
			if( $this->config->debug )
				echo "<!-- DEBUG: GenyActivityReport MySQL query : $query -->\n";
			return mysql_query($query,$this->handle);
		}
		else
			return false;
	}
	public function getActivityReportsListWithRestrictions($restrictions){
		// $restrictions is in the form of array("project_id=1","project_status_id=2")
		$last_index = count($restrictions)-1;
		$query = "SELECT activity_report_id,activity_report_checksum,activity_report_invoice_reference,activity_id,profile_id,activity_report_status_id FROM ActivityReports";
		if(count($restrictions) > 0){
			$query .= " WHERE ";
			foreach($restrictions as $key => $value) {
				$query .= $value;
				if($key != $last_index){
					$query .= " AND ";
				}
			}
		}
		if( $this->config->debug )
			echo "<!-- DEBUG: GenyActivityReport MySQL query : $query -->\n";
		$result = mysql_query($query, $this->handle);
		$obj_list = array();
		if (mysql_num_rows($result) != 0){
			while ($row = mysql_fetch_row($result)){
				$tmp_obj = new GenyActivityReport();
				$tmp_obj->id = $row[0];
				$tmp_obj->checksum = $row[1];
				$tmp_obj->invoice_reference = $row[2];
				$tmp_obj->activity_id = $row[3];
				$tmp_obj->profile_id = $row[4];
				$tmp_obj->status_id = $row[5];
				$obj_list[] = $tmp_obj;
			}
		}
		mysql_close();
		return $obj_list;
	}
	public getActivityReportsByProfileId($id){
		if(! is_numeric($id) )
			return false;
		return $this->getActivityReportsListWithRestrictions( array("profile_id=$id") );
	}
	public getActivityReportsByActivityId($id){
		if(! is_numeric($id) )
			return false;
		return $this->getActivityReportsListWithRestrictions( array("activity_id=$id") );
	}
	public getActivityReportsByReportStatusId($id){
		if(! is_numeric($id) )
			return false;
		return $this->getActivityReportsListWithRestrictions( array("activity_report_status_id=$id") );
	}
	public getActivityReportsByChecksum($chk){
		return $this->getActivityReportsListWithRestrictions( array("activity_report_checksum=".mysql_real_escape_string($chk)) );
	}
	public function getAllActivityReports(){
		return $this->getActivityReportsListWithRestrictions( array() );
	}
	public function loadActivityReportById($id){
		if(! is_numeric($id) )
			return false;
		$activity_reports = $this->getActivityReportsListWithRestrictions(array("activity_report_id=$id"));
		$activity_report = $activity_reports[0];
		if(isset($activity_report) && $activity_report->id > -1){
			$this->id = $activity_report->id;
			$this->checksum = $activity_report->checksum;
			$this->invoice_reference = $activity_report->invoice_reference ;
			$this->activity_id = $activity_report->activity_id ;
			$this->profile_id = $activity_report->profile_id ;
			$this->status_id = $activity_report->status_id ;
		}
	}
	public function loadActivityReportByInvoiceReference($ref){
		$activity_reports = $this->getActivityReportsListWithRestrictions(array("activity_report_invoice_reference=".mysql_real_escape_string($ref)));
		$activity_report = $activity_reports[0];
		if(isset($activity_report) && $activity_report->id > -1){
			$this->id = $activity_report->id;
			$this->checksum = $activity_report->checksum;
			$this->invoice_reference = $activity_report->invoice_reference ;
			$this->activity_id = $activity_report->activity_id ;
			$this->profile_id = $activity_report->profile_id ;
			$this->status_id = $activity_report->status_id ;
		}
	}
	public function loadActivityReportByChecksum($chk){
		$activity_reports = $this->getActivityReportsListWithRestrictions(array("activity_report_checksum=".mysql_real_escape_string($chk)));
		$activity_report = $activity_reports[0];
		if(isset($activity_report) && $activity_report->id > -1){
			$this->id = $activity_report->id;
			$this->checksum = $activity_report->checksum;
			$this->invoice_reference = $activity_report->invoice_reference ;
			$this->activity_id = $activity_report->activity_id ;
			$this->profile_id = $activity_report->profile_id ;
			$this->status_id = $activity_report->status_id ;
		}
	}
	public function updateString($key,$value){
		$this->updates[] = "$key='".mysql_real_escape_string($value)."'";
		return true;
	}
	public function updateInt($key,$value){
		if( is_numeric($value) ){
			$this->updates[] = "$key=$value";
			return true;
		}
		else
			return false;
	}
	public function updateBool($key,$value){
		if( is_bool($value) ){
			$this->updates[] = "$key=$value";
			return true;
		}
		else
			return false;
	}
	public function commitUpdates(){
		$query = "UPDATE ActivityReports SET ";
		foreach($this->updates as $up) {
			$query .= "$up,";
		}
		$query = rtrim($query, ",");
		$query .= " WHERE activity_report_id=".$this->id;
		if( $this->config->debug )
			echo "<!-- DEBUG: GenyActivityReport MySQL query : $query -->\n";
		return mysql_query($query, $this->handle);
	}
}
?>