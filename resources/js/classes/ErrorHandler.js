export class ErrorHandler {

    static showMessage(error) {
        if (error.response) {
            if (error.response.data.errors) {
                let msg = '';
                _.each(error.response.data.errors, (el) => {
                    msg += (typeof el === 'string') ? el : el.join('<br>');
                });
                new Swal({
                    html: msg,
                    type: 'error',
                });
            } else {
                new Swal(error.response.data.message, '', 'error');
            }
        } else {
            new Swal(error.message, '', 'error');
        }
    }
}