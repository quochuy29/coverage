import vue from 'vue';
import {useToast} from 'vue-toast-notification';

vue.mixin({
    methods: {
        showToast(message) {
            const $toast = useToast();
            let instance = $toast.success(message);
        }
    }
});