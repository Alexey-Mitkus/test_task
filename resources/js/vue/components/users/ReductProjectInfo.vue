<template>
<div class="lk-info">
    <div class="lk-info__header">
        <div class="header-title">
            <h4>Проектное управление</h4>
        </div>
    </div>
    
    <div class="lk-reduct-block">
        <div class="reduct-form">
            <div class="reduct-form__field">
                <div class="ui-kit-input">
                    <model-list-select class="iu-input_input" :list="roles" option-value="name" option-text="name" v-model="role" placeholder="Роль в проектной деятельности" required />
                </div>
            </div>


           <div class="reduct-form__field">
                <div class="ui-kit-input"> 
                    <input class="iu-input_input" type="text" v-model.trim="experience" required>
                    <label>Стаж проектной деятельности</label>
                    <!-- <span class="error-icon"></span> -->
                </div>
            </div>

            <!-- TAGS BLOCK -->
            <div class="reduct-form__tags">
                <div class="form__tags-title">
                    <h5 class="m-0">Используемые методологии:</h5>
                    <span></span>
                </div>

                <div class="form__tags-selected">
                    <div v-for="(tag, index) in selectedTags" class="tag" :key="tag" @click="delTag(index)">
                        <span>{{ tag }}</span>
                        <img src="/images/icons/close.svg" alt="">  
                    </div>
                </div>

                <div class="reduct-form__field">
                    <div class="ui-kit-input">
                        <input class="iu-input_input" type="text" v-model="newTag" @change="addNewTag()" required>
                        <label>Напишите название тега</label>
                        <!-- <span class="error-icon"></span> -->
                    </div>
                </div>

                <div class="form__tags-select">
                    <div v-for="(tag, index) in selectTags" class="tag" :key="tag" @click="addTag(index)">
                        <span>{{ tag }}</span>
                        <img src="/images/icons/add-icon.svg" alt="">  
                    </div>
                </div>
            </div>


            <div class="reduct-form__field">
                <div class="ui-kit-textaria" tabindex="0">
                    <textarea class="ui-textaria" name="" id="" cols="30" rows="10" v-model.trim="get" required></textarea>
                    <label class="theme">Что вы хотите получить в Проектном сообществе?</label>
                </div>
            </div>

            <div class="reduct-form__field">
                <div class="ui-kit-textaria" tabindex="0">
                    <textarea class="ui-textaria" name="" id="" cols="30" rows="10" v-model.trim="share" required></textarea>
                    <label class="theme">Чем вы готовы поделиться с Проектным сообществом?</label>
                </div>
            </div>
            

            <div class="reduct-form__buttons">
                <button @click.prevent="saveSettings()" class="color-red">Сохранить изменения</button>
                <button @click.prevent="projectReduct(false)">Отменить</button>
            </div>
        </div>
    </div>
</div>
</template>
<script>
import { ModelListSelect } from 'vue-search-select';
export default {
	name: 'ReductProjectInfo',
    props: [
        'project'
    ],
    data () {
        return {
            totalTags: [
                'Scrum', 'Agile', 'Классический проектный менеджмент', 'Lean', 'Kanban', 'Waterfall',
                'Six Sigma', 'PRINCE 2', 'IPM', 'PRISM', 'Crystal', 'XP', 'Пока никакие, но хочу научиться'
            ],
            
            roles: [],
            role: '',
            experience: '',
            get: '',
            share: '',
            userTags: [],

            selectedTags: [],
            selectTags: [],
            newTag: '',
            reductProjectInfo: true,
        }
    },
    methods: {
        getRoles()
        {
            var vm = this;
			axios
                .get(route('api.users.managment.roles'))
                .then((response) => {
                    vm.roles = response.data.data;
                });
        },
        projectReduct(reduct) {
            reduct == true ? this.reductProjectInfo = true : this.reductProjectInfo = false;
            
            this.$emit('projectReduct', {
                reductProjectInfo: this.reductProjectInfo
            })
        },

        sortTags()
        {
            var vm = this;
            if( !vm.lodash.isEmpty(vm.project) && !vm.lodash.isEmpty(vm.project.userTags) )
            {
                collect(vm.project.userTags).each((tag) => {
                    vm.selectedTags.push(tag);
                });
            }

            collect(vm.totalTags).each((tag) => {
                if( vm.lodash.isEmpty(vm.selectedTags) || !vm.lodash.isEmpty(vm.selectedTags) && !collect(vm.selectedTags).contains(tag) )
                {
                    vm.selectTags.push(tag);
                }
            });
        },

        addTag(index)
        {
            var vm = this;
            
            if( vm.selectedTags.length < 10 )
            {
                vm.selectedTags.push(vm.selectTags[index]);
                vm.selectTags.splice(index, 1);
            }
        },

        delTag(index)
        {
            var vm = this;

            vm.selectTags.push(vm.selectedTags[index]);
            vm.selectedTags.splice(index, 1);
        },

        addNewTag()
        {
            var vm = this;

            if( vm.selectedTags.length < 10 )
            {
                vm.selectedTags.push(vm.newTag);
                vm.newTag = '';
            }
        },

        saveSettings()
        {
            var vm = this;
            axios
                .post(route('api.users.show.update', vm.$parent.user), {
                    type: 'ProjectInfo',
                    data: {
                        role: vm.role,
                        experience: vm.experience,
                        get: vm.get,
                        share: vm.share,
                        selectedTags: vm.selectedTags
                    }
                })
                .then(response => {
                    vm.projectReduct(false);
                    vm.$parent.getUser(vm.$parent.user);
                })
                .catch(error => {
                    console.error(error)
                });
        },

        CountForm: function(number, titles){
            var number = Math.abs(number);
            
            if(Number.isInteger(number))
            {
                var cases = [2, 0, 1, 1, 1, 2];  
                return titles[ (number%100>4 && number%100<20)? 2 : cases[(number%10<5)?number%10:5] ];
            }
            return titles[1];
        }
    },
    mounted()
    {
        var vm = this;

        vm.getRoles();

        if( !vm.lodash.isEmpty(vm.project.methodologies) )
        {
            vm.project.userTags = [];
            vm.project.methodologies.forEach(tag => {
                vm.project.userTags.push(tag.pivot.value);
            });
        }
        vm.role = ( !vm.lodash.isEmpty(vm.project.role) ? vm.project.role.name : null );
        vm.experience = ( !vm.lodash.isEmpty(vm.project.start_at) && !vm.lodash.isEmpty(vm.project.end_at) ? vm.project.experience : '' );
        vm.get = vm.project.getting;
        vm.share = vm.project.share;
        vm.sortTags();

    },
    components: {
        ModelListSelect
    }
}

</script>