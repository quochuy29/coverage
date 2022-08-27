<template>
  <div class="sidenav-container" id="side-nav">
    <nav class="nav">
      <div class="nav__user">
            <div class="nav__user-image">
            <img :src="`${user.member_avatar}`" loading="lazy" alt="profile">
            </div>

            <div class="nav__user-info">
            <div class="nav__user-info-name">{{ user.member_login_name }}</div>
            <div class="nav__user-info-email">{{ user.member_email }}</div>
            </div>
      </div>
      <hr>

      <ul class="nav__list">
        <li class="nav__item">
          <a href="#" class="nav__link active" id="js__change">
            <a class="fa-solid fa-house nav__icon"></a>
            <span class="nav__name">Dashboard</span>
          </a>
        </li>

        <li class="nav__item">
          <a href="#" class="nav__link" id="js__change">
            <a class="fa-solid fa-comments nav__icon"></a>
            <span class="nav__name">Messenger</span>
          </a>
        </li>

        <li class="nav__item">
          <a href="#" class="nav__link" id="js__change">
            <a class="fa-solid fa-folder nav__icon"></a>
            <span class="nav__name">Projects</span>
            <a class="fa-solid fa-chevron-down toggle-icon"></a>
          </a>

          <ul class="nav__drop">
            <div class="padding">
              <li>
                <a href="#" id="js__change">
                  <span class="nav__name">Data</span>
                </a>
              </li>
              <li>
                <a href="#" id="js__change">
                  <span class="nav__name">Group</span>
                </a>
              </li>
              <li>
                <a href="#" id="js__change">
                  <span class="nav__name">Members</span>
                </a>
              </li>
            </div>
          </ul>
        </li>

        <li class="nav__item">
          <a href="#" class="nav__link" id="js__change">
            <a class="fa-solid fa-chart-pie nav__icon"></a>
            <span class="nav__name">Analytics</span>
          </a>
        </li>

        <li class="nav__item">
          <a href="#" class="nav__link" id="js__change">
            <a class="fa-solid fa-user-plus nav__icon"></a>
            <span class="nav__name">Team</span>
            <a class="fa-solid fa-chevron-down toggle-icon"></a>
          </a>

          <ul class="nav__drop">
            <div class="padding">
              <li>
                <a href="#"><a href="#" id="js__change">Data</a></a>
              </li>
              <li>
                <a href="#"><a href="#" id="js__change">Group</a></a>
              </li>
              <li>
                <a href="#"><a href="#" id="js__change">Members</a></a>
              </li>
            </div>
          </ul>
        </li>

        <li class="nav__item">
          <a href="#" class="nav__link" id="js__change">
            <a class="fa-solid fa-gear nav__icon"></a>
            <span class="nav__name">Settings</span>
          </a>
        </li>
      </ul>
    </nav>
  </div>
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
  mounted() {
  },
  methods: {
  }
}
</script>
<style scoped>
.nav{
  display:block;
}

ul {
  list-style: none;
}

a {
  text-decoration: none;
  color: inherit;
}

hr {
  margin: 0.5rem 0;
  opacity: 0.1;
  border: none;
  border-bottom: 1px solid #b6cefc;
}

img {
  display: block;
}

ol, ul {
    padding-left: 0;
}
</style>