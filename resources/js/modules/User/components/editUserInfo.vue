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
            <div class="info__name user-info">
                Member avatar
            </div>
            <div class="info__email__detail info-detail">
                <input type="file" @change="changeAvatar" class="input__file">
                <div v-if="imageData != null">                     
                    <button class="mod__btn" @click="onUpload">Upload</button>
                </div>   
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
    import firebase from 'firebase';
    
    export default {
        props: {
            member: {
                type: Object,
                default: {}
            }
        },
        data() {
            return {
                memberInfo: this.member,
                uploadValue: 0,
                picture: null,
                imageData: null
            }
        },
        methods: {
            changeAvatar(event) {
                this.uploadValue = 0;
                this.picture = null;
                this.imageData = event.target.files[0];
            },
        
            onUpload() {
                const storageRef = firebase.storage().ref(`${this.imageData.name}`).put(this.imageData);
                storageRef.on(`state_changed`, snapshot =>
                {
                    this.uploadValue = (snapshot.bytesTransferred/snapshot.totalBytes)*100;
                }, 
                error => {
                    console.log(error.message)
                },
                () => {
                    this.uploadValue = 100;
                    storageRef.snapshot.ref.getDownloadURL().then(async (url) => {
                        this.picture = await url;
                        this.$emit('change-avatar', this.picture);
                    });
                });

            }
        },
    }
</script>
<style lang="less">
    .user-info__detail {
        margin-bottom: 40px;
    }

    .input__file {
        line-height: 27px;
    }
</style>
