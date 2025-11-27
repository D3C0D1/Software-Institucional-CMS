# 🎓 CMS Institucional Policaribe

Sistema completo de gestión de contenido (CMS) para institución educativa con página web institucional, gestión de noticias y sistema PQRS desarrollado en PHP y MySQL.

![PHP](https://img.shields.io/badge/PHP-7.0+-blue)
![MySQL](https://img.shields.io/badge/MySQL-5.6+-orange)
![Bootstrap](https://img.shields.io/badge/Bootstrap-3.3.7-purple)
![License](https://img.shields.io/badge/license-MIT-green)

## 📋 Descripción

Sistema integral de gestión de contenido diseñado para instituciones educativas que incluye:

- 🌐 **Sitio web institucional** completo y responsive
- 📰 **Sistema de noticias** con panel de administración
- 📝 **Sistema PQRS** (Peticiones, Quejas, Reclamos y Sugerencias)
- 👥 **Gestión de usuarios** y control de acceso
- 🎨 **Dashboard administrativo** moderno e intuitivo
- 📊 **Estadísticas y reportes** en tiempo real

## 🚀 Características Principales

### 🔐 Sistema de Autenticación
- Login seguro con encriptación de contraseñas (password_hash)
- Gestión de sesiones PHP
- Control de acceso por roles
- Recuperación de contraseñas

### 📰 Gestión de Noticias (CRUD Completo)
- ✅ **Crear** noticias con editor WYSIWYG (TinyMCE)
- ✅ **Leer** y listar noticias con paginación
- ✅ **Actualizar** noticias existentes
- ✅ **Eliminar** noticias
- 📂 Organización por categorías
- 🏷️ Múltiples categorías por noticia
- 📷 Subida de imágenes (JPG, PNG, GIF, WebP, SVG)
- 🌟 Noticias destacadas para slider
- 📊 Contador de visitas
- 🔍 Búsqueda y filtrado avanzado
- 📅 Estados: Borrador, Publicado, Archivado

### 📝 Sistema PQRS (CRUD Completo)
- ✅ **Crear** solicitudes PQRS mediante formulario
- ✅ **Leer** y consultar radicados
- ✅ **Actualizar** estado de solicitudes
- ✅ **Eliminar** solicitudes completadas
- 📑 Tipos de solicitud:
  - Felicitaciones
  - Peticiones
  - Quejas
  - Reclamos
  - Sugerencias
- 🔢 Sistema de radicación automática
- 📎 Adjuntar archivos y evidencias
- 📧 Notificaciones por correo
- ⏱️ Control de tiempos de respuesta (8 días hábiles)
- 🔍 Consulta pública de radicados
- 🎯 Estados: Radicado, En Proceso, Resuelto, Cerrado
- 💬 Sistema de respuestas

### 👥 Gestión de Usuarios
- Crear, editar y eliminar usuarios
- Roles y permisos
- Perfil de usuario editable
- Cambio de contraseña

### 🎨 Panel de Administración (Dashboard)
- 📊 Estadísticas en tiempo real
- 📈 Gráficos de visitas y actividad
- 🔔 Notificaciones de PQRS pendientes
- 📰 Resumen de noticias publicadas
- 🎯 Acceso rápido a funciones principales
- 📱 Interfaz responsive y moderna

### 🌐 Sitio Web Público
- 🏠 Página de inicio con slider dinámico
- 📰 Sección de noticias institucionales
- ℹ️ Página "Quiénes somos"
- 🎓 Oferta académica (programas)
- 📋 Formulario PQRS público
- 📞 Página de contacto
- 📱 Diseño responsive (móvil, tablet, desktop)
- ♿ Accesible y optimizado para SEO

## 📁 Estructura del Proyecto

```
Software-Institucional-CMS/
├── 📂 admin/                          # Panel de administración
│   ├── index.php                      # Dashboard principal
│   ├── login.php                      # Inicio de sesión
│   ├── logout.php                     # Cerrar sesión
│   ├── articulos.php                  # Listado de noticias
│   ├── crear_articulo.php             # Crear noticia
│   ├── editar_articulo.php            # Editar noticia
│   ├── eliminar_articulo.php          # Eliminar noticia
│   ├── categorias.php                 # Gestión de categorías
│   ├── pqs.php                        # Gestión de PQRS
│   ├── usuarios.php                   # Gestión de usuarios
│   ├── crear_usuario.php              # Crear usuario
│   └── tools/                         # Herramientas auxiliares
│
├── 📂 api/                            # API REST
│   ├── articulos.php                  # API de noticias
│   └── pqrs.php                       # API de PQRS
│
├── 📂 uploads/                        # Archivos subidos
│   ├── articulos/                     # Imágenes de noticias
│   └── pqrs/                          # Adjuntos de PQRS
│
├── 📂 images/                         # Recursos multimedia
│   ├── blog/                          # Imágenes del blog
│   ├── logos/                         # Logotipos
│   ├── banners/                       # Banners institucionales
│   └── programas/                     # Imágenes de programas
│
├── 📂 policaribe/                     # Contenido institucional
│   ├── quienes-somos.html             # Información institucional
│   └── programas/                     # Oferta académica
│
├── 📂 templates/                      # Plantillas Joomla
├── 📂 components/                     # Componentes Joomla
├── 📂 modules/                        # Módulos Joomla
├── 📂 plugins/                        # Plugins Joomla
│
├── 📄 config.php                      # Configuración BD y sistema
├── 📄 funciones.php                   # Funciones auxiliares
├── 📄 index.html                      # Página de inicio
├── 📄 blog.php                        # Blog de noticias
├── 📄 articulo.php                    # Vista individual de noticia
├── 📄 pqrs.html                       # Formulario PQRS público
├── 📄 pqrs_submit.php                 # Procesamiento de PQRS
├── 📄 contacto.html                   # Página de contacto
├── 📄 poli_policaribe.sql             # Base de datos
└── 📄 README.md                       # Este archivo
```

## 🛠️ Requisitos del Sistema

### Software Requerido
- **Servidor Web:** Apache 2.4+ o Nginx
- **PHP:** 7.0 o superior (recomendado 7.4+)
- **MySQL:** 5.6 o superior / MariaDB 10.0+
- **AMPPS** (recomendado para desarrollo local)
- **Navegador:** Moderno (Chrome, Firefox, Safari, Edge)

### Extensiones PHP Necesarias
```
- pdo_mysql
- mysqli
- gd (para procesamiento de imágenes)
- fileinfo
- mbstring
- json
```

## 📦 Instalación

### Método 1: Instalación con AMPPS (Recomendado)

#### Paso 1: Clonar o Descargar el Proyecto
```bash
cd /Applications/AMPPS/www/
git clone https://github.com/D3C0D1/Software-Institucional-CMS.git
# O descargar y extraer el ZIP
```

#### Paso 2: Crear la Base de Datos
1. Abre **phpMyAdmin**: http://localhost/phpmyadmin
2. Crea una nueva base de datos llamada `policaribe`
3. Selecciona la base de datos
4. Ve a **Importar**
5. Selecciona el archivo `poli_policaribe.sql`
6. Haz clic en **Continuar**

**Alternativa por Terminal:**
```bash
mysql -u root -p policaribe < poli_policaribe.sql
# Contraseña por defecto de AMPPS: mysql
```

#### Paso 3: Configurar la Conexión
Abre el archivo `config.php` y verifica las credenciales:

```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'policaribe');
define('DB_USER', 'root');
define('DB_PASS', 'mysql');  // Cambiar según tu configuración
```

#### Paso 4: Configurar Permisos

**macOS/Linux:**
```bash
cd /Applications/AMPPS/www/Software-Institucional-CMS
chmod -R 755 uploads/
chmod -R 755 images/
```

**Windows:**
- Clic derecho en carpetas `uploads` e `images`
- Propiedades → Seguridad
- Asegurar permisos de escritura

#### Paso 5: Acceder al Sistema

**Sitio Web Público:**
```
http://localhost/Software-Institucional-CMS/index.html
```

**Panel de Administración:**
```
http://localhost/Software-Institucional-CMS/admin/login.php
```

**Credenciales por Defecto:**
- **Usuario:** admin@policaribe.edu.co
- **Contraseña:** admin123

> ⚠️ **IMPORTANTE:** Cambiar credenciales después del primer acceso

### Método 2: Instalación en Servidor de Producción

```bash
# 1. Subir archivos por FTP/SFTP
# 2. Crear base de datos en cPanel/Plesk
# 3. Importar poli_policaribe.sql
# 4. Editar config.php con credenciales de producción
# 5. Ajustar SITE_URL en config.php
```

## 💻 Uso del Sistema

### 🔐 Acceder al Panel de Administración

1. Navega a: `http://localhost/Software-Institucional-CMS/admin/login.php`
2. Ingresa credenciales
3. Accede al dashboard

### 📰 Gestionar Noticias

#### Crear Nueva Noticia
1. Click en **Noticias → Crear Noticia**
2. Completa el formulario:
   - **Título:** Título llamativo de la noticia
   - **Descripción corta:** Resumen breve (150-200 caracteres)
   - **Contenido:** Texto completo con editor visual TinyMCE
   - **Imagen principal:** Subir imagen destacada (JPG, PNG, GIF, WebP)
   - **Autor:** Nombre del autor
   - **Fecha de publicación:** Fecha y hora
   - **Estado:** Borrador / Publicado / Archivado
   - **Categorías:** Seleccionar una o más
   - **Destacado:** Marcar para aparecer en slider
3. Click en **Guardar Noticia**

#### Editar Noticia Existente
1. Ve a **Noticias → Registro de Noticias**
2. Click en botón **Editar** (ícono lápiz)
3. Modifica los campos necesarios
4. Click en **Actualizar**

#### Eliminar Noticia
1. Ve a **Noticias → Registro de Noticias**
2. Click en botón **Eliminar** (ícono basura)
3. Confirma la eliminación

#### Gestionar Categorías
1. Ve a **Noticias → Categorías**
2. Crear nueva: Completa formulario lateral
3. Editar: Click en nombre de categoría
4. Eliminar: Click en botón eliminar (requiere que no tenga noticias asociadas)
5. Activar/Desactivar: Toggle de estado

### 📝 Gestionar PQRS

#### Ver Solicitudes PQRS
1. Ve a **PQRSF → Ver registro de PQRSF**
2. Visualiza todas las solicitudes recibidas
3. Filtra por:
   - Estado (Radicado, En Proceso, Resuelto, Cerrado)
   - Tipo (Felicitación, Petición, Queja, Reclamo, Sugerencia)
   - Búsqueda por radicado, nombre o resumen

#### Responder una PQRS
1. Click en botón **Responder** (ícono verde)
2. Escribe la respuesta en el modal
3. Selecciona el nuevo estado
4. Click en **Guardar respuesta**

#### Cambiar Estado de PQRS
1. Usa el selector desplegable en la columna "Estado"
2. Selecciona el nuevo estado
3. Se actualiza automáticamente

#### Eliminar PQRS
1. Click en botón **Eliminar** (ícono rojo)
2. Confirma la eliminación

### 🔍 Consulta Pública de PQRS

Los usuarios pueden consultar el estado de su radicado:

1. Ir a: `http://localhost/Software-Institucional-CMS/pqrs.html`
2. Click en **Consultar radicado PQRS**
3. Ingresar número de radicado
4. Ver estado y respuesta (si existe)

### 👥 Gestionar Usuarios

1. Ve a **Usuarios → Gestión de usuarios**
2. **Crear nuevo usuario:**
   - Click en **Crear usuario**
   - Completa: nombre, email, contraseña, rol
3. **Editar usuario:** Click en botón editar
4. **Eliminar usuario:** Click en botón eliminar
5. **Actualizar perfil propio:** Click en tu nombre (esquina superior derecha)

## 🗄️ Estructura de la Base de Datos

### Tablas Principales

#### `articulos`
Almacena las noticias del sitio
```sql
- id (INT, PK, AUTO_INCREMENT)
- titulo (VARCHAR 255)
- slug (VARCHAR 255, UNIQUE)
- descripcion_corta (TEXT)
- contenido (LONGTEXT)
- imagen (VARCHAR 255)
- autor (VARCHAR 150)
- fecha_publicacion (DATETIME)
- estado (ENUM: borrador, publicado, archivado)
- destacado (BOOLEAN)
- visitas (INT)
- fecha_creacion (TIMESTAMP)
- fecha_actualizacion (TIMESTAMP)
```

#### `categorias`
Categorías para organizar noticias
```sql
- id (INT, PK, AUTO_INCREMENT)
- nombre (VARCHAR 100, UNIQUE)
- slug (VARCHAR 100, UNIQUE)
- descripcion (TEXT)
- activo (BOOLEAN)
- orden (INT)
- fecha_creacion (TIMESTAMP)
```

#### `articulo_categoria`
Relación muchos a muchos entre artículos y categorías
```sql
- articulo_id (INT, FK → articulos)
- categoria_id (INT, FK → categorias)
- PRIMARY KEY (articulo_id, categoria_id)
```

#### `pqrs`
Solicitudes PQRS de usuarios
```sql
- id (INT, PK, AUTO_INCREMENT)
- radicado (VARCHAR 50, UNIQUE)
- nombre (VARCHAR 150)
- identificacion (VARCHAR 50)
- correo (VARCHAR 150)
- telefono (VARCHAR 50)
- tipo (ENUM: felicitacion, peticion, queja, reclamo, sugerencia)
- resumen (VARCHAR 255)
- detalle (LONGTEXT)
- estado (ENUM: radicado, en_proceso, resuelto, cerrado)
- respuesta (LONGTEXT)
- fecha_radicado (TIMESTAMP)
- fecha_actualizacion (TIMESTAMP)
```

#### `pqrs_adjuntos`
Archivos adjuntos a PQRS
```sql
- id (INT, PK, AUTO_INCREMENT)
- pqrs_id (INT, FK → pqrs)
- nombre_original (VARCHAR 255)
- ruta (VARCHAR 255)
- mime (VARCHAR 100)
- size (INT)
- fecha_subida (TIMESTAMP)
```

#### `usuarios`
Usuarios del sistema administrativo
```sql
- id (INT, PK, AUTO_INCREMENT)
- nombre (VARCHAR 100)
- email (VARCHAR 150, UNIQUE)
- password (VARCHAR 255) -- Hash bcrypt
- rol (ENUM: admin, editor)
- activo (BOOLEAN)
- ultimo_acceso (DATETIME)
- fecha_creacion (TIMESTAMP)
- fecha_actualizacion (TIMESTAMP)
```

## 🔒 Seguridad

### Medidas Implementadas

✅ **Autenticación segura:**
- Contraseñas hasheadas con `password_hash()` (bcrypt)
- Sesiones PHP con cookies HttpOnly
- Protección CSRF en formularios

✅ **Protección contra inyección SQL:**
- PDO con prepared statements
- Binding de parámetros

✅ **Protección XSS:**
- Sanitización con `htmlspecialchars()`
- Validación de entrada de usuario

✅ **Subida de archivos segura:**
- Validación de tipos MIME
- Límite de tamaño (8MB para imágenes, 10MB para adjuntos)
- Nombres de archivo únicos con `uniqid()`
- Almacenamiento fuera del webroot (recomendado en producción)

✅ **Control de acceso:**
- Verificación de sesión en cada página administrativa
- Redirección automática si no está autenticado

### Recomendaciones de Seguridad

```bash
# 1. Cambiar credenciales por defecto
# 2. Usar HTTPS en producción
# 3. Configurar permisos restrictivos:
chmod 644 config.php
chmod 755 uploads/
chown www-data:www-data uploads/

# 4. Deshabilitar listado de directorios
# Agregar en .htaccess:
Options -Indexes

# 5. Ocultar versión de PHP
# En php.ini:
expose_php = Off
```

## 🛠️ Tecnologías Utilizadas

### Backend
- **PHP 7.0+** - Lenguaje del lado del servidor
- **MySQL/MariaDB** - Sistema de gestión de base de datos
- **PDO** - Capa de abstracción de base de datos
- **Joomla 3.x** - Framework CMS (componentes legacy)

### Frontend
- **HTML5** - Estructura semántica
- **CSS3** - Estilos y animaciones
- **Bootstrap 3.3.7** - Framework CSS responsive
- **jQuery 2.1.4** - Librería JavaScript
- **Font Awesome 4.7.0** - Iconos vectoriales
- **Slick Carousel** - Slider de imágenes
- **Swiper.js** - Slider moderno
- **TinyMCE 6** - Editor WYSIWYG

### Herramientas de Desarrollo
- **Git** - Control de versiones
- **AMPPS** - Entorno de desarrollo local
- **phpMyAdmin** - Administración de base de datos

## 🐛 Solución de Problemas

### Error de conexión a la base de datos
```
❌ Error: SQLSTATE[HY000] [1045] Access denied
```
**Solución:**
1. Verifica que AMPPS/Apache esté corriendo
2. Confirma credenciales en `config.php`
3. Verifica que la base de datos `policaribe` exista
4. Revisa permisos del usuario MySQL

### Las imágenes no se suben
```
❌ Error: No se pudo mover el archivo
```
**Solución:**
```bash
# Verificar permisos
ls -la uploads/
chmod -R 755 uploads/
chown -R tu_usuario:www-data uploads/

# Verificar límites PHP (php.ini)
upload_max_filesize = 10M
post_max_size = 12M
```

### No se puede acceder al admin
```
❌ Error: Página no encontrada
```
**Solución:**
1. Verifica la URL: `http://localhost/Software-Institucional-CMS/admin/login.php`
2. Verifica que `mod_rewrite` esté habilitado
3. Revisa archivo `.htaccess`
4. Comprueba importación de base de datos

### Editor TinyMCE no carga
**Solución:**
1. Verifica conexión a internet (usa CDN)
2. Abre consola del navegador (F12) y busca errores
3. Verifica que jQuery esté cargado antes de TinyMCE

### PQRS no se envían
**Solución:**
1. Verifica permisos en `uploads/pqrs/`
2. Revisa configuración de correo en `config.php`
3. Comprueba que la tabla `pqrs` exista
4. Verifica límites de subida de archivos

## 🔧 Configuración Avanzada

### Cambiar URL del sitio
Editar `config.php`:
```php
// Desarrollo
define('SITE_URL', 'http://localhost/Software-Institucional-CMS');

// Producción
define('SITE_URL', 'https://www.policaribe.edu.co');
```

### Configurar correo electrónico
Agregar en `config.php`:
```php
define('MAIL_HOST', 'smtp.gmail.com');
define('MAIL_PORT', 587);
define('MAIL_USER', 'correo@policaribe.edu.co');
define('MAIL_PASS', 'tu_contraseña');
define('MAIL_FROM', 'noreply@policaribe.edu.co');
define('MAIL_FROM_NAME', 'Policaribe');
```

### Cambiar contraseña de administrador

**Método 1: Por base de datos**
```sql
UPDATE usuarios 
SET password = '$2y$10$hash_generado' 
WHERE email = 'admin@policaribe.edu.co';
```

**Método 2: Generar hash en PHP**
```php
<?php
echo password_hash('nueva_contraseña', PASSWORD_DEFAULT);
?>
```

## 📊 API REST

El sistema incluye endpoints API para integraciones:

### API de Noticias
```
GET /api/articulos.php?action=list
GET /api/articulos.php?action=get&id=123
GET /api/articulos.php?action=destacados
GET /api/articulos.php?action=categoria&slug=eventos
```

### API de PQRS
```
GET /api/pqrs.php?action=consultar&radicado=PQR-2025-001
POST /api/pqrs.php?action=crear
```

## 🤝 Contribuir

Las contribuciones son bienvenidas. Para cambios importantes:

1. Fork el proyecto
2. Crea una rama para tu feature (`git checkout -b feature/NuevaCaracteristica`)
3. Commit tus cambios (`git commit -m 'Agregar nueva característica'`)
4. Push a la rama (`git push origin feature/NuevaCaracteristica`)
5. Abre un Pull Request

## 📝 Licencia

Este proyecto está bajo la Licencia MIT. Ver archivo `LICENSE` para más detalles.

## 👨‍💻 Autor

**D3C0D1**
- GitHub: [@D3C0D1](https://github.com/D3C0D1)
- Repositorio: [Software-Institucional-CMS](https://github.com/D3C0D1/Software-Institucional-CMS)

## 📧 Contacto y Soporte

Para soporte técnico, consultas o reportar problemas:

- **Email:** contacto@policaribe.edu.co
- **Issues:** [GitHub Issues](https://github.com/D3C0D1/Software-Institucional-CMS/issues)
- **Documentación:** [Wiki del proyecto](https://github.com/D3C0D1/Software-Institucional-CMS/wiki)

## 🎯 Roadmap / Próximas Funcionalidades

- [ ] Sistema de notificaciones push
- [ ] Integración con redes sociales
- [ ] Chat en vivo para PQRS
- [ ] Exportación de reportes PDF/Excel
- [ ] Panel de analytics avanzado
- [ ] API RESTful completa
- [ ] Modo oscuro (dark mode)
- [ ] Multiidioma (ES, EN)
- [ ] Sistema de comentarios en noticias
- [ ] Galería de imágenes institucional

---

**Desarrollado para:** Policaribe - Instituto de Formación Técnica Laboral  
**Versión:** 2.0  
**Última actualización:** Noviembre 2025  
**Branch actual:** main
