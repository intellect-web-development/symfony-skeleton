import './assets/main.css'
import './../smart-grid/smart-grid.scss'
import './main.primevue-components'

import { createApp } from 'vue'
import { createPinia } from 'pinia'

import App from './App.vue'
import router from './router'

import PrimeVue from 'primevue/config';

// todo: придумать как переключать локализацию динамически

import { ru } from 'yup-locales';
import { setLocale } from 'yup';

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

// todo: сделать возможность динамического переключения тем:
// скопировать каталог с ресурсами в public в процессе билда, на фронте сделать подгрузку стиля
// arya-blue
// aura-dark-noir          aura-light-noir         lara-dark-blue          lara-light-green        md-dark-indigo          nova-accent             tailwind-light
// arya-green              aura-dark-pink          aura-light-pink         lara-dark-cyan          lara-light-indigo       md-light-deeppurple     nova-alt                vela-blue
// arya-orange             aura-dark-purple        aura-light-purple       lara-dark-green         lara-light-pink         md-light-indigo         nova-vue                vela-green
// arya-purple             aura-dark-teal          aura-light-teal         lara-dark-indigo        lara-light-purple       mdc-dark-deeppurple     rhea                    vela-orange
// aura-dark-amber         aura-light-amber        bootstrap4-dark-blue    lara-dark-pink          lara-light-teal         mdc-dark-indigo         saga-blue               vela-purple
// aura-dark-blue          aura-light-blue         bootstrap4-dark-purple  lara-dark-purple        luna-amber              mdc-light-deeppurple    saga-green              viva-dark
// aura-dark-cyan          aura-light-cyan         bootstrap4-light-blue   lara-dark-teal          luna-blue               mdc-light-indigo        saga-orange             viva-light
// aura-dark-green         aura-light-green        bootstrap4-light-purple lara-light-amber        luna-green              mira                    saga-purple
// aura-dark-indigo        aura-light-indigo       fluent-light            lara-light-blue         luna-pink               nano                    soho-dark
// aura-dark-lime          aura-light-lime         lara-dark-amber         lara-light-cyan         md-dark-deeppurple      nova                    soho-light

import "primevue/resources/themes/aura-dark-green/theme.css";
import 'primeicons/primeicons.css';
import {registerPrimeVueComponents} from "@/main.primevue-components";

app.mount('#app')
