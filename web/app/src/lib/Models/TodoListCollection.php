<?php
  /*
   * TodoList
   */
namespace SteelBottle\Models;

use SteelBottle\Models\TodoList;

/*
 * TodoListCollection
 */
interface TodoListCollection
{
  public function current() : TodoList;    
}
