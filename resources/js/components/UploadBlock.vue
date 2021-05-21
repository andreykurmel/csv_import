<template>
    <div class="justify-content-center">
        <div class="card">
            <div class="card-header">Import Csv Products</div>

            <div class="card-body">
                <div class="form-group">
                    <input type="file" accept=".csv,.txt" class="form-control" @change="fileSelected"/>
                </div>
                <div class="form-group">
                    <input type="checkbox" v-model="csv_first_header"/>
                    <label>First row is header</label>
                </div>
                <div class="form-group">
                    <button class="btn btn-success" @click="uploadCsv">Proceed</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import {ErrorHandler} from "./../classes/ErrorHandler";

    export default {
        name: 'UploadBlock',
        data: function () {
            return {
                csv_file: null,
                csv_first_header: true,
            };
        },
        methods: {
            fileSelected(eve) {
                this.csv_file = _.first(eve.target.files);
            },
            uploadCsv() {
                if (this.csv_file) {
                    let data = new FormData();
                    data.append('uploaded_file', this.csv_file);
                    data.append('first_is_header', Number(this.csv_first_header));

                    axios.post('/api/import/csv_prepare', data, {
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        }
                    }).then(({ data }) => {
                        this.$emit('file-parsed', data);
                    }).catch(error => {
                        ErrorHandler.showMessage(error);
                    });
                } else {
                    new Swal('File is not selected', '', 'error');
                }
            },
        },
    }
</script>
