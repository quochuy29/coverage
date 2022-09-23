<template>
    <div class="mod__pager">
        <button class="mod__pager__btn prev-end" 
        :class="{disabled: pagination.current_page === 1}" type="button" 
        @click="gotoPage(1)">
            <span class="icon icon__pager__prev__end">
                <svg viewBox="0 0 17.572 12"><g transform="translate(0 -0.15)"><path d="M.295,70.682l5,5a1,1,0,0,0,1.413,0,1,1,0,0,0,0-1.414L3.413,70.975H13a1,1,0,0,0,0-2H3.413l3.294-3.294a1,1,0,0,0-1.414-1.414l-5,5A1,1,0,0,0,.295,70.682Z" transform="translate(3.572 -63.825)"></path><rect width="2.075" height="11.76" rx="1.038" transform="translate(0 0.27)"></rect></g></svg>
            </span>
        </button>
        <button class="mod__pager__btn prev" :class="{disabled: pagination.current_page === 1}" type="button" @click="gotoPage(pagination.current_page > 1 ? (pagination.current_page - 1) : 1)">
            <span class="icon icon__pager__prev">
                <svg viewBox="0 0 13.998 12">
                    <path d="M.295,70.682l5,5a1,1,0,0,0,1.413,0,1,1,0,0,0,0-1.414L3.413,70.975H13a1,1,0,0,0,0-2H3.413l3.294-3.294a1,1,0,1,0-1.414-1.414l-5,5A1,1,0,0,0,.295,70.682Z" transform="translate(-0.002 -63.975)"></path>
                </svg>
            </span>
        </button>

        <div class="mod__pager__num">
        <span class="current">{{ pagination.from }} - {{ pagination.to }}</span>
        <span class="total">{{ pagination.total }}</span>
        </div>

        <button class="mod__pager__btn next" 
        :class="{disabled: pagination.current_page === pagination.last_page}" type="button" 
        @click="gotoPage(pagination.current_page < pagination.last_page ? (pagination.current_page + 1) : pagination.current_page)">
            <span class="icon icon__pager__next">
                <svg viewBox="0 0 13.998 12">
                    <path d="M13.707,70.682l-5,5a1,1,0,0,1-1.413,0,1,1,0,0,1,0-1.414l3.294-3.293H1a1,1,0,0,1,0-2h9.587L7.295,65.682a1,1,0,0,1,1.414-1.414l5,5A1,1,0,0,1,13.707,70.682Z" transform="translate(-0.002 -63.975)"></path>
                </svg>
            </span>
        </button>
        <button class="mod__pager__btn next-end" 
        :class="{disabled: pagination.current_page === pagination.last_page}" type="button" 
        @click="gotoPage(pagination.last_page)">
            <span class="icon icon__pager__next__end">
                <svg viewBox="0 0 17.572 12">
                    <path d="M13.707,70.682l-5,5a1,1,0,0,1-1.413,0,1,1,0,0,1,0-1.414l3.294-3.293H1a1,1,0,0,1,0-2h9.587L7.295,65.682a1,1,0,0,1,1.414-1.414l5,5A1,1,0,0,1,13.707,70.682Z" transform="translate(-0.002 -63.975)"></path>
                    <rect width="2.075" height="11.76" rx="1.038" transform="translate(15.497 0.12)"></rect>
                </svg>
            </span>
        </button>
    </div>
</template>

<script>
    export default {
        name: 'ListPagination',
        props: {
            pagination: Object,
            default: {}
        },
        methods: {
            gotoPage(page) {
                if (this.pagination.current_page === page) {
                    return false;
                }
                this.$emit('goto-page', page);
            }
        }
    };
</script>

