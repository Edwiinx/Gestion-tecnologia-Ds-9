# Gestion-tecnologia-Ds-9
Repositorio sobre la realización de una web/aplicación sobre la gestión de inventario 



# 🗂️ Proyecto: Gestion-tecnologia-Ds-9

Este repositorio contiene una **aplicación web completa para la gestión de inventarios**, desarrollada en PHP y MySQL. Está orientada a pequeñas y medianas empresas que desean llevar control de entradas, salidas, stock, usuarios y compras, todo a través de una interfaz intuitiva.

---

# 🧰 Requisitos técnicos  

Para que el sistema funcione correctamente en un entorno local, asegúrate de tener las siguientes herramientas instaladas:

1. XAMPP
- Incluye Apache (servidor web), PHP (lenguaje backend) y MySQL (gestor de base de datos).  
- Compatible con Windows, Linux y Mac.

2. Base de datos (`NOID_DB.sql`)
- Archivo `.sql` que contiene la estructura y datos necesarios para el funcionamiento del sistema.  
- Debe importarse dentro de **phpMyAdmin**, que viene incluido en XAMPP.

---

# 📁 Instalación paso a paso

 1. Clonar o descargar el proyecto  
Clona el repositorio desde GitHub. Luego:

```bash
# Si usas Git:
git clone https://github.com/TuRepositorio/Gestion-tecnologia-Ds-9.git
```

O Mover el proyecto a HTDOCS si fue descargado en ZIP.
Extrae y luego Copia la carpeta extraída completa del proyecto dentro de la carpeta `htdocs` de XAMPP:

```
C:\xampp\htdocs\Gestion-tecnologia-Ds-9
```

3. Importar la base de datos

1. Abre tu navegador y accede a: [http://localhost/phpmyadmin](http://localhost/phpmyadmin)  
2. Hay que crera una base de datos nueva llamada: `NOID_DB` ->>> 
3. Haz clic sobre la base de datos creada y ve a la pestaña "Importar"  
4. Selecciona el archivo `NOID_DB.sql` incluido en el repositorio  
5. Ejecuta la importación

---

# 👤 Configuración de usuario MySQL

Para que el sistema se conecte correctamente a la base de datos, crea un usuario en MySQL con las siguientes credenciales:

```php
$host = "localhost";
$username = "mario";
$password = "12345678";
```

Pasos:  
1. En **phpMyAdmin**, ve a la pestaña **"Privilegios"**  
2. Crea un nuevo usuario llamado `mario` con la contraseña `12345678`  
3. Asigna **todos los privilegios** a ese usuario sobre la base de datos `NOID_DB`

---

# Ejecución del sistema

Una vez realizados todos los pasos, accede a la aplicación desde tu navegador con la siguiente URL:

```
http://localhost/Gestion-tecnologia-Ds-9/Index/inicio/login.php
```


CREDENCIALES PARA ACCEDER SEGUN TIPO(ADMINISTRADOR - COMPRADOR)

### Tabla de Usuarios Insertados

| NOMBRE_USUARIO | CONTRASENA   | TIPO           |
|----------------|--------------|----------------|
| Josue          | admin123     | ADMINISTRADOR  |
| Edwin          | clave456     | ADMINISTRADOR  |
| Maria          | pass789      | ADMINISTRADOR  |
| Mario          | admin987     | ADMINISTRADOR  |
| Ana            | comprador1   | COMPRADOR      |
| Luis           | comprador2   | COMPRADOR      |
| Carolina       | comprador3   | COMPRADOR      |
| Pedro          | comprador4   | COMPRADOR      |

---

 Funcionalidades principales del sistema

- Gestión de inventarios (stock, entradas, salidas)  
- Control de compras y ventas  
- Gestión de usuarios y roles  
- Historial de transacciones  
- Generación de recibos  
- Módulo de login seguro  
- Interfaz moderna con panel administrativo
