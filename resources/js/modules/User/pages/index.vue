<template>
    <div class="list__content_user">
        <div class="search__user">
            <form
            v-on:submit.prevent="searchName()"
            class="search__form">
                <input type="text" class="btn-search" placeholder="Search..." v-model="searchFieldName">
                <label for="search_member_name">
                    <input id="search_member_name" class="submit" type="submit" name="submit" value="検索" />
                    <span class="icon icon__serch" >
                        <svg viewBox="0 0 12 12.001">
                            <path
                            d="M11.727,10.4,8.921,7.594A4.875,4.875,0,1,0,7.6,8.922L10.4,11.727A.938.938,0,0,0,11.727,10.4ZM1.855,4.875a3,3,0,1,1,3,3A3,3,0,0,1,1.855,4.875Z"
                            transform="translate(-0.001 0)"
                            ></path>
                        </svg>
                    </span>
                </label>
            </form>
        </div>
        <User-List 
        :members="members" 
        :params="params" 
        :loading="loadingChild"
        :pagination="pagination"
        @sort-data="sortData"
        @goto-page="gotoPage">
        </User-List>
    </div>
</template>

<script>
export default {
    data() {
        return {
            members: [],
            params: {
                page: 1,
                sortField: 'member_name',
                sortType: 'asc',
                searchField: ''
            },
            searchFieldName: '',
            loadingChild: false,
            pagination: {}
        }
    },
    mounted() {
        this.getMember();
    },
    methods: {
        async getMember() {
            try {
                const member = await axios.get('member', {params: this.params});
                this.pagination = member.data;
                setTimeout(() => {
                    this.members = member.data.data;
                    this.loadingChild = true;
                }, 50);
            } catch (error) {
                console.log(error);
            }
        },
        sortData(sortField) {
            if (!this.members.length) {
                return false;
            }

            if (this.params.sortField !== sortField) {
                this.params.sortField = sortField;
                this.params.sortType = 'asc';
            } else {
                this.params.sortType = this.params.sortType === 'asc' ? 'desc' : 'asc';
            }
            this.$forceUpdate();
            this.getMember();
        },
        gotoPage(page) {
            this.params.page = page;
            this.pagination.current_page = page;
            this.$forceUpdate();
            this.getMember();
        },
        searchName() {
            this.params.searchField = this.searchFieldName;
            this.getMember();
        }
    },
}
</script>