export class ProductImport {

    /**
     *
     */
    constructor() {
        this.clear();
    }

    /**
     *
     * @param data
     */
    set_prepared(data) {
        if (data && data.file_link && data.csv_headers && data.product_columns) {
            this.prepared = true;
            this.file_link = data.file_link;
            this.csv_headers = data.csv_headers;
            this.product_columns = data.product_columns;
            this.first_is_header = data.first_is_header;
        } else {
            new Swal('Incorrect data for import', '', 'error');
        }
    }

    /**
     *
     */
    clear() {
        this.prepared = false;
        this.file_link = '';
        this.csv_headers = [];
        this.product_columns = [];
        this.first_is_header = false;
    }
}