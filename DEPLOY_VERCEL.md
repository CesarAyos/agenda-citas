Guía rápida para desplegar en Vercel

Resumen
- Este proyecto es una aplicación Laravel + Vite (Inertia). Para desplegarla entera en Vercel usamos un `Dockerfile` que construye los assets (Node) y las dependencias PHP (Composer) y luego ejecuta `php artisan serve` en el contenedor.

Pasos (resumido)
1. En el dashboard de Vercel crea un nuevo proyecto apuntando al repositorio.
2. Vercel respetará `vercel.json` y usará el `Dockerfile` para construir la imagen.
3. Configura las variables de entorno en Vercel (Settings → Environment Variables):
   - `APP_KEY` (usar `php artisan key:generate --show` localmente y copiar)
   - `APP_ENV=production`
   - `APP_DEBUG=false`
   - `APP_URL` (la URL de tu despliegue)
   - `DB_CONNECTION`, `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD` si usas base de datos remota
   - `MAIL_MAILER`, `MAIL_HOST`, `MAIL_PORT`, `MAIL_USERNAME`, `MAIL_PASSWORD`, `MAIL_ENCRYPTION`, `MAIL_FROM_ADDRESS`, `MAIL_FROM_NAME` según corresponda
   - Cualquier otra variable que aparezca en tu `.env`

Notas importantes
- El contenedor expone el puerto `3000`. Vercel proporciona la variable `PORT` automáticamente; el `Dockerfile` usa dicha variable.
- Por seguridad no incluyas `APP_KEY` ni credenciales en el repositorio. Usa el panel de Vercel.
- Si necesitas ejecutar migraciones al desplegar, puedes hacerlo manualmente desde la consola o añadir scripts de inicio; en este `Dockerfile` se intenta ejecutar `php artisan migrate --force` al arrancar (silencioso si falla).
- Para rendimiento y escalabilidad en producción, considera desplegar Laravel en un proveedor pensado para PHP (Render, Fly, DigitalOcean) y dejar sólo el frontend en Vercel.

Verificaciones locales antes de push
- Ejecuta localmente:
```
npm ci
npm run build
composer install --no-dev --optimize-autoloader
php artisan key:generate
```

Si quieres que yo haga ajustes adicionales (por ejemplo: usar Nginx en el contenedor, ejecutar workers, o separar frontend/backend), dime cuál prefieres.
