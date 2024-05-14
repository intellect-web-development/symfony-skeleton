import type {App} from "@vue/runtime-core";

import Button from "primevue/button"
import InputText from "primevue/inputtext"
import Textarea from "primevue/textarea";
import OverlayPanel from "primevue/overlaypanel"
import Badge from 'primevue/badge';
import Message from "primevue/message"
import Divider from 'primevue/divider';
import Toast from 'primevue/toast';
import BlockUI from 'primevue/blockui';
import Card from 'primevue/card';
import Splitter from 'primevue/splitter';
import SplitterPanel from 'primevue/splitterpanel';
import Toolbar from "primevue/toolbar";
import Menubar from "primevue/menubar";
import TieredMenu from 'primevue/tieredmenu';
import Skeleton from 'primevue/skeleton';
import Avatar from 'primevue/avatar';
import Sidebar from 'primevue/sidebar';
import DataView from 'primevue/dataview';
import DataViewLayoutOptions from 'primevue/dataviewlayoutoptions'
import AvatarGroup from 'primevue/avatargroup'
import Stepper from 'primevue/stepper';
import StepperPanel from 'primevue/stepperpanel';
import PickList from 'primevue/picklist';
import Dropdown from 'primevue/dropdown';

import BadgeDirective from 'primevue/badgedirective';

import ToastService from 'primevue/toastservice';
import PrimeVue from "primevue/config";
import Ripple from 'primevue/ripple';
import Tooltip from 'primevue/tooltip';

export function registerPrimeVueComponents(app: App) {
    app.use(ToastService);

    app.use(PrimeVue, {
        ripple: true
    });

    app.component('Button', Button);
    app.component('InputText', InputText);
    app.component('Textarea', Textarea);
    app.component('OverlayPanel', OverlayPanel);
    app.component('Badge', Badge);
    app.component('Message', Message);
    app.component('Divider', Divider);
    app.component('Toast', Toast);
    app.component('BlockUI', BlockUI);
    app.component('Card', Card);
    app.component('Splitter', Splitter);
    app.component('SplitterPanel', SplitterPanel);
    app.component('Toolbar', Toolbar);
    app.component('Menubar', Menubar);
    app.component('TieredMenu', TieredMenu);
    app.component('Skeleton', Skeleton);
    app.component('Avatar', Avatar);
    app.component('Sidebar', Sidebar);
    app.component('DataView', DataView);
    app.component('DataViewLayoutOptions', DataViewLayoutOptions);
    app.component('AvatarGroup', AvatarGroup);
    app.component('Stepper', Stepper);
    app.component('StepperPanel', StepperPanel);
    app.component('PickList', PickList);
    app.component('Dropdown', Dropdown);

    app.directive('badge', BadgeDirective);
    app.directive('ripple', Ripple);
    app.directive('tooltip', Tooltip);
}
