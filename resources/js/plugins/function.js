import vue from 'vue';

vue.mixin({
    methods: {
        showToast(message) {
            const id = `my-toast-${Date.now()}`;
            const h = this.$createElement;
            const vNodesMsg = h('div', {class: ['js-action-window', 'action-window', 'is-active']}, [
                h('button', {
                class: ['js-action-window__close', 'action-window__close'],
                on: {click: () => this.$bvToast.hide(id)}}, [
                h('svg', {attrs: 
                    {xmlns: 'http://www.w3.org/2000/svg',
                    viewBox: "0 0 24 24"}
                }, [h('g', [
                    h('path', {attrs: {fill: "none", d: "M0 0h24v24H0z"}}), 
                    h('path', {attrs: {fill: "#fff", d: "M12 10.586l4.95-4.95 1.414 1.414-4.95 4.95 4.95 4.95-1.414 1.414-4.95-4.95-4.95 4.95-1.414-1.414 4.95-4.95-4.95-4.95L7.05 5.636z"}})], 
                    )])
                ]),
                h('div', {class: ['action-window__message']}, [h('p', `${message}`)])
            ]);
    
            this.$bvToast.toast(vNodesMsg, {
                id: id,
                autoHideDelay: 5000,
                noCloseButton: true
            });
        }
    }
});