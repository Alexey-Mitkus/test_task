<template>
    <div class="services__wrapper">
        <!-- Header -->
        <div class="services__header">
            <div class="services__header-content">
                <h1>Цифровые сервисы</h1>
                <h3>Здесь мы собрали набор цифровых инструментов, которые будут полезны как новичкам, так и экспертам в управлении проектами</h3>
            </div>
            <img src="/images/services/services_cube.png" alt="">
        </div>
        
        <!-- Services -->
        <div class="services__content">
            <div class="services__items">

                <div @click="index = 1" class="services__item popup-link" tabindex="0">
                    <span class="number">01</span>
                    <div>
                        <img width="80px" height="80px" src="/images/services/services_Digital_Idia.svg" alt="">
                        <h3>Цифровая идея</h3>
                        <p>Поможет упаковать идею проекта</p>
                    </div>
                    <button>Подробнее</button>
                </div>

                <div @click="index = 2" class="services__item popup-link" tabindex="0">
                    <span class="number">02</span>
                    <div>
                        <img width="80px" height="80px" src="/images/services/services_Digital_Passport.svg" alt="">
                        <h3>Цифровой паспорт</h3>
                        <p>Поможет оформить паспорт проекта</p>
                    </div>
                    <button>Подробнее</button>
                </div>

                <div @click="index = 3" class="services__item popup-link" tabindex="0">
                    <span class="number">03</span>
                    <div>
                        <img width="80px" height="80px" src="/images/services/services_Thoughts.svg" alt="">
                        <h3>Здравые мысли</h3>
                        <p>Поможет подготовить выступление</p>
                    </div>
                    <button>Подробнее</button>
                </div>

                <div @click="index = 4, formData.type = 'consulting'" 
                    class="services__item popup-link" tabindex="0">
                    <div>
                        <span class="number">04</span>
                        <img width="80px" height="80px" src="/images/services/services_Consulting.svg" alt="">
                        <h3>Проектный консалтинг</h3>
                        <p>Поможет получить бесплатную консультацию эксперта</p>
                    </div>
                    <button>Подробнее</button>
                </div>

                <div @click="index = 5, formData.type = 'research'" 
                    class="services__item popup-link" tabindex="0">
                    <span class="number">05</span>
                    <div>
                        <img width="80px" height="80px" src="/images/services/services_Research.svg" alt="">
                        <h3>Исследования</h3>
                        <p>Поможет получить опыт участия в исследованиях продукта</p>
                    </div>
                    <button>Подробнее</button>
                </div>

                <div @click="index = 6" class="services__item-innactive" tabindex="0">
                    <span class="number">06</span>
                    <div>
                        <img width="80px" height="80px" src="/images/services/services_Knowledge_base_innactive.svg" alt="">
                        <h3>Банк идей</h3>
                        <p>Поможет собрать все идеи и предложения</p>
                    </div>
                    <div class="innactive-info">   
                    <button>Подробнее</button>
                    <img tabindex="0" class="service-tooltip" src="/images/services/tooltip-info.svg" alt="">
                        
                    <div class="innactive-tooltip">
                        <p>Данный раздел находится в разработке</p>
                        <div class="innactive-tooltip-polygon"></div>
                    </div>
                    </div>
                </div>
                
            </div>
        </div>
       
        <!-- Модальное окно нового документа-->
        <div id="popup" class="popup">
            <div class="popup__body">
                <div class="popup__content-services popup-show">
                    <img src="/images/close_popup.svg" alt="" class="popup__close close-popup">
            <!-- Динамический контент -->
                <!-- Цифровая Идея -->
                <div v-if="index == 1" class="services__popup-content">
                    <h2>Цифровая идея <span style="font-size:14px; font-weight:normal;">beta версия</span></h2>
                    
                    <p>Данный сервис поможет сформулировать и упаковать идею вашего проекта.<br>
                        После заполнения небольшой формы, вы получите структурированный документ, который можно скачать или распечатать. Для этого необходимо заполнить поля формы, опираясь на подсказки сервиса.</p>
                    <p>Итоговый документ доступен только автору и сохраняется в разделе «Мои документы».</p>

                    <p v-if="user == undefined" class="services__popup-guest-p">Для использования сервиса необходимо <span>войти</span> или <span>зарегистрироваться</span></p>

                    <div class="services__row-btns" v-if="user !== undefined">
                        <a :href="route('service.digital.idea.create')"><button class="ui-btn-red">Запустить сервис</button></a>
                    </div>

                    <div class="services__row-btns" v-else>
                        <a :href="route('login')"><button class="ui-btn-antic" >Вход</button></a>
                        <a :href="route('register')"><button class="ui-btn-red">Регистрация</button></a>
                    </div>
                </div>

                <!-- Цифровой Паспорт -->
                <div v-if="index == 2" class="services__popup-content">
                    <h2>Цифровой паспорт <span style="font-size:14px; font-weight:normal;">beta версия</span></h2>
                    <p>С помощью этого сервиса вы можете создать цифровую версию паспорта проекта, в которой будут учтены все необходимые критерии для его реализации.</p>
                    <p>Цифровой паспорт поможет оформить ваши мысли и упаковать их в типовой документ, который можно будет распечатать или скачать. Для этого необходимо заполнить поля формы, опираясь на подсказки сервиса.</p>
                    <p>Итоговый документ доступен только автору и сохраняется в разделе «Мои документы».</p>

                    <p v-if="user == undefined" class="services__popup-guest-p">Для использования сервиса необходимо <span>войти</span> или <span>зарегистрироваться</span></p>

                    <div class="services__row-btns" v-if="user !== undefined">
                        <a :href="route('service.digital.passport.create')"><button class="ui-btn-red">Создать паспорт</button></a>
                    </div>

                    <div class="services__row-btns" v-else>
                        <a :href="route('login')"><button class="ui-btn-antic" >Вход</button></a>
                        <a :href="route('register')"><button class="ui-btn-red">Регистрация</button></a>
                    </div>
                </div>

                <!-- Здравые мысли -->
                <div v-if="index == 3" class="services__popup-content">
                    <h2>Здравые мысли</h2>
                    <p> Сервис «Здравые мысли» поможет стать спикером на площадках сообщества.
                        <br>Достаточно указать тему или выбрать вариант из предложенного списка, определиться с форматом выступления и заполнить контактные данные.</p>
                    
                    <p>После этого с вами свяжутся организаторы сообщества и помогут подготовить выступление.</p>

                    <div class="services__row-btns">
                        <a :href="route('speaker.index')"><button class="ui-btn-red">Стать спикером</button></a>
                    </div>
                </div>

                <!-- Проектный консалтинг -->
                <div v-if="index == 4" class="services__popup-content">
                    <h2>Проектный консалтинг</h2>
                    <p>Это возможность получить бесплатную консультацию по вашим проектам или проектным инициативам.</p>
                    
                    <div v-if="user == undefined" class="services__popup-how-work" >
                        <label>Как это работает?</label>
                        <ol>
                            <li>После заполнения данной формы мы связываемся с вами, уточняем детали вашего запроса</li>
                            <li>Помогаем с оформлением и оцифровкой вашей идеи/проекта</li>
                            <li>Анализируем идею/проект и договариваемся о дальнейших шагах (проведение дополнительных консультаций, аудит проектной документации, встречи или тренинги)</li>
                        </ol>
                        <p  class="services__popup-guest-p">Для использования сервиса необходимо <span>войти</span> или <span>зарегистрироваться</span></p>
                    </div>
    
                    <template v-if="user !== undefined">
                        <!-- accardeon -->
                        <div class="ui-kit__accardeon">
                            <label for="accardeon-open">Как это работает?</label>
                            <input type="checkbox" id="accardeon-open" class="ui-kit__accardeon-show">
                            
                            <span class="open-accardeon-icon"></span>
                            <span class="open-accardeon-icon2"></span>
                            
                            <div class="ui-kit__accardeon-content">
                                <ol>
                                    <li>После заполнения данной формы мы связываемся с вами, уточняем детали вашего запроса</li>
                                    <li>Помогаем с оформлением и оцифровкой вашей идеи/проекта</li>
                                    <li>Анализируем идею/проект и договариваемся о дальнейших шагах (проведение дополнительных консультаций, аудит проектной документации, встречи или тренинги)</li>
                                </ol>
                            </div>
                        </div>

                        <p class="mt-4 mb-0" style="font-weight: bold;">Укажите статус вашего проекта:</p>

                        <form action="" class="consalting-form">
                            <!-- Чекбоксы -->
                            <div class="input-radio">
                                <input name="project" type="radio" value="Идея" class="custom-radio" id="1"
                                v-model="formData.fields.projectStatus" checked>
                                <label for="1">Идея</label>

                                <input name="project" type="radio" value="В работе" class="custom-radio" id="2"
                                v-model="formData.fields.projectStatus">
                                <label for="2">В работе</label>

                                <input name="project" type="radio" value="Завершен" class="custom-radio" id="3"
                                v-model="formData.fields.projectStatus">
                                <label for="3">Завершен</label>
                            </div>

                            <!-- Инпуты -->
                            <div tabindex="0" class="input-field" :class="{'error-border': errors.name}"> 
                                <input class="services-modal-input" type="text" v-model="formData.name" required>
                                <label>ФИО</label>
                                <span v-if="errors.name" class="error-icon"></span>
                            </div>
                            <div v-if="errors.name" class="services__popup-error-msg">{{ errors.name }}</div>

                            <div tabindex="0" class="input-field" :class="{'error-border': errors.organization}">      
                                <input class="services-modal-input" type="text"  v-model="formData.organization" required>
                                <label>Организация</label>
                                <span v-if="errors.organization" class="error-icon"></span>
                            </div>
                            <div v-if="errors.organization" class="services__popup-error-msg">{{ errors.organization }}</div>

                            <div tabindex="0" class="input-field" :class="{'error-border': errors.phone}">  
                                <input class="services-modal-input" type="text"  v-model="formData.phone" required>
                                <label>Телефон</label>
                                <span v-if="errors.phone" class="error-icon"></span>
                            </div>
                            <div v-if="errors.phone" class="services__popup-error-msg">{{errors.phone}}</div>

                            <!-- Текстовое поле -->
                            <div class="reserch-form">
                                <div class="content-theme">
                                    <textarea v-model="formData.fields.comment" required></textarea>
                                    <label class="theme">Напишите свои вопросы и комментарии</label>
                                </div>
                            </div>
                        </form>
                    
                        <div class="services__row-btns">
                            <button @click="validate()" class="ui-btn-red">Отправить заявку</button>
                        </div>
                    </template>

                    <div class="services__row-btns" v-else>
                        <a :href="route('login')"><button class="ui-btn-antic" >Вход</button></a>
                        <a :href="route('register')"><button class="ui-btn-red">Регистрация</button></a>
                    </div>
                </div>

                <!-- Исследования -->
                <div v-if="index == 5" class="services__popup-content">
                    <h2>Исследования</h2>                    
                    <p class="pr-5">С помощью данного сервиса вы можете организовать исследование или принять в нем участие.</p>
                    
                    <div v-if="user == undefined" class="services__popup-how-work" >
                        <label>Как это работает?</label>
                        <label>Если вы—организатор</label>
                        <ol>
                            <li>После заполнения данной формы мы связываемся с вами, уточняем детали</li>
                            <li>Готовим анонс с описанием деталей исследования</li>
                            <li>Помогаем собрать участников и организовать исследование</li>
                        </ol>
                        <label>Если вы—участник</label>
                        <ol>
                            <li>После заполнения данной формы мы связываемся с вами и 
                            рассказываем о текущих и планируемых исследованиях</li>
                            <li>Вы выбираете исследование, в котором хотите принять участие</li>
                            <li>Сопровождаем вас на этапе подачи заявки и во время участия в исследовании</li>
                        </ol>

                        <p  class="services__popup-guest-p">Для использования сервиса необходимо <span>войти</span> или <span>зарегистрироваться</span></p>
                    </div>
                    
                <template v-if="user !== undefined">

                    <!-- accardeon -->
                    <div class="ui-kit__accardeon">
                        <label for="accardeon-open">Как это работает?</label>
                        <input type="checkbox" id="accardeon-open" class="ui-kit__accardeon-show">
                        
                        <span class="open-accardeon-icon"></span>
                        <span class="open-accardeon-icon2"></span>
                        
                        <div class="ui-kit__accardeon-content">
                            <ol v-if="status">
                                <li>После заполнения данной формы мы связываемся с вами, уточняем детали</li>
                                <li>Готовим анонс с описанием деталей исследования</li>
                                <li>Помогаем собрать участников и организовать исследование</li>
                            </ol>
                            
                            <ol v-else>
                                <li>После заполнения данной формы мы связываемся с вами и рассказываем о текущих и планируемых исследованиях</li>
                                <li>Вы выбираете исследование, в котором хотите принять участие</li>
                                <li>Сопровождаем вас на этапе подачи заявки и во время участия в исследовании</li>
                            </ol>
                        </div>
                    </div>

                    <div @click="chageStatus()" class="services__popup-content-status">
                        <div class="status" :class="{active: status == true}">Организатор</div>
                        <div class="status" :class="{active: status == false}">Участник</div>
                    </div>
                    <!-- Организатор -->
                    <template v-if="status">
                        <form action="" class="reserch-form">
                            <!-- Текстовое поле -->
                            <div class="content-theme" :class="{'error-border': errors.theme}">
                                <textarea
                                    placeholder="" required
                                    v-model="formData.fields.theme" 
                                ></textarea>
                                <label class="theme">Тема исследования или название продукта</label>
                                <span v-if="errors.theme" class="error-icon"></span>
                            </div>
                            <div v-if="errors.theme" class="services__popup-error-msg">{{ errors.theme }}</div>

                            <!-- Инпуты -->
                            <div tabindex="0" class="input-field" :class="{'error-border': errors.name}"> 
                                <input class="services-modal-input" type="text" v-model="formData.name" required>
                                <!-- <span class="bar"></span> -->
                                <label>ФИО</label>
                                <span v-if="errors.name" class="error-icon"></span>
                            </div>
                            <div v-if="errors.name" class="services__popup-error-msg">{{ errors.name }}</div>

                            <div tabindex="0" class="input-field" :class="{'error-border': errors.organization}">      
                                <input class="services-modal-input" type="text"  v-model="formData.organization" required>
                                <!-- <span class="bar"></span> -->
                                <label>Организация</label>
                                <span v-if="errors.organization" class="error-icon"></span>
                            </div>
                            <div v-if="errors.organization" class="services__popup-error-msg">{{ errors.organization }}</div>

                            <div tabindex="0" class="input-field" :class="{'error-border': errors.phone}">  
                                <input class="services-modal-input" type="text"  v-model="formData.phone" required>
                                <!-- <span class="bar"></span> -->
                                <label>Телефон</label>
                                <span v-if="errors.phone" class="error-icon"></span>
                            </div>
                            <div v-if="errors.phone" class="services__popup-error-msg">{{ errors.phone }}</div>
                        </form>
                        
                        <div class="services__row-btns">
                            <button @click="validate()" class="ui-btn-red">Отправить заявку</button>
                        </div>
                    </template>
                    
                    <!-- Участник -->
                    <template v-else> 
                        <form action="" class="reserch-form">
                            <!-- Инпуты -->
                            <div tabindex="0" class="input-field" :class="{'error-border': errors.name}"> 
                                <input class="services-modal-input" type="text" v-model="formData.name" required>
                                <!-- <span class="bar"></span> -->
                                <label>ФИО</label>
                                <span v-if="errors.name" class="error-icon"></span>
                            </div>
                            <div v-if="errors.name" class="services__popup-error-msg">{{ errors.name }}</div>

                            <div tabindex="0" class="input-field" :class="{'error-border': errors.organization}">      
                                <input class="services-modal-input" type="text"  v-model="formData.organization" required>
                                <!-- <span class="bar"></span> -->
                                <label>Организация</label>
                                <span v-if="errors.organization" class="error-icon"></span>
                            </div>
                            <div v-if="errors.organization" class="services__popup-error-msg">{{ errors.organization }}</div>

                            <div tabindex="0" class="input-field" :class="{'error-border': errors.phone}">  
                                <input class="services-modal-input" type="text"  v-model="formData.phone" required>
                                <!-- <span class="bar"></span> -->
                                <label>Телефон</label>
                                <span v-if="errors.phone" class="error-icon"></span>
                            </div>
                            <!-- Пустой блок для того чтобы добавить пространство в попапе -->
                            <div class="content-theme empty-block" style="margin-top: 12px;border: none;"></div>
                            <div v-if="errors.phone" class="services__popup-error-msg">{{ errors.phone }}</div>
                        </form>
                        
                        <div class="services__row-btns">
                            <button @click="validate()" class="ui-btn-red">Отправить заявку</button>
                        </div>
                    </template> 
                </template>

                    <div class="services__row-btns" v-else>
                        <a :href="route('login')"><button class="ui-btn-antic" >Вход</button></a>
                        <a :href="route('register')"><button class="ui-btn-red">Регистрация</button></a>
                    </div>
                            
                </div>

                <!-- Уведомление о том, что заявка приянта -->
                <div v-if="index == 7" class="services__popup-content">
                    <div class="services__popup-done">
                        <img src="/images/services/done-popup.svg" alt="">
                        <h2>Спасибо, ваша заявка принята.</h2>
                        
                        <p>Мы свяжемся с вами в ближайшее время.</p>

                        <button @click="closePopup()" class="ui-btn-red">Хорошо</button>
                    </div>
                </div>
            <!-- Конец Динамического контента -->
                </div>
            </div>
        </div>
    </div>
</template>
<script>
export default ({
    name: 'Services-Index',
    props: [
        'user',
        'job'
    ],
    data () {
        return {
            index: 0,
            status: true,
            checkValidate: false,
            userInfo: [],
            userJob: [],
            formData: {
                userID: null,
                type: '',
                name: null,
                organization: null,
                phone: null,
                fields: { 
                    projectStatus: 'Идея',
                    person: 'organizator',
                    comment: '',
                    theme: '',
	            }
            } ,
            errors:{
                name: '',
                organization: '',
                phone: '',
                person: '',
                theme: '',
            },
        }
    },
    
    mounted()
    {
        if( this.user != undefined )
        {
            this.userInfo = JSON.parse(this.user);
            this.formData.userID = this.userInfo.id;
            this.formData.name = this.userInfo.full_name;
            this.formData.phone = this.userInfo.telephone.pivot.value;
        }

        if( this.job != undefined )
        {
            this.userJob = JSON.parse(this.job);
            this.formData.organization = this.userJob.organization.name;
        }

    },
    methods: {
        // Переключатель для раздела Исследования (Оганизатор\Участник)
        chageStatus() {
            this.status = this.status == false ? true : false;
            if(this.status === true) {
                this.formData.fields.person = 'organizator';
            } else {
                this.formData.fields.person = 'participant';
            }
        },
        // Закрывает попап
        closePopup() {
            let close = document.querySelector('.close-popup');
            close.click();
        },
        // Валидация форм
        validate() {
            this.formData.name === '' ? this.errors.name = 'Укажите ваши ФИО' : this.errors.name = '';
            this.formData.organization === '' ? this.errors.organization = 'Не указана организация' : this.errors.organization = '';
            this.formData.phone === '' ? this.errors.phone = 'Неправильно набран номер' : this.errors.phone = '';
            if (this.formData.type === 'research') {
                this.formData.fields.theme === '' ? this.errors.theme = 'Укажите тему исследования или название продукта' : this.errors.theme = '';
            }
            
            // Если активен раздел конcалтинг
            if (this.formData.type == 'consulting') {
                if(!this.errors.name && !this.errors.organization && !this.errors.phone) {
                    this.formData.fields.theme = '';
                    this.formData.fields.person = '';
                    this.submit();
                }
            // Если активен раздел исследования
            } else if (this.formData.type == 'research') {
                if(!this.errors.name && !this.errors.organization && !this.errors.phone) {
                    if (this.formData.fields.person === "organizator" && !this.errors.theme) {
                        this.formData.fields.comment = '';
                        this.formData.fields.projectStatus = '';
                        this.submit();
                    } else if (this.formData.fields.person === "participant") {
                        this.formData.fields.comment = '';
                        this.formData.fields.theme = '';
                        this.formData.fields.projectStatus = '';
                        this.submit();
                    }
                } 
            }
        }, 
        // CRUD
        submit() {
            axios.post(route('api.service.ticket.store'), this.formData)
            .then(response => {
                this.index = 7
            })
            .catch(error => {
                console.log(error)
                this.index = 8
            });  
        },
    },
})
</script>
