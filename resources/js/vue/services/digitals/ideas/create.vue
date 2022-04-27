<template>
    <div>
        <!-- Приветсвие -->
        <div v-if="stage == 0" class="digital-idea__welcome-card">
            <a :href="route('user.document.index')">
                <img width="16px" height="16px" src="/images/close_popup.svg" alt="close" class="ui__close-btn">
            </a>
            <h3 class="mb-4">Цифровая идея</h3>
            <p>Данный сервис поможет сформулировать и упаковать идею вашего проекта.</p>
            <p>После заполнения небольшой формы, вы получите структурированный документ, который можно скачать или распечатать. 
                Для этого необходимо заменить поля формы, опираясь на подсказки сервиса.</p>
            <p>Итоговый документ доступен только автору и сохраняется в разделе «Мои документы».</p>

            <button @click="stage = 1" class="ui-btn-red mt-4">Приступить</button>
        </div>

        <!-- Шаг 1 -->
        <div v-else class="digital-idea__forms">
            <!-- progress bar -->
            <div class="digital__progress-bar">
                <div class="progress-bar-sircle" :class="{'active-circle': stage === 1, 'ready-circle': stage > 1}">1</div>
                <span class="progress-bar-border" :class="{'ready-border': stage > 1}"></span>
                <div class="progress-bar-sircle" :class="{'active-circle': stage === 2, 'ready-circle': stage > 2}">2</div>
                <span class="progress-bar-border" :class="{'ready-border': stage > 2}"></span>
                <div class="progress-bar-sircle" :class="{'active-circle': stage === 3, 'ready-circle': stage > 3}">3</div>
            </div>
            <div @click="reduct = false" class="digital__preview popup-link">
                <img src="/images/passport/passport_preview.svg" alt="Предпросмотр">
                <span>Предпросмотр</span>
            </div>

            <!-- первая форма -->
            <div v-if="stage == 1" class="digital-idea__form">
                <form class="digital-idea__form-fields" @submit.prevent="onSubmit">

                    <label class="info">Название проекта</label>
                    <div v-if="errors.includes('title')" class="difital-idea__form-error">
                        {{getErrorDescription('title')}}
                    </div>
                    <div class="digital-idea__form-fields-input">
                        <input type="text"
                        :invalid="errors.includes('title')"
                        placeholder="Введите название, которое отражает суть вашей идеи" v-model="totalInfo.title">
                    </div>
                    
                    <label for="" class="info">Предпосылки проекта
                        <img src="/images/passport/info-circle.svg" alt="" 
                                tabindex="0" class="info-circle tooltip-active">
                        <!-- tooltip info -->
                        <div tabindex="0" class="tooltips">
                            <p>Это основания или стимулы, позволяющие судить о наличии 
                                потребности в реализации проекта. Могут иметь форму краткого 
                                текстового обоснования, дополненного реальными показателями, 
                                отражающими необходимость проекта</p>
                            <div tabindex="0" class="tooltips-invisible-block"></div>
                        </div>
                    </label>
                    <div v-if="errors.includes('json.prerequisites')" class="difital-idea__form-error">
                        {{getErrorDescription('json.prerequisites')}}
                    </div>
                    <textarea name="" id=""
                        :invalid="errors.includes('json.prerequisites')"
                        placeholder="Расскажите, что стало причиной возникновения вашей идеи. Укажите проблему(-ы), которую(-ые) она решит. Приведите аргументы, почему и как именно эта идея поможет решить данную(-ые) проблему(-ы)"
                        v-model="totalInfo.json.prerequisites">
                    </textarea>
                    
                </form>

                <div class="buttons-line">
                    <button @click="safeAndExit()" class="ui-btn-white">Сохранить и выйти</button>
                    
                    <div class="d-flex ">
                        <button @click="nextStage()" class="ui-btn-red">Далее</button>
                    </div>
                </div>
            </div>

            <!-- вторая форма -->
            <div v-if="stage == 2" class="digital-idea__form">
                <form action="" class="digital-idea__form-fields" @submit.prevent="onSubmit">

                    <label for="" class="info">Цели потенциального проекта
                        <img src="/images/passport/info-circle.svg" alt="" 
                                tabindex="0" class="info-circle tooltip-active">
                        <!-- tooltip info -->
                        <div tabindex="0" class="tooltips">
                            <p>Цели проекта – результаты, которых необходимо достичь 
                                при реализации проекта в заданных условиях.</p>
                            <a href="/storage/documents/celi-proekta.pdf" target="_blank">Подробнее...</a>
                            <div tabindex="0" class="tooltips-invisible-block"></div>
                        </div>
                    </label>
                    <div v-if="errors.includes('json.objectives')" class="difital-idea__form-error">
                        {{getErrorDescription('json.objectives')}}
                    </div>
                    <div v-for="(item, i) in totalInfo.json.objectives"
                        class="digital-idea__form-fields-input">
                        <input type="text" placeholder="Введите цель, которую необходимо достичь по итогам реализации идеи" 
                            :invalid="errors.includes('json.objectives')"
                            v-model="totalInfo.json.objectives[i]">
                        <div v-if="totalInfo.json.objectives.length > 1"
                            @click="delObjective(i)" class="digital-delete-field">Удалить
                        </div>
                    </div>
                    
                    <div v-if="totalInfo.json.objectives.length < 10" class="digital-idea__form-add-field" @click="addObjective">
                        <img src="/images/passport/add-field.svg" alt="">
                        <span>Добавить цель</span>
                    </div>

                    <label for="" class="info mt-5">Результаты и продукты проекта
                        <img src="/images/passport/info-circle.svg" alt="" 
                                tabindex="0" class="info-circle tooltip-active">
                        <!-- tooltip info -->
                        <div tabindex="0" class="tooltips">
                            <p>Это полезный эффект от реализации проекта, выраженный в 
                                форме продукта или каких-либо измеримых изменений. 
                                Результатом может быть научная разработка, строительный 
                                объект, учебная программа.</p>
                             <a href="/storage/documents/rezultat-proekta.pdf" target="_blank">Подробнее...</a>
                            <div tabindex="0" class="tooltips-invisible-block"></div>
                        </div>
                    </label>
                    <div v-if="errors.includes('json.results')" class="difital-idea__form-error">
                        {{getErrorDescription('json.results')}}
                    </div>
                    <div v-for="(item, i) in totalInfo.json.results"
                        class="digital-idea__form-fields-input">
                        <input type="text" placeholder="Введите ожидаемый результат или продукт проекта" 
                            :invalid="errors.includes('json.results')"
                            v-model="totalInfo.json.results[i]">
                        <div v-if="totalInfo.json.results.length > 1" @click="delResult(i)" 
                            class="digital-delete-field">Удалить
                        </div>
                    </div>

                    <div v-if="totalInfo.json.results.length < 10" class="digital-idea__form-add-field" @click="addResult">
                        <img src="/images/passport/add-field.svg" alt="">
                        <span>Добавить результат или продукт</span>
                    </div>
                </form>

                <!-- Кнопки -->
                <div class="buttons-line">
                    <button @click="safeAndExit()" class="ui-btn-white">Сохранить и выйти</button>
                    
                    <div class="d-flex ">
                        <button @click="stage = 1" class="ui-btn-white mr-3">Назад</button>
                        <button @click="nextStage()" class="ui-btn-red">Далее</button>
                    </div>
                </div>
            </div>

            <!-- Третья форма -->
            <div v-if="stage == 3" class="digital-idea__form">
                <form class="digital-idea__form-fields" @submit.prevent="onSubmit">
                    <label for="" class="info">Видение реализации</label>
                    <div v-if="errors.includes('json.vision')" class="difital-idea__form-error">
                        {{getErrorDescription('json.vision')}}
                    </div>
                    <textarea name="" id="" class="digital-idea__form-textaria-big"
                        placeholder="Расскажите о том, как бы вы реализовали вашу идею в виде проекта: необходимые шаги и действия, содержание и т.д."
                        :invalid="errors.includes('json.vision')"
                        v-model="totalInfo.json.vision">
                    </textarea>
                </form>

                <div class="buttons-line">
                    <button @click="safeAndExit()" class="ui-btn-white">Сохранить и выйти</button>
                    
                    <div class="d-flex ">
                        <button @click="stage = 2" class="ui-btn-white mr-3">Назад</button>
                        <button @click="finish()" class="ui-btn-red">Завершить</button>
                    </div>
                </div>
            </div>

            <!-- Завершение -->
            <div v-if="stage == 4" class="digital-idea__form">
                <h3>Документ сформирован и сохранен</h3>
                <p>Вы можете найти его в разделе «Мои документы»</p>
            
                <div class="buttons-line mt-5">
                    <button @click="reduct = true" class="ui-btn-white popup-link">Редактировать</button>
                    
                    <div class="d-flex">
                        <a :href="route('user.document.index')" class="ui-btn-antic  mr-3">Мои документы</a>
                    <button @click="generatePDF()" class="ui-btn-red">Скачать</button>
                    </div>
                </div>
            </div>
        </div>

<!-- Предпросмотр -->
        <div id="popup" class="popup">
            <div class="popup__body">
                <div class="popup-show digital-idea__popup">
                    <div class="digital-idea__popup-header">
                        <h3>Цифровая идея</h3>
                        <img class="ui__close-btn close-popup" src="/images/close_popup.svg" alt="">
                    </div>
                    
                    <div class="digital-idea__popup-content">
                        <!-- title -->
                        <div class="fields-input-title mt-3">
                            <textarea type="text" class="popup-title" v-model="totalInfo.title" :class="{'reduct-on': reduct}"
                            placeholder="Вы не указали название проекта" 
                            :readonly="!reduct"></textarea>
                        </div>

                        <div class="digital-idea__popup-content-desc">
                            <div class="digital-idea__popup-content-name">
                                <p>Организация</p>
                                <textarea type="text" v-model="totalInfo.organization"
                                    :class="{'reduct-on': reduct}" :readonly="!reduct"></textarea>

                            </div>
                        </div>

                        <h4>1. Видение реализации проекта и предпосылки</h4>
                        <h5>Видение реализации проекта</h5>
                        <div class="fields-input-title mt-3">
                            <textarea rows="8" type="text" v-model="totalInfo.json.vision" :class="{'reduct-on': reduct}" 
                            :readonly="!reduct"></textarea>
                        </div>

                        <h5>Предпосылки проекта</h5>
                        <div class="fields-input-title mt-3">
                            <textarea rows="8" type="text" v-model="totalInfo.json.prerequisites" :class="{'reduct-on': reduct}" 
                            :readonly="!reduct" class="mb-3"></textarea>
                        </div>

                        <!-- Цели -->
                        <h4>2. Цели и результаты проекта</h4>
                        <h5>Цели</h5>
                        <div v-for="(item, index) in totalInfo.json.objectives" class="digital-popup__form-fields-input">
                            <input type="text" placeholder="Введите цель, которой необходимо достичь по итогам реализации идеи" 
                                v-model="totalInfo.json.objectives[index]" :class="{'reduct-on': reduct}" :readonly="!reduct">
                            <div v-if="totalInfo.json.objectives.length > 1 && reduct"
                                @click="delObjective(index)" class="digital-delete-field">Удалить
                            </div>
                        </div>
                        <div v-if="totalInfo.json.objectives.length < 10 && reduct == true" 
                            class="digital-idea__form-add-field" 
                            @click="addObjective">
                            <img src="/images/passport/add-field.svg" alt="">
                            <span>Добавить цель</span>
                        </div>

                        <!-- Результаты -->
                        <h5>Результаты или продукты проекта</h5>
                        <div v-for="(item, index) in totalInfo.json.results"
                            class="digital-popup__form-fields-input">
                            <input type="text" placeholder="Введите цель, которой необходимо достичь по итогам реализации идеи" 
                                v-model="totalInfo.json.results[index]" :class="{'reduct-on': reduct}" :readonly="!reduct">
                            <div v-if="totalInfo.json.results.length > 1 && reduct == true"
                                @click="delResult(index)" class="digital-delete-field">Удалить
                            </div>
                        </div>
                        <div v-if="totalInfo.json.results.length < 10 && reduct" 
                            class="digital-idea__form-add-field" 
                            @click="addResult">
                            <img src="/images/passport/add-field.svg" alt="">
                            <span>Добавить цель</span>
                        </div>

                        <!-- buttons -->
                        <!-- редактирования -->
                        <div v-if="reduct == true" class="digital__popup-btns mt-5">
                            <button v-if="passport" @click="updatePassport(1)"     
                                    class="ui-btn-white close-popup">Сохранить и закрыть</button>
                            <button v-else @click="postPassport(1)"
                                    class="ui-btn-white close-popup">Сохранить и закрыть</button>

                            <div class="d-flex">
                                <button @click="closePopup()" class="ui-btn-red close-popup">Закрыть</button>
                            </div>
                        </div>

                        <!-- Режим просмотра -->
                        <div v-else class="digital__popup-btns mt-5">
                            <button v-if="passport" @click="updatePassport(0)"     
                                    class="ui-btn-white close-popup">Сохранить и закрыть</button>
                            <button v-else @click="postPassport(0)" 
                                    class="ui-btn-white close-popup">Сохранить и закрыть</button>
                            
                            <div v-if="reduct == false" class="d-flex">
                                <button @click="closePopup()" class="ui-btn-red close-popup">Закрыть</button>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>  
    </div>
</template>
<script>
export default {
    name: 'Create',
    props: [
        'job'
    ],
    data () {
        return {
            stage: 0,
            reduct: false,
            popup: true,
            // id нового документа, который пользователь сохранил
            id: '',
            passport: null,
            // Активная подсказка
            tooltip: '',
            // Файл с данными Цифровой идеи
            totalInfo: {
                title: '',
                organization: null,
                type: 'idea',
                ready: 0,
                date: new Date().toLocaleDateString(),
                json: {
                    prerequisites: '',
                    objectives: ['',],
                    results: ['',],
                    vision: '',
                    step: 1,
                }
            },
            tooltips: [
                {tooltip: 'Подсказка 1', id: ''},
                {tooltip: 'Подсказка 2', id: ''},
                {tooltip: 'Подсказка 3', id: ''},
                {tooltip: 'Подсказка 4', id: ''},
                {tooltip: 'Подсказка 5', id: ''},
            ],
            stages: [
                {
                    required: []
                },
                {
                    required: ['title', 'json.prerequisites']
                },
                {
                    required: ['json.objectives', 'json.results'],
                },
                {
                    required: ['json.vision']
                }
            ],
            errors: [],
            errors_descriptions: [
                {
                    field: 'title',
                    description: 'Поле обязательно для заполнения'
                },
                {
                    field: 'json.prerequisites',
                    description: 'Поле предпосылки обязательно для заполнения'
                }
            ]
        }
    },
    methods: {
        //Перед сменой шага, проведём валидацию
        nextStage(){
            let stage = this.stage
            let stageObj = this.stages[stage];

            this.stageValidateRequired(stage)

            if(this.errors.length == 0) {
                this.stage++;
                this.safeIdia();
            } else {
                return null;
            }
        },
        //Валидация
        stageValidateRequired(stage){
            let totalInfo = this.flatten(this.totalInfo);
            let stageObj = this.stages[stage];
            let stageRequired = stageObj.required;
            let errors = [];
            stageRequired.map(field => {
                if(!totalInfo[field] || totalInfo[field].length === 0)
                {
                    errors.push(field);
                }
                if(totalInfo[field] && totalInfo[field].length === 1)
                {
                    if(!totalInfo[field] && totalInfo[field][0].length === 0)
                    errors.push(field);
                }
            })

           this.errors = errors;

        },

        //Получить описание ошибки для поля
        getErrorDescription(field){
            let errors_descriptions = this.errors_descriptions;
            let error = errors_descriptions.filter(error => error.field == field);
            if(error.length > 0 && error[0].description)
            {
                return error[0].description;
            }
            else {
                return 'Поле обязательно для заполнения'
            }
        },

        //Помощник, который делает объект одноуровневым
        flatten(obj){
         var root = {};
         (function tree(obj, index){
           var suffix = toString.call(obj) == "[object Array]" ? "]" : "";
           for(var key in obj){
            if(!obj.hasOwnProperty(key))continue;
            root[index+key+suffix] = obj[key];
            if( toString.call(obj[key]) == "[object Array]" )tree(obj[key],index+key+suffix+"[");
            if( toString.call(obj[key]) == "[object Object]" )tree(obj[key],index+key+suffix+".");
           }
         })(obj,"");
         return root;
        },
        // Закрытие попапа
        closePopup() {
            let close = document.querySelector('.close-popup');
            close.click();
        },
        addObjective() {
            if(this.totalInfo.json.objectives.length < 10) {
                this.totalInfo.json.objectives.push('');
            }
        },
        delObjective(index) {
            this.totalInfo.json.objectives.splice(index, 1);
        },
        addResult() {
            if(this.totalInfo.json.results.length < 10) {
                this.totalInfo.json.results.push('');
            }
        },
        delResult(index) {
            this.totalInfo.json.results.splice(index, 1);
        },
        // Записываем идею в базу 
        postPassport(i) {
            // устанавливаем закончен ли паспорт или нет
            if( i === 1)
            {
                this.totalInfo.ready = 1;
            }
            this.totalInfo.json.step = this.stage;
            let data = this.totalInfo;
            axios
                .post(route('api.service.digital.passport.store'), data)
                .then(response => {
                    console.log('response.data.data', response.data.data);
                    this.id = response.data.data.id;
                    this.passport = response.data.data;
                })
                .catch(function (error) {
                    console.log(error);
                });
        },
        // Обновляем данные при редактиовании
        updatePassport(i) {
            // устанавливаем закончен ли паспорт или нет
            if( i === 1) {
                this.totalInfo.ready = 1;
            }
            this.totalInfo.json.step = this.stage;
            let id = this.id === '' ? this.passport.id : this.id;

            // let data = JSON.stringify(this.totalInfo);
            let data = this.totalInfo;
            axios
                .post(route('api.service.digital.passport.show.update', this.passport), data)
                .then(response => {
                    this.passport = response.data.data;
                    this.id = response.data.data.id;
                    // window.location.href = route('user.document.index);
                })
                .catch(function (error) {
                    console.log(error);
                }); 
        },
        // Завершаем редактирование паспорта и меняем его статутс на готовый
        finish() {
            let stage = this.stage
            let stageObj = this.stages[stage];

            this.stageValidateRequired(stage)

            if(this.errors.length > 0)
                return null;

            this.stage = 4;
            if ( this.passport ) {
                this.updatePassport(1);
            } else {
                this.postPassport(1);
            }
        },
        // Сохранение идеи
        safeIdia() {
            if ( !this.passport ) {
                this.postPassport(0);
            } else {
                this.updatePassport(0);
            }
        },
        // Завершаем редактирование и сохраняем как незаконченный документ
        // затем переходим на страницу Мои документы
        safeAndExit() {
            if (!this.passport) {
                this.postPassport(0);
            } else {
                this.updatePassport(0);
            }
            
            this.exit();
        },
        // редирект на страницу моих документов
        exit() {
            window.location.href = route('user.document.index');
        },
        // tooltips КОРОТКИЕ ПОДСКАЗКИ
        showTooltip(id) {
            this.tooltip = this.tooltips[id].tooltip;
        },
        // Генерация PDF
        generatePDF() {
            if( this.passport )
            {
                this.id = this.passport.id
            }
            let data = this.totalInfo;
            // let date = new Date(data.date);
            // data.date = date.toLocaleDateString()
            axios
                .post(route('api.service.digital.passport.show.update', this.passport), data)
                .then(response => {
                     window.location.href = route('service.digital.passport.show.index', this.passport);
                })
                .catch(function (error) {
                    console.log(error);
                });
        },
    },
    mounted()
    {
        var vm = this;
        vm.$set(this.totalInfo, 'organization', ( vm.job.organization !== null && vm.job.organization !== undefined ? vm.job.organization.name : vm.job.raw_organization ));
    },
}
</script>
