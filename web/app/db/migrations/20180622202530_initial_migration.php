<?php


use Phinx\Migration\AbstractMigration;

class InitialMigration extends AbstractMigration
{
  /**
   * Change Method.
   *
   * Write your reversible migrations using this method.
   *
   * More information on writing migrations is available here:
   * http://docs.phinx.org/en/latest/migrations.html#the-abstractmigration-class
   *
   * The following commands can be used in this method and Phinx will
   * automatically reverse them when rolling back:
   *
   *    createTable
   *    renameTable
   *    addColumn
   *    addCustomColumn
   *    renameColumn
   *    addIndex
   *    addForeignKey
   *
   * Any other distructive changes will result in an error when trying to
   * rollback the migration.
   *
   * Remember to call "create()" or "update()" and NOT "save()" when working
   * with the Table class.
   */
  public function change()
  {

    $task_table = $this->table('task', ['id' => false]);
    $task_table->addColumn('id', 'string', ['limit' => 64])
      ->addColumn('name', 'string', ['limit' => 128])
      ->addColumn('completed', 'integer')
      ->addColumn('created', 'datetime')
      ->create();

    $todolist_table = $this->table('todolist', ['id' => false]);
    $todolist_table->addColumn('id', 'string', ['limit' => 64])
      ->addColumn('name', 'string', ['limit' => 128])
      ->addColumn('description', 'text')
      ->addColumn('created', 'datetime')
      ->create();

    $todolist_tasks_table = $this->table('todolist_tasks', ['id' => false]);
    $todolist_tasks_table->addColumn('todolist_id', 'string', ['limit' => 64])
      ->addColumn('task_id', 'string', ['limit' => 64])
      ->create();

  }
}
