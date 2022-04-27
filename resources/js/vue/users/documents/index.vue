<template>
    <div class="my-documents__table">
        <!-- ЭЛЕМЕНТЫ СОРТИРОВКИ ДОКУМЕНТОВ ПО ГОТОВНОСТИ-->
        <div class="my-documents__categories">
            <div class="my-documents__categories-sort">
                <div @click="sortSatus = 'all'" class="status" 
                    :class="{active: sortSatus === 'all'}">Все</div>
                <div @click="sortSatus = 'draft'" class="status" 
                    :class="{active: sortSatus === 'draft'}">Черновики</div>
                <div @click="sortSatus = 'ready'" class="status" 
                    :class="{active: sortSatus === 'ready'}">Готовые паспорта</div>
            </div>
            <button @click="type = 'newdoc'" class="my-documents__categories-btn popup-link">Добавить документ</button>
        </div>

        

        <!-- ЧЕРНОВИКИ -->
        <div v-show="sortSatus === 'all' ||  sortSatus === 'draft'" class="my-documents__table-drafts">
            
            <!-- ПОИСК И СОРТИРОВКА ПО ТИПУ -->
            <div class="digital_title-categoty">
                <h3 >Черновики</h3>
            
                <div class="my-documents__searching">
                    <div class="my-documents__searching-search">
                        <img src="/images/passport/passport_search.svg" width="24px" height="24px" alt="">
                        <input type="text" placeholder="Поиск по названию" v-model="searchField.unready" @change="searchCard('unready')">
                    </div>
                    <div class="my-documents__searching-filter">
                        <select v-model="draftFilter">
                            <option selected disabled>Тип документа</option>
                            <option value="all">Все</option>
                            <option value="idea">Идея проекта</option>
                            <option value='passport'>Паспорт проекта</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Заголовки таблицы -->
            <div class="my-documents__table-title">
                <div class="my-documents__table-name">Название</div>
                <div class="my-documents__table-type">Тип</div>
                <div class="my-documents__table-date">
                    Дата создания</div>
                <div class="my-documents__table-btns"></div>
            </div>
            
            <!-- Вывод черновиков документов-->
            <div v-for="(item, index) in myPassports.unreadyFiltered" :key="item.id">
                <template v-if="draftFilter === 'all' || draftFilter === item.type">
                <div class="my-documents__table-item">
                    <div class="my-documents__mobyle-title">Название</div>
                    <div class="my-documents__table-name">{{ item.title }}</div>
                    <div class="my-documents__mobyle-title">Тип</div>
                    <div v-if="item.type === 'idea'" class="my-documents__table-type">Идея</div>
                    <div v-if="item.type === 'passport'" class="my-documents__table-type">Паспорт</div>
                    <div class="my-documents__mobyle-title">Дата создания</div>
                    <div class="my-documents__table-date">{{ item.date }}</div>
                    <!-- Кнопки управления -->
                    <div class="my-documents__mobyle-title"></div>
                    <div class="my-documents__table-btns">
                        <img @click="reductPopup(index, item.type, 'unready')" class="popup-link" src="/images/passport/passport_eye.svg" 
                            title="Предпросмотр" width="24px" height="24px">
                        <img @click="draftReduct(item)" src="/images/passport/passport_edit.svg" 
                            title="Редактировать" width="24px" height="24px">
                        <img @click="copyProject(item)" src="/images/passport/passport_clone.svg" 
                            title="Копировать" width="24px" height="24px">
                        <img @click="delPopup(index, 'unready')" class="popup-link" src="/images/passport/passport_delete.svg" 
                            title="Удалить" width="24px" height="24px">
                    </div>
                </div>
                </template>
            </div>
        </div>

        <!-- ГОТОВЫЕ ПАСПОРТА -->
        <div v-show="sortSatus === 'all' ||  sortSatus === 'ready'" class="my-documents__table-ready">
            
            <!-- ПОИСК И СОРТИРОВКА ПО ТИПУ -->
            <div class="digital_title-categoty">
                <h3>Готовые паспорта</h3>
                <div class="my-documents__searching">
                    <div class="my-documents__searching-search">
                        <img src="/images/passport/passport_search.svg" width="24px" height="24px" alt="">
                        <input type="text" placeholder="Поиск по названию" v-model="searchField.ready" @change="searchCard('ready')">
                    </div>
                    <div class="my-documents__searching-filter">
                        <select v-model="readyFilter">
                            <option selected disabled>Тип документа</option>
                            <option value="all">Все</option>
                            <option value="idea">Идея проекта</option>
                            <option value='passport'>Паспорт проекта</option>
                        </select>
                    </div>
                </div>
            </div>
        
            <!-- Заголовки таблицы -->
            <div class="my-documents__table-title">
                <div class="my-documents__table-name">Название</div>
                <div class="my-documents__table-type">Тип</div>
                <div class="my-documents__table-date">Дата создания</div>
                <div class="my-documents__table-btns"></div>
            </div>

            <!-- Вывод готовых документов -->
            <div v-for="(item, index) in myPassports.readyFiltered" :key="item.id">
                <template v-if="readyFilter === 'all' || readyFilter === item.type">
                <div class="my-documents__table-item">
                    <div class="my-documents__mobyle-title">Название</div>
                    <div class="my-documents__table-name">{{ item.title }}</div>
                    <div class="my-documents__mobyle-title">Тип</div>
                    <div v-if="item.type === 'idea'" class="my-documents__table-type">Идея</div>
                    <div v-if="item.type === 'passport'" class="my-documents__table-type">Паспорт</div>
                    <div class="my-documents__mobyle-title">Дата создания</div>
                    <div class="my-documents__table-date">{{ item.date }}</div>
                    <!-- Кнопки управления -->
                    <div class="my-documents__mobyle-title"></div>
                    <div class="my-documents__table-btns">
                        <img @click="reductPopup(index, item.type, 'ready')" class="popup-link" src="/images/passport/passport_edit.svg" 
                            title="Редактировать" width="24px" height="24px">
                        <img @click="copyProject(item)" src="/images/passport/passport_clone.svg" 
                            title="Копировать" width="24px" height="24px">
                        <img @click="generatePDF(item)" src="/images/passport/passport_load.svg" 
                            title="Скачать PDF" width="24px" height="24px">
                        <img @click="delPopup(index, 'ready')" class="popup-link" src="/images/passport/passport_delete.svg" 
                            title="Удалить" width="24px" height="24px">
                    </div>
                </div>
                </template>
            </div>
        </div>
        
        <!-- МОДАЛЬНЫЕ ОКНА -->
        <div id="popup" class="popup">
                <div class="popup__body">
                    <div class="popup-show digital-idea__popup">
                        <div @click="closePopup()" class="digital-idea__popup-header">
                            <img class="ui__close-btn close-popup" src="/images/close_popup.svg" alt="">
                        </div>
                    <!-- Dew doc -->
                    <template v-if="type === 'newdoc'">
                        <div class="popup__content ">
                            <h3>Выберите документ, который хотите создать</h3>
                            <form action="" id="add-document"> 
                                <div class="input-radio">
                                    <input id="1" type="radio" class="custom-radio" selected
                                            name="document" value="idea" v-model="docType">
                                    <label for="1">Идея проекта</label>
                                </div>
                                <div>
                                    <input id="2" type="radio" class="custom-radio"
                                            name="document" value="passport" v-model="docType">
                                    <label for="2">Паспорт проекта</label>
                                </div>
                            </form>
                            <button @click.prevent="startProject" class="passport__del-btn-yes">Создать</button>
                        </div>
                    </template>


                    <!-- Delete popup -->
                    <template v-else-if="type === 'delete'" >
                        <div class="popup__delete-doc">
                            <h3>Удаление документа</h3>
                            <div class="popup__content">
                                <p>Вы точно хотите удалить <b>«{{delItem.title}}»</b>?</p>
                                <div class="passport__del-btn">
                                    <button @click="delPassport(delItem)" class="passport__del-btn-yes">Удалить</button>
                                    <button @click="closePopup()" class="passport__del-btn-no ">Отменить</button>
                                </div>
                            </div>
                        </div>
                    </template>

                    <!-- Reduct popup -->
                    <!-- DIGITAL IDEA -->
                <template v-else-if="type === 'idea'">
                    <div @click="closePopup()" class="digital-idea__popup-header">
                            <h3>Цифровая идея</h3>
                            <!-- <img class="ui__close-btn close-popup" src="/images/close_popup.svg" alt=""> -->
                        </div>
                        
                        <div class="digital-idea__popup-content">
                            <!-- title -->
                            <div class="fields-input-title mt-3">
                                <textarea type="text" class="popup-title" v-model="reductReadyDoc.title" :class="{'reduct-on': reduct}"
                                placeholder="Вы не указали название проекта" 
                                :readonly="!reduct"></textarea>
                            </div>

                            <div class="digital-idea__popup-content-desc">
                                <div class="digital-idea__popup-content-name">
                                    <p>Организация</p>
                                    <textarea type="text" v-model="reductReadyDoc.organization"
                                        :class="{'reduct-on': reduct}" :readonly="!reduct"></textarea>

                                </div>
                            </div>

                            <h4>1. Видение реализации проекта и предпосылки</h4>
                            <h5>Видение реализации проекта</h5>
                            <div class="fields-input-title mt-3">
                                <textarea rows="8" type="text" v-model="reductReadyDoc.json.vision" :class="{'reduct-on': reduct}" 
                                :readonly="!reduct"></textarea>
                            </div>

                            <h5>Предпосылки проекта</h5>
                            <div v-if="errors.includes('json.prerequisites')" class="difital-idea__form-error">
                                {{getErrorDescription('json.prerequisites')}}
                            </div>
                            <div class="fields-input-title mt-3">
                                <textarea rows="8" type="text" 
                                :invalid="errors.includes('json.prerequisites')"
                                v-model="reductReadyDoc.json.prerequisites" 
                                :class="{'reduct-on': reduct}" 
                                :readonly="!reduct"></textarea>
                            </div>

                            <!-- Цели -->
                            <h4>2. Цели и результаты проекта</h4>
                            <h5>Цели</h5>
                            <div v-if="errors.includes('json.objectives')" class="difital-idea__form-error">
                                {{getErrorDescription('json.objectives')}}
                            </div>
                            <div v-for="(item, i) in reductReadyDoc.json.objectives" class="digital-popup__form-fields-input">
                                <input type="text" placeholder="Введите цель, которой необходимо достичь по итогам реализации идеи" 
                                    :invalid="errors.includes('json.objectives')"
                                    v-model="reductReadyDoc.json.objectives[i]" 
                                    :class="{'reduct-on': reduct}" :readonly="!reduct">
                                <div v-if="reductReadyDoc.json.objectives.length > 1 && reduct"
                                    @click="delField('objectives', i)" class="digital-delete-field">Удалить
                                </div>
                            </div>
                            <div v-if="reductReadyDoc.json.objectives.length < 10 && reduct == true" 
                                class="digital-idea__form-add-field" 
                                @click="addObjective()">
                                <img src="/images/passport/add-field.svg" alt="">
                                <span>Добавить цель</span>
                            </div>

                            <!-- Результаты -->
                            <h5>Результаты или продукты проекта</h5>
                            <div v-if="errors.includes('json.results')" class="difital-idea__form-error">
                                {{getErrorDescription('json.results')}}
                            </div>
                            <div v-for="(item, i) in reductReadyDoc.json.results"
                                class="digital-popup__form-fields-input">
                                <input type="text" 
                                    :invalid="errors.includes('json.results')"
                                    placeholder="Введите ожидаемый результат или продукт проекта" 
                                    v-model="reductReadyDoc.json.results[i]" :class="{'reduct-on': reduct}" :readonly="!reduct">
                                <div v-if="reductReadyDoc.json.results.length > 1 && reduct == true"
                                    @click="delField('results', i)" class="digital-delete-field">Удалить
                                </div>
                            </div>
                            <div v-if="reductReadyDoc.json.results.length < 10 && reduct" 
                                class="digital-idea__form-add-field" 
                                @click="addResult()">
                                <img src="/images/passport/add-field.svg" alt="">
                                <span>Добавить цель</span>
                            </div>

                            <!-- buttons -->
                            <!-- редактирования -->
                            <div v-if="reduct == true" class="digital__popup-btns mt-5">
                                <button  @click="updatePassport(1)"     
                                        class="ui-btn-white close-popup">Сохранить и закрыть</button>

                                <div class="d-flex">
                                    <button @click="generatePDF(reductReadyDoc)" class="ui-btn-antic mr-3">Скачать</button>
                                    <button @click="closePopup()" class="ui-btn-red close-popup">Закрыть</button>
                                </div>
                            </div>

                            <!-- Режим просмотра -->
                            <div v-else class="digital__popup-btns mt-5">
                                <div v-if="reduct == false" class="d-flex">
                                    <button @click="closePopup()" class="ui-btn-red close-popup">Закрыть</button>
                                </div>
                            </div>
                        </div>
                </template>

                <!-- Цифровой паспорт -->
                <template v-else-if="type === 'passport'">
                            <div class="digital__popup-header">
                            <h3>Цифровой паспорт</h3>
                            <!-- <img class="ui__close-btn close-popup" src="/images/close_popup.svg" alt=""> -->
                        </div>

                        <div class="digital-idea__popup-content">
                            <!-- title -->
                            <div v-if="errors.includes('title')" class="difital-idea__form-error">
                                {{getErrorDescription('title')}}
                            </div>
                            <div class="fields-input-title mt-3">
                                <textarea type="text" class="popup-title" 
                                :invalid="errors.includes('title')"
                                v-model="reductReadyDoc.title" :class="{'reduct-on': reduct}"
                                placeholder="Вы не указали название проекта" 
                                :readonly="!reduct"></textarea>
                            </div>
                            <!-- organisation -->
                            <div class="digital-idea__popup-content-desc">
                                <div class="digital-idea__popup-content-name">
                                    <p>Организация:</p>
                                    <textarea type="text" v-model="reductReadyDoc.organization"
                                        :class="{'reduct-on': reduct}" :readonly="!reduct"></textarea>
                                </div>
                            </div>
                            
                            <!-- Текущий статус проекта, даты инициализации и завершения проекта -->
                            <div class="digital__popup-content-status mb-3">
                                <div>
                                    <h5>Текущий статус проекта</h5>
                                    <input type="text" placeholder="Статус проекта" 
                                    v-model="reductReadyDoc.json.status" :class="{'reduct-on': reduct}" :readonly="!reduct">
                                </div>
                                <div>
                                    <h5>Дата инициации</h5>
                                    <input type="date" placeholder="Статус проекта" min="1990-01-01" max="2050-12-31"
                                    v-model="reductReadyDoc.json.startDate" :class="{'reduct-on': reduct}" :readonly="!reduct">
                                </div>
                                <div>
                                    <h5>Дата инициации</h5>
                                    <input type="date" placeholder="Статус проекта" min="1990-01-01" max="2050-12-31"
                                    v-model="reductReadyDoc.json.finishDate " :class="{'reduct-on': reduct}" :readonly="!reduct">
                                </div>
                            </div>

                            <!-- Описание и предпосылки проекта -->
                            <h4>1. Описание и предпосылки проекта</h4>
                            <h5>Описание проекта</h5>
                            <div v-if="errors.includes('json.prerequisites')" class="difital-idea__form-error">
                                {{getErrorDescription('json.prerequisites')}}
                            </div>
                            <div class="fields-input-title mt-3">
                                <textarea rows="8" type="text" 
                                :invalid="errors.includes('json.prerequisites')"
                                v-model="reductReadyDoc.json.description" :class="{'reduct-on': reduct}" 
                                :readonly="!reduct"></textarea>
                            </div>
                            
                            <h5>Предпосылки проекта</h5>
                            <div class="fields-input-title mt-3">
                                <textarea rows="8" type="text" v-model="reductReadyDoc.json.prerequisites" :class="{'reduct-on': reduct}" 
                                :readonly="!reduct" class="mb-3"></textarea>
                            </div>

                            <!-- Цели и реузьтаты проекта -->
                            <h4 class="mt-4">2. Цели и результаты проекта</h4>
                            <!-- Цели -->
                            <h5>Цель</h5>
                            <div v-if="errors.includes('json.objectives')" class="difital-idea__form-error">
                                {{getErrorDescription('json.objectives')}}
                            </div>
                            <div v-for="(item, i) in reductReadyDoc.json.objectives" class="digital-popup__form-fields-input">
                                <input  type="text" 
                                    :invalid="errors.includes('json.objectives')" @change="onFieldUpdate" 
                                    placeholder="Цель, которой необходимо достичь в рамках проекта" 
                                    v-model="reductReadyDoc.json.objectives[i]" :class="{'reduct-on': reduct}" :readonly="!reduct">
                                <div v-if="reductReadyDoc.json.objectives.length > 1 && reduct == true"
                                    @click="delField('objectives', i)" class="digital-delete-field">Удалить
                                </div>
                            </div>
                            <div v-if="reductReadyDoc.json.objectives.length < 10 && reduct == true" class="digital__form-add-field" 
                                @click="addField('objectives')">
                                <img src="/images/passport/add-field.svg" alt="">
                                <span>Добавить цель</span>
                            </div>

                            <!-- Метрика -->
                            <div class="digital-idea__popup-metrics">
                                <div class="digital-idea__popup-metrics-name">
                                    <h5>Название метрики</h5>
                                    <div v-if="errors.includes('json.metrics')" class="difital-idea__form-error">
                                        {{getErrorDescription('json.metrics')}}
                                    </div>
                                    <div v-for="(item, i) in reductReadyDoc.json.metrics" class="digital-popup__form-fields-input">
                                        <input  type="text" placeholder="Параметр успешности проекта" 
                                            :invalid="errors.includes('json.metrics')" @change="onFieldUpdate" 
                                            v-model="reductReadyDoc.json.metrics[i].metric" 
                                            :class="{'reduct-on': reduct}" :readonly="!reduct">
                                        <div v-if="reductReadyDoc.json.metrics.length > 1 && reduct == true"
                                            @click="delField('metrics', i)" class="digital-delete-field">Удалить
                                        </div>
                                    </div>
                                </div>
                                <div class="digital-idea__popup-metrics-target">
                                    <h5>Целевой показатель</h5>
                                    <div v-for="(item, i) in reductReadyDoc.json.metrics" class="digital-popup__form-fields-input">
                                        <input  type="text" placeholder="Число, %, иное" 
                                            v-model="reductReadyDoc.json.metrics[i].index" 
                                            :class="{'reduct-on': reduct}" :readonly="!reduct">
                                    </div>
                                </div>
                                <div class="digital-idea__popup-metrics-before">
                                    <h5>Было</h5>
                                    <div v-for="(item, i) in reductReadyDoc.json.metrics" class="digital-popup__form-fields-input">
                                        <input type="text" placeholder="Значение до"
                                        v-model="reductReadyDoc.json.metrics[i].dateFefore"
                                        :class="{'reduct-on': reduct}" :readonly="!reduct">
                                    </div>
                                </div>
                                <div class="digital-idea__popup-metrics-after">
                                    <h5>Стало</h5>
                                    <div v-for="(item, i) in reductReadyDoc.json.metrics" class="digital-popup__form-fields-input">
                                        <input type="text" placeholder="Значение после"
                                        v-model="reductReadyDoc.json.metrics[i].dateAfter"
                                        :class="{'reduct-on': reduct}" :readonly="!reduct">
                                    </div>
                                </div>
                            </div>
                            <div v-if="reductReadyDoc.json.metrics.length < 10 && reduct == true" class="digital__form-add-field" 
                            @click="addMetrics()">
                                <img src="/images/passport/add-field.svg" alt="">
                                <span>Добавить метрику</span>
                            </div>
                    

                            <h5>Результаты и продукты проекта</h5>
                            <div v-if="errors.includes('json.results')" class="difital-idea__form-error">
                                {{getErrorDescription('json.results')}}
                            </div>
                            <div v-for="(item, i) in reductReadyDoc.json.results" class="digital-popup__form-fields-input">
                                <input  type="text" placeholder="Ожидаемый результат или продукт проекта" 
                                    :invalid="errors.includes('json.results')" @change="onFieldUpdate" 
                                    v-model="reductReadyDoc.json.results[i]" :class="{'reduct-on': reduct}" 
                                    :readonly="!reduct">
                                <div v-if="reductReadyDoc.json.results.length > 1"
                                    @click="delField('results', i)" class="digital-delete-field">Удалить
                                </div>
                            </div>
                            <div v-if="reductReadyDoc.json.results.length < 10 && reduct == true" class="digital__form-add-field" 
                                @click="addField('results')">
                                <img src="/images/passport/add-field.svg" alt="">
                                <span>Добавить результат или продукт</span>
                            </div>

                            <h5>Риски проекта</h5>
                            <div v-if="errors.includes('json.risks')" class="difital-idea__form-error">
                                {{getErrorDescription('json.risks')}}
                            </div>
                            <div v-for="(item, i) in reductReadyDoc.json.risks" class="digital-popup__form-fields-input">
                                <input  type="text" placeholder="Выявленный риск проекта" 
                                    :invalid="errors.includes('json.risks')" @change="onFieldUpdate" 
                                    v-model="reductReadyDoc.json.risks[i]" :class="{'reduct-on': reduct}" 
                                    :readonly="!reduct">
                                <div v-if="reductReadyDoc.json.risks.length > 1 && reduct == true"
                                    @click="delField('risks', i)" class="digital-delete-field">Удалить
                                </div>
                            </div>
                            <div v-if="reductReadyDoc.json.risks.length < 10 && reduct == true" class="digital__form-add-field" 
                                @click="addField('risks')">
                                <img src="/images/passport/add-field.svg" alt="">
                                <span>Добавить возможный риск</span>
                            </div>

                            <!-- Лица вовлеченные в проект -->
                            <h4 class="mt-3">3. Лица, вовлеченные в проект</h4>
                            <h5>Руководитель проекта</h5>
                            <div v-if="errors.includes('json.director')" class="difital-idea__form-error">
                                {{getErrorDescription('json.director')}}
                            </div>
                            <div class="digital-popup__form-fields-input">
                                <input type="text" placeholder="Руководитель проекта" 
                                    :invalid="errors.includes('json.director')" @change="onFieldUpdate" 
                                    v-model="reductReadyDoc.json.director" 
                                    class="mb-2" :class="{'reduct-on': reduct}" :readonly="!reduct">
                            </div>

                            <h5>Команда проекта</h5>
                            <div v-for="(item, i) in reductReadyDoc.json.teamMates" class="digital-popup__form-fields-input">
                                <input  type="text" placeholder="Введите ФИО участника" 
                                    v-model="reductReadyDoc.json.teamMates[i]" :class="{'reduct-on': reduct}" :readonly="!reduct">
                                <div v-if="reductReadyDoc.json.teamMates.length > 1 && reduct == true"
                                    @click="delField('teamMates', i)" class="digital-delete-field">Удалить
                                </div>
                            </div>
                            <div v-if="reductReadyDoc.json.teamMates.length < 10 && reduct == true" class="digital__form-add-field" 
                                @click="addField('teamMates')">
                                <img src="/images/passport/add-field.svg" alt="">
                                <span>Добавить участника</span>
                            </div>


                            <h5 class="mb-3">Другие заинтересованные лица</h5>
                            <div class="digital__popup-content-interest">
                                <div class="digital__popup-content-role">
                                    <h5>Роль</h5>
                                    <div v-for="(item, i) in reductReadyDoc.json.interestMan" > 
                                        <span v-if="reduct == false">{{ item.role }}</span>
                                        <select v-else name="" id="" size="1" :class="{'reduct-on': reduct}" :readonly="!reduct"
                                            v-model="reductReadyDoc.json.interestMan[i].role">
                                            <option value="" selected disabled>Роль</option>
                                            <option value="Инициатор">Инициатор</option>
                                            <option value="Заказчик">Заказчик</option>
                                            <option value="Тех. заказчик">Тех. заказчик</option>
                                            <option value="Куратор">Куратор</option>
                                            <option value="Консультант">Консультант</option>
                                            <option value="Другое">Другое</option>
                                        </select>
                                        
                                    </div>
                                </div>
                                <div class="digital__popup-content-name">
                                    <h5>ФИО</h5>
                                    <div v-for="(item, i) in reductReadyDoc.json.interestMan">  
                                        <input type="text" :class="{'reduct-on': reduct}" :readonly="!reduct"
                                            placeholder="ФИО вовлеченного лица" 
                                            v-model="reductReadyDoc.json.interestMan[i].name">
                                        <div v-if="reductReadyDoc.json.interestMan.length > 1 && reduct == true"
                                            @click="delField('interestMan', i)"  class="digital-delete-field">Удалить
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div v-if="reductReadyDoc.json.interestMan.length < 10 && reduct == true" class="digital__form-add-field" 
                                @click="addInterestMan()">
                                <img src="/images/passport/add-field.svg" alt="">
                                <span>Добавить заинтересованное лицо</span>
                            </div>

                            <!-- Ресурсы -->
                            <h4 class="mt-4">4. Ресурсы, бюджет и план проекта</h4>
                            <div v-if="errors.includes('json.resources')" class="difital-idea__form-error">
                                {{getErrorDescription('json.resources')}}
                            </div>
                            <div class="digital__popup-content-interest">
                                <div class="digital__popup-content-name mr-3">
                                    <h5>Ресурсы</h5>
                                    <div v-for="(item, i) in reductReadyDoc.json.resources">  
                                        <input type="text" :class="{'reduct-on': reduct}" :readonly="!reduct"
                                            :invalid="errors.includes('json.resources')" @change="onFieldUpdate" 
                                            placeholder="Ресурс, необходимый для реализации проекта" 
                                            v-model="reductReadyDoc.json.resources[i].resource">
                                        <div v-if="reductReadyDoc.json.resources.length > 1 && reduct == true"
                                            @click="delField('resources', i)" class="digital-delete-field">Удалить
                                        </div>
                                    </div>
                                </div>
                                <div class="digital__popup-content-role mr-0">
                                    <h5>Ед.</h5>
                                    <div v-for="(item, i) in reductReadyDoc.json.resources" > 
                                        <input type="text" :class="{'reduct-on': reduct}" :readonly="!reduct"
                                        placeholder="ед." v-model="reductReadyDoc.json.resources[i].index">
                                    </div>
                                </div>
                            </div>
                            <div v-if="reductReadyDoc.json.interestMan.length < 10 && reduct == true" class="digital__form-add-field" 
                                @click="addResource()">
                                <img src="/images/passport/add-field.svg" alt="">
                                <span>Добавить ресурс</span>
                            </div>

                            <!-- Бюджет-->
                            <div class="digital__popup-content-interest mt-4">
                                <div class="digital__popup-content-name mr-3">
                                    <h5>Бюджет проекта</h5>
                                    <div v-if="errors.includes('json.budget')" class="difital-idea__form-error">
                                        {{getErrorDescription('json.budget')}}
                                    </div>
                                    <div v-for="(item, i) in reductReadyDoc.json.budget">  
                                        <input type="text" :class="{'reduct-on': reduct}" :readonly="!reduct"
                                            :invalid="errors.includes('json.budget')"
                                            @change="onFieldUpdate"
                                            placeholder="Статья расхода в проекте" 
                                            v-model="reductReadyDoc.json.budget[i].expense">
                                        <div v-if="reductReadyDoc.json.resources.length > 1 && reduct == true"
                                            @click="delField('budget', i)" class="digital-delete-field">Удалить
                                        </div>
                                    </div>
                                </div>
                                <div class="digital__popup-content-role mr-0">
                                    <h5>Руб.</h5>
                                    <div v-for="(item, i) in reductReadyDoc.json.budget"> 
                                        <input type="text" :class="{'reduct-on': reduct}" :readonly="!reduct"
                                        placeholder="руб." v-model="reductReadyDoc.json.budget[i].item">
                                    </div>
                                </div>
                            </div>
                            <div v-if="reductReadyDoc.json.interestMan.length < 10 && reduct == true" class="digital__form-add-field" 
                                @click="addBudget()">
                                <img src="/images/passport/add-field.svg" alt="">
                                <span>Добавить статью расходов</span>
                            </div>
                            
                            <!-- План Реализации -->
                            <div class="digital__popup-content-interest mt-4">
                                <div class="digital__popup-content-name mr-3">
                                    <h5>План реализации проекта</h5>
                                    <div v-if="errors.includes('json.plan')" class="difital-idea__form-error">
                                        {{getErrorDescription('json.plan')}}
                                    </div>
                                    <div v-for="(item, i) in reductReadyDoc.json.plan">  
                                        <input type="text" :class="{'reduct-on': reduct}" :readonly="!reduct"
                                            :invalid="errors.includes('json.plan')" @change="onFieldUpdate"
                                            placeholder="Название этапа проекта или задачи" 
                                            v-model="reductReadyDoc.json.plan[i].planName">
                                        <div v-if="reductReadyDoc.json.plan.length > 1 && reduct == true"
                                            @click="delField('plan', i)" class="digital-delete-field">Удалить
                                        </div>
                                    </div>
                                </div>
                                <div class="digital__popup-content-role mr-0">
                                    <h5>Дата</h5>
                                    <div v-for="(item, i) in reductReadyDoc.json.plan"> 
                                        <input type="date" :class="{'reduct-on': reduct}" :readonly="!reduct"
                                        placeholder="дд.мм.гггг" v-model="reductReadyDoc.json.plan[i].date"
                                        min="1990-01-01" max="2050-12-31">
                                    </div>
                                </div>
                            </div>
                            <div v-if="reductReadyDoc.json.plan.length < 100 && reduct == true" class="digital__form-add-field" 
                                @click="addPlan()">
                                <img src="/images/passport/add-field.svg" alt="">
                                <span>Добавить этап или задачу</span>
                            </div>

                            <!-- buttons -->
                            <!-- редактирования -->
                            <div v-if="reduct == true" class="digital__popup-btns mt-5">
                                <button  @click="updatePassport(1)"     
                                        class="ui-btn-white close-popup">Сохранить и закрыть</button>
                                <!-- <button v-else @click="postPassport(1)"
                                        class="ui-btn-white close-popup">Сохранить и закрыть</button> -->

                                <div class="d-flex">
                                    <button @click="generatePDF(reductReadyDoc)" class="ui-btn-antic mr-3">Скачать</button>
                                    <button @click="closePopup()" class="ui-btn-red close-popup">Закрыть</button>
                                </div>
                            </div>


                            <!-- Режим просмотра -->
                            <div v-else class="digital__popup-btns mt-5">
                                <div v-if="reduct == false" class="d-flex">
                                    <button @click="closePopup()" class="ui-btn-red close-popup">Закрыть</button>
                                </div>
                            </div>
                        </div>
                </template>
                </div>
            </div>
        </div>
    </div>

</template>
<script>
    export default {
        name: 'Index',
        data(){
            return {
                reduct: true,
                sortSatus: 'all',
                myPassports: {
                    ready: [],
                    readyFiltered: [],
                    unready: [],
                    unreadyFiltered: [],
                },
                type: 'newdoc',
                body: document.querySelector('body'),
                docType: 'idea',
                openPopup: false,
                delActive: false,
                reductActive: false,
                deleteDoc: false,
                // удаляемый документ
                delItem:'',
                reductReadyDoc: '',
                // фильтры типа документа
                searchField: {
                    unready: '',
                    ready: ''
                },
                draftFilter: 'all',
                readyFilter: 'all',
                saveButtonOn : true,
                stages: [
                    {
                        required: ['title', 'json.prerequisites','json.objectives', 'json.results','json.vision']
                    },
                    {
                        required: ['title', 'json.director', 'json.prerequisites','json.objectives', "json.results", "json.metrics", "json.resources", "json.budget", "json.risks", "json.plan"]
                    },
                ],
                errors: [],
                errors_descriptions: [
                    {
                        field: 'title',
                        description: 'Поле заголовок обязательно для заполнения'
                    },
                    {
                        field: 'json.prerequisites',
                        description: 'Поле предпосылки обязательно для заполнения'
                    }
                ]
            }
        },
        props: [
            'user'
        ],
        mounted()
        {
            this.getPassports();
        },
        watch:{
            'searchField.unready':function(newVal, oldVal){
                this.searchCard('unready');
            },
            'searchField.ready':function(newVal, oldVal){
                this.searchCard('ready');
            },
        },
        methods:{
            searchCard(type)
            {
                var vm = this;
                switch (type)
                {
                    case 'ready':
                        if( vm.searchField.ready )
                        {
                            vm.myPassports.readyFiltered = vm.myPassports.ready.filter((item) => {
                                return item.title.toLowerCase().includes(vm.searchField.ready.toLowerCase());
                            });
                        }else{
                            vm.myPassports.readyFiltered = vm.myPassports.ready;
                        }
                    break;
                    case 'unready':
                        if( vm.searchField.unready )
                        {
                            vm.myPassports.unreadyFiltered = vm.myPassports.unready.filter((item) => {
                                return item.title.toLowerCase().includes(vm.searchField.unready.toLowerCase());
                            });
                        }else{
                            vm.myPassports.unreadyFiltered = vm.myPassports.unready;
                        }
                    break;
                    default:
                    break;
                }
            },
            // Валидация
            onFieldUpdate()
            {
                let stage = this.reductReadyDoc.type == 'idea' ? 0 : 1;
                this.stage = stage;
                let stageObj = this.stages[stage];

                this.stageValidateRequired(stage)

                if(this.errors.length == 0)
                {
                    this.saveButtonOn = 1;
                }else{
                    this.saveButtonOn = 0;
                }
            },
            //Валидация
            stageValidateRequired(stage)
            {
                let reductReadyDoc = this.flatten(this.reductReadyDoc);
                let stageObj = this.stages[stage];
                let stageRequired = stageObj.required;
                let errors = [];
                stageRequired.map(field => {
                    
                    if(reductReadyDoc[field].length === 0)
                    {
                        errors.push(field);
                    }

                    if(reductReadyDoc[field].length > 0)
                    {
                        if(reductReadyDoc[field][0].length === 0)
                        {
                            errors.push(field);
                        } else if( reductReadyDoc[field][0].length != 0 ){
                            let keys = Object.keys(reductReadyDoc[field][0])
                            if(reductReadyDoc[field][0][keys[0]].length === 0)
                            errors.push(field);
                        }
                    }

                });
                this.errors = errors;
            },

            //Получить описание ошибки для поля
            getErrorDescription(field)
            {
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

            startProject()
            {
                switch (this.docType)
                {
                    case 'idea':
                        window.location.href = route('service.digital.idea.create');
                    break;
                    case 'passport':
                        window.location.href = route('service.digital.passport.create');
                    break;
                    default:
                        this.docType = '';
                    break;
                }
            },
            formatDate(date)
            {
                return new Date(date).toDateString();
            },
            // Удаление поля в режиме редактирования Готовой Цифровой Идеи
            delField(field, index)
            {
                this.reductReadyDoc.json[field].splice(index, 1);
            },
            // добавление заинтересованного лица
            addInterestMan()
            {
                this.reductReadyDoc.json.interestMan.push({name: '', role: ''});
            },
            // добавление метрики
            addMetrics()
            {
                this.reductReadyDoc.json.metrics.push({metric: '', index: '', dateBefore: '', dateAfter: ''});
            }, 
            // добавление ресурса
            addResource()
            {
                if(this.reductReadyDoc.json.resources.length < 100)
                {
                    this.reductReadyDoc.json.resources.push({resource: '', index: ''})
                }
            },
            // добавление бюджета
            addBudget()
            {
                if(this.reductReadyDoc.json.budget.length < 100)
                {
                    this.reductReadyDoc.json.budget.push({expense: '', item: ''});
                }
            },
            // Добавление плана
            addPlan()
            {
                if(this.reductReadyDoc.json.plan.length < 100)
                {
                    this.reductReadyDoc.json.plan.push({planName: '', date: ''});
                }
            },
            addObjective()
            {
                if(this.reductReadyDoc.json.objectives.length < 10)
                {
                    this.reductReadyDoc.json.objectives.push('');
                }
            },
            addResult()
            {
                if(this.reductReadyDoc.json.results.length < 10)
                {
                    this.reductReadyDoc.json.results.push('');
                }
            },
            // reduct element popup
            reductPopup(index, type, ready)
            {
                this.delActive = false;
                this.reductActive = true;
                this.reduct = ready === 'ready' ? true : false;
                this.showPopup();
                this.type = type;
                this.reductReadyDoc = this.myPassports[ready][index];
            },
            // Закрытие попапа редактирования паспорта
            closeReductPopup()
            {
                this.openPopup = false
                this.closePopup();
            },
            closePopup()
            {
                let close = document.querySelector('.close-popup');
                close.click();
                this.type = 'newdoc';
            },
            showPopup()
            {
                const lockPaddingValue = window.innerWidth - document.querySelector('.wrapper').offsetWidth + 'px';
                this.body.style.paddingRight = lockPaddingValue;
                this.body.classList.add('lock');
                this.openPopup = true;
            },
            // Переход на Редактирование черновика
            draftReduct(item)
            {
                window.location.href = ( item.type == 'passport' ? route('service.digital.passport.show.edit', item) : route('service.digital.idea.show.edit', item) );
            },
            // ЗАПРОСЫ С СЕРВЕРА
            // Запрос и вывод на страницу паспартов 
            getPassports()
            {
                let v = this;
                axios
                    .get(route('api.users.show.passports', this.user))
                    .then((response) => {
                        v.myPassports.ready = [];
                        v.myPassports.readyFiltered = [];
                        v.myPassports.unready = [];
                        v.myPassports.unreadyFiltered = [];
                        response.data.data.forEach((item) => {
                            if(item.ready === 1) {
                                v.myPassports.ready.push(item);
                                v.myPassports.readyFiltered.push(item);
                            } else {
                                v.myPassports.unready.push(item);
                                v.myPassports.unreadyFiltered.push(item);
                            }
                        });
                        v.searchCard('unready');
                        v.searchCard('ready');
                    })
                    .catch((error) => {
                        console.log(error);
                    });
            },
            // CRUD
            // Удаление записи
            // Методы для редактирования данных в мадальном окне
            delPopup(index, item)
            {
                // вызыввем попап для подтверждения удаления
                this.type = 'delete';
                this.reductActive = false;
                this.delItem = this.myPassports[item][index];
            },
            delPassport(item)
            {
                this.closePopup();
                axios
                    .delete(route('api.service.digital.passport.show.destroy', item))
                    .then((response) => {
                        this.getPassports()
                        this.closePopup();
                    })
                    .catch((error) => {
                        console.log(error);
                    });
            },
            // Копирование записи
            copyProject(item)
            {
                axios
                    .post(route('api.service.digital.passport.show.copy', item))
                    .then((response) => {
                        this.getPassports();
                    })
                    .catch((error) => {
                        console.log(error);
                    });
            },
            // Обновление отредактированного Паспорта или Идеи
            updatePassport(id)
            {
                // let data = JSON.stringify(this.reductReadyDoc);
                let data = this.reductReadyDoc;
                // let date = new Date(data.date);
                // data.date = date.toLocaleDateString()
                axios
                    .post(route('api.service.digital.passport.show.update', this.reductReadyDoc), data)
                    .then((response) => {
                        this.closePopup();
                        this.getPassports();
                    })
                    .catch((error) => {
                        console.log(error);
                    });
            },
            // Генерация PDF
            generatePDF(item)
            {
                let data = this.reductReadyDoc;
                // let date = new Date(data.date);
                // data.date = date.toLocaleDateString()
                
                if( data )
                {
                    axios
                        .post(route('api.service.digital.passport.show.update', item), data)
                        .then((response) => {
                            Object.assign(document.createElement('a'), { style: 'display:none;', class: 'd-none', target: '_blank', href: route('service.digital.passport.show.index', item)}).click();
                        })
                        .catch((error) => {
                            console.log(error);
                        });
                }else{
                    Object.assign(document.createElement('a'), { style: 'display:none;', class: 'd-none', target: '_blank', href: route('service.digital.passport.show.index', item)}).click();
                }
            },
        }
    }
</script>