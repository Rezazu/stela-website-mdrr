<?php

class TodoListUpdateTodoListRequest
{
    private $id;
    private $todo_list;
    private $user_update;
	/**
	 * @return mixed
	 */
	function getId() {
		return $this->id;
	}
	
	/**
	 * @param mixed $id 
	 * @return TodoListUpdateTodoListRequest
	 */
	function setId($id){
		$this->id = $id;
	}
	
	/**
	 * @return mixed
	 */
	function getTodoList() {
		return $this->todo_list;
	}
	
	/**
	 * @param mixed $todo_list 
	 * @return TodoListUpdateTodoListRequest
	 */
	function setTodoList($todo_list){
		$this->todo_list = $todo_list;
	}
	
	/**
	 * @return mixed
	 */
	function getUserUpdate() {
		return $this->user_update;
	}
	
	/**
	 * @param mixed $user_update 
	 * @return TodoListUpdateTodoListRequest
	 */
	function setUserUpdate($user_update){
		$this->user_update = $user_update;
	}
}