# Tennis Tournament API

Esta es una aplicación desarrollada en Laravel para simular torneos de tenis de eliminación directa. La API permite registrar jugadores, simular torneos y consultar los resultados de torneos completados.

## **Descripción**

La API tiene dos endpoints principales:

1. `/api/simulate`: Simula un torneo de tenis a partir de una lista de jugadores.
2. `/api/tournaments`: Devuelve una lista de torneos completados con filtros opcionales de fecha y género.

---

## **Requisitos**

- PHP 8.1+
- Composer
- MySQL 8.0 (o compatible)
- Laravel 11+

---

## **Instalación**

1. Clona el repositorio:

   ```bash
   git clone <URL_DEL_REPOSITORIO>
   cd tennis-tournament
   ```

2. Instala las dependencias con Composer:

   ```bash
   composer install
   ```

3. Crea el archivo `.env`:

   ```bash
   cp .env.example .env
   ```

   Configura las variables de entorno según tus necesidades, como la conexión a la base de datos.

4. Genera la clave de la aplicación:

   ```bash
   php artisan key:generate
   ```

5. Migra las bases de datos:

   ```bash
   php artisan migrate
   ```

6. Ejecuta el servidor de desarrollo:

   ```bash
   php artisan serve
   ```

   La aplicación estará disponible en `http://localhost:8000`.

---

## **Endpoints**

### **1. Simular un torneo**

- **URL:** `/api/simulate`
- **Método:** `POST`
- **Descripción:** Simula un torneo de tenis y devuelve el ganador.

#### **Cuerpo de la solicitud (JSON):**

```json
{
    "players": [
        {
            "name": "Juan Perez",
            "skill_level": 95,
            "strength": 85,
            "speed": 88,
            "reaction_time": 80,
            "gender": "male"
        },
        {
            "name": "Pablo Osorio",
            "skill_level": 92,
            "strength": 90,
            "speed": 85,
            "reaction_time": 75,
            "gender": "male"
        }
    ],
    "gender": "male"
}
```

#### **Respuestas posibles:**

- **201 Created:**  
  ```json
  {
      "winner": {
          "id": 1,
          "name": "Juan Perez",
          "skill_level": 95,
          "gender": "male"
      }
  }
  ```

- **422 Unprocessable Entity:**  
  ```json
  {
      "message": "Todos los jugadores deben ser del mismo género"
  }
  ```

- **500 Internal Server Error:**  
  ```json
  {
      "message": "Ocurrió un error inesperado"
  }
  ```

---

### **2. Obtener torneos completados**

- **URL:** `/api/tournaments`
- **Método:** `POST`
- **Descripción:** Devuelve una lista de torneos completados. Los parámetros `date` y `gender` son opcionales.

#### **Parámetros de consulta:**

| Parámetro | Tipo   | Descripción                                | Obligatorio |
|-----------|--------|--------------------------------------------|-------------|
| date      | string | Fecha en formato `YYYY-MM-DD`.             | No          |
| gender    | string | Género del torneo (`male` o `female`).     | No          |

#### **Ejemplos de solicitudes:**

1. **Obtener torneos completados en una fecha específica:**

   ```bash
   POST /api/tournaments
   ```

   #### **Cuerpo de la solicitud (JSON):**
   ```json
        {
            "date": "2025-02-05"
        }

2. **Obtener torneos completados de género masculino:**

   ```bash
   POST /api/tournaments
   ```

   #### **Cuerpo de la solicitud (JSON):**
   ```json
        {
            "gender": "male"
        }

3. **Obtener todos los torneos completados:**

   ```bash
   POST /api/tournaments
   ```

#### **Respuesta (200 OK):**

```json
[
    {
        "id": 1,
        "name": "Wimbledon",
        "status": "completed",
        "gender": "male",
        "created_at": "2025-02-05T14:20:30.000000Z"
    },
    {
        "id": 2,
        "name": "Tennis",
        "status": "completed",
        "gender": "female",
        "created_at": "2025-02-04T12:10:15.000000Z"
    }
]
```

---

## **Ejecutar pruebas**

La aplicación incluye pruebas unitarias para los controladores, servicios y validadores. Puedes ejecutar las pruebas con el siguiente comando:

```bash
php artisan test
```

---

## **Licencia**

Este proyecto está bajo la licencia [MIT](https://opensource.org/licenses/MIT).
```

