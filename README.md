# Sistema de Blog para CECAR

Sistema completo de gestión de blog con panel de administración desarrollado en PHP y MySQL.

## 📋 Requisitos

- AMPPS (Apache + MySQL + PHP)
- PHP 7.0 o superior
- MySQL 5.6 o superior
- Navegador web moderno

## 🚀 Instalación

### Paso 1: Importar la Base de Datos

1. Abre **phpMyAdmin** desde AMPPS (http://localhost/phpmyadmin)
2. Crea una nueva base de datos llamada `blog_cecar`
3. Selecciona la base de datos creada
4. Ve a la pestaña **"Importar"**
5. Selecciona el archivo `database.sql` desde la carpeta del proyecto
6. Haz clic en **"Continuar"** para importar

**Alternativa por Terminal:**
```bash
mysql -u root -p < database.sql
# Contraseña por defecto de AMPPS: mysql
```

### Paso 2: Verificar Configuración

1. Abre el archivo `config.php`
2. Verifica que las credenciales de la base de datos sean correctas:
   ```php
   define('DB_HOST', 'localhost');
   define('DB_NAME', 'blog_cecar');
   define('DB_USER', 'root');
   define('DB_PASS', 'mysql'); // Contraseña por defecto de AMPPS
   ```

### Paso 3: Configurar Permisos

Asegúrate de que la carpeta `uploads/` tenga permisos de escritura:

**En macOS/Linux:**
```bash
chmod -R 755 uploads/
```

**En Windows:**
- Clic derecho en la carpeta `uploads`
- Propiedades → Seguridad
- Asegúrate de que el usuario tenga permisos de escritura

### Paso 4: Acceder al Sistema

#### Sitio Web Público:
- **URL:** http://localhost/sitio_web/blog.php

#### Panel de Administración:
- **URL:** http://localhost/sitio_web/admin/login.php
- **Usuario:** admin@cecar.edu.co
- **Contraseña:** admin123

> ⚠️ **IMPORTANTE:** Cambia la contraseña del administrador después del primer inicio de sesión.

## 📁 Estructura del Proyecto

```
sitio_web/
├── admin/                      # Panel de administración
│   ├── index.php              # Dashboard
│   ├── login.php              # Página de inicio de sesión
│   ├── logout.php             # Cerrar sesión
│   ├── articulos.php          # Listado de artículos
│   ├── crear_articulo.php     # Crear nuevo artículo
│   ├── editar_articulo.php    # Editar artículo existente
│   ├── eliminar_articulo.php  # Eliminar artículo
│   └── categorias.php         # Gestión de categorías
├── uploads/                    # Carpeta para imágenes subidas
│   └── articulos/             # Imágenes de artículos
├── config.php                  # Configuración y conexión a BD
├── funciones.php              # Funciones de base de datos
├── blog.php                   # Página principal del blog
├── articulo.php               # Vista de artículo individual
├── database.sql               # Script de base de datos
└── README.md                  # Este archivo
```

## 🎨 Características

### Panel de Administración

- ✅ **Dashboard** con estadísticas en tiempo real
- ✅ **Gestión de Artículos** (CRUD completo)
  - Crear, editar y eliminar artículos
  - Editor de texto enriquecido (TinyMCE)
  - Subida de imágenes
  - Múltiples categorías por artículo
  - Estados: Borrador, Publicado, Archivado
  - Artículos destacados para slider
  - Contador de visitas

- ✅ **Gestión de Categorías** (CRUD completo)
  - Crear, editar y eliminar categorías
  - Ordenamiento personalizado
  - Activar/desactivar categorías

### Sitio Web Público

- ✅ **Listado de artículos** con paginación
- ✅ **Filtrado por categorías**
- ✅ **Búsqueda de artículos**
- ✅ **Slider de artículos destacados**
- ✅ **Vista individual de artículos**
- ✅ **Botones de compartir en redes sociales**
- ✅ **Artículos relacionados**
- ✅ **Diseño responsive con Bootstrap**

## 🗄️ Base de Datos

El sistema incluye:

- **4 tablas principales:**
  - `articulos` - Almacena los artículos del blog
  - `categorias` - Categorías para organizar artículos
  - `articulo_categoria` - Relación muchos a muchos
  - `usuarios` - Usuarios del panel de administración

- **10 categorías predefinidas:**
  - Pregrados
  - Posgrados
  - Educación Continuada
  - Investigación
  - Extensión
  - Eventos
  - Noticias Institucionales
  - Convenios
  - Vida Universitaria
  - Egresados

- **6 artículos de ejemplo** con contenido real

- **1 usuario administrador** por defecto

## 🔒 Seguridad

- Contraseñas encriptadas con `password_hash()`
- Protección contra SQL Injection (PDO con prepared statements)
- Protección contra XSS con `htmlspecialchars()`
- Control de acceso por sesiones
- Validación de subida de archivos

## 🛠️ Tecnologías Utilizadas

### Backend:
- PHP 7.0+
- MySQL/MariaDB
- PDO para base de datos

### Frontend:
- HTML5 / CSS3
- Bootstrap 3.3.7
- jQuery 2.1.4
- Font Awesome 4.7.0
- Slick Carousel
- TinyMCE 6 (Editor WYSIWYG)

## 📝 Uso del Sistema

### Crear un Artículo

1. Accede al panel de administración
2. Ve a **Artículos → Crear Nuevo Artículo**
3. Completa los campos:
   - Título *
   - Descripción corta *
   - Contenido completo * (con editor visual)
   - Imagen principal *
   - Autor *
   - Fecha de publicación *
   - Estado (Borrador/Publicado/Archivado)
   - Categorías * (al menos una)
   - Marcar como destacado (opcional)
4. Haz clic en **"Guardar Artículo"**

### Gestionar Categorías

1. Ve a **Categorías**
2. Usa el formulario lateral para crear/editar
3. Las categorías con artículos asociados no se pueden eliminar
4. Puedes cambiar el orden de aparición
5. Desactiva categorías sin eliminarlas

## 🐛 Solución de Problemas

### Error de conexión a la base de datos
- Verifica que AMPPS esté ejecutándose
- Confirma que la base de datos `blog_cecar` existe
- Revisa las credenciales en `config.php`

### Las imágenes no se suben
- Verifica permisos de la carpeta `uploads/`
- Asegúrate de que el tamaño del archivo sea menor a 5MB
- Formatos permitidos: JPG, PNG, GIF

### No se puede acceder al admin
- Verifica que hayas importado `database.sql`
- Usuario: `admin@cecar.edu.co`
- Contraseña: `admin123`

### Editor TinyMCE no carga
- Verifica tu conexión a internet (usa CDN)
- Revisa la consola del navegador para errores

## 🔄 Actualización de Contraseña

Para cambiar la contraseña del administrador:

```sql
UPDATE usuarios 
SET password = PASSWORD_HASH('nueva_contraseña', PASSWORD_DEFAULT) 
WHERE email = 'admin@cecar.edu.co';
```

O ejecuta esto en PHP:

```php
<?php
echo password_hash('nueva_contraseña', PASSWORD_DEFAULT);
// Copia el hash generado y actualiza manualmente en la base de datos
?>
```

## 📧 Soporte

Para soporte técnico o reportar problemas, contacta al equipo de desarrollo de CECAR.

---

**Desarrollado para:** Corporación Universitaria del Caribe - CECAR  
**Versión:** 1.0  
**Fecha:** Enero 2025
