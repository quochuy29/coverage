<template>
    <div class="mod__modal is-active">
        <div class="mod__modal__container">
            <div class="mod__modal__overlay"></div>

            <div class="mod__modal__inner w600">
                <header class="mod__modal__header">
                    <h2 class="title">Import member</h2>

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
                        <form action="" method="">
                            <div class="import__upload__content">
                                <div class="import__upload__file">
                                    <button class="mod__btn" type="button" @click="changeFile">
                                        <label id="upload-label" for="upload-file">ファイルを選択</label>
                                    </button>
                                    <span id="name_file_import" :title="fileName">{{fileName}}</span>
                                </div>
                                <input ref="file" type="file" name="file" @click="resetFileUpload" @change="handleUploadFile($event)" style="display: none;" accept=".csv">
                            </div>

                            <div class="mod__modal__btn__unit">
                                <button class="mod__btn" type="button"
                                    @click="close"><span>Cancel</span></button>
                                <button class="mod__btn bg-color-primary" @click="uploadFile" type="button"><span>Upload</span></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'uploadFile',
        props: {
            fileName: String,
            default: 'hhaha'
        },
        data() {
            return {
                
            }
        },
        methods: {
            close() {
                this.$emit('close');
            },
            uploadFile() {
                this.$emit('upload-file');
            },
            changeFile() {
                //khi attribute for của label trùng với id của input thì khi click vào label sẽ click vào input
                this.$emit('change-file');
            },
            resetFileUpload() {
                this.$refs.file.value = '';
            },
            handleUploadFile(e) {
                this.$emit('handle-upload-file');
            }
        }
    }
</script>
