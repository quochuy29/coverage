import changeColor from "../plugins/changeColor";
import sideBar from "../plugins/sideBar";

function load() {
    window.removeEventListener('loaded', load);
    changeColor.init();
    sideBar.init();
}

export default load;
  