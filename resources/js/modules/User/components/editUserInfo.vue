<template>
    <div class="user-info__detail">
        <div class="user__info">
            <div class="info__login__name user-info">
                Member ID
            </div>
            <div class="info__login__name__detail info-detail">
                <input disabled type="text" class="mod__input" v-model="memberInfo.member_login_name">
            </div>
        </div>
        <div class="user__info">
            <div class="info__name user-info">
                Member name
            </div>
            <div class="info__name__detail info-detail">
                <input type="text" class="mod__input" v-model="memberInfo.member_name">
            </div>
        </div>
        <div class="user__info">
            <div class="info__member__password user-info">
                Member password
            </div>
            <div class="info__member__password__detail info-detail">
                <input type="password" class="mod__input" v-model="memberInfo.member_password">
            </div>
        </div>
        <div class="user__info">
            <div class="info__member__confirm__password user-info">
                Member confirm password
            </div>
            <div class="info__member__confirm__password__detail info-detail">
                <input type="password" class="mod__input" v-model="memberInfo.member_confirm_password">
            </div>
        </div>
        <div class="user__info">
            <div class="info__email user-info">
                Member email
            </div>
            <div class="info__email__detail info-detail">
                <input type="text" class="mod__input" v-model="memberInfo.member_email">
            </div>
        </div>
        <div class="user__info">
            <div class="info__phone__mobile user-info">
                Member phone mobile
            </div>
            <div class="info__phone__mobile__detail info-detail">
                <input type="text" class="mod__input" v-model="memberInfo.member_phone_mobile">
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            member: {
                type: Object,
                default: {}
            }
        },
        data() {
            return {
                memberInfo: this.member
            }
        }
    }
</script>
