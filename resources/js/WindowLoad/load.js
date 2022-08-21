import changeColor from "../plugins/changeColor";

function load() {
    window.removeEventListener('loaded', load);
    changeColor.init();
}

export default load;
  