   ************************ API REST PACIENTES **************************


** Crear y Configurar La bd

*** Generar las Migraciones para la tabla 'pacientes'
	
	php artisan make:migration create_pacientes_table


*** en la tabla 'pacientes' le asignamos los campos que llevaran. al terminar de asignarlos debemos migrar.

** php artisan migrate -> Ejecutar todas las migraciones

*** Para Migrar solo 1 Migracion:	
php artisan migrate --path=database/migrations/2024_04_09_185454_create_pacientes_table


*** IMPORTANTE: los campos de numeros si son se usaran para calcular algo es recomendado definirlos como 'string'.


*************************************** API REST - SEEDER (Registros de Prueba)

	php artisan make:seeder PacienteSeeder


importar use Illuminate\Support\Facades\DB;

al terminar de crear los registros falsos, ejecutamos php artisan db:seed




************************************* CREAR CONTROLADOR Y MODELO PACIENTE
	
Modelo:
	php artisan make:model Paciente

Controlador: dentro de Carpeta API y el controlador debe tener una estructura de API.

	php artisan make:controller API/PacienteController --api


***** PARA ELIMINAR UN CONTROLADOR NO DESEADO

SI NO HEMOS MIGRADO, PODEMOS ELIMINARLO MANUALMENTE, PERO SI HEMOS MIGRADO, DEBEMOS HACER ROLLBACK, Y VOLVER A CREARLO Y VOLVER A MIGRARLO.



*********************************** ASIGNACION MASIVA MODELO PACIENTE
    protected $fillable = [
        'nombres',
        'apellidos',
        'edad',
        'sexo',
        'dni',
        'tipo_sangre',
        'telefono',
        'correo',
        'direccion'
    ];

    //para no mostrar estos campos
    protected $hidden = [
        'created_at', 
        'updated_at'
    ];


***************************************** REVERTIR MIGRACION

CADA VEZ QUE SE HAGAN CAMBIOS EN LAS MIGRACIONES O TABLAS SE DEBEN REALIZAR LOS CAMBIOS.

Revierte Migracion todas las Tablas ->	php artisan migrate:refresh --seed

---Revierte Migracion SOLO 1 Tabla: 
php artisan migrate:refresh --path=database\migrations\2024_04_09_185454_create_pacientes_table.php --seed




***************************************** GUARDAR REGISTROS

***** CREAR UN REQUEST DE VALIDACIONES

	php artisan make:request GuardarPacienteRequest

-Crear las validaciones
-Importar metodo request en el metodo store



***************************************** Mostrar un Registro

para devolver un solo registro hacemos uso del metodo show y del modelo Paciente y la variable $paciente, la cual es lo mismo que $id. y se la pasamos a la ruta {paciente}



***************************************** ACTUALIZAR REGISTROS

** CREAR NUEVO ARCHIVO DE VALIDACION PARA ACTUALIZAR ActualizarPacienteRequest

	php artisan make:request ActualizarPacienteRequest

Para Actualizar por Postman: metodo put, habilitamos el header accept, json
y en el body le pasamos todos los parametros de un usuario especifico y tambien el id, y modificamos el body json y send. en la url debemos pasarle el id.

http://localhost:8000/api/pacientes/11


CUANDO UN CAMPO ESTE MARCADO COMO UNICO, SE DEBE PASAR PARAMETROS EXTRAS EN EL METODO 'UPDATE', EL CUAL NOS PERMITA INGRESAR EL MISMO CAMPO QUE ESTA DEFINIDO COMO UNICO.

"dni" => ["required", "unique:pacientes,dni," .$this->route('paciente')->id,],



***************************************** ELIMINAR REGISTROS

Para Actualizar por Postman: metodo delete, habilitamos el header accept, json
y en el body le pasamos todos los parametros de un usuario especifico y tambien el id, y modificamos el body json y send. en la url debemos pasarle el id.

http://localhost:8000/api/pacientes/10

"msg": "Paciente Eliminado correctamente"




***************************************** RESOURCES - TRANSFORMA LA RESPUESTA
https://laravel.com/docs/10.x/eloquent-resources#main-content
https://laravel.com/docs/10.x/helpers#main-content

Ubicacion: app\Http\Resources\PacienteResource.php

LA FORMA CORRECTA PARA USAR LAS APIS ES USAR EL METODO RESOURCE.

RESOURCES LO USAMOS PARA TRANSFORMAR LA RESPUESTA DEL API. Resource transforma los datos entre el modelo y el json.

Beneficios de resources:

-Podemos devolver unicamente los campos que queramos.
-Podemos cambiar el formato de la respuesta api.
-Podemos enviar la informacion en Mayuscula o Minuscula
-Podemos Cambiar el valor de la propiedad.


**MIGRAR CAMBIOS
php artisan migrate:refresh --path=database\migrations\2024_04_09_185454_create_pacientes_table.php --seed


*** CREAR UN RESOURCE - app\Http\Resources\PacienteResource.php

	php artisan make:resource PacienteResource





***************************************** Laravel Sanctum - API-TOKENS
https://laravel.com/docs/10.x/sanctum#main-content


1- Instalacion:

	composer require laravel/sanctum


2- Publicar y Migrar

	php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"


3- 	php artisan migrate:reset


4- Finally, you should run your database migrations.

	php artisan migrate --seed


5- Ir a app/Http/Kernel.php

pegar esta linea en 'api'

\Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,


6- Crear Controlador para tener los metodos y los apitokens

	php artisan make:controller AutenticarController

6.1 Crearemos 3 metodos:

	- Registrar usuario (register)
	- Login de usuario (log in)
	- Salir de sesion (log out)

----------------------------------- METODO RegistrO

6.2 - Crear un Request de validacion para el metodo registro

	php artisan make:request RegistroRequest

6.3 Importar Request en metodo registro()

6.4 - Creando Usuario con Postman - Metodo Post

- http://localhost:8000/api/registro

- Configurar Header
Accept - application/json

-Body + Send
{
    "name" : "Juan",
    "email": "juan2021@gmail.com",
    "password": "1234"
}

"Usuario registrado correctamente"

7 ----------------------------------- METODO LOGIN

7.2 - Crear un Request de validacion para el metodo login

	php artisan make:request AccesoRequest


7.3 Importar Request en metodo login()
& Importar: 
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

7.4- Crear la logica para crear el token

7.5- Crear la ruta en api.php


7.6 - Logeando Usuario con Postman - Metodo POST

- http://localhost:8000/api/login

- Configurar Header
Accept - application/json
Accept-Encoding - Accept-Encoding


-Body + Send
{
    "email": "juan2021@gmail.com",
    "password": "1234"
}

Respuesta: 
{
    "res ": true,
    "token": "1|oiqyUDgmTFh1aK8APTJe6MD7vvtdhGhEJxjITPYP95735dd4",
    "msg": "Autenticación exitosa"
}


-------------- AHORA PARA HACER LOGIN




8 ----------------------------------- METODO LOGOUT

1- http://localhost:8000/api/logout

2- envolvemos la ruta logout en un middleware

3- Pasamos el token que se genero al iniciar sesion en el Authorization, seleccionamos Bearer Token y en el campo del lado, pegamos el token.


***** AHORA PARA VER TODOS LOS PACIENTES O PARA ACCEDER A LA RUTAS DE PACIENTES DEBEMOS PASAR EL TOKEN GENERADO AL INICIAR SESION DEBEMOS PASARLO EN POSTMAN EN Authorization - Bearer TOken y pegamos el Token generado y ya podemos hacer SEND y nos mostrara los pacientes o podemos acceder a todas las rutas de pacientes.

EN TODOS LOS METODOS DEBEMOS AGREGAR EL TOKEN
- Crear paciente
- Ver pacientes
- Ver 1 paciente
- Actualizar Paciente
- Eliminar Paciente







***************************************** Test API REST

LIMPIAR BASE DE DATOS:

	php artisan migrate:reset
	
	php artisan migrate --seed






*************************************  Tablas Relacionadas

Crearemos una relacion de muchos a muchos, entre Usuarios y Roles. 
Para esto crearemos una tabla intermedio (pivot) role_user

Users            Roles
  |                |
  |                |                   		
  |   role_user    |
  |                |
  |                |
    roles_asignados


--Relacion Muchos a Muchos:
https://laravel.com/docs/8.x/eloquent-relationships#updating-many-to-many-relationships


-- MIgraciones 
https://laravel.com/docs/4.2/migrations



1- Crear Tabla Roles con su migracion

php artisan make:model Role -m


2- Crear tabla pivot intermedia 

	php artisan make:migration create_roles_asignados_table --	create=roles_asignados


3- Crear campos en cada tabla


4- Migrar CAMBIOS

	php artisan migrate:refresh --seed



5- 


Insertando Usuario nuevo con rol EN POSTMAN - CREANDO NUEVO REGISTRO USUARIO

{
    "name" : "Maicol",
    "email": "maicol05@gmail.com",
    "password": "1234",
    "roles": [
        "1",
        "2"
    ]
}



******************************** RETORNAR DATA RELACIONADA

en el metodo login, usamos el parametro with('roles') para mostrar los roles que tiene este usuario, desde postman, debemos logearnos.

$user = User::with('roles')->where('email', $request->email)->first();
