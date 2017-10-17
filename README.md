# Api Social

Esta api corresponde al proyecto [social](https://github.com/aochoa017/social "Repositorio Social")
para servir las peticiones necesarias para todo el desarrollo de la aplicación.

Está desarrollada en PHP con [Slim Framework](https://www.slimframework.com/ "Slim Framework")

## Peticiones Usuario

Endpoint | Métodp | Descripción
--- | --- | ---
**`/users`** | GET | Lista todos los usuarios
/user/{id} | GET | Lista el usuario con id = {id}
/users | GET | Lista todos los usuarios
/user/{id} | GET | Lista el usuario con id = {id}
/user/find/{user} | GET | Lista el usuario con usuario = {user}
/user | POST | Actualiza el usuario que se mande en la request del body
/user/new | POST | Crea un nuevo usuario sin validarlo
/user/new/{token} | GET | Verifica que el token es válido para validar al usuario nuevo
/user/new/{token} | POST | Valida el nuevo usuario creado
/user/{id}| POST | Actualiza el usuario con id = {id}
/user/{id}| DELETE | Elimina el usuario con id = {id}
/profiles| GET | Lista los perfiles de todos los usuarios
/profile/{id} | GET | Lista el perfil del usuario con id = {id}
/profile/{id} | PUT | Actualiza el perfil del usuario con id = {id}
/user/find/{user}| GET | Lista el perfil del usuario con usuario = {user}
/profile/avatar/{id} | GET | Se obtiene la url del avatar del usuario con id = {id}
/profile/avatar/{id} | POST | Se obtiene la url del nuevo avatar subido por el usuario con id = {id}
/login | POST | Se valida las credenciales y se obtiene un token
/contacts/{id} | GET | Lista los contactos del usuario con id = {id}
/contacts/requests/{id} | GET | Lista las solicitudes de contacto del usuario con id = {id}
/contacts/petitions/{id} | GET | Lista las peticiones de contacto del usuario con id = {id}
/contacts/{id} | PUT | Actualiza la lista los contactos del usuario con id = {id}
/contacts/accept/{id} | POST | Se acepta el contacto con usuario con id = {id}
/contacts/{id} | GET | Lista los contacto del usuario con id = {id}
/user/{id} | GET | Lista el usuario con id = {id}
