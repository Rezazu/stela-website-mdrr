<?php
class Dpr_ViaService
{

	function __construct()
	{
		$this->via = new Via();
	}

	function getAllData()
	{
		$select = $this->via->select()->where('status = 1')->order('id');
		$result = $this->via->fetchAll($select);
		return $result;
	}

	// function getAllQueryData()
	// {
	// 	$sql = 'SELECT * FROM status_tiket WHERE status = 1 ORDER BY status_tiket';

	// 	$db = Zend_Registry::get('db_stela');
	// 	$stmt = $db->query($sql);
	// 	$stmt->setFetchMode(Zend_Db::FETCH_OBJ);
	// 	$result = $stmt->fetchAll();
	// 	return $result;
	// }

	function getData($id)
	{
		$select = $this->via->select()->where('id = ?', $id);
		$result = $this->via->fetchRow($select);
		return $result;
	}

	// function getQueryData($id)
	// {
	// 	$sql = 'SELECT *
	// 			FROM status_tiket
	// 			WHERE status = 1 AND id = ?';

	// 	$db = Zend_Registry::get('db_stela');
	// 	$stmt = $db->query($sql, array($id));
	// 	$stmt->setFetchMode(Zend_Db::FETCH_OBJ);
	// 	$result = $stmt->fetch();
	// 	return $result;
	// }

	function addData($via)
	{
		$user_log = Zend_Auth::getInstance()->getIdentity()->nama;
		$tanggal_log = date('Y-m-d H:i:s');

		$params = array(
			'via' => $via,
			'user_input' => $user_log,
			'tanggal_input' => $tanggal_log,
			'user_update' => $user_log,
			'tanggal_update' => $tanggal_log
		);

		return $this->via->insert($params);
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

	function editData($id, $via)
	{
		$user_log = Zend_Auth::getInstance()->getIdentity()->nama;
		$tanggal_log = date('Y-m-d H:i:s');

		$params = array(
			'via' => $via,
			'user_update' => $user_log,
			'tanggal_update' => $tanggal_log
		);

		$where = $this->via->getAdapter()->quoteInto('id = ?', $id);
		if (!$this->via->update($params, $where)) throw new Exception('Update failed,id not found');
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
		$where = $this->via->getAdapter()->quoteInto('id = ?', $id);
		$result = $this->via->delete($where);
		if (!$result) throw new Exception('Delete failed,id not found');
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

		$where = $this->via->getAdapter()->quoteInto('id = ?', $id);
		$result = $this->via->update($params, $where);
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

    public function getListVia()
    {
        $via = $this->getAllData();
        foreach ($via as $v)
        {
            $listVia[$v['id']] = [
                'id' => $v['id'],
                'via' => $v['via']
            ];
        }
        return $listVia;
    }
}
