<?php
class Dpr_TiketDataService
{

	function __construct()
	{
		$this->tiket_data = new TiketData();
	}

	function getAllData()
	{
		$select = $this->tiket_data->select()->order('id_tiket');
		$result = $this->tiket_data->fetchAll($select);
		return $result;
	}

	function getAllQueryData()
	{
		$sql = 'SELECT * FROM tiket_data ORDER BY id_tiket';

		$db = Zend_Registry::get('db_stela');
		$stmt = $db->query($sql);
		$stmt->setFetchMode(Zend_Db::FETCH_OBJ);
		$result = $stmt->fetchAll();
		return $result;
	}

	function getData($id)
	{
		$select = $this->tiket_data->select()->where('id = ?', $id);
		$result = $this->tiket_data->fetchRow($select);
		return $result;
	}

	function getQueryData($id)
	{
		$sql = 'SELECT * FROM tiket_data WHERE status = 1 AND id = ?';

		$db = Zend_Registry::get('db_stela');
		$stmt = $db->query($sql, array($id));
		$stmt->setFetchMode(Zend_Db::FETCH_OBJ);
		$result = $stmt->fetch();
		return $result;
	}

	function addData($id_tiket,	$id_barang, $id_petugas, $jumlah_keluar, $sn, $tanggal_pemasangan, $lokasi_pemasangan)
	{
		$user_log = Zend_Auth::getInstance()->getIdentity()->pengguna;
		$tanggal_log = date('Y-m-d H:i:s');

		$params = array(
			'id_tiket' => $id_tiket,
			'id_barang' => $id_barang,
			'jumlah_keluar' => $jumlah_keluar,
			'sn' => $sn,
			'tanggal_pemasangan' => $tanggal_pemasangan,
			'lokasi_pemasangan' => $lokasi_pemasangan,
			'id_petugas' => $id_petugas,
			'user_input' => $user_log,
			'tanggal_input' => $tanggal_log,
			'user_update' => $user_log,
			'tanggal_update' => $tanggal_log,
		);

		$this->tiket_data->insert($params);
	}

	function editData($id, $id_barang, $id_petugas,	$jumlah_keluar,	$sn, $tanggal_pemasangan, $lokasi_pemasangan)
	{
		$user_log = Zend_Auth::getInstance()->getIdentity()->pengguna;
		$tanggal_log = date('Y-m-d H:i:s');

		$params = array(
			'id_barang' => $id_barang,
			'jumlah_keluar' => $jumlah_keluar,
			'sn' => $sn,
			'tanggal_pemasangan' => $tanggal_pemasangan,
			'lokasi_pemasangan' => $lokasi_pemasangan,
			'id_petugas' => $id_petugas,
			'user_update' => $user_log,
			'tanggal_update' => $tanggal_log,
		);

		$where = $this->tiket_data->getAdapter()->quoteInto('id = ?', $id);
		$this->tiket_data->update($params, $where);
	}

	function deleteData($id)
	{
		$where = $this->tiket_data->getAdapter()->quoteInto('id = ?', $id);
		$this->tiket_data->delete($where);
	}

	function deleteQueryData($id)
	{
		$sql = 'DELETE FROM tiket_data WHERE id = ?';

		$db = Zend_Registry::get('db_stela');
		$db->query($sql, array($id));
	}

	function softDeleteData($id)
	{
		$user_log = Zend_Auth::getInstance()->getIdentity()->pengguna;
		$tanggal_log = date('Y-m-d H:i:s');

		$params = array(
			'status' => 9,
			'user_update' => $user_log,
			'tanggal_update' => $tanggal_log
		);

		$where = $this->tiket_data->getAdapter()->quoteInto('id = ?', $id);
		$this->tiket_data->update($params, $where);
	}

	function softDeleteQueryData($id)
	{
		$user_log = Zend_Auth::getInstance()->getIdentity()->pengguna;
		$tanggal_log = date('Y-m-d H:i:s');

		$sql = 'UPDATE tiket_data SET status = 9, user_update = ?, tanggal_update = ? WHERE id = ?';

		$db = Zend_Registry::get('db_stela');
		$db->query($sql, array($user_log, $tanggal_log, $id));
	}
}
