/**
 * ChangeColor
 * モバイル用のメニューの切り替えです
 */
 class ChangeColor {
    static TOGGLE_CLASS = 'selected';
  
    /**
     * menu button click
     * @param {MouseEvent} event click event
     */
    onClick = (event) => {
        this.toggle(event);
    };

     /**
   * 初期設定
   * @param {Object} element menu btn
   */
    constructor(element) {
        this.element = element;
    }

    /**
     * ChangeColor start
     * @memberof changeColor
     */
    start() {
      const body = document.getElementsByTagName('body')[0];
  
      if (!body) {
        return;
      }
  
      body.addEventListener('click', this.onClick.bind(this), false);
    }
  
    /**
     * ChangeColor toggle
     */
    async toggle(event) {
        if(event.target.closest('#js__change')){
            await this.resetColor(event);
            this.addColor(event);
        }
    }

    resetColor(event){
        const element  = document.querySelectorAll('#js__change');
        element.forEach(el => {
            el.style.color = '';
        });
    }

    addColor(event) {
        event.target.closest('#js__change').style.color = '#94d41c';
    }
  }
  
  const init = () => {
    const menuBtn = document.getElementById('js__change');
  
    if (!menuBtn) {
      return;
    }
  
    const changeColor = new ChangeColor(menuBtn);
    changeColor.start();
  };
  
  /**
   * ChangeColor
   * @type {{init: init}}
   */
  const changeColor = {
    init
  };
  
  export default changeColor;  