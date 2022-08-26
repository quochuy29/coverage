import changeColor from "../plugins/changeColor";
import checkLogin from "../plugins/checkLogin";
import sideBar from "../plugins/sideBar";

function load() {
    window.removeEventListener('loaded', load);
    // checkLogin.init();
    changeColor.init();
    sideBar.init();
}

export default load;
  