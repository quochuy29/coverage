<template>
    <div>
        olaa
        <a href="javascript:void(0);" @click="alo">a</a>
    </div>
</template>

<script>
    export default {
        methods: {
            alo() {
                console.log(this.$routea('start'));
            }
        },
    }
</script>

<style lang="scss" scoped>

</style>