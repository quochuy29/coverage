/**
 * SideBar
 * モバイル用のメニューの切り替えです
 */
 class SideBar {
    hideSideNav = () => {
        this.toggleSideNav();
    };

     /**
   * 初期設定
   * @param {Object} navLinks nav link
   * @param {Object} sideNav side nav
   * @param {Object} ola main
   * @param {Object} menuBtn menu btn
   */
    constructor(navLinks, sideNav, ola, menuBtn) {
        this.navLinks = navLinks;
        this.sideNav = sideNav;
        this.ola = ola;
        this.menuBtn = menuBtn;
    }

    /**
     * SideBar start
     * @memberof sideBar
     */
    start() {
        this.navLinks.forEach((link) => link.addEventListener("click", this.drop));
        this.menuBtn.addEventListener("click", this.hideSideNav.bind(this));
    }
  
    /**
     * SideBar toggle
     */
    toggleSideNav() {
      console.log(1);
        const {sideNav, ola} = this;
        if (sideNav.style.width == "2.8rem" || sideNav.style.width == "") {
            sideNav.style.width = "12rem";
            ola.style.width = "75%"
        } else {
            // close side nav
            sideNav.style.width = "2.8rem";
            ola.style.width = "95%"
            // close all opened sub menus
            document.querySelectorAll('.nav__drop').forEach(drop => drop.style.height = '0px');

        }
    }
    drop() {
        const sideNav = document.querySelector("#side-nav");
        const ola = document.querySelector(".ola");
        const subMenu = this.nextElementSibling;
        if (subMenu) {
          // if sub menu exists
          if (subMenu.style.height == "0px" || subMenu.style.height == "") {
            subMenu.style.height = subMenu.scrollHeight + "px";
            // open side nav
            sideNav.style.width = "10rem";
            ola.style.width = "75%"
          } else {
            subMenu.style.height = "0px";
            sideNav.style.width = "2.8rem";
            ola.style.width = "94%"
          }
        }
    }
}
  
  const init = () => {
    const navLinks = document.querySelectorAll(".nav__link");
    
    if (!navLinks) {
        return;
    }
    
    const menuBtn = document.querySelector("#nav-toggle");
    if (!menuBtn) {
        return;
    }

    const sideNav = document.querySelector("#side-nav");
    if (!sideNav) {
        return;
    }
    
    const ola = document.querySelector(".ola");
    if (!ola) {
        return;
    }
  
    const sideBar = new SideBar(navLinks, sideNav, ola, menuBtn);
    sideBar.start();
  };
  
  /**
   * SideBar
   * @type {{init: init}}
   */
  const sideBar = {
    init
  };
  
  export default sideBar;  