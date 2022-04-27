<template>
    <div class="lk-info">
        <div class="lk-info__header">
            <div class="header-title">
                <h4>Образование</h4>
            </div>
            <div class="lk-block-reduct" @click="educationReduct(false)">
                <img src="/images/icons/close.svg" alt="">
            </div>
        </div>
        <!-- education -->

        <template v-for="(item, index) in education">
            <!-- education block -->
            <div v-if="!item.reduct" class="education-block" :key="index">
                <div class="education-block-title">
                    <span class="education-block__title">{{ item.type.name }}</span>
                    <div class="education-block__icons">
                        <img @click="reduct(index)" class="education-block__reduct-icon" src="/images/icons/reduct-icon.svg">
                        <img @click="delEducation(item, index)" class="education-block__trash-icon" src="/images/icons/trash-icon.svg">
                    </div>
                </div>
                <template v-if="item.type.slug === 'advanced'">
                    <h5 v-if="item.course_link">
                        <a :href="item.course_link" target="_blank">{{ item.course_organization }}, {{ item.finishDate }}</a>
                    </h5>
                    <h5 v-else>{{ item.course_organization }}{{ !lodash.isEmpty(item.finishDate) ? ', ' + item.finishDate : '' }}</h5>
                    <p>{{ item.speciality }}</p>
                </template>
                <template v-else>
                    <h5>{{ item.institution.name }}{{ !lodash.isEmpty(item.finishDate) ? ', ' + item.finishDate : '' }}</h5>
                    <p>{{ item.speciality }}</p>
                </template>
            </div>

            <!-- form block -->
            <div v-else class="reduct-education_form" :key="index">
                <hr class="hr-grey-top">
                
                <div class="lk-reduct-block">
                    <form class="reduct-form">
                        
                        <!-- Тип образования -->
                        <div class="reduct-form__field">
                            <div class="ui-kit-input">
                                <v-select :options="types" v-model="item.type" label="name" :clearable="false" :filterable="false" placeholder="Тип" required>
                                    <template v-slot:option="option">
                                        <span>{{ option.name }}</span>
                                    </template>
                                    <template #open-indicator="{ attributes }">
                                        <span v-bind="attributes"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 8" fill="#000000"><path d="M8,8a.737.737,0,0,1-.49621-.19026L.23109,1.22182A.69141.69141,0,0,1,.19557.2241.74338.74338,0,0,1,1.22351.18963L8,6.32809,14.77656.18963A.74275.74275,0,0,1,15.804.2241a.691.691,0,0,1-.035.99772L8.49625,7.80974A.737.737,0,0,1,8,8Z" /></svg></span>
                                    </template>
                                </v-select>
                            </div>
                        </div>

                        <!-- Учебное заведение / ВУЗ -->
                        <div v-if="!lodash.isEmpty(item.type) && item.type.slug !== 'advanced'" class="reduct-form__field">
                            <div class="ui-kit-input">
                                <v-select taggable :options="SelectPaginated" v-model="item.institution" label="name" :clearable="false" :filterable="false" @open="SelectOnOpen" @close="SelectOnClose" @search="getSearchEducation" :placeholder="( item.type.slug == 'high' ? 'ВУЗ' : 'Учебное заведение' )">
                                    <template v-slot:option="option">
                                        <span>{{ option.name }}</span>
                                    </template>
                                    <template #open-indicator="{ attributes }">
                                        <span v-bind="attributes"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 8" fill="#000000"><path d="M8,8a.737.737,0,0,1-.49621-.19026L.23109,1.22182A.69141.69141,0,0,1,.19557.2241.74338.74338,0,0,1,1.22351.18963L8,6.32809,14.77656.18963A.74275.74275,0,0,1,15.804.2241a.691.691,0,0,1-.035.99772L8.49625,7.80974A.737.737,0,0,1,8,8Z" /></svg></span>
                                    </template>
                                    <template #list-footer>
                                        <li v-show="SelectHasNextPage" ref="loadmore" class="vs__loading">
                                            <span>Загрузка....</span>
                                        </li>
                                    </template>
                                </v-select>
                            </div>
                        </div>
                        <!-- Наименование курса  -->
                        <div v-if="!lodash.isEmpty(item.type) && item.type.slug === 'advanced'" class="reduct-form__field">
                            <div class="ui-kit-input"> 
                                <input class="iu-input_input" type="text" v-model="item.course_name" required>
                                <label>Наименование курса</label>
                                <!-- <span class="error-icon"></span> -->
                            </div>
                        </div>

                        <!-- Организатор курса -->
                        <div v-if="!lodash.isEmpty(item.type) && item.type.slug === 'advanced'" class="reduct-form__field">
                            <div class="ui-kit-input"> 
                                <input class="iu-input_input" type="text" v-model="item.course_organization" required>
                                <label>Организатор курса</label>
                                <!-- <span class="error-icon"></span> -->
                            </div>
                        </div>

                        <!-- Ссылка на курс -->
                        <div v-if="!lodash.isEmpty(item.type) && item.type.slug === 'advanced'" class="reduct-form__field">
                            <div class="ui-kit-input"> 
                                <input class="iu-input_input" type="text" v-model="item.course_link">
                                <label>Ссылка на курс (при наличии)</label>
                                <!-- <span class="error-icon"></span> -->
                            </div>
                        </div>

                        <!-- Специальность -->
                        <div  class="reduct-form__field">
                            <div class="ui-kit-input"> 
                                <input class="iu-input_input" type="text" v-model="item.speciality" required>
                                <label>Специальность</label>
                                <!-- <span class="error-icon"></span> -->
                            </div>
                        </div>

                        <!-- Год окончания -->
                        <div class="reduct-form__field">
                            <div class="ui-kit-input">
                                <the-mask mask="####" class="iu-input_input" type="text" :masked="true" v-model.trim="item.finishDate" required />
                                <label>Год окончания</label>
                                <!-- <span class="error-icon"></span> -->
                            </div>
                        </div>

                        <div class="reduct-form__buttons">
                            <button @click.prevent="saveSettings(index)" class="color-red">Сохранить изменения</button>
                            <button @click="cancelReduct(index)">Отменить</button>
                        </div>
                    </form>
                </div>

                <hr class="hr-grey-bottom">
            </div>
        </template>

        <!-- Add new field -->
        <div class="reduct-form__buttons">
            <button class="add-button" @click="addEducation()">Добавить образование</button>
        </div>
        
    </div>
</template>
<script>
    import {TheMask} from 'vue-the-mask';

    export default {
        name: 'ReductEducationInfo',
        components:{
            TheMask
        },
        props: [
            'educationArr'
        ],
        data () {
            return {
                types: [],
                education: [],
                educations: [],
                educationsRaw: [],
                currentReduct:[],
                reductEducationInfo: true,
                defaultElement:{
                    reduct: true,    // режим просмотра или редактирования
                    type: [], // тип учебного заведения 
                    institution: null, // учебное заведение
                    course_name: '', // наименование курса
                    course_organization: '', // организатор курса
                    course_link: '', // ссылка на курс
                    speciality: '',  // специализация
                    finishDate: '',  // дата окончания
                    status: 0,       // новая запись или редактиремая старая
                },
                select:{
                    page: 1,
                    limit: 20,
                    search: null,
                    current_page: 1,
                    last_page: 1,
                },
                observer: null,
            }
        },
        mounted()
        {
            var vm = this;
            vm.observer = new IntersectionObserver(vm.SelectInfiniteScroll);

            vm.getEducations(vm);
            vm.getEducationCategories();

            if( !vm.lodash.isEmpty(vm.educationArr) )
            {
                vm.education = vm.educationArr.map((item) => {
                    return {
                        id: item.id,
                        slug: item.slug,
                        reduct: false,
                        type: item.type,
                        institution: (  !vm.lodash.isEmpty(item.university) ? item.university : ( !vm.lodash.isEmpty(item.raw_education) ? { name: item.raw_education } : '' ) ),
                        course_name: item.course_name,
                        course_organization: item.course_organization,
                        course_link: item.course_link,
                        speciality: ( item.type.slug == 'advanced' ? item.course_name : item.position),  // специализация
                        finishDate: ( !vm.lodash.isEmpty(item.end_at) ? moment(item.end_at).format('YYYY') : '' ),  // дата окончания
                        status: 1,       // новая запись или редактиремая старая
                    }
                });
            } else {
                vm.addEducation();
            }
        },
        computed: {
            SelectFiltered()
            {
                var vm = this;
                return vm.educations;
                // return countries.filter((country) => country.includes(vm.select.search));
            },
            SelectPaginated()
            {
                var vm = this;
                // return vm.SelectFiltered.slice(0, vm.select.limit * vm.select.page);
                return vm.SelectFiltered;
            },
            SelectHasNextPage()
            {
                var vm = this;
                return vm.select.last_page > vm.select.current_page;
            },
        },
        methods:{
            async SelectOnOpen()
            {
                var vm = this;
                if( vm.SelectHasNextPage )
                {
                    await vm.$nextTick();
                    vm.observer.observe(vm.$refs.loadmore[0]);
                }
            },
            SelectOnClose()
            {
                var vm = this;
                vm.observer.disconnect();
            },
            async SelectInfiniteScroll([{ isIntersecting, target }])
            {
                var vm = this;
                if( isIntersecting )
                {
                    const ul = target.offsetParent;
                    const scrollTop = target.offsetParent.scrollTop;

                    vm.select.page++;
                    vm.getEducations(vm);

                    await vm.$nextTick();
                    ul.scrollTop = scrollTop;

                }
            },
            getEducationCategories()
            {
                var vm = this;
                axios
                    .get(route('api.education.categories'))
                    .then((response) => {
                        vm.types = response.data.data;
                        vm.$set(this.defaultElement, 'type', vm.types[0]);
                    });
            },
            getSearchEducation(query)
            {
                var vm = this;
                vm.select.search = query;
                vm.select.page = 1;
                vm.getEducations(vm);
            },
            getEducations: _.debounce((vm) => {
                axios
                    .get(route('api.education.index'), {
                        params: {
                            type: !vm.lodash.isEmpty(vm.currentReduct.type) ? vm.currentReduct.type.slug : null,
                            search: vm.select.search,
                            page: vm.select.page,
                            limit: vm.select.limit,
                        }
                    })
                    .then((response) => {
                        response.data.data.data.forEach((element) => {
                            if( !collect(vm.educations).contains('id', element.id) )
                            {
                                vm.educations.push(element);
                            }
                        });
                        vm.select.last_page = response.data.data.last_page;
                        vm.select.current_page = response.data.data.current_page;
                    });
                },
            350),
            educationReduct(reduct)
            {
                reduct == true ? this.reductEducationInfo = true : this.reductEducationInfo = false;
                
                this.$emit('educationReduct', {
                    reductEducationInfo: this.reductEducationInfo
                })
            },
            // редактироваие образования
            reduct(index)
            {
                var vm = this;
                vm.currentReduct = vm.education[index];
                vm.education.forEach((element) => {
                    element.reduct = false;
                });
                vm.education[index].reduct = true;
            },
            // отмена редактирования
            cancelReduct(index)
            {
                var vm = this;
                vm.education[index].reduct = false;
                vm.currentReduct = [];
                if( vm.education[index].status == 0 )
                {
                    vm.delEducation(null, index);
                }  
            },
            // удаление образования
            delEducation(item, index)
            {
                var vm = this;
                if( item !== null )
                {
                    axios
                        .delete(route('api.users.show.education.show.destroy', {user: vm.$parent.user, UserEducation: item}))
                        .then(response => {
                            vm.education.splice(index, 1);
                            vm.$parent.getUser(vm.$parent.user);
                        })
                        .catch(error => {
                            console.error(error)
                        });
                }else{
                    vm.education.splice(index, 1);
                }
            },
            addEducation()
            {
                var vm = this;
                vm.education.push(vm.defaultElement);
                vm.currentReduct = vm.defaultElement;
            },
            saveSettings(index)
            {
                var vm = this;
                axios
                    .post(route('api.users.show.update', vm.$parent.user), {
                        type: 'EducationInfo',
                        data: vm.education[index]
                    })
                    .then(response => {
                        vm.cancelReduct(index);
                        vm.$parent.getUser(vm.$parent.user);
                    })
                    .catch(error => {
                        console.error(error)
                    });
            }
        }
    }
</script>
