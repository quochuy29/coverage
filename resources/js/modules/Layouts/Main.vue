<template>
    <div class="container-fluid">
        <Layouts-Header :user="user"></Layouts-Header>
        <div class="main">
            <Layouts-Sidebar :user="user"></Layouts-Sidebar>
            <div class="ola">
                <router-view></router-view>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                user: {}
            }
        },
        async mounted() {
            await this.getUser();
        },
        methods: {
            async getUser() {
                try {
                    const user = await axios.get('user');
                    this.user = user.data
                } catch(error) {
                    if (error.response.status !== 200) {
                        this.$router.push('login')
                    }
                }
            }
        },
    }
</script>