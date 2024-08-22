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
import AvatarGroup from 'primevue/avatargroup'
import Stepper from 'primevue/stepper';
import PickList from 'primevue/picklist';
import Dropdown from 'primevue/dropdown';
import Password from "primevue/password";
import PanelMenu from 'primevue/panelmenu';
import Breadcrumb from 'primevue/breadcrumb';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import ColumnGroup from 'primevue/columngroup';
import Row from 'primevue/row';
import SelectButton from 'primevue/selectbutton'
import Paginator from 'primevue/paginator'
import MultiSelect from 'primevue/multiselect'
import Dialog from 'primevue/dialog'
import ButtonGroup from 'primevue/buttongroup'
import ConfirmDialog from 'primevue/confirmdialog'
import SpeedDial from 'primevue/speeddial'
import InputSwitch from 'primevue/inputswitch'

import BadgeDirective from 'primevue/badgedirective';

import ConfirmationService from 'primevue/confirmationservice';
import ToastService from 'primevue/toastservice';

import PrimeVue from "primevue/config";
import Ripple from 'primevue/ripple';
import Tooltip from 'primevue/tooltip';

// todo <IWD-3295>: обновить зависимости при обновлении prime-vue до 4 версии
export function registerPrimeVueComponents(app: App) {
    app.use(ToastService);
    app.use(ConfirmationService);

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
    app.component('AvatarGroup', AvatarGroup);
    app.component('Stepper', Stepper);
    app.component('PickList', PickList);
    app.component('Dropdown', Dropdown);
    app.component('Password', Password);
    app.component('PanelMenu', PanelMenu);
    app.component('Breadcrumb', Breadcrumb);
    app.component('DataTable', DataTable);
    app.component('Column', Column);
    app.component('ColumnGroup', ColumnGroup);
    app.component('Row', Row);
    app.component('SelectButton', SelectButton);
    app.component('Paginator', Paginator);
    app.component('MultiSelect', MultiSelect);
    app.component('Dialog', Dialog);
    app.component('ButtonGroup', ButtonGroup);
    app.component('ConfirmDialog', ConfirmDialog);
    app.component('SpeedDial', SpeedDial);
    app.component('InputSwitch', InputSwitch);

    app.directive('badge', BadgeDirective);
    app.directive('ripple', Ripple);
    app.directive('tooltip', Tooltip);
}
