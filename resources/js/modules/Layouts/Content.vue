<template>
    <div class="ola">
       <router-view></router-view>
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