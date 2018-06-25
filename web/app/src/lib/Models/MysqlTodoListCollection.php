<?php

namespace SteelBottle\Models;

use SteelBottle\Models\TodoList;

/*
 * TodoListCollection
 */
class MysqlTodoListCollection implements TodoListCollection
{
  private $_db;
  private $_data;
  
  public function __construct($db) {
    $this->_db = $db;
  }

  public function current() : TodoList {
    return parent::current();
  }

  public function offsetGet($offset) : TodoList {
    return parent::offsetGet($offset);
  }

  public function getTodoLists($searchString="", $limit=0, $skip=0) {
    $this->load($searchString="", $limit=0, $skip=0);
    return $this->_data;
  }

  public function clear() {
    $this->_data = [];
  }
  
  public function load($searchString="", $limit=0, $skip=0) {
    $pdo = $this->_db;
    $this->clear();

    $sql = 'SELECT * FROM todolist';
    if (!empty($searchString)) {
      $sql .= " WHERE description LIKE ?";
    }
    if (!empty($limit)) {
      $sql .= " LIMIT ?";
    }
    if (!empty($skip)) {
      $sql .= " OFFSET ?";
    }
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$searchString, $skip, $limit]);

    while ($row = $stmt->fetch()) {
      $new_list = new TodoList($row['id'], $row['name'], $row['description'], []);
      $new_list->loadTasks($pdo);
      $this->_data[] = $new_list;
    }
  }

  public function addList(TodoList $new_list) {
    $this->_data[] = $new_list;
  }

  public function save() {
    $pdo = $this->_db;

    $sql = "INSERT INTO todolist (`id`,`name`,`description`,`completed`) VALUES (?,?,?,?)";
    $stmt = $pdo->prepare($sql);

    
    foreach ($this->_data as $list) {
      $completed = !empty($list->completed) ? 1 : 0;
      $description = empty($list->description) ? "" : $list->description;

      $stmt->execute([$list->id, $list->name, $description, $completed]);
      $list->saveTasks($pdo, $list->id);
    }
  }

  public function getTodoListById($listId) {
    $pdo = $this->_db;
    $this->clear();

    $sql = 'SELECT * FROM todolist WHERE id = ?';
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$listId]);

    $row = $stmt->fetch();

    if (empty($row)) {
      return null;
    }
    
    $todo_list = new TodoList($row['id'], $row['name'], $row['description']);
    $todo_list->loadTasks($pdo);

    
    /* echo "\nGETTODOLISTBYID:\n";   */
    /* print_r($todo_list);   */
    /* echo "\nGETTODOLISTBYID;\n";   */

    return $todo_list;
  }
}
