<template>
    <div class="container-fluid">
        <div class="form-group d-flex justify-content-around links-wrap">
            <router-link to="/about">About</router-link>
            <router-link to="/products">Products</router-link>
        </div>

        <upload-block
                v-if="!for_import.prepared"
                @file-parsed="csvParsed"
        ></upload-block>
        <mapper-block
                v-else=""
                :product_import="for_import"
                @mapping-finished="mapFinished"
        ></mapper-block>
    </div>
</template>

<script>
    import {ProductImport} from "./../classes/ProductImport";

    import UploadBlock from "./UploadBlock";
    import MapperBlock from "./MapperBlock";

    export default {
        components: {
            MapperBlock,
            UploadBlock,
        },
        name: 'ProductImportComponent',
        data: function () {
            return {
                for_import: new ProductImport(),
            };
        },
        methods: {
            csvParsed(data) {
                this.for_import.set_prepared(data);
            },
            mapFinished() {
                this.for_import.clear();
            },
        },
    }
</script>

<style lang="scss" scoped="">
    .links-wrap {
        font-size: 1.5em;
        font-weight: bold;
    }
</style>