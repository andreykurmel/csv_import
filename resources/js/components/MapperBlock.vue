<template>
    <div class="justify-content-center" v-if="product_import.prepared">
        <div class="card max-height">
            <div class="card-header">Map Product Attributes</div>

            <div v-if="progress < 0" class="card-body overflow-auto">
                <table class="table table-bordered form-group">
                    <thead>
                    <tr>
                        <th scope="col">Product Column</th>
                        <th scope="col">Csv Column</th>
                        <th scope="col">Product Type</th>
                        <th scope="col">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="column in product_import.product_columns">
                        <td>
                            <input class="form-control" v-model="column.name"/>
                        </td>
                        <td>
                            <select class="form-control" v-model="column.map_index">
                                <option :value="-1"></option>
                                <option v-for="(header,index) in product_import.csv_headers" :value="index">{{ header }}</option>
                            </select>
                        </td>
                        <td>
                            <select class="form-control" v-model="column.type">
                                <option value="string">String</option>
                                <option value="integer">Integer</option>
                                <option value="float">Float</option>
                                <option value="text">Text</option>
                            </select>
                        </td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>
                            <input class="form-control" v-model="new_column.name"/>
                        </td>
                        <td>
                            <select class="form-control" v-model="new_column.map_index">
                                <option :value="-1"></option>
                                <option v-for="(header,index) in product_import.csv_headers" :value="index">{{ header }}</option>
                            </select>
                        </td>
                        <td>
                            <select class="form-control" v-model="new_column.type">
                                <option value="string">String</option>
                                <option value="integer">Integer</option>
                                <option value="float">Float</option>
                                <option value="text">Text</option>
                            </select>
                        </td>
                        <td>
                            <button class="btn btn-success" :disabled="!new_column.name" @click="addNewColumn">Add</button>
                        </td>
                    </tr>
                    </tbody>
                </table>
                <div class="form-group">
                    <button class="btn btn-success" @click="sendMapRequest">Map and Save</button>
                </div>
            </div>
            <div v-else="" class="card-body">
                <div class="progress-wrapper" v-show="progress > -1">
                    <div class="progress-bar" :style="{width: progress+'%'}"></div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import {ProductImport} from "./../classes/ProductImport";

    export default {
        name: 'MapperBlock',
        data: function () {
            return {
                new_column: this.emptyColumn(),
                progress: -1,
            };
        },
        props: {
            product_import: ProductImport,
        },
        methods: {
            emptyColumn() {
                return {
                    name: '',
                    map_index: -1,
                    type: 'string',
                };
            },
            addNewColumn() {
                this.product_import.product_columns.push( this.new_column );
                this.new_column = this.emptyColumn();
            },
            sendMapRequest() {
                axios.post('/api/import/csv_store', {
                    file_link: this.product_import.file_link,
                    product_mapping: this.product_import.product_columns,
                    first_is_header: this.product_import.first_is_header,
                }).then(({ data }) => {
                    if (data) {
                        this.progress = 0;
                        Echo.channel('import-current-status-'+data)
                            .listen('.changed', (data) => {
                                this.progress = data.completed_percent;
                                if (this.progress >= 100) {
                                    this.progress = -1;
                                    this.$emit('mapping-finished');
                                }
                            });
                    } else {
                        this.$emit('mapping-finished');
                    }
                }).catch(error => {
                    ErrorHandler.showMessage(error);
                });
            },

        },
        mounted() {
        },
    }
</script>

<style lang="scss" scoped="">
    .max-height {
        max-height: 100vh;
    }
    .progress-wrapper {
        margin-top: 10px;
        height: 32px;
        border-radius: 5px;
        border: 1px solid #CCC;

        .progress-bar {
            height: 100%;
        }
    }
</style>