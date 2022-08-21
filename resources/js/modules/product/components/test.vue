<template>
    <div class="ola">
        olaa
        <router-link :to="{ name: 'login'}">alo</router-link>
    </div>
</template>

<script>
    export default {
        methods: {
            alo() {
                console.log(this.$routes('start'));
            }
        },
    }
</script>