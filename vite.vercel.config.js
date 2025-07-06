// Configuración para Vite y Vercel
import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
            // Asegurarse de que los assets se compilen en una ruta que Vercel pueda servir
            publicDirectory: 'public_html',
        }),
    ],
    build: {
        // Configura el directorio de salida para Vercel
        outDir: 'dist',
        // Asegúrate de que se vacíe el directorio antes de compilar
        emptyOutDir: true,
    },
    // Asegúrate de que el directorio público sea accesible
    publicDir: 'public',
});
