<template>
    <div class="import__member">
        <div class="mod__user_button">
            <button type="button" @click="openOvlSetting" class="mod__btn btn-back">
                Export
            </button>
            <button type="button" @click="ovlUploadFile" class="mod__btn btn-delete__user">
                Import
            </button>
        </div>
        <User-Upload-File ref="overlay-upload"
            v-if="uploadFlag"
            :fileName="fileName"
            @close="close"
            @handle-upload-file="handleUploadFile"
            @upload-file="uploadFile"
            @change-file="changeFile">
        </User-Upload-File>
        <User-Ovl-Setting-Export
            v-if="settingExportFlag"
            @closeOvl="closeOvl">
        </User-Ovl-Setting-Export>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                csv: [],
                uploadFlag: false,
                file: null,
                settingExportFlag: false,
                fileName: null,
                clicked: false
            }
        },
        methods: {
            openOvlSetting() {
                this.settingExportFlag = true;
            },
            close() {
                if (this.clicked) {
                    return false;
                }

                this.uploadFlag = false;
                this.file = '';
            },
            ovlUploadFile() {
                this.fileName = '';
                this.uploadFlag = true;
            },
            handleUploadFile() {
                this.file = this.$refs['overlay-upload'].$refs.file.files[0];
                this.fileName = _.cloneDeep(this.file.name);
            },
            changeFile() {
                this.$refs['overlay-upload'].$refs.file.click();
            },
            async uploadFile() {
                if (this.clicked) {
                    return false;
                }
                this.clicked = true;
                this.overlayFlag = true;
                try {
                    const formData = new FormData();
                    if (this.file !== '') {
                        formData.append('file', this.file);
                    }
                    const response = await axios.post(`member/upload-file`, formData);
                    if (response !== undefined && response.status === 200) {
                        this.overlayFlag = false;
                        this.file = '';
                        this.importFile();
                    }
                } catch (error) {
                    this.clicked = false;
                    alert(error.response.data.error);
                }
            },
            async importFile() {
                try {
                    const response = await axios.post(`member/import`);
                } catch(error) {
                    const url = error.response.data.url_error;
                    const fileName = error.response.data.file_name;
                    this.downloadErrorLog(url, fileName);
                }
                this.clicked = false;
            },
            downloadErrorLog(url, fileName) {
                const a = document.createElement('a');
                a.href = url;
                a.download = fileName;
                document.body.appendChild(a);
                a.click();
                document.body.removeChild(a);
            },
            closeOvl() {
                this.settingExportFlag = false;
            }
        },
    }
</script>

<style lang="scss" scoped>

</style>