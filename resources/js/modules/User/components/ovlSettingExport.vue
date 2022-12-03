<template>
    <div class="mod__modal is-active">
        <div class="mod__modal__container">
            <div class="mod__modal__overlay"></div>

            <div class="mod__modal__inner w600">
                <header class="mod__modal__header">
                    <h2 class="title">Export member</h2>

                    <button class="mod__modal__header__close" type="button" @click="close">
                        <span class="icon icon__cross">
                            <svg viewBox="0 0 14 14">
                                <path
                                    d="M13.588,107.586a1.4,1.4,0,1,1-1.978,1.978L7,104.957l-4.61,4.607a1.4,1.4,0,1,1-1.98-1.98l4.611-4.611L.41,98.365a1.4,1.4,0,0,1,1.98-1.98L7,101l4.611-4.611a1.4,1.4,0,1,1,1.98,1.98l-4.611,4.611Z"
                                    transform="translate(0 -95.975)"></path>
                            </svg>
                        </span>
                    </button>
                </header>

                <div class="mod__modal__content">
                    <div class="import__upload">
                        <div class="import__upload__content">
                            <div class="import__upload__file">
                                <span class="form-check-span">
                                    Export deleted user
                                </span>
                                <input class="check-form" type="checkbox" v-model="user_deleted" id="user_deleted">
                                <br>
                                <span class="form-check-span">
                                    Quantity
                                </span>
                                <input type="text" class="mod__input" id="user_quantity" v-model="user_quantity">
                            </div>
                        </div>
                    </div>
                    <div class="mod__modal__btn__unit">
                        <button class="mod__btn" type="button"
                            @click="close"><span>Cancel</span></button>
                        <button class="mod__btn bg-color-primary" @click="exportCSV" type="button"><span>Export</span></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'ovlSettingExport',
        data() {
            return {
                user_deleted: false,
                user_quantity: 0
            }
        },
        methods: {
            close() {
                this.$emit('closeOvl');
            },
            async exportCSV() {
                try {
                    const response = await axios.get(`member/export?user_quantity=${this.user_quantity}&user_deleted=${this.user_deleted}`);
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
                    'member_password': 'member password',
                    'member_email': 'member email',
                    'member_phone_mobile': 'member phone mobile',
                };
            },
        }
    }
</script>

<style lang="less" scoped>
.import__upload__file {
    display: block;
    .mod__input {
        height: 35px;
        width: 110px;
        border-radius: 5px;
        outline: none;
        border: 1px solid #ddd;
        padding-left: 10px;
    }
}
</style>