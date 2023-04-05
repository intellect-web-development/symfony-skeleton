export class Select2 {
    initSelects () {
        const input = $('.select2-hidden-accessible');

        if( input.length > 0 ) {
            input.each(function (index, element) {
                const select = $(element);
                const options = select.data('suggest-options');
                options.width = '100%';
                select.select2(options);
            })
        }
    }
}