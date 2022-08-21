<template>
  <main class="main">
    <div class="container">
      <div class="login__screen">
        <div class="login__logo">
          <img src="assets/images/logo-name.png" alt="Coverage" />
        </div>
        <div v-if="error" class="login__error">
          <p>{{ error }}</p>
        </div>

        <form action="javascript:void(0);" @submit.prevent="login()" method="" class="login__form">
          <div class="login__form__unit">
            <label for="userID">User Name</label>
            <input type="text" name="" id="userID" v-model="member_login_name">
          </div>

          <div class="login__form__unit">
            <label for="password">Password</label>
            <input type="password" name="" id="password" v-model="member_password">
          </div>

          <button class="login__form__btn" type="submit"><span>Login</span></button>
        </form>

        <ul class="login__link">
          <li><a href="#">Forgot password</a></li>
          <li><a href="#">No no no</a></li>
        </ul>
      </div>
    </div>
  </main>
</template>

<script>
    export default {
        data() {
          return {
            member_login_name: '',
            member_password: '',
            error: ''
          }
        },
        methods: {
          async login () {
            const postData = {
              member_login_name: this.member_login_name,
              password: this.member_password
            };

            try {
              const response = await axios.post('auth/login', postData);
              localStorage.setItem('token', response.data.access_token);
              localStorage.setItem('auth_user', JSON.stringify(response.data.auth_user));
              this.$router.push('app');
            } catch (error) {
              this.error = error.response.data.message;
            }
          }
        }
    }
</script>