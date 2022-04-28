<template>
<div class="motivation-callange-item">
    <div class="callange-item" :class="{'callange-item__active': formOpen}">
        <div class="callange-item__title">
            <img :src="challenge.image" alt="">
            
            <div>
                <span>{{ challenge.points }} баллов</span>
                <p>{{ challenge.title }}</p>
            </div>
        </div>
        <button @click="formOpen = true">Подтвердить</button>
    </div>

    <div v-if="formOpen" class="callange-form">
        <form action="">
            <label class="form-title">Введите ссылку на материал или прикрепите необходимый файл<span>*</span></label>
            <input type="hidden" name="id" :value="challenge.id">
            <div class="ui-kit-input">
                <input type="text" class="iu-input_input" v-model.trim="link" required>
                <label for="">https://</label>
            </div>

            <!-- loaded files -->
            <!-- user loaded file name -->
            <div v-if="loadedFileName" class="added-file">
                <p>Загружен: {{ loadedFileName }}</p>
                <img @click="delLoadedFile()" src="/images/knowledge-base/knowleadge-base_trash-icon.svg" alt="">
            </div>

            <!-- add file -->
            <div class="add-file mb-52">
                <label for="file"><img src="/images/knowledge-base/knowleadge-load-icon.svg" alt="">Добавить файл</label>
                <input type="file" id="file" ref="file" name="file" v-on:change="getFileName()">
                <span>(Формат: pdf, xls, jpg, png, doc; Максимальное количество файлов -1)</span>
            </div>

            <label class="form-title">Поле для вашего сообщения</label>
            
            <!-- text aria -->
            <div tabindex="1" class="form-textaria">
                <textarea name="" id="" cols="30" rows="2"
                    placeholder="Напишите свое сообщение" v-model.trim="message">
                </textarea>
            </div>

            <div class="form-buttons">
                <button @click.prevent="sendMessage()" class="button-purple">Отправить</button>
                <button @click="formOpen = false">Отменить</button>
            </div>
        </form>
    </div>

</div>
</template>

<script>



export default {
    name: 'MotivationChallengeItem',
    props: {
        'challenge': Object,
    },
    data () {
        return {
            formOpen: false,
            loadedFileName: '',
            file: null,
            link: '',
            message: '',
            id: 0,
        }
    },
    methods: {
        // file name
        getFileName() {
            if(this.$refs.file) {
                this.loadedFileName = this.$refs.file.files[0].name;
                this.handleFileUpload();
            }
        },
        // file
        handleFileUpload(){
            this.file = this.$refs.file.files[0];
        },
        
        delLoadedFile() {
            this.loadedFileName = ''
            this.file = null;
        },

        // отправка заявки 
        sendMessage() {
            let formData = new FormData();

            formData.append('message', this.message);
            formData.append('link', this.link);
            formData.append('file', this.file);
            formData.append('id', this.id);

            console.log(formData);

            // axios.post(route('/'), formData, {
            //     headers: {
            //         'Content-Type': 'multipart/form-data'
            //     }
            // })
            // .then(res => {
                
            // })
            
        }
    }, 
    watch: {
        challenge () {
            this.formOpen = false;
            this.link = '';
            this.message = '';
            this.file = null;
            this.loadedFileName = '';
        }
    }

}

</script>