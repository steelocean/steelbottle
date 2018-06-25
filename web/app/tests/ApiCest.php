<?php
class ApiCest 
{
  public function tryApi(ApiTester $I) {
    $this->simpleGetTest($I, '/');
  }
  public function tryApiSteelBottle(ApiTester $I) {
    $this->simpleGetTest($I, '/steelbottle');
  }

  private function simpleGetTest(ApiTester $I, $query_path) {
    $I->sendGET($query_path);
    $I->seeResponseCodeIs(200);
    $I->seeResponseIsJson();
  }

  public function createTodoList(ApiTester $I) {
    $uid = uniqid();
    $I->sendPOST('/steelbottle/todo/v1/lists', array('name'=>$uid, 'description' => "Description for $uid"));
    $I->seeResponseCodeIs(201);
    $I->seeResponseIsJson();

    $I->seeResponseMatchesJsonType([
				    'id' => 'string',
				    'name' => 'string',
				    'description' => 'string',
				    ]);

  }

  public function createTodoListThenGETTodoList(ApiTester $I) {
    $uid = uniqid();
    $I->sendPOST('/steelbottle/todo/v1/lists', array('name'=>$uid, 'description' => "Description for $uid"));
    $json = $I->grabResponse();
    $json_obj = json_decode($json);
    $query_path = '/steelbottle/todo/v1/list/'.$json_obj->id;
    
    $I->sendGET($query_path);
    $I->seeResponseCodeIs(200);
    $I->seeResponseIsJson();

    $I->seeResponseContainsJson(array('id' => $json_obj->id));
  }

  public function createTodoListThenGETTodoLists(ApiTester $I) {
    $uid = uniqid();
    $I->sendPOST('/steelbottle/todo/v1/lists', array('name'=>$uid, 'description' => "Description for $uid"));
    $I->seeResponseCodeIs(201);
    $I->seeResponseIsJson();


    $json = $I->grabResponse();
    $json_obj = json_decode($json);
    $search_id = $json_obj->id;

    
    $query_path = '/steelbottle/todo/v1/lists';
    $I->sendGET($query_path);
    $I->seeResponseCodeIs(200);
    $I->seeResponseIsJson();

    $I->seeResponseContains('"id"'.":".'"' .$search_id. '"');
  }

  public function createTodoListCreateTask(APITester $I) {
    $uid = uniqid();
    $I->sendPOST('/steelbottle/todo/v1/lists', array('name'=>$uid, 'description' => "Description for $uid"));
    $I->seeResponseCodeIs(201);
    $I->seeResponseIsJson();


    $json = $I->grabResponse();
    $json_obj = json_decode($json);
    $list_id = $json_obj->id;

    //  POST /list/{id}/tasks
    // {name:xxx, description}

    $query_path = '/steelbottle/todo/v1/list/' .$list_id. '/tasks';
    $I->sendPOST($query_path, array('name'=>"Task Name $uid", 'description' => "Task Description $uid"));
    $I->seeResponseCodeIs(201);
    $I->seeResponseIsJson();
  }

  public function createTodoListCreateTaskMarkCompleted(APITester $I) {
    $uid = uniqid();
    $I->sendPOST('/steelbottle/todo/v1/lists', array('name'=>$uid, 'description' => "Description for $uid"));
    $I->seeResponseCodeIs(201);
    $I->seeResponseIsJson();


    $json = $I->grabResponse();
    $json_obj = json_decode($json);
    $list_id = $json_obj->id;

    //  POST /list/{id}/tasks
    // {name:xxx, description}

    $query_path = '/steelbottle/todo/v1/list/' .$list_id. '/tasks';
    $I->sendPOST($query_path, array('name'=>"Task Name $uid", 'description' => "Task Description $uid"));
    $I->seeResponseCodeIs(201);
    $I->seeResponseIsJson();

    $json = $I->grabResponse();
    $json_obj = json_decode($json);
    $task_id = $json_obj->id;

    // POST /steelbottle/todo/v1/list/{id}/task/{taskId}/complete
    // TODO: mark task complete and check it
    
  }

}