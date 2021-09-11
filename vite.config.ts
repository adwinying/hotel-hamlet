import path from 'path'
import { defineConfig } from 'laravel-vite'
import vue from '@vitejs/plugin-vue'

export default defineConfig()
  .withValetCertificates({})
  .withPlugin(vue)
  .merge({
    server: {
      host: '0.0.0.0',
      port: parseInt(process.env.VITE_PORT, 10) || 3000,
    },

    resolve: {
      alias: {
        '@': path.resolve(__dirname, '/resources/js')
      }
    },
  })
