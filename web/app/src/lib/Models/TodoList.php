<?php
  /*
   * TodoList
   */
namespace SteelBottle\Models;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;
use SteelBottle\Models\Task;

/*
 * TodoList
 */
class TodoList {
  /* @var UUID $id  */
  public $id;
  /* @var string $name  */
  public $name;
  /* @var string $description  */
  public $description;
  /* @var \\Models\Task[] $tasks  */
  public $tasks;

  public function __construct($id="", $name="Default Name", $description="Default Description") {
    $this->id = empty($id) ? $this->_generateUUIDString() : $id;
    $this->name = $name;
    $this->description = $description;
    $this->tasks = [];
  }

  public function loadTasks($pdo) {
    if (empty($this->id)) {
      return false;
    }

    $sql  = 'SELECT * FROM task t ';
    $sql .= 'JOIN todolist_tasks tlt ON tlt.task_id = t.id ';
    $sql .= 'WHERE tlt.todolist_id = ?';

    $stmt = $pdo->prepare($sql);
    $stmt->execute([$this->id]);

    while ($row = $stmt->fetch()) {
      $new_task = new Task($row['id'], $row['name'], $row['completed']);
      $this->tasks[] = $new_task;
    }

    return true;
  }

  public function saveTasks($pdo) {
    if (empty($this->id)) {
      return false;
    }
    if (empty($this->tasks)) {
      return true;
    }

    $sql_task = "INSERT INTO task (`id`,`name`,`completed`) VALUES(?,?,?)";
    $stmt_task = $pdo->prepare($sql_task);

    $sql_todolist_task = "INSERT INTO todolist_tasks (`task_id`,`todolist_id`) VALUES(?,?)";
    $stmt_todolist_task  = $pdo->prepare($sql_todolist_task);

    foreach ($this->tasks as $task) {
      $stmt_task->execute([$task->id, $task->name, $task->completed]);
      $stmt_todolist_task->execute([$task->id, $this->id]);
    }

    return true;
  }

  public function addTask($task) {
    $this->tasks[] = $task;
  }

  public function markTaskCompleted($task_id) {
    if (empty($this->tasks)) {
      return true;
    }
    for ($i=0; $i < count($this->tasks); $i++) {
      if ($this->tasks[$i]->id == $task_id) {
	$this->tasks[$i]->completed = 1;
      }
    }
  }

  private function _generateUUIDString() {
    $uuid = Uuid::uuid1();
    return $uuid->toString(); // i.e. c4a760a8-dbcf-5254-a0d9-6a4474bd1b62
  }
}
