<template>
    <User-List 
    :members="members" 
    :params="params" 
    :loading="loadingChild"
    :pagination="pagination"
    @sort-data="sortData"
    @goto-page="gotoPage">
    </User-List>
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
        }
    },
}
</script>