import { defineConfig } from 'vite';
import { resolve } from 'path';
import fs from 'fs';

export default defineConfig(({ command }) => ({
    build: {
        // 'public/build' klasörüne çıktı verir
        outDir: resolve(__dirname, 'public/build'),
        // Eski build dosyalarını temizle
        emptyOutDir: true,
        // Vite'ın hangi dosyaları build edeceğini bilmesi için manifest oluştur
        manifest: true,
        rollupOptions: {
            // Ana JS dosyamızın yolu
            input: resolve(__dirname, 'public/assets/js/app.js'),
        },
    },
    server: {
        // Geliştirme sunucusu ayarları
        strictPort: true,
        port: 5173,
        // Geliştirme sunucusu çalıştığında, PHP'nin bunu anlayabilmesi için
        // 'public' klasöründe 'hot' adında bir dosya oluştur
        hmr: {
            host: 'localhost',
        },
    },
    plugins: [
        // 'hot' dosyasını yöneten özel bir plugin
        {
            name: 'phoenix-hot-file',
            configureServer(server) {
                const hotFilePath = resolve(__dirname, 'public/hot');
                server.httpServer.on('listening', () => {
                    fs.writeFileSync(hotFilePath, 'Vite dev server is running.');
                });
                // Vite kapandığında dosyayı sil
                process.on('exit', () => {
                    if (fs.existsSync(hotFilePath)) {
                        fs.unlinkSync(hotFilePath);
                    }
                });
            }
        }
    ]
}));
