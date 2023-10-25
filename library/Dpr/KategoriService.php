<?php
class Dpr_KategoriService
{

	function __construct()
	{
		$this->kategori = new Kategori();
		$this->subKategoriService = new Dpr_SubKategoriService();
	}

	function getAllData()
	{
		$select = $this->kategori->select()->where('status = 1')->order('kategori');
		$result = $this->kategori->fetchAll($select);
		return $result;
	}

	function getAllQueryData()
	{
		$sql = 'SELECT * FROM kategori WHERE status = 1 ORDER BY kategori';

		$db = Zend_Registry::get('db_stela');
		$stmt = $db->query($sql);
		$stmt->setFetchMode(Zend_Db::FETCH_OBJ);
		$result = $stmt->fetchAll();
		return $result;
	}

	function getData($id)
	{
		$select = $this->kategori->select()->where('id = ?', $id);
		$result = $this->kategori->fetchRow($select);
		return $result;
	}

	function getQueryData($id)
	{
		$sql = 'SELECT *
				FROM kategori
				WHERE status = 1 AND id = ?';

		$db = Zend_Registry::get('db_stela');
		$stmt = $db->query($sql, array($id));
		$stmt->setFetchMode(Zend_Db::FETCH_OBJ);
		$result = $stmt->fetch();
		return $result;
	}

	function addData($kategori)
	{
		$user_log = Zend_Auth::getInstance()->getIdentity()->nama;
		$tanggal_log = date('Y-m-d H:i:s');

		$params = array(
			'kategori' => $kategori,
			'user_input' => $user_log,
			'tanggal_input' => $tanggal_log,
			'user_update' => $user_log,
			'tanggal_update' => $tanggal_log
		);

		$this->kategori->insert($params);
	}

	function addQueryData($kategori)
	{
		$user_log = Zend_Auth::getInstance()->getIdentity()->nama;
		$tanggal_log = date('Y-m-d H:i:s');

		$sql = 'INSERT INTO kategori (kategori, user_input, tanggal_input, user_update, tanggal_update) 
				VALUES (?, ?, ?, ?, ?)';

		$db = Zend_Registry::get('db_stela');
		$db->query($sql, array($kategori, $user_log, $tanggal_log, $user_log, $tanggal_log));
	}

	function editData($id, $kategori)
	{
		$user_log = Zend_Auth::getInstance()->getIdentity()->nama;
		$tanggal_log = date('Y-m-d H:i:s');

		$params = array(
			'kategori' => $kategori,
			'user_update' => $user_log,
			'tanggal_update' => $tanggal_log
		);

		$where = $this->kategori->getAdapter()->quoteInto('id = ?', $id);
		$this->kategori->update($params, $where);
	}

	function editQueryData($id, $kategori)
	{
		$user_log = Zend_Auth::getInstance()->getIdentity()->nama;
		$tanggal_log = date('Y-m-d H:i:s');

		$sql = 'UPDATE kategori SET kategori = ?, user_update = ?, tanggal_update = ? WHERE id = ?';

		$db = Zend_Registry::get('db_stela');
		$db->query($sql, array($kategori, $user_log, $tanggal_log, $id));
	}

	function deleteData($id)
	{
		$where = $this->kategori->getAdapter()->quoteInto('id = ?', $id);
		$this->kategori->delete($where);
	}

	function deleteQueryData($id)
	{
		$sql = 'DELETE FROM kategori WHERE id = ?';

		$db = Zend_Registry::get('db_stela');
		$db->query($sql, array($id));
	}

	function softDeleteData($id)
	{
		$user_log = Zend_Auth::getInstance()->getIdentity()->nama;
		$tanggal_log = date('Y-m-d H:i:s');

		$params = array(
			'status' => 9,
			'user_update' => $user_log,
			'tanggal_update' => $tanggal_log
		);

		$where = $this->kategori->getAdapter()->quoteInto('id = ?', $id);
		$this->kategori->update($params, $where);
	}

	function softDeleteQueryData($id)
	{
		$user_log = Zend_Auth::getInstance()->getIdentity()->nama;
		$tanggal_log = date('Y-m-d H:i:s');

		$sql = 'UPDATE kategori SET status = 9, user_update = ?, tanggal_update = ? WHERE id = ?';

		$db = Zend_Registry::get('db_stela');
		$db->query($sql, array($user_log, $tanggal_log, $id));
	}

	function getAllDataWithSubKategori()
	{
		$kategori = $this->getAllData()->toArray();
		$sub_kategori = $this->subKategoriService->getAllData()->toArray();
		foreach ($kategori as $i => $j) {
			$temp = [];
			foreach ($sub_kategori as $k => $l) {
				if ($l['id_kategori'] == $j['id']) {
					array_push($temp, $l);
				}
			}
			$kategori[$i]['sub_kategori'] = $temp;
		}
		return $kategori;
	}

	function getDataWithSubKategori($id)
	{
		$kategori = $this->getData($id)->toArray();
		$sub_kategori = $this->subKategoriService->getAllData()->toArray();
		$kategori['sub_kategori'] = [];
		foreach ($sub_kategori as $k => $v) {
			if ($v['id_kategori'] == $kategori['id']) {
				array_push($kategori['sub_kategori'], $v);
			}
		}
		return $kategori;
	}
}
