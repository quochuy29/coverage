<template>
    <User-List 
    :members="members" 
    :params="params" 
    :loading="loadingChild"
    @sort-data="sortData">
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
            loadingChild: false
        }
    },
    mounted() {
        this.getMember();
    },
    methods: {
        async getMember() {
            try {
                const member = await axios.get('member', {params: this.params});
                setTimeout(() => {
                    this.members = member.data;
                    this.loadingChild = true;
                }, 50);
            } catch (error) {
                console.log(error);
            }
        },
        sortData(sortField) {
            console.log(1);
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
        }
    },
}
</script>