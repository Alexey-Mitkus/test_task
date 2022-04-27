<template>
    <div class="lk-main" v-if="isload">

        <components-users-onboarding />

        <!-- left side -->
        <div class="lk-left-coll">

            <!-- Аватар + личная информация -->
            <components-users-lk-photo :key="'update_lk_photo_' + updateKey" v-if="!reductTotalInfo" @totalReduct="onTotalReduct" :totalInfo="totalInfo" :status="status" />

            <!-- Загрузка аватара -->
            <components-users-reduct-photo :key="'reduct_photo_' + updateKey" v-if="reductTotalInfo" :totalInfo="totalInfo" ref="userreductphoto" />

            <!-- Прогрессбар -->
            <components-users-lk-progress-bar :key="'update_lk_progress_bar_' + updateKey" :progress="progress" />

        </div>
        <!-- right side -->
        <div class="lk-right-coll">

            <!-- Редактирование общей информации -->
            <components-users-reduct-total-info :key="'reduct_total_info_' + updateKey" v-if="reductTotalInfo" @totalReduct="onTotalReduct" :totalInfo="totalInfo" :status="status" />

            <!-- Контактная инфомация -->
            <components-users-lk-contact-info :key="'update_lk_contact_info_' + updateKey" v-if="!reductContactInfo" @contactReduct="onContactReduct" :contactInfo="contactInfo" :status="status" />

            <!-- Контактная инфомация редактирование -->
            <components-users-reduct-contact-info :key="'reduct_lk_contact_info_' + updateKey" v-if="reductContactInfo" @contactReduct="onContactReduct" :contactInfo="contactInfo" />

            <!-- Работа -->
            <components-users-lk-job-info :key="'update_job_info_' + updateKey" v-if="!reductJobInfo" @jobReduct="onJobReduct" :job="job" :status="status" />
            
            <!-- Работа редактирование -->
            <components-users-reduct-job-info :key="'reduct_job_info_' + updateKey" v-if="reductJobInfo" @jobReduct="onJobReduct" :job="job" />

            <!-- Опыт проектной деятельности -->
            <components-users-lk-project-management :key="'update_project_management_' + updateKey" v-if="!reductProjectInfo" @projectReduct="onProjectReduct" :project="project" :status="status" />

            <!--  -->
            <components-users-reduct-project-info :key="'reduct_project_management_' + updateKey" v-if="reductProjectInfo" @projectReduct="onProjectReduct" :project="project" />

            <!-- Образование -->
            <components-users-lk-education :key="'update_lk_education_' + updateKey" v-if="!reductEducationInfo" @educationReduct="onEducationReduct" :educationArr="education" :status="status" />

            <components-users-reduct-education-info :key="'reduct_lk_education_' + updateKey" v-if="reductEducationInfo" @educationReduct="onEducationReduct" :educationArr="education" />

        </div>

    </div>
</template>
<script>
export default {
	name: 'index',
    props:[
        'data',
        'status'
    ],
    data () {
        return {
            // status отвечает за то, кто просматривает странцу: 
            // user - это личная старница пользователя
            // viewer - это когда участник просматрвает страницу
            // admin - это когда администратор просматривает страницу пользователя

            isload: false,
            user: [],

            // состояния редактирвания 
            reductTotalInfo:     false,
            reductContactInfo:   false,
            reductJobInfo:       false,
            reductProjectInfo:   false,
            reductEducationInfo: false,

            // Основная информация + avatar
            totalInfo: [],

            // прогресс бар
            progress: 0,

            // Контактная инофрмация
            contactInfo: [],

            // работа 
            job: [],

            // проектное управление
            project: [],

            // образование
            education: [],

            updateKey: 0,

            userFields:[]
        }
    },
    mounted()
    {
        var vm = this;
        vm.isload = false;
        vm.getContactsFields();
        vm.getUser(vm.data);
    },
    methods: {
        getUser(curruser)
        {
            var vm = this;
			axios
                .get(route('api.users.show.index', curruser), {
                    params:{
                        type: 'fullData'
                    }
                })
                .then((response) =>{
                    vm.user = response.data.data;
                    vm.$nextTick(() => {
                        vm.generatedUserData();
                        vm.isload = true;
                    });
                })
                .catch(function(error){
                    console.error(error);
                });
        },
        getContactsFields()
        {
            var vm = this;
			axios
                .get(route('api.users.field.index'))
                .then((response) =>{
                    vm.userFields = response.data.data.filter((field) => {
                        return field.group_id == 1 || field.group_id == 2;
                    });
                })
                .catch(function(error){
                    console.error(error);
                });
        },
        generatedUserData()
        {
            var vm = this;
            
            vm.$set(this, 'totalInfo', {
                avatar: !vm.lodash.isEmpty(vm.user.avatar) ? vm.user.avatar : '/images/avatar.png',
                firstName: ( !vm.lodash.isEmpty(vm.user.first_name) ? vm.user.first_name.pivot.value : null ),
                lastName: ( !vm.lodash.isEmpty(vm.user.last_name) ? vm.user.last_name.pivot.value : null ),
                middleName: ( !vm.lodash.isEmpty(vm.user.middle_name) ? vm.user.middle_name.pivot.value : null ),
                status: !vm.lodash.isEmpty(vm.user.roles) && collect(vm.user.roles).count() ? collect(vm.user.roles).first().title : 'Участник сообщества',
                birthdate: ( !vm.lodash.isEmpty(vm.user.birth_date) ? moment(vm.user.birth_date.pivot.value).format('DD.MM.YYYY') : null ),
                showBirthYear: ( !vm.lodash.isEmpty(vm.user.birth_date) ? ( vm.user.birth_date.pivot.is_show == 1 ? false : true ) : false ),
            });

            vm.$set(this, 'contactInfo', collect(vm.user.fields));
            vm.$set(this, 'job', vm.user.job);
            vm.$set(this, 'project', vm.user.managment);
            vm.$set(this, 'education', vm.user.educations);
            vm.$set(this, 'progress', vm.user.rating);
            
            vm.$nextTick(() => {
                vm.updateKey++;
            });
            
        },
        onTotalReduct( data ) {
            this.reductTotalInfo = data.reductTotalInfo;
        },

        onContactReduct( data ) {
            this.reductContactInfo = data.reductContactInfo;
        },

        onJobReduct( data ) {
            this.reductJobInfo = data.reductJobInfo;
        },

        onProjectReduct( data ) {
            this.reductProjectInfo = data.reductProjectInfo;
        },

        onEducationReduct( data ) {
            this.reductEducationInfo = data.reductEducationInfo;
        }
    }
}

</script>