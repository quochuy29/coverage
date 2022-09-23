<template>
    <header id="header">
        <div class="nav__brand">
            <div class="nav__icon nav__icon--menu" id="nav-toggle">
                <a class="fa-solid fa-bars"></a>
            </div>
            <a href="#" class="nav__brand-logo">
                <img width="90" src="/assets/images/logo-name.png" alt="">
            </a>
        </div>

        <div class="nav__user">
            <div class="nav__user-image">
            <img :src="`/${user.member_avatar}`" loading="lazy" alt="profile">
            </div>

            <div class="nav__user-info">
            <div class="nav__user-info-name">{{ user.member_login_name }}</div>
            <div class="nav__user-info-email">{{ user.member_email }}</div>
            </div>
        </div>
    </header>
</template>

<script>
export default {
    props: {
        user: {
            type: Object,
            require: true,
            default: {}
        }
    },
    data() {
        return {
            
        }
    },
    methods: {
    },
}
</script>
<style lang="less" scoped>
a {
  text-decoration: none;
  color: inherit;
}
</style>