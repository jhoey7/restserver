<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Model_Users extends CI_Model
{
	function GetUsers($id="") {
		$sql = "SELECT no, nim, nama, jurusan, jenjang, agama, noktp, tlahir, tgllahir
				FROM m_mahasiswa";
		if($id) {
			$sql .= " WHERE no = '".$id."'";
		}
		return $this->db->query($sql)->result_array();
	}

	function PostUser($data) {
		$status = REST_Controller::HTTP_NOT_FOUND;
		$err    = false;
		if(strlen($data["nim"]) <> 5) {
			$err = true;
			$descerr = "NIM harus 5 digit";
		} elseif(!is_numeric($data["noktp"])) {
			$err = true;
			$descerr = "No KTP Harus Numeric";
		} elseif(!is_numeric($data["tgllahir"])) {
			$err = true;
			$descerr = "Tgl Lahir Harus Numeric";
		}
		
		if($err === true){
			$result = array("STATUS"		=> FALSE,
							"DESCRIPTION"	=> $descerr
						   );
		}else{
			$sql = "SELECT no FROM m_mahasiswa WHERE no = '".$data["no"]."'";
			$result = $this->db->query($sql);
			if ($result->num_rows() > 0) {
				$this->db->where(array("no"=>$data["no"]));
				$this->db->update("m_mahasiswa",$data);
				$descerr = "Data Berhasil Diupdate.";
			} else {
				$this->db->insert("m_mahasiswa",$data);
				$descerr = "Data Berhasil Ditambahkan.";
			}

			$status = REST_Controller::HTTP_OK;
			$result = array("STATUS"		=> TRUE,
							"DESCRIPTION"	=> $descerr
						   );
		}
		
		return array($result,$status);
	}
}
?>