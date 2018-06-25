<?php
  /*
   * Task
   */
namespace SteelBottle\Models;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;

/*
 * Task
 */
class Task {
  /* @var UUID $id  */
  public $id;
  /* @var string $name  */
  public $name;
  /* @var Bool $completed  */
  public $completed;

  public function __construct($id="", $name="Default Task Name", $completed=0) {
    $this->id = empty($id) ? $this->_generateUUIDString() : $id;
    $this->name = $name;
    $this->completed = $completed;
  }
  private function _generateUUIDString() {
    $uuid = Uuid::uuid1();
    return $uuid->toString(); // i.e. c4a760a8-dbcf-5254-a0d9-6a4474bd1b62
  }

}
