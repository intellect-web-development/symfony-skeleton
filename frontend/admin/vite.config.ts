import { fileURLToPath, URL } from 'node:url'

import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'
import vueJsx from '@vitejs/plugin-vue-jsx'
import AutoImport from 'unplugin-auto-import/vite'
import { unheadComposablesImports } from 'unhead'

// Используйте import.meta.url, если необходимо
const projectDir = fileURLToPath(new URL('.', import.meta.url));

// https://vitejs.dev/config/
export default defineConfig({
  plugins: [
    vue(),
    vueJsx(),
    AutoImport({
      imports: [
        unheadComposablesImports[0],
      ],
    }),
  ],
  resolve: {
    alias: {
      '@': fileURLToPath(new URL('./src', import.meta.url))
    }
  },
  build: {
    target: 'esnext',
  },
  // todo <IWD-3295>: нужно найти более оптимальный способ подключить primevue-sass-theme, сейчас он тянет это из гита, и в результате могут быть аномалии
  //  UPD: при обновлении до 4 версии primevue проблема решится сама собой, кусок кода, ответственный за primevue-sass-theme нужно будет отпилить полностью
  optimizeDeps: {
    exclude: ['primevue-sass-theme']
  },
  server: {
    fs: {
      allow: [
        projectDir,
        `${projectDir}/../../node_modules/primevue-sass-theme/themes/`,
      ],
    },
  },
})
