<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require '../vendor/autoload.php';
$app = new \Slim\App();
if (!file_exists('users.json')){
    file_put_contents('users.json', '');
}

$app->get('/', function (Request $request, Response $response) use($app){ //Получение списка User
    $file = file_get_contents('users.json');
    $data = json_decode($file, true);
    return $response->withJSON($data,200);
});

$app->get('/user/', function (Request $request, Response $response) { //Получение User по id
    $id = $request->getParsedBody()['id'];
    $file = file_get_contents('users.json');
    $data = json_decode($file, true);
    if (isset($id)) {
        foreach ($data as $user) {
            foreach ($user as $user_id) {
                if ($user_id['id'] == $id){
                    return $response->withJSON($user_id,200);
                }
            }
        }
        return $response->withJSON('User not found.',200);
    }
    return $response->withJSON(['status'=>'Error, see documentation'],200);
});

$app->post('/user/', function (Request $request, Response $response){  //Добавление User
    $name = $request->getParsedBody()['name'];
    $id = 1;
    $file = file_get_contents('users.json');
    $data = json_decode($file, true);
    if (isset($name)) {
        if (!$data['user'][0]) {
            $data['user'][0]['id'] = $id;
            $data['user'][0]['name'] = $name;
            file_put_contents('users.json', json_encode($data));
        } else {
            for ($i = count($data['user']); $i >= 0 ; $i--) {
                $last_user = $data['user'][$i];
                if ($last_user['id']) {
                    $id = (int)$last_user['id'] + 1;
                    break;
                }
            }
            $data['user'][$id - 1]['id'] = $id;
            $data['user'][$id - 1]['name'] = $name;
            file_put_contents('users.json', json_encode($data));
        }
        return $response->withJSON(['status' => 'User created', 'id' => $id], 201);
    }
    return $response->withJSON(['status'=>'Error, see documentation'],200);
});

$app->put('/user/', function (Request $request, Response $response) use ($app) {  //Редактирование User по id;
    $file = file_get_contents('users.json');
    $data = json_decode($file, true);
    $request_data = $request->getParsedBody();
    $name = $request_data['name'];
    $id =  (int)$request_data['id'];
    if (isset($id) && isset($name)){
        for ($i = 0; $i < count($data['user']); $i++){
            if ($data['user'][$i]['id'] === $id){
                $data['user'][$i]['name'] = $name;
                file_put_contents('users.json', json_encode($data));
                return $response->withJSON(['status'=>'User changed', 'id'=>$id],200);
            }
        }
        return $response->withJSON('User not found.',200);
    }
    return $response->withJSON(['status'=>'Error, see documentation'],200);
});

$app->delete('/user/', function (Request $request, Response $response) use ($app) {  //Удаление User по id;
    $file = file_get_contents('users.json');
    $data = json_decode($file, true);
    $id = (int)$request->getParsedBody()['id'];
    if (isset($id)) {
        for ($i = 0; $i < count($data['user']); $i++){
            if ($data['user'][$i]['id'] === $id){
                unset($data['user'][$i]);
                file_put_contents('users.json', json_encode($data));
                return $response->withJSON(['status'=>'User deleted', 'id'=>$id],200);
            }
        }
        return $response->withJSON('User not found.',200);
    }
    return $response->withJSON(['status'=>'Error, see documentation'],200);
});

$app->run();