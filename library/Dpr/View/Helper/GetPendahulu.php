<?php
class Dpr_View_Helper_GetPendahulu {

	function GetPendahulu($no_anggota) {
		$anggotaService = new Dpr_AnggotaService();
		$row = $anggotaService->getPendahulu($no_anggota);
		return $row->nama;
	}

}