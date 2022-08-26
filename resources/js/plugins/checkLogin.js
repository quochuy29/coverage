/**
 * CheckLogin
 * モバイル用のメニューの切り替えです
 */
 class CheckLogin {
  
    onLoad = () => {
        this.check();
    };

    /**
     * CheckLogin start
     * @memberof checkLogin
     */
    start() {
      const body = document.getElementsByTagName('body')[0];
  
      if (!body) {
        return;
      }
  
      body.addEventListener('load', this.onLoad.bind(this), false);
    }

    check() {
        const auth = JSON.parse(localStorage.getItem('auth_user'));
        if (auth === null || typeof(auth) === "undefined") {
            console.log(1);
            this.$router.push({ path: '/login' })
        }
    }
  }
  
  const init = () => {
    const checkLogin = new CheckLogin();
    checkLogin.start();
  };
  
  /**
   * CheckLogin
   * @type {{init: init}}
   */
  const checkLogin = {
    init
  };
  
  export default checkLogin;  