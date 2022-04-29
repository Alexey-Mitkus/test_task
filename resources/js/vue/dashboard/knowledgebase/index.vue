<template>
<div class="knowledge-base__content">

    <h1>База знаний</h1>

    <!-- search field -->
    <div class="kb-content__search">
        <img src="/images/knowledge-base/knowleadge-base_search.svg" class="kb-content__search-icon">
        <input type="text" placeholder="Поиск по базе знаний..." v-model="searchField" @change="searchCard()">
        <img v-if="searchField.length > 0" @click="searchField = ''" class="close-icon"
            src="/images/knowledge-base/knowleadge-base_close-icon.svg" alt="">
    </div>

    <!-- filters -->
    <div v-if="approved == false" class="kb-content__filters">
        <!-- themes -->
        <ul class="kb__filters" tabindex="0">
            <li class="kb__filter-theme">
                <span class="kb__filter-title">{{ filters.activeTheme }}</span>   
                <img src="/images/knowledge-base/knowleadge-base_arrow-down.svg" alt="">
                <ul>
                    <li>
                        <span @click="getFilter(1, 'all')" class="kb__filter-title">Все темы</span>
                    </li>
                    <li v-for="theme in themes">
                        <span @click="getFilter(1, theme)" class="kb__filter-title">{{ theme.name }}</span>
                        <template v-if="theme.childrens.length > 0">
                            <img src="/images/knowledge-base/knowleadge-base_arrow-right.svg" alt="">
                            <div class="menu-item">
                                <ul>
                                    <li v-for="subtheme in theme.childrens">
                                        <span @click="getFilter(1, subtheme)" class="kb__filter-title">{{subtheme.name}}</span>
                                    </li>
                                </ul>
                            </div>
                        </template>
                    </li>
                </ul>
            </li>
        </ul>
        
        <!-- formats -->
        <ul class="kb__filters" tabindex="0">
            <li class="kb__filter-theme">
                <span class="kb__filter-title">{{ filters.activeFormat }}</span>   
                <img src="/images/knowledge-base/knowleadge-base_arrow-down.svg" alt="">
                <ul>
                    <li>
                        <span @click="getFilter(2, 'all')" class="kb__filter-title">Все форматы</span>
                    </li>
                    <li v-for="format in formats">
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

    <!-- add material button -->
    <div class="kb-content__admin-sort">
        <div class="kb-content__admin-sort-items">
            <div @click="changeCardType()" class="sort-item">
                <label>Ожидают публикации<span>({{ waitCards.length }})</span></label>
                <span v-if="approved == true" class="red-border"></span>
            </div>

            <div @click="changeCardType()" class="sort-item">
                <label>Опубликованные<span>({{ approvedCards.length }})</span></label>
                <span v-if="approved == false" class="red-border"></span>
            </div>
        </div>

        <button @click="addMaterial()" class="ui-btn-red">Добавить материал</button>
    </div>

    <div v-if="delArray.length > 0" class="kb-content__admin-chosen">
        <span>Выбрано ({{ delArray.length }})</span><button @click="openModal('delete')">Удалить</button>
    </div>
    
    <!-- cards -->
    <div class="kb-content__cards">
        <template v-if="waitCards.length > 0 && approved == true">    
            <!-- admin functions -->
            <div v-for="(card, index) in waitCards" class="kb-content__card" :class="{'choosen': false}">
                <div>
                    <div class="kb-content__card-admin-func">
                        <img @click="chooseCards(card.id, card.title, card.slug)" src="/images/knowledge-base/knowleadge-base_select-func.svg" alt="">
                        <img v-if="delArray.length < 1" @click="reductCard(index, 'wait')" src="/images/knowledge-base/knowleadge-base_edit-func.svg" alt="">
                        <img v-if="delArray.length < 1" @click="delCard(card)" 
                            src="/images/knowledge-base/knowleadge-base_delete-func.svg" alt="">
                    </div>
                    <div class="kb-content__card-format">
                        <span class="card-format">{{ card.format[0].name }}</span>
                        <span v-if="card.lang !== 'rus'" class="card-format ml-2">{{ card.lang }}</span>
                    </div>
                    <!-- <span class="kb-content__card-format">{{ card.format[0].name }}</span> -->
                    <img v-if="!card.image" src="/images/knowledge-base/knowleadge-base_cover.png" alt="">
                    <img v-else :src="card.image" alt="">
                    <div class="kb-content__card-tags">
                        <span class="kb__tag">
                            <span v-for="tag in card.tags"> #{{ tag.name }}</span>
                        </span>
                    </div>
                    <div class="kb-content__card-info">
                        <h5>{{ card.title }}</h5>
                        <p>{{ card.description }}</p>
                    </div>
                </div>
                
                <div class="kb-content__card-footer">
                    <a target="_blank" :href="route('knowledge-base.show.index', card)" class="kb__read-more">
                        Читать
                        <img src="/images/knowledge-base/knowleadge-base_arrow-up-right.svg" alt="">
                    </a>
                </div>
            </div>
        </template>

        <template v-else-if="approvedCards.length > 0 && approved == false">    
            <!-- admin functions -->
            <div v-for="(card, index) in approvedCards" class="kb-content__card" :class="{'choosen': false}">
                <div>
                    <div class="kb-content__card-admin-func">
                        <img @click="chooseCards(card.id, card.title, card.slug)" src="/images/knowledge-base/knowleadge-base_select-func.svg" alt="">
                        <img v-if="delArray.length < 1" @click="reductCard(index, 'approved')" src="/images/knowledge-base/knowleadge-base_edit-func.svg" alt="">
                        <img v-if="delArray.length < 1" @click="delCard(card)" 
                            src="/images/knowledge-base/knowleadge-base_delete-func.svg" alt="">
                    </div>
                    <div class="kb-content__card-format">
                        <span class="card-format">{{ card.format[0].name }}</span>
                        <span v-if="card.lang !== 'rus'" class="card-format ml-2">{{ card.lang }}</span>
                    </div>
                    <img v-if="!card.image" src="/images/knowledge-base/knowleadge-base_cover.png" alt="">
                    <img v-else :src="card.image" alt="">
                    <div class="kb-content__card-tags">
                        <span class="kb__tag">
                            <span v-for="tag in card.tags"> #{{ tag.name }}</span>
                        </span>
                    </div>
                    <div class="kb-content__card-info">
                        <h5>{{ card.title }}</h5>
                        <p>{{ card.description}}</p>
                    </div>
                </div>
                
                <div class="kb-content__card-footer">
                    <a target="_blank" :href="route('knowledge-base.show.index', card)" class="kb__read-more">
                        Читать
                        <img src="/images/knowledge-base/knowleadge-base_arrow-up-right.svg" alt="">
                    </a>
                    <div @click="likePost(card)" class="kb__likes">
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

        <!-- load any cards -->

        
    </div>

    <div class="kb-content__load-any">
        <template v-if="approved == true && waitCards.length < waitPages.all">
            <button @click="loadPosts(100)">Показать еще {{ GetWait }} из {{ waitPages.all - waitCards.length }}</button>
        </template>
        <template v-else-if="approved == false && approvedCards.length < approvedPages.all">
            <button @click="loadPosts(200)">Показать еще {{ GetApproved }} из {{ approvedPages.all - approvedCards.length }}</button>
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

                <p class="attention"><sup>*</sup> — Поля, обязательные для заполнения</p>

                <h3 class="modal-title2 mb-24">Обложка материала<sup>*</sup></h3>
                <div class="kb-modal__image-block mb-52">
                    <div class="kb-modal__image">
                        <img src="/images/knowledge-base/knowleadge-base_cover.png" alt="">
                    </div>
                    <div class="kb-modal__load">
                        <p v-if="imageLoadedName">Загружено: {{ imageLoadedName }}</p>
                        <div class="kb-modal__load-title mb-52">
                            <label for="image">
                                <img src="/images/knowledge-base/knowleadge-base_add-file-icon.svg" alt="">
                                Добавить фото
                            </label>
                            <input type="file" id="image" ref="image" name="image" v-on:change="getImage()">    
                        </div>
                            
                        <span class="kb-modal__load-formats">Формат: jpg, png.</span>
                    </div>
                </div>

                
                <h3 class="modal-title2">Введите ссылку на материал или прикрепите необходимый файл<sup>*</sup></h3>

                <!-- link (input-red)-->
                <div class="input-field mb-24">
                    <input class="input" type="text" placeholder="https://example.ru"
                        :class="{'input-red': $v.forms.link.$error}" v-model.lazy="forms.link"  @blur="$v.forms.link.$touch()">
                    <template v-if="$v.forms.link.$error">
                        <img src="/images/knowledge-base/knowleadge-base_warning-icon.svg" class="field-warning">
                        <!-- <p v-if="!$v.forms.link.check" class="input-alert-msg">Такая ссылка уже есть в Базе Знаний</p> -->
                        <p v-if="!$v.forms.link.url" class="input-alert-msg">Веедена некоректная ссылка. Требуемый формат: https://example.ru</p>
                        <p v-else class="input-alert-msg">Данное поле обязательно для заполнения</p>
                    </template>
                </div>
                

                <div v-if="forms.file" class="add-file mb-24">
                    <a :href="forms.file.download"><img src="/images/knowledge-base/knowleadge-base_load-icon.svg" alt=""> {{ forms.file.name}} </a>
                    <label @click="delStorageFile(forms.slug)">
                        <img src="/images/knowledge-base/knowleadge-base_trash-icon.svg" alt=""> Удалить Файл
                    </label>
                </div>

                <!-- user loaded file name -->
                <div v-if="loadedFileName" class="added-file mb-24">
                    <p>{{ loadedFileName }}</p>
                    <img @click="delLoadedFile()" src="/images/knowledge-base/knowleadge-base_trash-icon.svg" alt="">
                    <img src="/images/knowledge-base/knowleadge-base_load-icon.svg" alt="">
                </div>

                <!-- add file -->
                <div class="add-file mb-52">
                    <label for="file"><img src="/images/knowledge-base/knowleadge-load-icon.svg" alt="">Добавить файл</label>
                    <input type="file" id="file" ref="file" name="file" v-on:change="getFileName()">
                    <span>(Формат: pdf, xls,xlsx, docs,docx; Максимальное количество файлов -1)</span>
                </div>
                
                <!-- formats radio button -->
                <h3 class="modal-title2">Укажите формат<sup>*</sup></h3>
                <div class="ui-kit__radio">
                    <template v-for="format in formats">
                        <input name="format" type="radio" :value="format.id" v-model="forms.format"
                            class="ui-kit__custom-radio"  :id="'format' + format.id">
                        <label :for="'format' + format.id">{{ format.name }}</label>
                    </template>
                </div>

                <!-- new format -->
                <template v-if="formats.filter((format) => { return format.name == 'Другое'}).length > 0 && forms.format == formats.filter((format) => { return format.name == 'Другое'})[0].id">
                    <h3 class="modal-title2">Укажите новый формат</h3>
                    <div class="input-field">
                        <input class="input" type="text" placeholder="Укажите новый формат" 
                            v-model="forms.newFormat.text" :class="{'input-red': forms.newFormat.max - forms.newFormat.text.length <= 0}">
                        <span class="chars-count" :class="{'chars-count-red': forms.newFormat.max - forms.newFormat.text.length <= 0}">
                            {{forms.newFormat.max - forms.newFormat.text.length }}
                        </span>
                        <template v-if="forms.newFormat.max - forms.newFormat.text.length <= 0">
                            <img src="/images/knowledge-base/knowleadge-base_warning-icon.svg" class="field-warning">
                            <p class="input-alert-msg">Название формата не должно превышать {{ forms.newFormat.max }} символов</p>
                        </template>
                    </div>
                </template>

                <!-- themes radio button -->
                <h3 class="modal-title2 mt-52">Выберите тему<sup>*</sup></h3>
                <div class="ui-kit__radio">
                    <template v-for="theme in themes">
                        <input name="theme" type="radio" :value="theme.id" v-model="forms.theme"
                            class="ui-kit__custom-radio" :id="'theme' + theme.id">
                        <label :for="'theme' + theme.id">{{ theme.name }}</label>
                    </template>
                </div>

                <!-- new theme -->
                <template v-if="themes.filter((theme) => { return theme.name == 'Другое'}).length > 0 && forms.theme === themes.filter((theme) => { return theme.name == 'Другое'})[0].id">
                    <h3 class="modal-title2">Укажите новую тему</h3>
                    <div class="input-field">
                        <input class="input" type="text" placeholder="Укажите новую тему" 
                            v-model="forms.newTheme.text" :class="{'input-red': forms.newTheme.max - forms.newTheme.text.length <= 0}">
                        <span class="chars-count" :class="{'chars-count-red': forms.newTheme.max - forms.newTheme.text.length <= 0}">
                            {{forms.newTheme.max - forms.newTheme.text.length }}
                        </span>
                        <template v-if="forms.newTheme.max - forms.newTheme.text.length <= 0">
                            <img src="/images/knowledge-base/knowleadge-base_warning-icon.svg" class="field-warning">
                            <p class="input-alert-msg">Название темы не должно превышать {{ forms.newTheme.max }} символов</p>
                        </template>
                    </div>
                </template>

                <!-- language -->
                <h3 class="modal-title2 mt-52">Выберете язык материала<sup>*</sup></h3>
                <div class="ui-kit__radio mb-52">
                    <input name="lang" type="radio" value="русский" class="ui-kit__custom-radio" id="rus-lang" checked>
                    <label for="rus-lang">Русский</label>

                    <input name="lang" type="radio" value="английский" class="ui-kit__custom-radio" id="eng-lang">
                    <label for="eng-lang">Английский</label>
                </div>

                <!-- Undercategory field-->
                <h3 class="modal-title2">Укажите подкатегорию темы</h3>
                <div class="input-field mb-52">
                    <input class="input" type="text" placeholder="Agile, презентации,  примеры заполнения, методические материалы..." 
                        v-model="forms.sub_theme.text" @blur="$v.forms.sub_theme.text.$touch()"
                        :class="{'input-red': $v.forms.sub_theme.text.$error}">
                    <span class="chars-count" :class="{'chars-count-red': $v.forms.sub_theme.text.$error}">
                        {{forms.sub_theme.max - forms.sub_theme.text.length }}
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
                    <template v-if="approved == true && forms.id">
                        <button  class="ui-btn-red mr-4" @click="updatePost('approved')">Опубликовать</button>
                        <button  class="ui-btn-antic" @click="rejectCard()">Отклонить</button>
                    </template>
                    <template v-else-if="approved == false && forms.id">
                        <button  class="ui-btn-red mr-4" @click="updatePost('update')">Сохранить</button>
                        <button  class="ui-btn-antic" @click="closeModal()">Отменить</button>
                    </template>
                    <template v-else>
                        <button  class="ui-btn-red mr-4" @click="store()">Добавить материал</button>
                        <button  class="ui-btn-antic" @click="closeModal()">Отменить</button>
                    </template>
                </div>
            </template>
            
            <!-- sendig OK (при успешной отправке материала) -->
            <template v-if="modalType === 'send-ok'">
                <div class="content">
                    <h2 class="modal-title">Материал успешно добавлен.</h2>

                    <p class="mb-52">Ваш материал успешно добавлен и опубликован</p>

                    <div class="kb-btns-line">
                        <button  @click="closeModal()" class="ui-btn-red">Хорошо</button>
                    </div>
                </div>
            </template>

            <!-- sendig false (ошибка при добавлении материала) -->
            <template v-if="modalType === 'send-false'">
                <div class="content">
                    <h2 class="modal-title">Ошибка</h2>

                    <p class="mb-52">Произошла ошибка. Попробуйте загрузить материал снова, либо попробуйте повторить позже.</p>
                    <div class="kb-btns-line">
                        <button  @click="closeModal()" class="ui-btn-red">Хорошо</button>
                    </div>
                </div>
            </template>

            <!-- reason for rejection -->
            <template v-if="modalType === 'rejection'">
                <div class="content">
                    <h2 class="modal-title">Причина отклонения материала</h2>

                    <div class="input-field mb-52">
                        <textarea name="description" class="text-aria" id="" cols="30" rows="2" 
                            placeholder="Напишите причину отклонения материала" v-model="reject">
                        </textarea>
                    </div>
                    <div class="kb-btns-line">
                        <button  @click="sendReject()" class="ui-btn-red">Отправить</button>
                    </div>
                </div>
            </template>

            <!-- confirm delete files -->
            <template v-if="modalType === 'delete'">
                <div class="content">
                    <h2 class="modal-title">Вы уверены, что хотите удалить следующие <br> материалы?</h2>
                    <div class="input-field mb-24">
                        <ol>
                            <li v-for="card in delArray">{{ card.title }}</li>
                        </ol>
                    </div>
                    <div class="kb-btns-line">
                        <button  @click="ConfirmDelCard()" class="ui-btn-red mr-4">Подтвердить</button>
                        <button  @click="closeModal()" class="ui-btn-antic">Отменить</button>
                    </div>
                </div>
            </template>

            </div>
        </div>
    </div>

</div>
</template>
<script>

    import {required, maxLength, url, } from 'vuelidate/lib/validators';

    export default {
        name: 'index',
        data(){
            return {
                // тип выбранных карточек
                approved: true,
                // Открытие модального окна
                madalOpen: false,
                modalType: 'form',
                // когда для удаления выбирают несколько карточек
                selectedCards: false,
                // строка поиска
                searchField: '',
                delArray: [],
                // ошибки
                errors: [],
                // причина отказа
                reject: '',
                // data
                // карточки на модерации
                waitCards: [],
                waitPages: {all: 0, current: 0, last: 0, any: 6},
                // карточки опубликованные
                approvedCards: [],
                approvedPages: {all: 0, current: 0, last: 0, any: 6},
                // фильтры
                formats: [],
                themes: [],
                filters: {
                    themes: 'all', activeTheme: 'Все темы', 
                    format: 'all', activeFormat: 'Все форматы',
                    orderBy: 'newest', activeOrderBy: 'По умолчанию'
                },
                // files
                file: null,
                loadedFileName: '',
                image: null,
                imageLoadedName: '',
                
                // форма
                forms: {
                    title: {max: 56, text: ''},
                    description: {max: 94, text: ''},
                    link: '',
                    format: 1,
                    theme: 1,
                    lang: 'rus',
                    sub_theme: {max: 40, text: ''},
                    tags: {max: 57, text: ''},
                    // новывя Тема и Формат
                    newFormat: {max: 20, text: ''},
                    newTheme: {max: 20, text: ''},
                }, 
            }
        },
        methods: {
            // добавить новый материал
            addMaterial() {
                this.resetFormFields();
                this.openModal('form');
            },
            // сброс полей формы
            resetFormFields() {
                this.forms.title = {max: 56, text: ''};
                this.forms.description = {max: 94, text: ''};
                this.forms.link = '';
                this.forms.format = 1;
                this.forms.theme = 1;
                this.forms.lang = 'rus';
                this.forms.sub_theme = {max: 40, text: ''};
                this.forms.tags = {max: 57, text: ''};
                this.forms.id = '';
                this.forms.slug = '';
                this.forms.file = null;
                this.imageLoadedName = '';
                this.image = null;
                this.forms.newFormat = {max: 20, text: ''};
                this.forms.newTheme = {max: 20, text: ''};
            },

            // open and close Modal window
            openModal(type) {
                this.modalType = type
                this.madalOpen = true;
                document.body.classList.add('lock');
            },

            closeModal() {
                this.madalOpen = false;
                this.delArray = [],
                this.errors = [],
                this.resetFormFields();
                document.body.classList.remove('lock');
            },
            // Меняет тип отображаемых карточек
            changeCardType() {
                if( this.approved == true )
                {
                    this.approved = false
                    this.activeCards = this.approvedCards;
                } else {
                    this.approved = true
                    this.activeCards = this.waitCards;
                }
                this.delArray = [];
            },
            // remove focus from filter
            filtersRemoveFocus()
            {
                let filters = document.querySelectorAll('.kb__filters');
                filters.forEach(item => {
                    item.blur();
                });
            },
            // reduct card set fields
            reductCard(index, type)
            {
                let card;

                switch (type)
                {
                    case 'wait':
                        card = this.waitCards[index];
                    break;
                    case 'approved':
                        card = this.approvedCards[index];
                    break;
                    default:
                    break;
                }

                let sub_theme = '';
                let tags = '';

                card.sub_theme.forEach((value) => {
                    sub_theme += `${value.name}, `
                })

                card.tags.forEach((value) => {
                    tags += `${value.name}, `
                })

                let cardField = {
                    title: {max: 56, text: card.title},
                    description: {max: 94, text: card.description},
                    link: card.link,
                    format: card.format[0].id,
                    theme: card.theme[0].id,
                    lang: card.lang,
                    sub_theme: {max: 40, text: this.arrayToString(card.sub_theme)},
                    tags: {max: 57, text: this.arrayToString(card.tags)},
                    id: card.id,
                    slug: card.slug,
                    file: card.file,
                    image: card.image,
                    newFormat: {max: 20, text: ''},
                    newTheme: {max: 20, text: ''},
                }
                this.forms = cardField;
                this.openModal('form')
            },

            // array to string
            arrayToString(array)
            {
                let last = array.length - 1,
                string = '';

                array.forEach((value, index) => {
                    last === index ? string += `${value.name}` : string += `${value.name}, ` 
                })
                return string;
            },

            // parse string on array
            parseString(string)
            {
                let array = [];
                string = string.split(',');
                string.forEach((value, index) => {
                    array.push(value.trim());
                });
                return array;
            },

            // remove focus from filter
            filtersRemoveFocus()
            {
                let filters = document.querySelectorAll('.kb__filters');
                filters.forEach(item => {
                    item.blur();
                });
            },

            // file name
            getFileName()
            {
                if(this.$refs.file) {
                    this.loadedFileName = this.$refs.file.files[0].name;
                    this.file = this.$refs.file.files[0];
                }
            },

            // image
            getImage()
            {
                if(this.$refs.image) {
                    this.imageLoadedName = this.$refs.image.files[0].name;
                    this.image = this.$refs.image.files[0];
                }
            },

            delLoadedFile()
            {
                this.loadedFileName = ''
                this.$refs.file.files.pop();
            },

            // Удаление файла с сервера
            delStorageFile(slug)
            {
                axios
                    .delete(route('api.knowledge-base.show.media.destroy'))
                    .then((res) => {
                        this.forms.file = null;
                    })
                    .catch(error => {
                        console.log(error);
                    });
            },

            // check link in the database R
            checkLink(value)
            {
                axios
                    .get(route('api.knowledge-base.search-link'), {
                        params: {
                            link: value
                        }
                    })
                    .then((res) => {
                        if(res.data.success == true) {
                            return true
                        } else {
                            return false
                        }
                    })
                    .catch(error => {
                        console.log(error);
                    });
            },

            // dellete 1 card modal open,
            delCard(card)
            {
                this.delArray.push(card);
                this.modalType = 'delete';
                this.madalOpen = true;
            },

            // delete card
            ConfirmDelCard()
            {
                let cards = this.approved == true ? this.waitCards : this.approvedCards;
                cards.forEach((card, index) => {
                    this.delArray.forEach((value) => {
                        if( value.id === card.id )
                        {
                            axios
                                .delete(route('api.knowledge-base.show.destroy', value.slug))
                                .then((response) => {
                                    if( response.data.success == true )
                                    {
                                        cards.splice(index, 1);
                                    }
                                })
                                .catch((error) => {
                                    console.log(error)
                                })
                        }
                    })
                });
                this.delArray = [];
                this.closeModal()
            },

            // load any posts
            loadPosts(status = 200)
            {
                var vm = this,
                    _query = {
                        orderBy: vm.filters.orderBy,
                        status: status,
                        page: 0,
                        themes:[],
                        formats:[],
                    };

                switch (status)
                {
                    case 200:

                        if( vm.approvedPages.current < vm.approvedPages.last )
                        {
                            vm.approvedPages.current++
                        }else{
                            vm.approvedPages.current;
                        }

                        _query.page = vm.approvedPages.current;

                        if( vm.filters.themes !== 'all' )
                        {
                            _query.themes.push(vm.filters.themes);
                        }

                        if( vm.filters.format !== 'all' )
                        {
                            _query.formats.push(vm.filters.format);
                        }
                        
                    break;
                    default:
                        
                        if( vm.waitPages.current < vm.waitPages.last )
                        {
                            vm.waitPages.current++;
                        }else{
                            vm.waitPages.current;
                        }
                        _query = {
                            status: 100,
                            page: vm.waitPages.current,
                        };
                    break;
                }

                axios
                    .get(route('api.knowledge-base.index', { _query: _query }))
                    .then(response => {
                        switch (status) {
                            case 100:
                                response.data.data.data.forEach((value) => {
                                    vm.waitCards.push(value)
                                });
                            break;
                            case 200:
                                response.data.data.data.forEach((value) => {
                                    vm.approvedCards.push(value)
                                });
                            break;
                        
                            default:
                            break;
                        }
                    })
            },

            // choose cards
            chooseCards(id, title, slug) {
                
                let indexOf = false;

                this.delArray.forEach((value, index) => {
                    if( value.id === id )
                    {
                        indexOf = index;
                    }
                })

                if(indexOf === false) {
                    this.delArray.push({id: id, title: title, slug: slug});
                } else {
                    this.delArray.splice(indexOf, 1);
                }

                this.selectedCards = this.delArray.length > 0 ? true : false;
            },

            reductCardFunction(index)
            {
                return this.delArray.indexOf(index, 0);
            },

            // Отклонить материал
            rejectCard()
            {
                this.modalType = 'rejection';   
            },

            sendReject()
            {
                var vm = this;
                axios
                    .post(route('api.knowledge-base.show.reject', vm.forms.slug), {
                        reject: vm.reject
                    })
                    .then(response => {
                        if( response.data.success == true )
                        {
                            vm.waitCards.forEach((value, index) => {
                                if( value.id === vm.forms.id )
                                {
                                    vm.waitCards.splice(index, 1)
                                }
                            })
                            vm.reject = '';
                            vm.closeModal();
                        } else {
                            openModal('send-false');
                        }
                    });
            },

            // remove focus from filter
            filtersRemoveFocus() {
                let filters = document.querySelectorAll('.kb__filters');
                filters.forEach(item => {
                    item.blur();
                });
            },
            
            // Запрос по фильтрам
            getFilter(type, value)
            {
                switch (type)
                {
                    case 1:
                        if( value === 'all' )
                        {
                            this.filters.themes = 'all'; 
                            this.filters.activeTheme = 'Все темы';
                        } else {
                            this.filters.themes = value.id;
                            this.filters.activeTheme = value.name
                        }
                    break;
                    case 2:
                        if(value === 'all')
                        {
                            this.filters.format = 'all'; 
                            this.filters.activeFormat = 'Все форматы';
                        } else {
                            this.filters.format = value.id;
                            this.filters.activeFormat = value.name
                        }
                    break;
                    case 3:
                        switch (value)
                        {
                            case 'popular':
                                this.filters.orderBy = 'popular';
                                this.filters.activeOrderBy = 'Сначала популярные';
                            break;
                            case 'newest':
                                this.filters.orderBy = 'newest';
                                this.filters.activeOrderBy = 'Сначала новые';
                            break;
                            case 'likes':
                                this.filters.orderBy = 'likes';
                                this.filters.activeOrderBy = 'По лайкам';
                            break;
                            default:
                            break;
                        }
                    break;
                    default:
                    break;
                }
                this.filtersRemoveFocus();
                this.filterSearch();
            },

            filterSearch()
            {
                var vm = this,
                    _query = {
                        status: 200,
                        orderBy: vm.filters.orderBy,
                        themes:[],
                        formats:[],
                    };

                if( vm.filters.themes !== 'all' )
                {
                    _query.themes.push(vm.filters.themes);
                }

                if( vm.filters.format !== 'all' )
                {
                    _query.formats.push(vm.filters.format);
                }

                axios
                    .get(route('api.knowledge-base.index', { _query: _query }))
                    .then(response => {

                        vm.approvedCards = response.data.data.data;
                        vm.approvedPages.all = response.data.data.allcount;
                        vm.approvedPages.current = response.data.data.currentPage;
                        vm.approvedPages.last = response.data.data.lastPage;
                    })
                    .catch((error) => {
                        console.log(error)
                    });
            },

            // SERVER
            // ПОИСК
            searchCard()
            {
                let status;
                status = this.approved === true ? 100 : 200;

                if( !this.searchField )
                {

                    if( this.approved === true )
                    {
                        this.getWaitCards()
                    }else{
                        this.getApprovedCards();
                    }

                } else {
                    axios
                        .get(route('api.knowledge-base.index'), {
                            params: {
                                status: status,
                                search: this.searchField
                            }
                        })
                        .then(response => {
                            switch(status)
                            {
                                case 100:
                                    this.waitCards = response.data.data.data;
                                break;
                                default:
                                    this.approvedCards = response.data.data.data;
                                break;
                            }    
                        })
                }
            },

            // store 
            store()
            {
                let formData = new FormData();
                
                formData.append('title', this.forms.title.text);
                formData.append('description', this.forms.description.text);
                formData.append('link', this.forms.link);
                formData.append('format', this.forms.format);
                formData.append('theme', this.forms.theme);

                if( this.forms.sub_theme.text !== '' )
                {
                    formData.append('sub_theme', this.forms.sub_theme.text);
                }
                
                if( this.forms.tags.text !== '' )
                {
                    formData.append('tags', this.forms.tags.text);
                }

                formData.append('image', this.image);

                if( this.forms.newFormat.text !== '' && ( this.formats.filter((format) => { return format.name == 'Другое'}).length > 0 ) && this.forms.format === this.formats.filter((format) => { return format.name == 'Другое'})[0].id )
                {
                    formData.append('newFormat', this.forms.newFormat.text);
                }

                if( this.forms.newTheme.text !== '' && ( this.themes.filter((theme) => { return theme.name == 'Другое'}).length > 0 ) && this.forms.theme === this.themes.filter((theme) => { return theme.name == 'Другое'})[0].id )
                {
                    formData.append('newTheme', this.forms.newTheme.text);
                }

                axios
                    .post(route('api.knowledge-base.store.admin'), formData, {
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        }
                    })
                    .then( response => {
                        if( response.data.success == false )
                        {
                            this.openModal('send-false', error)
                            
                        } else {
                            this.openModal('send-ok');
                            this.getApprovedCards();
                            this.resetFormFields();
                        }
                        // delete window["forms"]
                    })
                    .catch(error => {
                        console.log(error);
                    });
            },

            // Лайки (likes)
            likePost(card)
            {
                axios
                    .post(route('api.knowledge-base.show.sulikes', card))
                    .then(response => {
                        card.liked = response.data.data.liked
                        card.likes = response.data.data.likes
                    })
            },

            // update post
            updatePost(type)
            {
                let url = '',
                    card;
                let formData = new FormData();

                switch (type)
                {
                    case 'approved':
                        url = route('api.knowledge-base.show.approved', this.forms.slug);
                    break;
                    default:
                        url = route('api.knowledge-base.show.update', this.forms.slug);
                    break;
                }
                
                formData.append('title', this.forms.title.text);
                formData.append('description', this.forms.description.text);
                formData.append('link', this.forms.link);
                formData.append('format', this.forms.format);
                formData.append('theme', this.forms.theme);
                formData.append('image', this.image);

                if( this.forms.sub_theme.text !== '' )
                {
                    formData.append('sub_theme', this.forms.sub_theme.text);
                }

                if( this.forms.tags.text !== '' )
                {
                    formData.append('tags', this.forms.tags.text);
                }

                if( this.file )
                {
                    formData.append('file', this.file);
                }
                
                if( this.forms.newFormat.text !== '' && this.formats.filter((format) => { return format.name == 'Другое'}).length > 0 && this.forms.format === this.formats.filter((format) => { return format.name == 'Другое'})[0].id )
                {
                    formData.append('newFormat', this.forms.newFormat.text)
                }

                if( this.forms.newTheme.text !== '' && this.themes.filter((theme) => { return theme.name == 'Другое'}).length > 0 && this.forms.theme === this.themes.filter((theme) => { return theme.name == 'Другое'})[0].id )
                {
                    formData.append('newTheme', this.forms.newTheme.text)
                }

                axios
                    .post(url, formData, {
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        }
                    })
                    .then( response => {

                        if( response.data.success == false )
                        {
                            this.openModal('send-false');
                        } else {

                            switch (type)
                            {
                                case 'approved':
                                    this.waitCards.forEach((value, index) => {
                                        if( value.id === this.forms.id )
                                        {
                                            this.waitCards.splice(index, 1);
                                        }  
                                    })
                                    this.getApprovedCards(200);
                                break;
                                default:
                                    this.approvedCards.forEach((value, index) => {
                                        if( value.id === response.data.data.id )
                                        {
                                            this.approvedCards[index] = response.data.data;
                                        }
                                    });
                                break;
                            }
                            this.openModal('send-ok');
                        }
                    })
                    .catch(error => {
                        console.log(error);
                    }) 
            },

            getWaitCards(status = 100)
            {
                var vm = this;
                axios
                    .get(route('api.knowledge-base.index'), {
                        params:{
                            status: status
                        }
                    })
                    .then(response => {
                        vm.waitCards = response.data.data.data;
                        vm.formats = response.data.data.filters.formats.lists;
                        vm.themes = response.data.data.filters.themes.lists;

                        vm.waitPages.all = ( response.data.data.allcount - vm.waitCards.length );
                        vm.waitPages.current = response.data.data.currentPage;
                        vm.waitPages.last = response.data.data.lastPage;
                    });
            },

            getApprovedCards(status = 200)
            {
                var vm = this;
                axios
                    .get(route('api.knowledge-base.index'), {
                        params:{
                            status: status
                        }
                    })
                    .then(response => {
                        vm.approvedCards = response.data.data.data;
                        vm.approvedPages.all = response.data.data.allcount;
                        vm.approvedPages.current = response.data.data.currentPage;
                        vm.approvedPages.last = response.data.data.lastPage;  
                    });
            },
        },
        computed:{
            GetWait()
            {
                if ( this.waitPages.all - this.waitCards.length > 6 )
                {
                    return 6;
                } else {
                    return this.waitPages.all - this.waitCards.length;
                }
            },
            GetApproved()
            {
                if( this.approvedPages.all - this.approvedCards.length > 6 )
                {
                    return 6;
                } else {
                    return this.approvedPages.all - this.approvedCards.length
                }
            }
        },
        beforeMount() {
            this.getWaitCards();
            this.getApprovedCards();
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
                    required,
                    url,
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
                },
            }   
        }
    }
</script>
