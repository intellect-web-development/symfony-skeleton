import { Controller } from '@hotwired/stimulus';
import { Select2 } from "../js/select2";

export default class extends Controller {
    connect() {
        const select2 = new Select2()
        select2.initSelects()
    }
}