# TDIW-store

Tienda virtual para las prácticas de la asignatura TDIW.

Añadir en local los archivos `.htaccess` y `.htpasswd` según está indicado en el enunciado de la práctica:

##.htaccess
```
AuthType Basic
AuthName "Entrar usuari i password"
AuthUserFile /tdiw/tdiw-j3/public_html/.htpasswd
Require user admin
```

##.htpasswd
Generar este archivo mediante el comando 
```
htpasswd -c .htpasswd admin
```

##.gitignore
Finamente, añadir `.htaccess` y `.htpasswd` al archivo `.gitignore` para que no se añadan al índice.
