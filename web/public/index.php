<?php
  /**
   * Simple ToDo API
   * @version 1.0.0
   */

require_once __DIR__ . '/../app/vendor/autoload.php';

use SteelBottle\Models\MysqlTodoListCollection;
use SteelBottle\Models\TodoList;
use SteelBottle\Models\Task;

/** BEGIN CONFIG */
/**
 * Put this in a separate php file for configuration info.
 */
$config = array();
$config['displayErrorDetails'] = true;
$config['addContentLengthHeader'] = false;

$config['db']['host']   = 'mysql';
$config['db']['user']   = 'steelbottle';
$config['db']['pass']   = 'steelbottle';
$config['db']['dbname'] = 'steelbottle';

/** END CONFIG */

$app = new Slim\App(['settings' => $config]);


$container = $app->getContainer();
$container['db'] = function ($c)
  {
   $db = $c['settings']['db'];
   $pdo = new PDO('mysql:host=' . $db['host'] . ';dbname=' . $db['dbname'],
		  $db['user'], $db['pass']);
   $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
   $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
   return $pdo;
  };

/**
 * POST addList
 * Summary: creates a new list
 * Notes: Adds a list to the system
 * Output-Formats: [application/json]
 */
$app->POST('/steelbottle/todo/v1/lists', function($request, $response, $args) {
    $body = $request->getParsedBody();
    if (empty($body) || empty($body['name']) || empty($body['description'])) {
      return $response->withJson([], 400);
    }
    $id = empty($body['id']) ? "" : $body['id'];

    $todo_list = new TodoList($id, $body['name'], $body['description']);
    $todo_list->loadTasks($this->db);

    $todo_list_collection = new MysqlTodoListCollection($this->db);
    $todo_list_collection->addList($todo_list);
    $todo_list_collection->save();
    
    $new_response = $response->withJson($todo_list, 201);
    return $new_response;
  });

/**
 * GET getList
 * Summary: return the specified todo list
 * Notes: 
 * Output-Formats: [application/json]
 */
$app->GET('/steelbottle/todo/v1/list/{id}', function($request, $response, $args) {

    $todo_list_collection = new MysqlTodoListCollection($this->db);
    $todo_list = $todo_list_collection->getTodoListById($args['id']);

    $json = json_encode($todo_list);
    $response->write($json);
    return $response;
  });

/**
 * POST addTask
 * Summary: add a new task to the todo list
 * Notes: 
 * Output-Formats: [application/json]
 */
$app->POST('/steelbottle/todo/v1/list/{id}/tasks', function($request, $response, $args) {

    $todo_list_collection = new MysqlTodoListCollection($this->db);
    $todo_list = $todo_list_collection->getTodoListById($args['id']);

    if (empty($todo_list)) {
      $new_response = $response->withJson("Bad List Id -".$args['id']." did not retrieve todo list", 400);
      return $new_response;
    }

    $body = $request->getParsedBody();

    $new_task = new Task("", $body['name']);

    $todo_list->addTask($new_task);
    $todo_list->saveTasks($this->db);

    $new_response = $response->withJson($new_task, 201);
    return $new_response;

  });


/**
 * POST putTask
 * Summary: updates the completed state of a task
 * Notes: 
 * Output-Formats: [application/json]
 */
$app->POST('/steelbottle/todo/v1/list/{id}/task/{taskId}/complete', function($request, $response, $args) {
            
    $todo_list_collection = new MysqlTodoListCollection($this->db);
    $todo_list = $todo_list_collection->getTodoListById($args['id']);

    $todo_list->markTaskCompleted($args['taskId']);
    $todo_list->saveTasks($this->db);
            
    $body = $request->getParsedBody();
    $response->write('How about implementing putTask as a POST method ?');
    return $response;
  });


/**
 * GET searchLists
 * Summary: returns all of the available lists
 * Notes: Searches the todo lists that are available 
 * Output-Formats: [application/json]
 */
$app->GET('/steelbottle/todo/v1/lists', function($request, $response, $args) {
            
    $queryParams = $request->getQueryParams();
    $searchString = $queryParams['searchString'];
    $skip = $queryParams['skip'];
    $limit = $queryParams['limit'];    

    $todo_list_collection = new MysqlTodoListCollection($this->db);
    $todo_lists = $todo_list_collection->getTodoLists($searchString, $skip, $limit);
    $json = json_encode($todo_lists);
    $response->write($json);
    return $response;
  });

$app->GET('/', function($request, $response, $args) {
    $json = json_encode('Hello, World!');
    $response->write($json);
    return $response;
  });
$app->GET('/steelbottle', function($request, $response, $args) {
    $json = json_encode('Welcome To Steel Bottle Todo List!');
    $response->write($json);
    return $response;
  });



$app->run();
