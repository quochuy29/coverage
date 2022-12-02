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
        <User-Upload-File
            v-if="uploadFlag"
            @close="close"
            @handle-upload-file="handleUploadFile"
            @upload-file="uploadFile">
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
                settingExportFlag: false
            }
        },
        methods: {
            openOvlSetting() {
                this.settingExportFlag = true;
            },
            close() {
                this.uploadFlag = false;
            },
            ovlUploadFile() {
                this.uploadFlag = true;
            },
            handleUploadFile(file) {
                this.file = file;
            },
            async uploadFile() {
                try {
                    const formData = new FormData();
                    formData.append('file', this.file);
                    const response = await axios.post(`member/upload-file`, formData);
                    this.importFile();
                } catch (error) {
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