# rest_api_slim

localhost:8000/user/name={new_name} -method 'post' create new user where name = {new_name}
localhost:8000/ - method 'Get' - view all user
localhost:8000/user/id={id} - method 'get' - view user where id = {id}
localhost:8000/user/id={id}&name={new_name}&_METHOD=PUT - method 'PUT' change user where id = {id}, and new name = {new_name}
localhost:8000/user/id={id}&_METHOD=DELETE - method 'delete' - delete user  where id = {id}
