<template>
    <div class="card" v-if="product">
        <div class="card-header">
            <label>{{ product.name }}</label>
        </div>
        <div class="card-body overflow-auto">
            <div><label>{{ product.brand }}</label></div>
            <div><label>{{ product.variant }}</label></div>
            <div><label>{{ product.price }}</label></div>
            <div><label>{{ product.description }}</label></div>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'SingleProduct',
        data: function () {
            return {
                product: null,
            };
        },
        props: {
            id: String|Number,
        },
        watch: {
            id(to, from) {
                this.load(to);
            }
        },
        methods: {
            load(id) {
                axios.post('/api/products/get', {
                    id: id,
                }).then(({ data }) => {
                    this.product = data;
                });
            },
        },
        mounted() {
            this.load(this.id);
        },
    }
</script>

<style lang="scss" scoped="">
</style>