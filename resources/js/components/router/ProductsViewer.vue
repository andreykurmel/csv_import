<template>
    <div class="justify-content-center">
        <router-link :to="{name:'home'}">Products</router-link>

        <div class="container">
            <div class="row">
                <div class="col-3 card" v-for="prod in all_products">
                    <div class="card-header">
                        <router-link :to="'/about/'+prod.id">{{ prod.name }}</router-link>
                    </div>
                    <div class="card-body overflow-auto">
                        <div><label>{{ prod.brand }}</label></div>
                        <div><label>{{ prod.variant }}</label></div>
                        <div><label>{{ prod.price }}</label></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'ProductsViewer',
        data: function () {
            return {
                all_products: [],
            };
        },
        props: {
        },
        methods: {
        },
        mounted() {
            axios.post('/api/products/get', {
                limit: 12,
            }).then(({ data }) => {
                this.all_products = data;
            });
        },
    }
</script>

<style lang="scss" scoped="">
</style>