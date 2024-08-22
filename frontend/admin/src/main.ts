import './assets/main.scss'
import './assets/smart-grid.scss'
import './main.primevue-components'

import { createApp } from 'vue'
import { createPinia } from 'pinia'

import App from './App.vue'
import router from './router'

import PrimeVue from 'primevue/config';

import { ru } from 'yup-locales';
import { setLocale } from 'yup';

import { createHead } from 'unhead'
createHead()

setLocale(ru);

const app = createApp(App)

app.use(createPinia())
app.use(router)
// @ts-ignore
app.use(PrimeVue);

registerPrimeVueComponents(app)

import { createI18n } from 'vue-i18n'

import { default as translations } from './translations'

import mdiVue from 'mdi-vue/v3'
import * as mdijs from '@mdi/js'
app.use(mdiVue, {
    icons: mdijs
})

import moment from 'moment';
import 'moment/dist/locale/ru.js';
moment.locale('ru');

const i18n = createI18n({
    locale: 'ru', // set locale
    fallbackLocale: 'en', // set fallback locale
    messages: translations,
})

app.use(i18n)

import 'primeicons/primeicons.css';
import {registerPrimeVueComponents} from "@/main.primevue-components";

app.mount('#app')
