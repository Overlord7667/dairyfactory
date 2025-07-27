import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'

export default defineConfig({
  plugins: [vue()],
  server: {
    proxy: {
      '/milk': {
        target: 'http://localhost:8080', // адрес вашего Yii2-бэкенда
        changeOrigin: true,
        rewrite: (path) => path.replace(/^\/milk/, '/milk'), // если у вас путь начинается с /milk
      },
    },
  },
})