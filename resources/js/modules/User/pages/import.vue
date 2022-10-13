<template>
    <div class="import__member">
        <div class="mod__user_button">
            <button type="button" @click="exportCSV" class="mod__btn btn-back">
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
        @upload-file="uploadFile"></User-Upload-File>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                csv: [],
                uploadFlag: false,
                file: null
            }
        },
        methods: {
            async exportCSV() {
                try {
                    const response = await axios.get(`member/export`);
                    this.csv = response.data.data ?? [];
                    this.downloadCSV(this.csv);
                } catch (error) {
                    console.log(error);
                }
            },
            formatFileName() {
                const formattedDate = new Date().toJSON().slice(0, 10).replace(/-/g, '');
                const filename = 'user_' + formattedDate.toString() + '.csv';
                this.exportedFilename = filename;
            },
            downloadCSV: function(csvData) {
                const headers = this.formatHeader();
                csvData.unshift(headers);
                const jsonObject = JSON.stringify(csvData);
                const csv = this.convertToCSV(jsonObject);
                this.formatFileName();

                const blob = new Blob([csv], {
                    'type': 'csv/plain'
                });
                const link = document.createElement('a');
                if (link.download !== undefined) {
                    link.href = window.URL.createObjectURL(blob);
                    link.download = this.exportedFilename;
                    link.click();
                    csvData.splice(0, 1);
                }
            },
            convertToCSV(objArray) {
                const array = typeof objArray !== 'object' ? JSON.parse(objArray) : objArray;
                let str = '\ufeff';
                for (let i = 0; i < array.length; i++) {
                    let line = '';
                    const rowData = array[i];
                    let count = 0;
                    for (const index in rowData) {
                        if ({}.hasOwnProperty.call(rowData, index)) {
                            count ++;
                            if (index == 'member_login_name') {
                                const idx = 'member_login_name';
                                if ((rowData[idx] == null || rowData[idx] == '') && Object.keys(rowData).length) {
                                    line += ',';
                                    continue;
                                }
                            }
                            let cell = rowData[index] !== null ? rowData[index] : '';
                            if (i == 0) {
                                line +=cell;
                            } else {
                                if (typeof cell === 'string' && cell.includes('"')) {
                                    cell = cell.replace(/\"/g, '""');
                                }
                                line += '"'+cell+'"';
                            }
                            if (line !== '' && count < Object.keys(rowData).length) {
                                line += ',';
                            }
                        }
                    }
                    str += line + '\r\n';
                }
                return str;
            },
            formatHeader() {
                return {
                    'member_code': 'member code',
                    'member_name': 'member name',
                    'member_login_name': 'member login name',
                    'member_email': 'member email',
                    'member_phone_mobile': 'member phone mobile',
                };
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
                const response = await axios.post(`member/import`);
            }
        },
    }
</script>

<style lang="scss" scoped>

</style>