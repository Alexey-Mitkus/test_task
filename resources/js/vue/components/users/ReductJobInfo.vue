<template>
<div class="lk-info">
    <div class="lk-info__header">
        <div class="header-title">
            <h4>Место работы</h4>
        </div>
    </div>
    
    <div class="lk-reduct-block">
        <div class="reduct-form">
        	<div v-if="medWorker" class="reduct-form__field">
                <div class="ui-kit-input">
                    <model-list-select class="form-control" name="workplace" required :list="organizations" option-value="value" option-text="text" v-model="workplace" placeholder="Ваше место работы" />
                </div>
                <div class="box-description ">Начните вводить полное наименование организации или её 
                    номер, затем выберите подходящий вариант из списка</div>
		    </div>

            <div v-else class="reduct-form__field">
                <div class="ui-kit-input"> 
                    <input class="iu-input_input" type="text" v-model.trim="workplace" required>
                    <label>Ваше место работы</label>
                </div>
            </div>

           <div class="reduct-form__field">
                <div class="ui-kit-input"> 
                    <input class="iu-input_input" type="text" v-model.trim="position" required>
                    <label>Должность</label>
                    <!-- <span class="error-icon"></span> -->
                </div>
            </div>

            <div class="reduct-form__field">
                <div class="ui-kit-input">
                    <the-mask mask="####" class="iu-input_input"
						name="year" id="year"  
						required type="text" 
						:masked="true"
                        v-model.trim="jobStart">
					</the-mask>
                    <label>Год начала работы</label>
                    <span class="calendar-icon"></span>

                    <!-- <span class="error-icon"></span> -->
                </div>
            </div>

            <!-- TAGS BLOCK -->
            <div class="reduct-form__tags">
                <div class="form__tags-title">
                    <h5 class="">Опыт:</h5>
                    <p class="m-0">Укажите через запятую дополнительные области знаний, в которых у вас есть опыт (не более 10)</p>
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
                        <label>Название тега</label>
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

            <div class="reduct-form__buttons">
                <button @click.prevent="saveSettings()" class="color-red">Сохранить изменения</button>
                <button @click.prevent="jobReduct(false)">Отменить</button>
            </div>
        </div>
    </div>
</div>
</template>
<script>
import { ModelSelect, ModelListSelect } from 'vue-search-select';
import {TheMask} from 'vue-the-mask';

export default {
	name: 'ReductJobInfo',
    props: [
        'job'
    ],
    data () {
        return {
            // список всех тагов
            totalTags: [
                'Управление проектом', 'Аналитика', 'Управление продуктом', 'Маркетинг',
                'Управление персоналом', 'Исследования', 'Организация мероприятий', 'Коммуникации',
                'Ит', 'Финансы', 'Управление поставками', 'Налоги', 'Управление изменениями'
            ],

            // поля формы
            workplace: {
			    value: '',
			    text: ''
            },
            position: '',
            jobStart: '',
            userTags: [],
            
            // доп поля
            optionsWorkplace: [], // список медучреждений
            selectedTags: [],
            selectTags: [],
            newTag: '',
            reductJobInfo: true,
            medWorker: true,
            organizations: []
        }
    },
    methods: {
        jobReduct(reduct) {
            reduct == true ? this.reductJobInfo = true : this.reductJobInfo = false;
            
            this.$emit('jobReduct', {
                reductJobInfo: this.reductJobInfo
            })
        },

        sortTags()
        {
            var vm = this;
            if( !vm.lodash.isEmpty(vm.job) && !vm.lodash.isEmpty(vm.job.userTags) )
            {
                collect(vm.job.userTags).each((tag) => {
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
                vm.selectedTags.push(vm.selectTags[index])
                vm.selectTags.splice(index, 1);
            }
        },

        delTag(index)
        {
            var vm = this;
            vm.selectTags.push(vm.selectedTags[index])
            vm.selectedTags.splice(index, 1);
        },

        addNewTag()
        {
            var vm = this;

            if( vm.selectedTags.length < 10 )
            {
                vm.selectedTags.push(vm.newTag)
                vm.newTag = '';
            }
        },

        getOrganizations()
        {
            var vm = this;
			axios.get(route('api.organizations.index'))
			.then((response) => {
				var organizationData = response.data.data;
				organizationData = organizationData.map((organization) => {
					return {
						value: organization.id,
						text: ( organization.name != organization.abbreviation ? organization.name + ' - ' + organization.abbreviation : organization.name )
					}
				});
				organizationData.unshift({
					value: 0,
					text: 'Моей организации нет в списке'
				});
				vm.organizations = organizationData;
			});
        },
        searchHospital (searchText) {
			this.searchText = searchText;
			axios.get(route('api.organizations.index'), {
				params: {
					search: searchText
				}
			})
			.then((response) => {
				var organizationData = response.data.data;
				organizationData = organizationData.map((organization) => {
					return {
						value: organization.id,
						text: ( organization.name != organization.abbreviation ? organization.name + ' - ' + organization.abbreviation : organization.name )
					}
				});

				organizationData.unshift({
					value: 0,
					text: 'Моей организации нет в списке'
				});

				this.optionsWorkplace = organizationData;
			});
		},

        saveSettings() {
            var vm = this;
            axios
                .post(route('api.users.show.update', vm.$parent.user), {
                    type: 'JobInfo',
                    data: {
                        workplace: this.workplace,
                        position: this.position,
                        jobStart: this.jobStart,
                        tags: this.selectedTags,
                    }
                })
                .then(response => {
                    vm.jobReduct(false);
                    vm.$parent.getUser(vm.$parent.user);
                })
                .catch(error => {
                    console.error(error)
                });
        }
 
    },
    mounted()
    {
        var vm = this;

        vm.getOrganizations();
        
        if( !vm.lodash.isEmpty(vm.job) )
        {
            if( !vm.lodash.isEmpty(vm.job.tags) )
            {
                vm.job.userTags = [];
                vm.job.tags.forEach(tag => {
                    vm.job.userTags.push(tag.pivot.value);
                });
            }

            vm.position = vm.job.position;
            vm.jobStart = moment(vm.job.start_at).format('YYYY');
        }

        vm.sortTags();

        if( !vm.lodash.isEmpty(vm.job) && !vm.lodash.isEmpty(vm.job.organization) )
        {
            vm.workplace = {
                text: vm.job.organization.name,
                value: vm.job.organization.id
            };
        }else{
            vm.workplace = {
			    value: '',
			    text: ''
            };
        }
    },

    components: {
		TheMask,  ModelSelect, ModelListSelect
	},
}

</script>