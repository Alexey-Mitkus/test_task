<template>
<div class="knowledge-base__content">
    <!-- add material button -->
    <div class="kb-content__add-material">
        <div class="kb-content__add-material-desk">
            <h4>Есть идеи?</h4>
            <p>Вы можете предложить свой материал для базы знаний, заполнив форму на сайте</p>
        </div>
        <div class="kb-content__add-material-btn">
            <button v-if="auth" @click="openModal('form')" class="ui-btn-red">Предложить материал</button>
            <button v-else @click="openModal('register')" class="ui-btn-red">Предложить материал</button>
        </div>
    </div>

    <!-- search field -->
    <div class="kb-content__search">
        <img src="/images/knowledge-base/knowleadge-base_search.svg" class="kb-content__search-icon">
        <input type="text" placeholder="Поиск по базе знаний..." v-model="searchField" @change="searchCard()">
        <img v-if="searchField.length > 0" @click="searchField = ''" class="close-icon"
            src="/images/knowledge-base/knowleadge-base_close-icon.svg" alt="">
    </div>

    <!-- filters -->
    <div class="kb-content__filters">
        <!-- themes -->
        <ul class="kb__filters" tabindex="0">
            <li class="kb__filter-theme">
                <span class="kb__filter-title">{{ filters.activeTheme }}</span>   
                <img src="/images/knowledge-base/knowleadge-base_arrow-down.svg" alt="">
                <ul>
                    <li>
                        <span @click="getFilter(1, 'all')" class="kb__filter-title">Все темы</span>
                    </li>
                    <li v-for="theme in themes" :key="theme.id">
                        <span @click="getFilter(1, theme)" class="kb__filter-title">{{ theme.name }}</span>
                        <template v-if="theme.childrens.length > 0">
                            <img src="/images/knowledge-base/knowleadge-base_arrow-right.svg" alt="">
                            <div class="menu-item">
                                <ul>
                                    <li v-for="subtheme in theme.childrens" :key="subtheme.id">
                                        <span @click="getFilter(1, subtheme)" class="kb__filter-title">{{subtheme.name}}</span>
                                    </li>
                                </ul>
                            </div>
                        </template>
                    </li>
                </ul>
            </li>
        </ul>
        <!--  -->
        
        <!-- formats -->
        <ul class="kb__filters" tabindex="0">
            <li class="kb__filter-theme">
                <span class="kb__filter-title">{{ filters.activeFormat }}</span>   
                <img src="/images/knowledge-base/knowleadge-base_arrow-down.svg" alt="">
                <ul>
                    <li>
                        <span @click="getFilter(2, 'all')" class="kb__filter-title">Все форматы</span>
                    </li>
                    <li v-for="format in formats" :key="format.id">
                        <span @click="getFilter(2, format)" class="kb__filter-title">{{ format.name }}</span>   
                    </li>
                </ul>
            </li>
        </ul>
        
        <!-- sort -->
        <ul class="kb__filters" tabindex="0">
            <li class="kb__filter-theme">
                <span class="kb__filter-title">{{ filters.activeOrderBy }}</span>   
                <img src="/images/knowledge-base/knowleadge-base_arrow-down.svg" alt="">
                <ul>
                    <li>
                        <span @click="getFilter(3, 'popular')" class="kb__filter-title">Сначала популярные</span>
                    </li>
                    <li>
                        <span @click="getFilter(3, 'newest')" class="kb__filter-title">Сначала новые</span>   
                    </li>
                    <li>
                        <span @click="getFilter(3, 'likes')" class="kb__filter-title">По лайкам</span>   
                    </li>
                </ul>
            </li>
        </ul>
    </div>

    <!-- cards -->
    <div class="kb-content__cards">
        <!--  -->
        <template v-if="cards.length > 0">
            <div v-for="card in cards" :key="card.id" class="kb-content__card" :class="{'choosen': false}">
                <div>
                    <div class="kb-content__card-format">
                        <span class="card-format">{{ card.format[0].name }}</span>
                        <span v-if="card.lang !== 'rus'" class="card-format ml-2">{{ card.lang }}</span>
                    </div>
                    <img v-if="card.image" :src="card.image" alt="">
                    <img  v-else src="/images/knowledge-base/knowleadge-base_cover.png" alt="">
                    <div class="kb-content__card-tags">
                        <span class="kb__tag">
                            <span v-for="(tag, index) in card.tags" :key="index"> #{{ tag.name }}</span>
                        </span>
                    </div>
                    <div class="kb-content__card-info">
                        <h5>{{ card.title }}</h5>
                        <p>{{ card.description}}</p>
                    </div>
                </div>
                
                <div class="kb-content__card-footer">
                    <a v-if="auth" target="_blank" :href="route('knowledge-base.show.index', card.slug)" class="kb__read-more">Читать
                        <img  src="/images/knowledge-base/knowleadge-base_arrow-up-right.svg" alt="">
                    </a>
                    <a v-else href="#" @click.prevent="openModal('register')" class="kb__read-more">Читать
                        <img  src="/images/knowledge-base/knowleadge-base_arrow-up-right.svg" alt="">
                    </a>

                    <div v-if="auth" @click="likePost(card)" class="kb__likes">
                        <img v-if="card.liked" src="/images/knowledge-base/knowleadge-base_heart-active.svg" alt="" class="kb__likes-heart">
                        <img v-else src="/images/knowledge-base/knowleadge-base_heart.svg" alt="" class="kb__likes-heart">
                        <span class="kb__likes-count">{{ card.likes }}</span>
                    </div>
                    <div v-else @click="openModal('register')" class="kb__likes">
                        <img v-if="card.liked" src="/images/knowledge-base/knowleadge-base_heart-active.svg" alt="" class="kb__likes-heart">
                        <img v-else src="/images/knowledge-base/knowleadge-base_heart.svg" alt="" class="kb__likes-heart">
                        <span class="kb__likes-count">{{ card.likes }}</span>
                    </div>
                </div>
            </div>
        </template>

        <template v-else>
            <div class="kb-content__empty_cards">
                По вашему запросу нет материалов
            </div>
        </template>

    </div>

    <!-- load any cards -->
    <div class="kb-content__load-any">
        <template v-if="cards.length < anyPages.all">
            <button @click="loadPosts()">Показать еще {{ GetAny }} из {{ anyPages.all - cards.length }}</button>
        </template>
    </div>

    <!-- modal add material -->
    <div class="modal-aria" :class="{'modal-none': madalOpen == false}">
        <div class="modal-body">
            <img @click="closeModal()" class="ui__close-btn" src="/images/close_popup.svg" alt="">
            <!-- form -->
            <div class="content">
            <template v-if="modalType === 'form'">
                <h2 class="modal-title">Добавление материала в базу знаний</h2>

                <div class="modal-desc">
                    <p>База знаний – это библиотека материалов, которую могут пополнять все участники сообщества. 
                        <br>Если вы хотите предложить материал, заполните форму ниже.
                    </p>
                    <p>1. &nbsp;&nbsp;Укажите ссылку на источник, где размещен материал, или прикрепите файл (это может быть документ, таблица или книга)</p>
                    <p>2. &nbsp;&nbsp;Выберите формат и тему материала, укажите его название. По желанию можете заполнить дополнительные поля</p>
                </div>

                <!-- <div class="ui-kit__check-item">
                    <input id="check" name="check" value="check" type="checkbox" class="ui-kit__custom-checkbox">
                    <label for="check">Ознакомлен(-а) с правилами<sup>*</sup></label>
                </div> -->

                <p class="attention"><sup>*</sup> — Поля, обязательные для заполнения</p>
                
                <h3 class="modal-title2">Введите ссылку на материал или прикрепите необходимый файл<sup>*</sup></h3>

                <!-- link (input-red)-->
                <div class="input-field mb-24">
                    <input class="input" type="text" placeholder="https://example.ru"
                        :class="{'input-red': $v.forms.link.$error}" v-model="forms.link"  @blur="$v.forms.link.$touch()">
                        <template v-if="$v.forms.link.$error">
                            <img src="/images/knowledge-base/knowleadge-base_warning-icon.svg" class="field-warning">
                            <p v-if="!$v.forms.link.url" class="input-alert-msg">Веедена некоректная ссылка. Требуемый формат: https://example.ru</p>
                            <!-- <p v-else-if="!$v.forms.link.findlink" class="input-alert-msg">Такая ссылка уже есть в базе знаний</p> -->
                            <p v-else-if="!$v.forms.link.checkFile" class="input-alert-msg">Данное поле обязательно для заполнения, если не загружен файл</p>
                            <!-- <p v-else class="input-alert-msg">Данное поле обязательно для заполнения</p> -->
                        </template>
                </div>

                <!-- user loaded file name -->
                <div v-if="loadedFileName" class="added-file mb-24">
                    <p>{{ loadedFileName }}</p>
                    <img @click="delLoadedFile()" src="/images/knowledge-base/knowleadge-base_trash-icon.svg" alt="">
                </div>

                <!-- add file -->
                <div class="add-file mb-52">
                    <label for="file"><img src="/images/knowledge-base/knowleadge-load-icon.svg" alt="">Добавить файл</label>
                    <input type="file" id="file" ref="file" name="file" v-on:change="getFileName()">
                    <span>(Формат: pdf, xls,xlsx, docs,docx; Максимальное количество файлов -1)</span>
                </div>
                
                <!-- formats radio button -->
                <h3 class="modal-title2">Укажите формат<sup>*</sup></h3>
                <div class="ui-kit__radio mb-52">
                    <template v-for="format in formats">
                        <input name="format" type="radio" :value="format.id" v-model="forms.format" class="ui-kit__custom-radio"  :id="'format' + format.id">
                        <label :for="'format' + format.id">{{ format.name }}</label>
                    </template>
                        <input name="format" type="radio" :value="9" v-model="forms.format"
                            class="ui-kit__custom-radio"  id="format9">
                        <label for="format9">Другое</label>
                </div>

                <!-- themes radio button -->
                <h3 class="modal-title2">Выберите тему<sup>*</sup></h3>
                 <div class="ui-kit__radio mb-52">
                    <template v-for="theme in themes">
                        <input name="theme" type="radio" :value="theme.id" v-model="forms.theme" class="ui-kit__custom-radio" :id="'theme' + theme.id">
                        <label :for="'theme' + theme.id">{{ theme.name }}</label>
                    </template>
                        <input name="theme" type="radio" value="14" v-model="forms.theme"
                            class="ui-kit__custom-radio" id="theme14">
                        <label for="theme14">Другое</label>
                </div>

                <!-- Undercategory field-->
                <h3 class="modal-title2">Укажите подкатегорию темы</h3>
                <div class="input-field mb-52">
                    <input class="input" type="text" placeholder="Agile, презентации,  примеры заполнения, методические материалы..." 
                        v-model="forms.sub_theme.text" @blur="$v.forms.sub_theme.text.$touch()"
                        :class="{'input-red': $v.forms.sub_theme.text.$error}">
                    <span class="chars-count" :class="{'chars-count-red': $v.forms.sub_theme.text.$error}">
                        {{ forms.sub_theme.max - forms.sub_theme.text.length }}
                    </span>
                    <template v-if="$v.forms.sub_theme.text.$error">
                        <img src="/images/knowledge-base/knowleadge-base_warning-icon.svg" class="field-warning">
                        <p class="input-alert-msg">Название материала не должно превышать {{ forms.sub_theme.max }} символов</p>
                    </template>
                </div>

                <!-- title field-->
                <h3 class="modal-title2">Введите название материала<sup>*</sup></h3>
                <div class="input-field mb-52">
                    <input class="input" type="text"  placeholder="Топ-7 программ для управления проектами..."
                        v-model="forms.title.text" @blur="$v.forms.title.text.$touch()"
                        :class="{'input-red': $v.forms.title.text.$error}">
                    <span class="chars-count" :class="{'chars-count-red': $v.forms.title.text.$error}">
                        {{ forms.title.max - forms.title.text.length }}
                    </span>
                    <template v-if="$v.forms.title.text.$error">
                        <img src="/images/knowledge-base/knowleadge-base_warning-icon.svg" class="field-warning">
                        <p v-if="!$v.forms.title.text.maxLength" class="input-alert-msg">Название материала не должно превышать {{ forms.title.max }} символов</p>
                        <p v-else class="input-alert-msg">Это поле обязательно для заполнения</p>
                    </template>
                </div>

                <!-- description field-->
                <h3 class="modal-title2">Введите описание материала<sup>*</sup></h3>
                <div class="input-field mb-52">
                    <textarea name="description" class="text-aria" id="" cols="30" rows="2"
                        placeholder="Вебинар о том, как из идеи вырастить готовый продукт"
                        v-model="forms.description.text" @blur="$v.forms.description.text.$touch()"
                        :class="{'input-red': $v.forms.description.text.$error}">
                    </textarea>
                    <span class="chars-count" :class="{'chars-count-red': $v.forms.description.text.$error}">
                        {{ forms.description.max - forms.description.text.length }}
                    </span>
                    <template v-if="$v.forms.description.text.$error">
                        <img src="/images/knowledge-base/knowleadge-base_warning-icon.svg" class="field-warning">
                        <p v-if="!$v.forms.description.text.maxLength" class="input-alert-msg">Оисание материала не должно превышать {{ forms.title.max }} символов</p>
                        <p v-else class="input-alert-msg">Это поле обязательно для заполнения</p>
                    </template>
                </div>

                <!-- tags field -->
                <h3 class="modal-title2">Укажите теги, но не больше 3-х</h3>
                <div class="input-field mb-52">
                    <textarea name="tags" class="text-aria" id="" cols="30" rows="2"
                        v-model="forms.tags.text" @blur="$v.forms.tags.text.$touch()" 
                        placeholder="управление проектами, создание продукта, инструмент"
                        :class="{'input-red': $v.forms.tags.text.$error}">
                    </textarea>
                    <span class="chars-count" :class="{'chars-count-red': $v.forms.tags.text.$error}">
                        {{ forms.tags.max - forms.tags.text.length }}
                    </span>
                    <template v-if="$v.forms.tags.text.$error">
                        <img src="/images/knowledge-base/knowleadge-base_warning-icon.svg" class="field-warning">
                        <p class="input-alert-msg">Теги не должно превышать {{ forms.tags.max }} символов</p>
                    </template>
                </div>

                <div class="kb-btns-line">
                    <!-- <button  @click="store()" class="ui-btn-red">Отправить</button> -->
                    <button  v-if="$v.$invalid == true" class="ui-btn-disabled">Отправить</button>
                    <button  v-else @click="store()" class="ui-btn-red">Отправить</button>
                </div>
            </template>
            
            <!-- sendig OK (при успешной отправке материала)-->
            <template v-if="modalType === 'send-ok'">
                <div class="content">
                    <h2 class="modal-title">Материал успешно отправлен на модерацию.</h2>

                    <p class="mb-52">Ваш материал успешно добавлен. После проверки мы отправим сообщение 
                        о статусе материала, которое вы сможете посмотреть в разделе «Уведомления» на 
                        странице своего профиля. Если материал будет одобрен, он появится в базе знаний.<br>
                        Спасибо за то, что пополняете базу знаний сообщества.
                        
                    </p>

                    <div class="buutons">
                        <button  @click="closeModal()" class="ui-btn-red">Хорошо</button>
                    </div>
                </div>
            </template>

            <!-- sendig false (ошибка при добавлении материала) -->
            <template v-if="modalType === 'send-false'">
                <div class="content">
                    <h2 class="modal-title">Ошибка!</h2>

                    <p class="mb-52">При добавлении материала произошла ошибка. Попробуйте загрузить матерал снова, либо попробуйте повторить позже.</p>

                    <div class="buutons">
                        <button  @click="closeModal()" class="ui-btn-red">Хорошо</button>
                    </div>
                </div>
            </template>

            <!-- for unreguster users -->
            <template v-if="modalType === 'register'">
                <h2 class="modal-title">Доступ к материалам</h2>

                <p class="mb-52">Для работы с материалами базы знаний необходимо <b>войти</b> или <b>зарегистрироваться</b></p>

                <div class="kb-btns-line">
                    <a :href="route('login')"><button class="ui-btn-antic mr-4" >Вход</button></a>
                    <a :href="route('register')"><button class="ui-btn-red">Регистрация</button></a>
                </div>
            </template>
        
            </div>
        </div>
    </div>
</div>
</template>
<script>

import {required, maxLength, url, helpers} from 'vuelidate/lib/validators';

function checkLink (value)
{
    axios.get(route('api.knowledge-base.search-link'), { link: value })
    .then((response) => {
        if(response.data.message === "link exists")
        {
            return true
        } else {
            return false
        }
    });
}

// let findlink = (value) => { return checkLink (value)};


export default {
    name: 'knowledgebase-index',
    props: [
        'auth'
    ],
    data () {
        return {
            madalOpen: false,
            modalType: 'form',
            loadedFileName: '',
            // строка поиска
            searchField: '',
            startdata: [],
            filters: [],
            file: null,

            // кнопка "Показать еше"
            anyPages: {all: 0, current: 0, last: 0, any: 6},
            
            // Форма 
            forms: {
                title: {max: 56, text: ''},
                description: {max: 94, text: ''},
                link: '',
                format: 1,
                theme: 1,
                sub_theme: {max: 40, text: ''},
                tags: {max: 57, text: ''},
            },
            //data
            formats: [],
            themes: [],
            // филтры
            filters: {
                themes: 'all', activeTheme: 'Все темы', 
                format: 'all', activeFormat: 'Все форматы',
                orderBy: 'newest', activeOrderBy: 'По умолчанию'
            },
            cards: [],
        }
    },

    // МЕТОДЫ
    methods: {
        getData()
        {
            axios.get(route('api.knowledge-base.index'))
            .then((response) => {

                this.startdata = response.data.data;

                this.cards = this.startdata.data;

                this.startdata.filters.formats.lists.forEach((value) => {
                    if( value.name !== 'Другое' )
                    {
                        this.formats.push(value);
                    }
                });

                this.startdata.filters.themes.lists.forEach((value) => {
                    if( value.name !== 'Другое' )
                    {
                        this.themes.push(value);
                    }
                });

                this.anyPages.all = this.startdata.allcount;
                this.anyPages.current = this.startdata.currentPage;
                this.anyPages.last = this.startdata.lastPage;

            });
        },
        // open and close Modal window
        openModal(modalType) {
            this.modalType = modalType;
            this.madalOpen = true;
            document.body.classList.add('lock');
        },

        closeModal() {
            this.madalOpen = false;
            document.body.classList.remove('lock');
        },

        clearSeachFiled() {
            this.search = '';
        },

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

        // parse string on array
        parseString(string) {
            let array = [];
            string = string.split(',');
            string.forEach((value, index) => {
                array.push(value.trim());
            });
            return array;
        },

        // сброс полей формы
        resetFormFields() {
            this.forms.link = '';
            this.forms.format = 1;
            this.forms.theme = 1;
            this.forms.sub_theme = {max: 40, text: ''};
            this.forms.title = {max: 56, text: ''};
            this.forms.description = {max: 94, text: ''};
            this.forms.tags = {max: 57, text: ''};
            this.file = null;
            this.loadedFileName = '';
        },

        // send 
        store() {
            let formData = new FormData();
            formData.append('title', this.forms.title.text);
            formData.append('description', this.forms.description.text);
            formData.append('link', this.forms.link);
            formData.append('format', this.forms.format);
            formData.append('theme', this.forms.theme);
            if(this.forms.sub_theme.text !== '')
                formData.append('sub_theme', this.forms.sub_theme.text);
            if(this.forms.tags.text !== '')
                formData.append('tags', this.forms.tags.text);
            formData.append('file', this.file);

            axios.post(route('api.knowledge-base.store'), formData, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            })
            .then( response => {
                if( response.data.success == false)
                {
                    this.openModal('send-false') 
                } else {
                    this.openModal('send-ok')
                    this.resetFormFields()
                }
                // delete window["formData"]
            })
            .catch(error => {
                console.log(error);
            })            
        },

        // load any posts
        loadPosts() {
            if(this.anyPages.current < this.anyPages.last) {
                this.anyPages.current += 1;

                let url = route('api.knowledge-base.index') + `?status=200&`;

                if (this.filters.themes !== 'all')
                    url += `themes[]=${this.filters.themes}&`;

                if (this.filters.format !== 'all')
                    url += `formats[]=${this.filters.format}&`;
                
                url += `orderBy=${this.filters.orderBy}&page=${this.anyPages.current}`;
                
                axios.get(url)
                .then(response => {
                    response.data.data.data.forEach((value) => {
                        this.cards.push(value)
                    })
                })
            }
        },

        // Фильтр
        // remove focus from filter
        filtersRemoveFocus() {
            let filters = document.querySelectorAll('.kb__filters');
            filters.forEach(item => {
                item.blur();
            });
        },

        // Запрос по фильтрам
        getFilter(type, value) {
            if(type === 1) {
                if(value === 'all') {
                   this.filters.themes = 'all'; 
                   this.filters.activeTheme = 'Все темы';
                } else {
                    this.filters.themes = value.id;
                    this.filters.activeTheme = value.name
                }
            } else if (type === 2) {
                if(value === 'all') {
                   this.filters.format = 'all'; 
                   this.filters.activeFormat = 'Все форматы';
                } else {
                    this.filters.format = value.id;
                    this.filters.activeFormat = value.name
                }
            } else if (type === 3) {
                if (value === 'popular') {
                    this.filters.orderBy = 'popular';
                    this.filters.activeOrderBy = 'Сначала популярные'
                }
                if (value === 'newest') {
                    this.filters.orderBy = 'newest';
                    this.filters.activeOrderBy = 'Сначала новые'
                }
                if (value === 'likes') {
                    this.filters.orderBy = 'likes';
                    this.filters.activeOrderBy = 'По лайкам'
                }
            }

            this.filtersRemoveFocus()

            this.filterSearch()
        },

        filterSearch() {
            let url = route('api.knowledge-base.index') + '?status=200&';

            if (this.filters.themes !== 'all')
                url += `themes[]=${this.filters.themes}&`;

            if (this.filters.format !== 'all')
                url += `formats[]=${this.filters.format}&`;
            
            url += `orderBy=${this.filters.orderBy}`;

            axios.get(url)
            .then(response => {
                this.cards = response.data.data.data;
                this.anyPages.all = response.data.data.allcount;
                this.anyPages.current = response.data.data.currentPage;
                this.anyPages.last = response.data.data.lastPage;
            })
            .catch((error) => {
                console.log(error)
            })
        },

        // SERVER
        // ПОИСК
        searchCard() {
            axios.get(route('api.knowledge-base.index'), {
                params: {
                    status: 200,
                    search: this.searchField
                }
            })
            .then(response => {
                this.cards = response.data.data.data;
            })
        },

        // Лайки (likes)
        likePost(card) {
            axios.post(route('api.knowledge-base.show.sulikes', card.slug))
            .then(response => {
                card.liked = response.data.data.liked
                card.likes = response.data.data.likes
            })
        },
    },
    computed: {
        GetAny() {
            if (this.anyPages.all - this.cards.length > 6) {
                return 6;
            } else {
                return this.anyPages.all - this.cards.length;
            }
        },
    },

    mounted() {
        this.getData();
    },
    // ВАЛИДАЦИЯ ПОЛЕй
    validations: {
        forms: {
            title: {

                text: {
                    required,
                    maxLength: maxLength(56),
                }
            },
            description: {
                text: {
                    required,
                    maxLength: maxLength(94),
                }
            },
            link: {
                url,
                checkFile(value) {
                    if(value === '' && !this.loadedFileName) {
                        return false
                    } else {
                        return true
                    }
                }
            },
            sub_theme: {
                text: {
                    maxLength: maxLength(40),
                }
            },
            tags: {
                text: {
                    maxLength: maxLength(57),
                }
            }
        }
    },

}
</script>