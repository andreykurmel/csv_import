<template>
    <div class="container">
        <div class="row">
            <div class="col-4 d-flex flex-wrap align-items-center justify-content-center">
                <div class="card form-group">
                    <div class="card-header">
                        <router-link :to="{name:'home'}">About</router-link>
                    </div>

                    <div class="card-body overflow-auto">
                        <label>Some about about</label>
                    </div>
                </div>
                <router-view style="max-width: 350px;"></router-view>
            </div>
            <div class="col-8">
                <router-view name="preproducts"></router-view>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'AboutElement',
        data: function () {
            return {
            };
        },
        props: {
        },
        methods: {
        },
        mounted() {
            this.$forceUpdate();
        },
    }
</script>

<style lang="scss" scoped="">
</style>