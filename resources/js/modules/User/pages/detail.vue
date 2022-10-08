<template>
    <div v-if="loading" class="user__detail">
        <div class="mod__user_button">
            <button type="button" @click="back" class="mod__btn btn-back">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-reply" viewBox="0 0 16 16">
                    <path d="M6.598 5.013a.144.144 0 0 1 .202.134V6.3a.5.5 0 0 0 .5.5c.667 0 2.013.005 3.3.822.984.624 1.99 1.76 2.595 3.876-1.02-.983-2.185-1.516-3.205-1.799a8.74 8.74 0 0 0-1.921-.306 7.404 7.404 0 0 0-.798.008h-.013l-.005.001h-.001L7.3 9.9l-.05-.498a.5.5 0 0 0-.45.498v1.153c0 .108-.11.176-.202.134L2.614 8.254a.503.503 0 0 0-.042-.028.147.147 0 0 1 0-.252.499.499 0 0 0 .042-.028l3.984-2.933zM7.8 10.386c.068 0 .143.003.223.006.434.02 1.034.086 1.7.271 1.326.368 2.896 1.202 3.94 3.08a.5.5 0 0 0 .933-.305c-.464-3.71-1.886-5.662-3.46-6.66-1.245-.79-2.527-.942-3.336-.971v-.66a1.144 1.144 0 0 0-1.767-.96l-3.994 2.94a1.147 1.147 0 0 0 0 1.946l3.994 2.94a1.144 1.144 0 0 0 1.767-.96v-.667z"/>
                </svg>
            </button>
            <button type="button" @click="deleteUser" class="mod__btn btn-delete__user">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16"> <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/>
                    <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/>
                </svg>
            </button>
        </div>
        <div class="user__detail__info">
            <div class="user__avatar">
                <img class="avatar" :src="(avatar == null) ? `${member.member_avatar}` : avatar" alt="">
            </div>
            <div class="user__info">
                <div class="name">
                    {{ member.member_name }}
                </div>
                <div class="user__email__phone">
                    <div class="email">
                        {{ member.member_email }}
                    </div>
                    <div class="phone">
                        {{ member.member_phone_mobile }}
                    </div>
                </div>
            </div>
        </div>
        <div class="tab-user">
            <div class="action-tab-user edit" v-if="isEdit">
                <button type="button" @click="changeEdit" class="mod__btn btn-cancel__user">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                        <g><path fill="none" d="M0 0h24v24H0z"/> <path d="M5.828 7l2.536 2.536L6.95 10.95 2 6l4.95-4.95 1.414 1.414L5.828 5H13a8 8 0 1 1 0 16H4v-2h9a6 6 0 1 0 0-12H5.828z"/></g>
                    </svg>
                </button>
                <button type="button" @click="saveEdit" class="mod__btn btn-edit__user">
                    <svg width="24" stroke-width="1.5" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M20 12V5.74853C20 5.5894 19.9368 5.43679 19.8243 5.32426L16.6757 2.17574C16.5632 2.06321 16.4106 2 16.2515 2H4.6C4.26863 2 4 2.26863 4 2.6V21.4C4 21.7314 4.26863 22 4.6 22H11" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M8 10H16M8 6H12M8 14H11" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M16 5.4V2.35355C16 2.15829 16.1583 2 16.3536 2C16.4473 2 16.5372 2.03725 16.6036 2.10355L19.8964 5.39645C19.9628 5.46275 20 5.55268 20 5.64645C20 5.84171 19.8417 6 19.6464 6H16.6C16.2686 6 16 5.73137 16 5.4Z" fill="currentColor" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M17.9541 16.9394L18.9541 15.9394C19.392 15.5015 20.102 15.5015 20.5399 15.9394V15.9394C20.9778 16.3773 20.9778 17.0873 20.5399 17.5252L19.5399 18.5252M17.9541 16.9394L14.963 19.9305C14.8131 20.0804 14.7147 20.2741 14.6821 20.4835L14.4394 22.0399L15.9957 21.7973C16.2052 21.7646 16.3988 21.6662 16.5487 21.5163L19.5399 18.5252M17.9541 16.9394L19.5399 18.5252" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </button>
            </div>
            <div class="action-tab-user view" v-else>
                <button type="button" @click="changeEdit" class="mod__btn btn-view__user">
                    <svg width="24" height="24" stroke-width="1.5" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M6 6L14 6" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"/> <path d="M6 10H18" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"/> <path d="M12 14L18 14" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"/> <path d="M12 18L18 18" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"/> <path d="M2 21.4V2.6C2 2.26863 2.26863 2 2.6 2H18.2515C18.4106 2 18.5632 2.06321 18.6757 2.17574L21.8243 5.32426C21.9368 5.43679 22 5.5894 22 5.74853V21.4C22 21.7314 21.7314 22 21.4 22H2.6C2.26863 22 2 21.7314 2 21.4Z" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"/> <path d="M18 5.4V2.35355C18 2.15829 18.1583 2 18.3536 2C18.4473 2 18.5372 2.03725 18.6036 2.10355L21.8964 5.39645C21.9628 5.46275 22 5.55268 22 5.64645C22 5.84171 21.8417 6 21.6464 6H18.6C18.2686 6 18 5.73137 18 5.4Z" fill="currentColor" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"/> <path d="M6 18V14H8V18H6Z" fill="currentColor" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </button>
            </div>
        </div>
        <User-View-User-Info v-if="!isEdit" :member="member"></User-View-User-Info>
        <User-Edit-User-Info @change-avatar="changeAvatar" v-else :member="member"></User-Edit-User-Info>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                member: {},
                loading: false,
                isEdit: false,
                avatar: null
            }
        },
        props: {
            id: {
                type: Number,
                default: null
            }
        },
        created() {
            this.getDetailMember();
        },
        methods: {
            async getDetailMember() {
                try {
                    const member = await axios.get(`member/detail/${this.id}`);
                    this.loading = true;
                    this.member = member.data;
                    if (this.member.member_avatar === '' || this.member.member_avatar === null) {
                        this.member.member_avatar = 'assets/images/271_fb902c8.jpg';
                    }
                } catch (error) {
                    console.log(error);
                }
            },
            back() {
                this.$router.push({name: 'userIndex'})
            },
            async deleteUser() {
                try {
                    const member = await axios.delete(`member/delete/${this.id}`);
                    this.showToast(member.data.message);
                    // this.$router.push({name: 'userIndex'});
                } catch (error) {
                    alert('Delete this user not successfully!');
                    console.log(error);
                }
            },
            changeEdit() {
                (!this.isEdit) ? this.isEdit = true : this.isEdit = false;
            },
            async saveEdit() {
                try {
                    const member = await axios.put(`member/edit/${this.id}`, this.member);
                    this.member = member.data
                    if (this.member.member_avatar === '' || this.member.member_avatar !== null) {
                        this.member.member_avatar = 'http://localhost:8080/assets/images/271_fb902c8.jpg';
                    }
                    this.isEdit = false;
                } catch (error) {
                    console.log(error);
                }
            },
            changeAvatar(avatar) {
                console.log(avatar);
                if (avatar !== null && avatar !== '') {
                    this.avatar = avatar;
                    this.member.member_avatar = avatar;
                }
            },
            // showToast(message) {
            //     const h = this.$createElement;
            //     const vNodesMsg = h('div', {class: ['js-action-window', 'action-window', 'is-active']}, [
            //         h('button', {
            //         class: ['js-action-window__close', 'action-window__close'],
            //         on: {click: () => this.$bvToast.hide()}}, [
            //         h('img', {attrs: {src: '/images/icon_cross.svg'}})
            //         ]),
            //         h('div', {class: ['action-window__message']}, [h('p', `${message}`)])
            //     ]);

            //     this.$bvToast.toast(vNodesMsg, {
            //         autoHideDelay: 5000,
            //         noCloseButton: true
            //     });

            // }
        }
    }
</script>
