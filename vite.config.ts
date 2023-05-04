import path from 'path'
import { defineConfig } from 'vite'
import laravel from 'laravel-vite-plugin'
import vue from '@vitejs/plugin-vue'

export default defineConfig({
  server: {
    hmr: {
      host: process.env.VITE_HOST,
    },

    host: '0.0.0.0',
    port: parseInt(process.env.VITE_PORT ?? '3000', 10),
  },

  plugins: [
    laravel([
      'resources/css/app.css',
      'resources/js/app.ts',
    ]),

    vue({
      template: {
        transformAssetUrls: {
          base: null,
          includeAbsolute: false,
        },
      },
    }),
  ],

  resolve: {
    alias: {
      '@': path.resolve(__dirname, '/resources/js')
    }
  },
})
