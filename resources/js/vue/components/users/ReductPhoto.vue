<template>
    <div class="lk-photo">
        <!-- <span class="reduct-icon"></span> -->
        <div class="photo">
            <img width="172" height="172" :src="totalInfo.avatar" alt="">
        </div>
        <div class="load-new-avatar">
            
            <input type="file" id="load" ref="file" @change="handleFileUpload()">
            <label for="load" class="load-new-avatar">
                <img src="/images/icons/load-cloud-blue.svg" alt="">
                Загрузить новое фото
            </label>
        </div>

    </div>
</template>
    
<script>
export default {
	name: 'ReductPhoto',
    props: [
        'totalInfo'
    ],
    data () {
        return {
            file: '',
			image: '',
        }
    },
    methods: {
        // Получение файла аватарки
        handleFileUpload() {
            this.file = this.$refs.file.files[0];
            this.image = URL.createObjectURL(this.file);
            this.submitFile();
        },

        // Загрузка аватара
        submitFile() {
            var vm = this,
                formData = new FormData();
            formData.append('type', 'Photo');
            formData.append('data', vm.file);
            axios
                .post(route('api.users.show.update', vm.$parent.user), formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                })
                .then(response => {
                    // this.userAvatar = response.data.avatar;
                    vm.$parent.getUser(vm.$parent.user);
                })
                .catch(error => {
                    console.error(error)
                });
        },
    }

}
</script>