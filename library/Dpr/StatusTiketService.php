<?php
class Dpr_StatusTiketService
{

	function __construct()
	{
		$this->status_tiket = new StatusTiket();
	}

	function getAllData()
	{
		$select = $this->status_tiket->select()->order('id');
		$result = $this->status_tiket->fetchAll($select);
		return $result;
	}

	function getAllQueryData()
	{
		$sql = 'SELECT * FROM status_tiket WHERE status = 1 ORDER BY id';

		$db = Zend_Registry::get('db_stela');
		$stmt = $db->query($sql);
		$stmt->setFetchMode(Zend_Db::FETCH_OBJ);
		$result = $stmt->fetchAll();
		return $result;
	}

	function getData($id)
	{
		$select = $this->status_tiket->select()->where('id = ?', $id);
		$result = $this->status_tiket->fetchRow($select);
		return $result;
	}

	function getQueryData($id)
	{
		$sql = 'SELECT *
				FROM status_tiket
				WHERE id = ?';

		$db = Zend_Registry::get('db_stela');
		$stmt = $db->query($sql, array($id));
		$stmt->setFetchMode(Zend_Db::FETCH_OBJ);
		$result = $stmt->fetch();
		return $result;
	}

	/**
	 * Add Data status tiket 
	 * @param $status_tiket String
	 * @return id
	 **/
	function addData($status_tiket)
	{
		$user_log = Zend_Auth::getInstance()->getIdentity()->nama;
		$tanggal_log = date('Y-m-d H:i:s');

		$params = array(
			'status_tiket' => $status_tiket,
			'user_input' => $user_log,
			'tanggal_input' => $tanggal_log,
			'user_update' => $user_log,
			'tanggal_update' => $tanggal_log
		);

		return $this->status_tiket->insert($params);
	}

	// function addQueryData($agama)
	// {
	// 	$user_log = Zend_Auth::getInstance()->getIdentity()->nama;
	// 	$tanggal_log = date('Y-m-d H:i:s');

	// 	$sql = 'INSERT INTO agama (agama, user_input, tanggal_input, user_update, tanggal_update) 
	// 			VALUES (?, ?, ?, ?, ?)';

	// 	$db = Zend_Registry::get('db_magang');
	// 	$db->query($sql, array($agama, $user_log, $tanggal_log, $user_log, $tanggal_log));
	// }

	/**
	 * @param integer $id
	 * @param string $status_tiket
	 * @return StatusTiket
	 */
	function editData($id, $status_tiket)
	{
		$user_log = Zend_Auth::getInstance()->getIdentity()->nama;
		$tanggal_log = date('Y-m-d H:i:s');

		$params = array(
			'status_tiket' => $status_tiket,
			'user_update' => $user_log,
			'tanggal_update' => $tanggal_log
		);

		$where = $this->status_tiket->getAdapter()->quoteInto('id = ?', $id);
		$this->status_tiket->update($params, $where);
		return $this->getData($id);
	}

	// function editQueryData($id, $agama)
	// {
	// 	$user_log = Zend_Auth::getInstance()->getIdentity()->nama;
	// 	$tanggal_log = date('Y-m-d H:i:s');

	// 	$sql = 'UPDATE agama SET agama = ?, user_update = ?, tanggal_update = ? WHERE id = ?';

	// 	$db = Zend_Registry::get('db_magang');
	// 	$db->query($sql, array($agama, $user_log, $tanggal_log, $id));
	// }


	function deleteData($id)
	{
		$where = $this->status_tiket->getAdapter()->quoteInto('id = ?', $id);
		$result = $this->status_tiket->delete($where);
		if (!$result) throw new Exception('Delete failed');
	}

	// function deleteQueryData($id)
	// {
	// 	$sql = 'DELETE FROM status_tiket WHERE id = ?';

	// 	$db = Zend_Registry::get('db_stela');
	// 	$db->query($sql, array($id));
	// }

	function softDeleteData($id)
	{
		$user_log = Zend_Auth::getInstance()->getIdentity()->nama;
		$tanggal_log = date('Y-m-d H:i:s');

		$params = array(
			'status' => 9,
			'user_update' => $user_log,
			'tanggal_update' => $tanggal_log
		);

		$where = $this->status_tiket->getAdapter()->quoteInto('id = ?', $id);
		$result = $this->status_tiket->update($params, $where);
		if (!$result) throw new Exception('Failed to update status,id not found');
	}

	// function softDeleteQueryData($id)
	// {
	// 	$user_log = Zend_Auth::getInstance()->getIdentity()->nama;
	// 	$tanggal_log = date('Y-m-d H:i:s');

	// 	$sql = 'UPDATE agama SET status = 9, user_update = ?, tanggal_update = ? WHERE id = ?';

	// 	$db = Zend_Registry::get('db_magang');
	// 	$db->query($sql, array($user_log, $tanggal_log, $id));
	// }

	public function getListStatus()
	{
		$status = $this->getAllQueryData();

		foreach ($status as $stat) {
			$listStatus[$stat->id] = [
				'id' => $stat->id,
				'status' => $stat->status_tiket
			];
		}

		return $listStatus;
	}
}
