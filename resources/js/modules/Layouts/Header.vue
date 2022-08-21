<template>
  <div class="sidenav-container" id="side-nav">
    <nav class="nav">
      <div class="nav__brand">
        <div class="nav__icon nav__icon--menu" id="nav-toggle">
          <i class="fa-solid fa-bars"></i>
        </div>

        <span href="#" class="nav__brand-logo">
          <img width="90" src="assets/images/logo-name.png" alt="">
        </span>
      </div>

      <hr>

      <ul class="nav__list">
        <li class="nav__item">
          <a href="#" class="nav__link active" id="js__change">
            <i class="fa-solid fa-house nav__icon"></i>
            <span class="nav__name">Dashboard</span>
          </a>
        </li>

        <li class="nav__item">
          <a href="#" class="nav__link" id="js__change">
            <i class="fa-solid fa-comments nav__icon"></i>
            <span class="nav__name">Messenger</span>
          </a>
        </li>

        <li class="nav__item">
          <a href="#" class="nav__link" id="js__change">
            <i class="fa-solid fa-folder nav__icon"></i>
            <span class="nav__name">Projects</span>
            <i class="fa-solid fa-chevron-down toggle-icon"></i>
          </a>

          <ul class="nav__drop">
            <div class="padding">
              <li>
                <a href="#" id="js__change"><a href="#">Data</a></a>
              </li>
              <li>
                <a href="#" id="js__change"><a href="#">Group</a></a>
              </li>
              <li>
                <a href="#" id="js__change"><a href="#">Members</a></a>
              </li>
            </div>
          </ul>
        </li>

        <li class="nav__item">
          <a href="#" class="nav__link" id="js__change">
            <i class="fa-solid fa-chart-pie nav__icon"></i>
            <span class="nav__name">Analytics</span>
          </a>
        </li>

        <li class="nav__item">
          <a href="#" class="nav__link" id="js__change">
            <i class="fa-solid fa-user-plus nav__icon"></i>
            <span class="nav__name">Team</span>
            <i class="fa-solid fa-chevron-down toggle-icon"></i>
          </a>

          <ul class="nav__drop">
            <div class="padding">
              <li>
                <a href="#" id="js__change"><a href="#">Data</a></a>
              </li>
              <li>
                <a href="#" id="js__change"><a href="#">Group</a></a>
              </li>
              <li>
                <a href="#" id="js__change"><a href="#">Members</a></a>
              </li>
            </div>
          </ul>
        </li>

        <li class="nav__item">
          <a href="#" class="nav__link" id="js__change">
            <i class="fa-solid fa-gear nav__icon"></i>
            <span class="nav__name">Settings</span>
          </a>
        </li>
      </ul>

      <hr>

      <div class="nav__user">
        <div class="nav__user-image">
          <img src="https://wallpapercave.com/uwp/uwp358219.jpeg" alt="profile">
        </div>

        <div class="nav__user-info">
          <div class="nav__user-info-name">Jane Doe</div>
          <div class="nav__user-info-email">janedoe@gmail.com</div>
        </div>
      </div>
    </nav>
  </div>
</template>

<script>
export default {
  mounted() {
            // -------- toggle sub menus --------
    const navLinks = document.querySelectorAll(".nav__link");

    navLinks.forEach((link) => link.addEventListener("click", drop));

    function drop() {
      const subMenu = this.nextElementSibling;
      if (subMenu) {
        // if sub menu exists
        if (subMenu.style.height == "0px" || subMenu.style.height == "") {
          subMenu.style.height = subMenu.scrollHeight + "px";
          // open side nav
          sideNav.style.width = "14rem";
        } else {
          subMenu.style.height = "0px";
        }
      }
    }

    // --------- Toggle Side Nav --------
    const menuBtn = document.querySelector("#nav-toggle");
    const sideNav = document.querySelector("#side-nav");

    menuBtn.addEventListener("click", toggleSideNav);
    function toggleSideNav() {
      if (sideNav.style.width == "5.6rem" || sideNav.style.width == "") {
        sideNav.style.width = "14rem";
      } else {
        // close side nav
        sideNav.style.width = "5.6rem";
        // close all opened sub menus
        document.querySelectorAll('.nav__drop').forEach(drop => drop.style.height = '0px');

      }
    }
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