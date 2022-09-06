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
   * @param {Boolean} response menubtn
   */
    constructor(navLinks, sideNav, ola, menuBtn, response) {
        this.navLinks = navLinks;
        this.sideNav = sideNav;
        this.ola = ola;
        this.menuBtn = menuBtn;
        this.response = response;
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
        const {sideNav, ola, response} = this;
        if (sideNav.style.width == "2.8rem" || sideNav.style.width == "") {
            sideNav.style.width = "12rem";
            if(response){
              ola.style.width = "calc(100% - 12rem)";
            }
        } else {
            // close side nav
            sideNav.style.width = "2.8rem";
            if(response){
              ola.style.width = "calc(100% - 2.8rem)";
            }
            // close all opened sub menus
            document.querySelectorAll('.nav__drop').forEach(drop => drop.style.height = '0px');
        }
    }

    /**
     * drop sidebar
     */
    drop() {
        const response = window.matchMedia("(max-width: 769px)").matches;
        const sideNav = document.querySelector("#side-nav");
        const ola = document.querySelector(".ola");
        const subMenu = this.nextElementSibling;
        if (subMenu) {
          // if sub menu exists
          if (subMenu.style.height == "0px" || subMenu.style.height == "") {
            subMenu.style.height = subMenu.scrollHeight + "px";
            // open side nav
            sideNav.style.width = "12rem";
            if(response){
              ola.style.width = "calc(100% - 12rem)";
            }
          } else {
            subMenu.style.height = "0px";
            // if (response) {
            //   sideNav.style.width = "12rem";
            //   if(response){
            //     ola.style.width = "calc(100% - 2.8rem)";
            //   }
            // }
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

    const response = window.matchMedia("(max-width: 769px)").matches;
  
    const sideBar = new SideBar(navLinks, sideNav, ola, menuBtn, response);
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