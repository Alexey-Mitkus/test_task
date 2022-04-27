<template>
    <div>
        <!-- Приветсвие -->
        <div v-if="stage == 0" class="digital__welcome-card">
            <a :href="route('user.document.index')">
                <img width="16px" height="16px" src="/images/close_popup.svg" alt="close" class="ui__close-btn">
            </a>
            <h3 class="mb-4">Цифровой паспорт</h3>
            <p>С помощью этого сервиса вы можете создать цифровую версию паспорта проекта, в которой будут учтены все необходимые критерии для его реализации.</p>
            <p>Цифровой паспорт поможет оформить ваши мысли и упаковать их в типовой документ, который можно будет распечатать или скачать. Для этого необходимо заполнить поля формы, опираясь на подсказки сервиса.</p>
            <p>Итоговый документ доступен только автору и сохраняется в разделе "Мои документы".</p>
            <button @click="stage = 1" class="ui-btn-red mt-4">Приступить</button>
        </div>

        <!-- Шаг 1 -->
        <div v-else class="digital__forms">
            <!-- progress bar -->
            <div class="digital__progress-bar">
                <div class="progress-bar-sircle"  :class="{'active-circle': stage === 1, 'ready-circle': stage > 1}">1</div>
                <span class="progress-bar-border" :class="{'ready-border': stage > 1}"></span>
                <div class="progress-bar-sircle"  :class="{'active-circle': stage === 2, 'ready-circle': stage > 2}">2</div>
                <span class="progress-bar-border" :class="{'ready-border': stage > 2}"></span>
                <div class="progress-bar-sircle"  :class="{'active-circle': stage === 3, 'ready-circle': stage > 3}">3</div>
                <span class="progress-bar-border" :class="{'ready-border': stage > 3}"></span>
                <div class="progress-bar-sircle"  :class="{'active-circle': stage === 4, 'ready-circle': stage > 4}">4</div>
                <span class="progress-bar-border" :class="{'ready-border': stage > 4}"></span>
                <div class="progress-bar-sircle"  :class="{'active-circle': stage === 5, 'ready-circle': stage > 5}">5</div>
                <span class="progress-bar-border" :class="{'ready-border': stage > 5}"></span>
                <div class="progress-bar-sircle"  :class="{'active-circle': stage === 6, 'ready-circle': stage > 6}">6</div>
                <span class="progress-bar-border" :class="{'ready-border': stage > 6}"></span>
                <div class="progress-bar-sircle"  :class="{'active-circle': stage === 7, 'ready-circle': stage > 7}">7</div>
                <span class="progress-bar-border" :class="{'ready-border': stage > 7}"></span>
                <div class="progress-bar-sircle"  :class="{'active-circle': stage === 8, 'ready-circle': stage > 8}">8</div>
            </div>
            <div @click="reduct = false" class="digital__preview popup-link">
                <img src="/images/passport/passport_preview.svg" alt="Предпросмотр">
                <span>Предпросмотр</span>
            </div>

            <!-- 1 шаг опросника -->
            <div v-if="stage === 1" class="digital__form">
                <form class="digital__form-fields" @submit.prevent="onSubmit">

                    <!-- Название проекта -->
                    <label class="info">Название проекта</label>
                    <div v-if="errors.includes('title')" class="difital-idea__form-error">
                        {{getErrorDescription('title')}}
                    </div>
                    <div class="digital__form-fields-input">
                        <input type="text" 
                        :invalid="errors.includes('title')"
                        placeholder="Введите название, которое отражает суть вашей идеи" v-model="totalInfo.title">
                    </div>

                    <!-- Название организации -->
                    <label >Название организации
                    </label>
                    <div class="digital__hospitals">
                        <model-select @searchchange="searchHospital" class="form-control" name="field3" autocomplete="field3" required :options="organizations" v-model="totalInfo.organization" placeholder="Ваше место работы" />
                    </div>

                    <div class="digital__form-fields-half-block">
                        <!-- Дата инициации проекта -->
                        <div class="digital__form-fields-input half">
                            <label class="info">
                                Дата инициации проекта
                                <img src="/images/passport/info-circle.svg" alt="" tabindex="0" class="info-circle tooltip-active">
                                <!-- tooltip info -->
                                <div tabindex="0" class="tooltips">
                                    <p>Дата начала стадии проекта, на которой происходит определение целей и задач проекта, разрабатывается устав, определяются руководитель проекта, участники команды и заинтересованные лица.</p>
                                    <div tabindex="0" class="tooltips-invisible-block"></div>
                                </div>
                            </label>
                            <input type="date" v-model="totalInfo.json.startDate" min="1990-01-01" max="2050-12-31">
                        </div>

                        <!-- Дата закрытия проекта -->
                        <div class="digital__form-fields-input half">
                            <label class="info">
                                Дата завершения
                                <img src="/images/passport/info-circle.svg" alt="" tabindex="0" class="info-circle tooltip-active">
                                <!-- tooltip info -->
                                <div tabindex="0" class="tooltips">
                                    <p>Дата официального окончания всех работ проекта.</p>
                                    <div tabindex="0" class="tooltips-invisible-block"></div>
                                </div>
                            </label>
                            <input type="date" v-model="totalInfo.json.finishDate" min="1990-01-01" max="2050-12-31">
                        </div>
                    </div>
                </form>

                <div class="buttons-line">
                    <button @click="safeAndExit()" class="ui-btn-white">Сохранить и выйти</button>
                    
                    <div class="d-flex ">
                        <button @click="nextStage()" class="ui-btn-red">Далее</button>
                    </div>
                </div>
            </div>

            <!-- 2 шаг опросника -->
            <div v-if="stage === 2" class="digital__form">
                <form class="digital__form-fields" @submit.prevent="onSubmit">
                    <!-- Руководитель проекта -->
                    <label for="" class="info">Руководитель проекта
                        <img src="/images/passport/info-circle.svg" alt="" tabindex="0" class="info-circle tooltip-active">
                        <div tabindex="0" class="tooltips">
                            <p>Руководитель проекта - это сотрудник, который управляет командой и работами по проекту, распоряжается ресурсами, отвечает за достижение цели и получение результата. </p>
                            <a href="/storage/documents/rukovoditel-proekta.pdf" target="_blank">Подробнее...</a>
                            <div tabindex="0" class="tooltips-invisible-block"></div>
                        </div>
                    </label>
                    <div v-if="errors.includes('json.director')" class="difital-idea__form-error">{{ getErrorDescription('json.director') }}</div>
                    <div class="digital__form-fields-input">
                        <input  type="text" :invalid="errors.includes('json.director')" placeholder="Введите ФИО руководителя" v-model="totalInfo.json.director">
                    </div>

                    <!-- Команда проекта -->
                    <label for="" class="info">Команда проекта (при наличии)
                        <img src="/images/passport/info-circle.svg" alt="" 
                                tabindex="0" class="info-circle tooltip-active">
                        <div tabindex="0" class="tooltips">
                            <p>Это группа специалистов, объединенных на период реализации 
                                проекта для выполнения работ и достижения его целей.</p>
                            <div tabindex="0" class="tooltips-invisible-block"></div>
                        </div>
                    </label>
                    <div v-for="(item, i) in totalInfo.json.teamMates" class="digital__form-fields-input" :key="i">
                        <input type="text" placeholder="Введите ФИО участника" v-model="totalInfo.json.teamMates[i]">
                        <div v-if="totalInfo.json.teamMates.length > 1" @click="delField('teamMates', index)" class="digital-delete-field">Удалить</div>
                    </div>
                    <div v-if="totalInfo.json.teamMates.length < 10" class="digital__form-add-field" @click="addField('teamMates', 10)">
                        <img src="/images/passport/add-field.svg" alt="">
                        <span>Добавить участника</span>
                    </div>

                    <!-- Другие заинтересованные лица -->
                    <label class="info mt-5" >Другие заинтересованные лица (при наличии)</label>
                    <div class="digital__form-metrics">
                        <div class="digital__form-metrics-block1">
                            <div class="digital__form-metrics-block1-input" v-for="(item, i) in totalInfo.json.interestMan" :key="i">  
                                <input type="text" placeholder="Введите ФИО участника"  v-model="totalInfo.json.interestMan[i].name">
                            </div>
                        </div>
                        <div class="digital__form-metrics-block2">
                            <div class="digital__form-metrics-block1-input" v-for="(item, i) in totalInfo.json.interestMan" :key="i"> 
                                <select size="1" v-model="totalInfo.json.interestMan[i].role">
                                    <option value="" selected disabled>Роль</option>
                                    <option value="Инициатор">Инициатор</option>
                                    <option value="Заказчик">Заказчик</option>
                                    <option value="Тех. заказчик">Тех. заказчик</option>
                                    <option value="Куратор">Куратор</option>
                                    <option value="Консультант">Консультант</option>
                                    <option value="Другое">Другое</option>
                                </select>
                                <div v-if="totalInfo.json.interestMan.length > 1" @click="delField('interestMan', i)"  class="digital-delete-field">Удалить</div>
                            </div>
                        </div>
                    </div>

                    <div v-if="totalInfo.json.interestMan.length < 10" class="digital__form-add-field" @click="addInterestMan()">
                        <img src="/images/passport/add-field.svg" alt="">
                        <span>Добавить заинтересованное лицо</span>
                    </div>
                </form>    
                <!-- Кнопки перехода -->
                <div class="buttons-line">
                    <button @click="safeAndExit()" class="ui-btn-white">Сохранить и выйти</button>
                    <div class="d-flex ">
                        <button @click="stage = 1" class="ui-btn-white mr-3">Назад</button>
                        <button @click="nextStage()" class="ui-btn-red">Далее</button>
                    </div>
                </div>
            </div>

            <!-- 3 шаг опросника -->
            <div v-if="stage === 3" class="digital__form">
                <form class="digital__form-fields" @submit.prevent="onSubmit">
                    <!-- Предпосылки проекта -->
                    <label class="info">Предпосылки проекта:
                        <img src="/images/passport/info-circle.svg" alt="" tabindex="0" class="info-circle tooltip-active">
                        <div tabindex="0" class="tooltips">
                            <p>Это формальные основания для инициации проекта, а также факторы, лежащие в основе идеи реализации данного проекта.</p>
                            <div tabindex="0" class="tooltips-invisible-block"></div>
                        </div>
                    </label>
                     <div v-if="errors.includes('json.prerequisites')" class="difital-idea__form-error">
                        {{getErrorDescription('json.prerequisites')}}
                    </div>
                    <textarea :invalid="errors.includes('json.prerequisites')" placeholder="Расскажите, что стало причиной появления идеи данного проекта. " v-model="totalInfo.json.prerequisites"></textarea>
                    <!-- Цели проекта -->
                    <label class="info mt-5">Цели проекта:
                        <img src="/images/passport/info-circle.svg" alt="" tabindex="0" class="info-circle tooltip-active">
                        <!-- tooltip info -->
                        <div tabindex="0" class="tooltips">
                            <p>Это результаты, которых необходимо достичь при реализации проекта в заданных условиях.</p>
                            <a href="/storage/documents/celi-proekta.pdf" target="_blank">Подробнее...</a>
                            <div tabindex="0" class="tooltips-invisible-block"></div>
                        </div>
                    </label>
                    <div v-if="errors.includes('json.objectives')" class="difital-idea__form-error">{{ getErrorDescription('json.objectives') }}</div>
                    <div v-for="(item, i) in totalInfo.json.objectives" class="digital__form-fields-input" :key="i">
                        <input type="text" :invalid="errors.includes('json.objectives')" placeholder="Введите цель, которую необходимо достичь в рамках проекта"  v-model="totalInfo.json.objectives[i]">
                        <div v-if="totalInfo.json.objectives.length > 1" @click="delField('objectives', i)" class="digital-delete-field">Удалить</div>
                    </div>
                    <div v-if="totalInfo.json.objectives.length < 10" class="digital__form-add-field" @click="addField('objectives', 10)">
                        <img src="/images/passport/add-field.svg" alt="">
                        <span>Добавить цель</span>
                    </div>
                </form>    
                <!-- Кнопки перехода -->
                <div class="buttons-line">
                    <button @click="safeAndExit()" class="ui-btn-white">Сохранить и выйти</button>
                    <div class="d-flex ">
                        <button @click="stage = 2" class="ui-btn-white mr-3">Назад</button>
                        <button @click="nextStage()" class="ui-btn-red">Далее</button>
                    </div>
                </div>
            </div>

            <!-- 4 шаг опросника -->
            <div v-if="stage === 4" class="digital__form">
                <form class="digital__form-fields" @submit.prevent="onSubmit">
                    <!-- Результаты и продукты проекта -->
                    <label for="" class="info">Результаты и продукты проекта
                        <img src="/images/passport/info-circle.svg" alt="" tabindex="0" class="info-circle tooltip-active">
                        <div tabindex="0" class="tooltips">
                            <p>Это полезный эффект от реализации проекта, выраженный в форме продукта или каких-либо измеримых изменений. Результатом может быть научная разработка, строительный объект, учебная программа.</p>
                            <a href="/storage/documents/rezultat-proekta.pdf" target="_blank">Подробнее...</a>
                            <div tabindex="0" class="tooltips-invisible-block"></div>
                        </div>
                    </label>
                    <div v-for="(item, i) in totalInfo.json.results" class="digital__form-fields-input" :key="i">
                        <input  type="text" placeholder="Введите ожидаемый результат или продукт проекта" v-model="totalInfo.json.results[i]">
                        <div v-if="totalInfo.json.results.length > 1" @click="delField('results', i)" class="digital-delete-field">Удалить
                        </div>
                    </div>

                    <div v-if="totalInfo.json.results.length < 10" class="digital__form-add-field" @click="addField('results', 10)">
                        <img src="/images/passport/add-field.svg" alt="">
                        <span>Добавить результат или продукт</span>
                    </div>

                    <!-- Метрики -->
                    <div class="digital__form-metrics mt-5">
                        <div class="digital__form-metrics-block1">
                            <label for="" class="info">Метрики:
                                <img src="/images/passport/info-circle.svg" alt="" tabindex="0" class="info-circle tooltip-active">
                                <div tabindex="0" class="tooltips">
                                    <p>Это качественные и количественные показатели, отражающие различные характеристики. Метрики продукта позволяют оценить его уровень успешности и динамику развития, а метрики проекта - его текущее состояние, результативность и эффективность.</p>
                                    <a href="/storage/documents/metriki-proekta.pdf" target="_blank">Подробнее...</a>
                                    <div tabindex="0" class="tooltips-invisible-block"></div>
                                </div>
                            </label>
                            <div class="digital__form-metrics-block1-input" v-for="(item, i) in totalInfo.json.metrics" :key="i">  
                                <input type="text" placeholder="Введите параметр, для оценки успешности проекта"  v-model="totalInfo.json.metrics[i].metric">
                                <div v-if="totalInfo.json.metrics.length > 1" @click="delField('metrics', i)"  class="digital-delete-field">Удалить</div>
                            </div>
                        </div>
                        
                        <div class="digital__form-metrics-block2">
                            <label for="">Целевые показатели</label>
                            <div v-for="(item, i) in totalInfo.json.metrics" :key="i">
                                <input type="text" placeholder="Число, %, иное" v-model="totalInfo.json.metrics[i].index">
                            </div>
                        </div>
                    </div>

                    <div v-if="totalInfo.json.metrics.length < 10" class="digital__form-add-field" @click="addMetrics()">
                        <img src="/images/passport/add-field.svg" alt="">
                        <span>Добавить метрику</span>
                    </div>

                </form>
                <!-- Кнопки перехода -->
                <div class="buttons-line">
                    <button @click="safeAndExit()" class="ui-btn-white">Сохранить и выйти</button>
                    
                    <div class="d-flex ">
                        <button @click="stage = 3" class="ui-btn-white mr-3">Назад</button>
                        <button @click="nextStage()" class="ui-btn-red">Далее</button>
                    </div>
                </div>
            </div>

            <!-- 5 шаг опросника -->
            <div v-if="stage === 5" class="digital__form">
                <form class="digital__form-fields" @submit.prevent="onSubmit">
                    <!-- Необходимые ресурсы -->
                    <label>Необходимые ресурсы</label>
                    <div v-if="errors.includes('json.resources')" class="difital-idea__form-error">
                        {{getErrorDescription('json.resources')}}
                    </div>
                    <div class="digital__form-metrics">
                        <div class="digital__form-metrics-block1">
                            <div class="digital__form-metrics-block1-input" v-for="(item, i) in totalInfo.json.resources" :key="i">
                                <input type="text" :invalid="errors.includes('json.resources')" placeholder="Введите ресурс, необходимый для реализации проекта"  v-model="totalInfo.json.resources[i].resource">
                            </div>
                        </div>
                        
                        <div class="digital__form-metrics-block2">
                            <div class="digital__form-metrics-block1-input" v-for="(item, i) in totalInfo.json.resources" :key="i">
                                <input type="text" placeholder="ед." v-model="totalInfo.json.resources[i].index">
                                <div v-if="totalInfo.json.resources.length > 1" @click="delField('resources', i)" class="digital-delete-field">Удалить</div>
                            </div>
                        </div>
                    </div>

                    <div v-if="totalInfo.json.resources.length < 10" class="digital__form-add-field" @click="addResource()">
                        <img src="/images/passport/add-field.svg" alt="">
                        <span>Добавить ресурс</span>
                    </div>

                    <!-- Бюджет -->
                    <div class="digital__form-metrics mt-5">
                        <div class="digital__form-metrics-block1">
                            <label class="info">Бюджет (статьи расходов)
                                <img src="/images/passport/info-circle.svg" alt="" tabindex="0" class="info-circle tooltip-active">
                                <div tabindex="0" class="tooltips">
                                    <p>Это план затрат, необходимых для исполнения проекта. Бюджет должен покрывать все расходы и сохранять устойчивость проекта в долгосрочной перспективе.</p>
                                    <a href="/storage/documents/formirovanie-byudzheta-proekta.pdf" target="_blank">Подробнее...</a>
                                    <div tabindex="0" class="tooltips-invisible-block"></div>
                                </div>
                            </label>
                            <div v-if="errors.includes('json.budget')" class="difital-idea__form-error">{{ getErrorDescription('json.budget') }}</div>
                            <div class="digital__form-metrics-block1-input" v-for="(item, i) in totalInfo.json.budget" :key="i">
                                <input type="text" :invalid="errors.includes('json.budget')" placeholder="Введите статью расхода в проекте"  v-model="totalInfo.json.budget[i].expense">
                            </div>
                        </div>
                        
                        <div class="digital__form-metrics-block2">
                            <div v-for="(item, i) in totalInfo.json.budget" :key="i">
                                <input type="text" placeholder="руб." v-model="totalInfo.json.budget[i].item">
                                <div v-if="totalInfo.json.budget.length > 1" @click="delField('budget', i)" class="digital-delete-field">Удалить</div>
                            </div>
                        </div>
                    </div>

                    <div v-if="totalInfo.json.budget.length < 10" class="digital__form-add-field" @click="addBudget()">
                        <img src="/images/passport/add-field.svg" alt="">
                        <span>Добавить статью расходов</span>
                    </div>
                </form>
                <!-- Кнопки перехода -->
                <div class="buttons-line">
                    <button @click="safeAndExit()" class="ui-btn-white">Сохранить и выйти</button>
                    
                    <div class="d-flex ">
                        <button @click="stage = 4" class="ui-btn-white mr-3">Назад</button>
                        <button @click="nextStage()" class="ui-btn-red">Далее</button>
                    </div>
                </div>
            </div>

            <!-- 6 шаг опросника -->
            <div v-if="stage === 6" class="digital__form">
                <form class="digital__form-fields" @submit.prevent="onSubmit">
                    <!-- Риски проекта -->
                    <label for="" class="info">
                        Риски проекта
                        <img src="/images/passport/info-circle.svg" alt="" tabindex="0" class="info-circle tooltip-active">
                        <div tabindex="0" class="tooltips">
                            <p>Это неопределенное событие или условие, которое в случае наступления будет иметь отрицательное или положительное влияние на сроки, ресурсы, содержание или результаты проекта.</p>
                            <a href="/storage/documents/upravlenie-riskami-proekta.pdf" target="_blank">Подробнее...</a>
                            <div tabindex="0" class="tooltips-invisible-block"></div>
                        </div>
                    </label>
                    <div v-if="errors.includes('json.risks')" class="difital-idea__form-error">{{ getErrorDescription('json.risks') }}</div>
                    <div v-for="(item, i) in totalInfo.json.risks" class="digital__form-fields-input" :key="i">
                        <input  type="text" 
                            :invalid="errors.includes('json.risks')" 
                            placeholder="Введите возможный риск проекта" 
                            v-model="totalInfo.json.risks[i]">
                        <div v-if="totalInfo.json.risks.length > 1"
                            @click="delField('risks', i)" class="digital-delete-field">Удалить
                        </div>
                    </div>

                    <div v-if="totalInfo.json.risks.length < 100" class="digital__form-add-field" 
                        @click="addField('risks', 100)">
                        <img src="/images/passport/add-field.svg" alt="">
                        <span>Добавить возможный риск</span>
                    </div>
                </form>
                <!-- Кнопки перехода -->
                <div class="buttons-line">
                    <button @click="safeAndExit()" class="ui-btn-white">Сохранить и выйти</button>
                    
                    <div class="d-flex ">
                        <button @click="stage = 5" class="ui-btn-white mr-3">Назад</button>
                        <button @click="nextStage()" class="ui-btn-red">Далее</button>
                    </div>
                </div>
            </div>

            <!-- 7 шаг опросника -->
            <div v-if="stage === 7" class="digital__form">
                <form class="digital__form-fields" @submit.prevent="onSubmit">
                    <!-- Видение реализации (по желанию) -->
                    <label for="" class="info">Видение реализации (по желанию)
                    </label>
                    <textarea class="digital__form-description"
                        placeholder="Расскажите о том, какой вы видите реализацию проекта"
                        v-model="totalInfo.json.description">
                    </textarea>

                </form>
                <!-- Кнопки перехода -->
                <div class="buttons-line">
                    <button @click="safeAndExit()" class="ui-btn-white">Сохранить и выйти</button>
                    
                    <div class="d-flex ">
                        <button @click="stage = 6" class="ui-btn-white mr-3">Назад</button>
                        <button @click="nextStage()" class="ui-btn-red">Далее</button>
                    </div>
                </div>
            </div>

            <!-- 8 шаг опросника -->
            <div v-if="stage === 8" class="digital__form">
                <form class="digital__form-fields" @submit.prevent="onSubmit">
                    <!-- План реализации проекта -->
                    <div class="digital-idea__form-metrics">
                        <div class="digital-idea__form-metrics-block1">
                            <label for="" class="info">План реализации проекта:
                                <img src="/images/passport/info-circle.svg" alt="" 
                                    tabindex="0" class="info-circle tooltip-active">
                                <div tabindex="0" class="tooltips">
                                    <p>План реализации проекта – это очередность этапов, 
                                        задач и контрольных точек проекта для достижения цели.</p>
                                    <div tabindex="0" class="tooltips-invisible-block"></div>
                                </div>
                            </label>
                            <div v-if="errors.includes('json.plan')" class="difital-idea__form-error">
                                {{getErrorDescription('json.plan')}}
                            </div>
                            <div class="digital__form-metrics-block1-input"
                                v-for="(item, i) in totalInfo.json.plan">
                                <input type="text"
                                :invalid="errors.includes('json.plan')"
                                placeholder="Введите название этапа проекта или задачи" 
                                v-model="totalInfo.json.plan[i].planName">
                            </div>
                        </div>
                        
                        <div class="digital__form-metrics-block2">
                            <label for="">Сроки</label>
                            <div v-for="(item, i) in totalInfo.json.plan">
                                <input type="date" placeholder="дд.мм.гггг" 
                                v-model="totalInfo.json.plan[i].date"
                                min="1990-01-01" max="2050-12-31">
                                <div v-if="totalInfo.json.plan.length > 1"
                                    @click="delField('plan', i)" class="digital-delete-field">Удалить
                                </div>
                            </div>
                        </div>
                    </div>

                    <div v-if="totalInfo.json.plan.length < 100" class="digital__form-add-field"
                        @click="addPlan()">
                        <img src="/images/passport/add-field.svg" alt="">
                        <span>Добавить этап или задачу</span>
                    </div>
                </form>
                <!-- Кнопки перехода -->
                <div class="buttons-line">
                    <button @click="safeAndExit()" class="ui-btn-white">Сохранить и выйти</button>
                    
                    <div class="d-flex ">
                        <button @click="stage = 7" class="ui-btn-white mr-3">Назад</button>
                        <button @click="finish()" class="ui-btn-red">Завершить</button>
                    </div>
                </div>
            </div>

            <!-- 9 шаг опросника (ЗАВЕРШЕНИЕ) -->
            <div v-if="stage === 9" class="digital__form">
                <h3>Документ сформирован и сохранен</h3>
                <p>Вы можете найти его в разделе «Мои документы»</p>
            
                <div class="buttons-line mt-5">
                    <button @click="reduct = 1" class="ui-btn-white popup-link">Редактировать</button>
                    
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
                    <div class="digital__popup-header">
                        <h3>Цифровой паспорт</h3>
                        <img class="ui__close-btn close-popup" src="/images/close_popup.svg" alt="">
                    </div>

                    <div class="digital-idea__popup-content">
                        <!-- title -->
                        <div class="fields-input-title mt-3">
                            <textarea type="text" class="popup-title" v-model="totalInfo.title" :class="{'reduct-on': reduct}"
                            placeholder="Вы не указали название проекта" 
                            :readonly="!reduct"></textarea>
                        </div>
                        <!-- organisation -->
                        <div class="digital-idea__popup-content-desc">
                            <div class="digital-idea__popup-content-name">
                                <p>Организация:</p>
                                <textarea type="text" v-model="totalInfo.organization"
                                    :class="{'reduct-on': reduct}" :readonly="!reduct"></textarea>
                            </div>
                        </div>
                        
                        <!-- Текущий статус проекта, даты инициализации и завершения проекта -->
                        <div class="digital__popup-content-status mb-3">
                            <div>
                                <h5>Текущий статус проекта</h5>
                                <input type="text" placeholder="Статус проекта" 
                                v-model="totalInfo.json.status" :class="{'reduct-on': reduct}" :readonly="!reduct">
                            </div>
                            <div>
                                <h5>Дата инициации</h5>
                                <input type="date" placeholder="Статус проекта" min="1990-01-01" max="2050-12-31"
                                v-model="totalInfo.json.startDate" :class="{'reduct-on': reduct}" :readonly="!reduct">
                            </div>
                            <div>
                                <h5>Дата инициации</h5>
                                <input type="date" placeholder="Статус проекта" min="1990-01-01" max="2050-12-31"
                                v-model="totalInfo.json.finishDate " :class="{'reduct-on': reduct}" :readonly="!reduct">
                            </div>
                        </div>

                        <!-- Описание и предпосылки проекта -->
                        <h4>1. Описание и предпосылки проекта</h4>
                        <h5>Описание проекта</h5>
                        <div class="fields-input-title mt-3">
                            <textarea rows="8" type="text" v-model="totalInfo.json.description" :class="{'reduct-on': reduct}" 
                            :readonly="!reduct"></textarea>
                        </div>
                        
                        <h5>Предпосылки проекта</h5>
                        <div class="fields-input-title mt-3">
                            <textarea rows="8" type="text" v-model="totalInfo.json.prerequisites" :class="{'reduct-on': reduct}" 
                            :readonly="!reduct"></textarea>
                        </div>

                        <!-- Цели и реузьтаты проекта -->
                        <h4 class="mt-4">2. Цели и результаты проекта</h4>
                        <!-- Цели -->
                        <h5>Цели</h5>
                        <div v-for="(item, i) in totalInfo.json.objectives" class="digital-popup__form-fields-input">
                            <input  type="text" placeholder="Цель, которой необходимо достичь в рамках проекта" 
                                v-model="totalInfo.json.objectives[i]" :class="{'reduct-on': reduct}" :readonly="!reduct">
                            <div v-if="totalInfo.json.objectives.length > 1 && reduct == true"
                                @click="delField('objectives', i)" class="digital-delete-field">Удалить
                            </div>
                        </div>
                        <div v-if="totalInfo.json.objectives.length < 10 && reduct == true" class="digital__form-add-field" 
                            @click="addField('objectives', 10)">
                            <img src="/images/passport/add-field.svg" alt="">
                            <span>Добавить цель</span>
                        </div>

                        <!-- Метрика -->
                        <div class="digital-idea__popup-metrics">
                            <div class="digital-idea__popup-metrics-name">
                                <h5>Название метрики</h5>
                                <div v-for="(item, i) in totalInfo.json.metrics" class="digital-popup__form-fields-input">
                                    <input  type="text" placeholder="Параметр успешности проекта" 
                                        v-model="totalInfo.json.metrics[i].metric" 
                                        :class="{'reduct-on': reduct}" :readonly="!reduct">
                                    <div v-if="totalInfo.json.metrics.length > 1 && reduct == true"
                                        @click="delField('metrics', i)" class="digital-delete-field">Удалить
                                    </div>
                                </div>
                            </div>
                            <div class="digital-idea__popup-metrics-target">
                                <h5>Целевой показатель</h5>
                                <div v-for="(item, i) in totalInfo.json.metrics" class="digital-popup__form-fields-input">
                                    <input  type="text" placeholder="Число, %, иное" 
                                        v-model="totalInfo.json.metrics[i].index" 
                                        :class="{'reduct-on': reduct}" :readonly="!reduct">
                                </div>
                            </div>
                            <div class="digital-idea__popup-metrics-before">
                                <h5>Было</h5>
                                <div v-for="(item, i) in totalInfo.json.metrics" class="digital-popup__form-fields-input">
                                    <input type="text" placeholder="Значение до"
                                     v-model="totalInfo.json.metrics[i].datebefore"
                                     :class="{'reduct-on': reduct}" :readonly="!reduct">
                                </div>
                            </div>
                            <div class="digital-idea__popup-metrics-after">
                                <h5>Стало</h5>
                                <div v-for="(item, i) in totalInfo.json.metrics" class="digital-popup__form-fields-input">
                                    <input type="text" placeholder="Значение после"
                                     v-model="totalInfo.json.metrics[i].dateAfter"
                                     :class="{'reduct-on': reduct}" :readonly="!reduct">
                                </div>
                            </div>
                        </div>
                        <div v-if="totalInfo.json.metrics.length < 10 && reduct == true" class="digital__form-add-field" 
                           @click="addMetrics()">
                            <img src="/images/passport/add-field.svg" alt="">
                            <span>Добавить метрику</span>
                        </div>
                

                        <h5>Результаты и продукты проекта</h5>
                        <div v-for="(item, i) in totalInfo.json.results" class="digital-popup__form-fields-input">
                            <input  type="text" placeholder="Ожидаемый результат или продукт проекта" 
                                v-model="totalInfo.json.results[i]" :class="{'reduct-on': reduct}" :readonly="!reduct">
                            <div v-if="totalInfo.json.results.length > 1"
                                @click="delField('results', i)" class="digital-delete-field">Удалить
                            </div>
                        </div>
                        <div v-if="totalInfo.json.results.length < 10 && reduct == true" class="digital__form-add-field" 
                            @click="addField('results', 10)">
                            <img src="/images/passport/add-field.svg" alt="">
                            <span>Добавить результат или продукт</span>
                        </div>

                        <h5>Риски проекта</h5>
                        <div v-for="(item, i) in totalInfo.json.risks" class="digital-popup__form-fields-input">
                            <input  type="text" placeholder="Выявленный риск проекта" 
                                v-model="totalInfo.json.risks[i]" :class="{'reduct-on': reduct}" :readonly="!reduct">
                            <div v-if="totalInfo.json.risks.length > 1 && reduct == true"
                                @click="delField('risks', i)" class="digital-delete-field">Удалить
                            </div>
                        </div>
                        <div v-if="totalInfo.json.risks.length < 100 && reduct == true" class="digital__form-add-field" 
                            @click="addField('risks', 100)">
                            <img src="/images/passport/add-field.svg" alt="">
                            <span>Добавить возможный риск</span>
                        </div>

                        <!-- Лица вовлеченные в проект -->
                        <h4 class="mt-3">3. Лица, вовлеченные в проект</h4>
                        <h5>Руководитель проекта</h5>
                        <div class="digital-popup__form-fields-input">
                            <input type="text" placeholder="Руководитель проекта" 
                                v-model="totalInfo.json.director" 
                                class="mb-2" :class="{'reduct-on': reduct}" :readonly="!reduct">
                        </div>

                        <h5>Команда проекта</h5>
                        <div v-for="(item, i) in totalInfo.json.teamMates" class="digital-popup__form-fields-input">
                            <input  type="text" placeholder="Введите ФИО участника" 
                                v-model="totalInfo.json.teamMates[i]" :class="{'reduct-on': reduct}" :readonly="!reduct">
                            <div v-if="totalInfo.json.teamMates.length > 1 && reduct == true"
                                @click="delField('teamMates', i)" class="digital-delete-field">Удалить
                            </div>
                        </div>
                        <div v-if="totalInfo.json.teamMates.length < 10 && reduct == true" class="digital__form-add-field" 
                            @click="addField('teamMates', 10)">
                            <img src="/images/passport/add-field.svg" alt="">
                            <span>Добавить участника</span>
                        </div>

                        <h5 class="mb-3">Другие заинтересованные лица</h5>
                        <div class="digital__popup-content-interest">
                            <div class="digital__popup-content-role">
                                <h5>Роль</h5>
                                <div v-for="(item, i) in totalInfo.json.interestMan" > 
                                    <span v-if="reduct == false">{{ item.role }}</span>
                                    <select v-else size="1" :class="{'reduct-on': reduct}" :readonly="!reduct"
                                        v-model="totalInfo.json.interestMan[i].role">
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
                                <div v-for="(item, i) in totalInfo.json.interestMan">  
                                    <input type="text" :class="{'reduct-on': reduct}" :readonly="!reduct"
                                        placeholder="ФИО вовлеченного лица" 
                                        v-model="totalInfo.json.interestMan[i].name">
                                    <div v-if="totalInfo.json.interestMan.length > 1 && reduct == true"
                                        @click="delField('interestMan', i)"  class="digital-delete-field">Удалить
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div v-if="totalInfo.json.interestMan.length < 10 && reduct == true" class="digital__form-add-field" 
                            @click="addInterestMan()">
                            <img src="/images/passport/add-field.svg" alt="">
                            <span>Добавить заинтересованное лицо</span>
                        </div>

                        <!-- Ресурсы -->
                        <h4 class="mt-4">4. Ресурсы, бюджет и план проекта</h4>
                        <div class="digital__popup-content-interest">
                            <div class="digital__popup-content-name mr-3">
                                <h5>Ресурсы</h5>
                                <div v-for="(item, i) in totalInfo.json.resources">  
                                    <input type="text" :class="{'reduct-on': reduct}" :readonly="!reduct"
                                        placeholder="Ресурс, необходимый для реализации проекта" 
                                        v-model="totalInfo.json.resources[i].resource">
                                    <div v-if="totalInfo.json.resources.length > 1 && reduct == true"
                                        @click="delField('resources', i)" class="digital-delete-field">Удалить
                                    </div>
                                </div>
                            </div>
                            <div class="digital__popup-content-role mr-0">
                                <h5>Ед.</h5>
                                <div v-for="(item, i) in totalInfo.json.resources" > 
                                    <input type="text" :class="{'reduct-on': reduct}" :readonly="!reduct"
                                    placeholder="ед." v-model="totalInfo.json.resources[i].index">
                                </div>
                            </div>
                        </div>
                        <div v-if="totalInfo.json.interestMan.length < 10 && reduct == true" class="digital__form-add-field" 
                            @click="addResource()">
                            <img src="/images/passport/add-field.svg" alt="">
                            <span>Добавить ресурс</span>
                        </div>

                        <!-- Бюджет-->
                        <div class="digital__popup-content-interest mt-4">
                            <div class="digital__popup-content-name mr-3">
                                <h5>Бюджет проекта</h5>
                                <div v-for="(item, i) in totalInfo.json.budget">  
                                    <input type="text" :class="{'reduct-on': reduct}" :readonly="!reduct"
                                        placeholder="Статья расхода в проекте" 
                                        v-model="totalInfo.json.budget[i].expense">
                                    <div v-if="totalInfo.json.resources.length > 1 && reduct == true"
                                        @click="delField('budget', i)" class="digital-delete-field">Удалить
                                    </div>
                                </div>
                            </div>
                            <div class="digital__popup-content-role mr-0">
                                <h5>Руб.</h5>
                                <div v-for="(item, i) in totalInfo.json.budget"> 
                                    <input type="text" :class="{'reduct-on': reduct}" :readonly="!reduct"
                                    placeholder="руб." v-model="totalInfo.json.budget[i].item">
                                </div>
                            </div>
                        </div>
                        <div v-if="totalInfo.json.interestMan.length < 10 && reduct == true" class="digital__form-add-field" 
                            @click="addBudget()">
                            <img src="/images/passport/add-field.svg" alt="">
                            <span>Добавить статью расходов</span>
                        </div>
                        
                        <!-- План Реализации -->
                        <div class="digital__popup-content-interest mt-4">
                            <div class="digital__popup-content-name mr-3">
                                <h5>План реализации проекта</h5>
                                <div v-for="(item, i) in totalInfo.json.plan">  
                                    <input type="text" :class="{'reduct-on': reduct}" :readonly="!reduct"
                                        placeholder="Название этапа проекта или задачи" 
                                        v-model="totalInfo.json.plan[i].planName">
                                    <div v-if="totalInfo.json.plan.length > 1 && reduct == true"
                                        @click="delField('plan', i)" class="digital-delete-field">Удалить
                                    </div>
                                </div>
                            </div>
                            <div class="digital__popup-content-role mr-0">
                                <h5>Дата</h5>
                                <div v-for="(item, i) in totalInfo.json.plan"> 
                                    <input type="date" :class="{'reduct-on': reduct}" :readonly="!reduct"
                                    placeholder="дд.мм.гггг" v-model="totalInfo.json.plan[i].date"
                                    min="1990-01-01" max="2050-12-31">
                                </div>
                            </div>
                        </div>
                        <div v-if="totalInfo.json.plan.length < 100 && reduct == true" class="digital__form-add-field" 
                            @click="addPlan()">
                            <img src="/images/passport/add-field.svg" alt="">
                            <span>Добавить этап или задачу</span>
                        </div>

                        <!-- buttons -->
                        <!-- редактирования -->
                        <div v-if="reduct == true" class="digital__popup-btns mt-5">
                            <button v-if="passport" @click="updatePassport(1)"     
                                    class="ui-btn-white close-popup">Сохранить и закрыть</button>
                            <button v-else @click="postPassport(1)"
                                    class="ui-btn-white close-popup">Сохранить и закрыть</button>

                            <div class="d-flex">
                                <!-- <button class="ui-btn-antic mr-3">Скачать</button> -->
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
import { ModelSelect } from 'vue-search-select';
import { Form, HasError, AlertError, AlertSuccess } from 'vform';

export default {
    name: 'Create',
    data () {
        return {
            stage: 0,
            reduct: false,
            popup: true,
            // id нового документа, который пользователь сохранил
            id: '',
            organizations: [],
            passport: null,
            totalInfo: {
                title: '',
                organization: '',
                type: 'passport',
                ready: 0,
                date: new Date().toLocaleDateString(),
                json: {
                    startDate: '',
                    finishDate: '',
                    director: '',
                    status: '',
                    teamMates: [''],
                    interestMan: [{name: '', role: ''},],
                    objectives: ['',],
                    results: ['',],
                    metrics: [{metric: '', index: '', dateBefore: '', dateAfter: ''},],
                    resources: [{resource: '', index: ''},],
                    budget: [{expense: '', item: ''},],
                    risks: ['',],
                    description: '',
                    prerequisites: '',
                    plan: [{planName: '', date: ''}],
                    step: 1,
                },
            },
            stages: [
                {
                    required: []
                },
                {
                    required: ['title']
                },
                {
                    required: ["json.director"],
                },
                {
                    required: ["json.prerequisites", "json.objectives"]
                },
                {
                    required: ["json.results", "json.metrics"]
                },
                {
                    required: ["json.resources", "json.budget"]
                },
                {
                    required: ["json.risks"]
                },
                {
                    required: []
                },
                {
                    required: ["json.plan"]
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
    props: [
    ],
    methods: {
        //Перед сменой шага, проведём валидацию
        nextStage(){
            let stage = this.stage
            let stageObj = this.stages[stage];

            this.stageValidateRequired(stage)

            if(this.errors.length === 0) {
                this.stage++;
                this.savePassort();
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
                // console.log(field)
                if(!totalInfo[field] || totalInfo[field].length === 0)
                {
                    errors.push(field);
                }
                if(totalInfo[field] && totalInfo[field].length > 0)
                {
                    if(!totalInfo[field][0] || totalInfo[field][0].length === 0)
                    errors.push(field);
                    else if(totalInfo[field][0] && totalInfo[field][0].length != 0)
                    {
                        let keys = Object.keys(totalInfo[field][0])
                        if(totalInfo[field][0][keys[0]]) {
                            if(totalInfo[field] && totalInfo[field][0][keys[0]].length === 0)
                            errors.push(field);
                        }
                    }
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

    // МЕТОДЫ ОПРОСНИКА
        // метод для добавления поля 
        addField(field, count) {
            if(this.totalInfo.json[field].length < count) {
                this.totalInfo.json[field].push('');
            }
        },
        // метод для удаления поля 
        delField(field, index) {
            this.totalInfo.json[field].splice(index, 1);
        },

        // добавление заинтересованного лица
        addInterestMan() {
            this.totalInfo.json.interestMan.push({name: '', role: ''});
        },
        // добавление метрики
        addMetrics() {
            this.totalInfo.json.metrics.push({metric: '', index: '', dateBefore: '', dateAfter: ''});
        }, 
        // добавление ресурса
        addResource() {
            if(this.totalInfo.json.resources.length < 100) {
                this.totalInfo.json.resources.push({resource: '', index: ''})
            }
        },
        // добавление бюджета
        addBudget() {
            if(this.totalInfo.json.budget.length < 100) {
                this.totalInfo.json.budget.push({expense: '', item: ''});
            }
        },
        // Добавление плана
        addPlan() {
            if(this.totalInfo.json.plan.length < 100) {
                this.totalInfo.json.plan.push({planName: '', date: ''});
            }
        },
    // МЕТОДЫ ФОРМАТИРОВАНИЯ

    // ОТПРАВКА НА СЕРВЕР
        // Записываем паспорт в базу 
        // Записываем идею в базу 
        postPassport(i) {
            // устанавливаем закончен ли паспорт или нет
            if( i === 1) {
                this.totalInfo.ready = 1;
            }
            this.totalInfo.json.step = this.stage;

            // let data = JSON.stringify(this.totalInfo);
            let data = this.totalInfo;
            axios
                .post(route('api.service.digital.passport.store'), data)
                .then(response => {
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
        finish()
        {
            let stage = this.stage
            let stageObj = this.stages[stage];

            this.stageValidateRequired(stage);

            if( this.errors.length > 0 )
            {
                return null;
            }

            this.stage = 9;

            if( this.passport )
            {
                this.updatePassport(1);
            } else {
                this.postPassport(1);
            }
        },
        // Завершаем редактирование и сохраняем как незаконченный документ
        // затем переходим на страницу Мои документы
        // сохранение паспорта
        savePassort() {
            if( !this.passport )
            {
                this.postPassport(0);
            } else {
                this.updatePassport(0);
            }
        },
        safeAndExit() {
            if( !this.passport )
            {
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
        // Генерация PDF
        generatePDF()
        {
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
        // если передан паспорт, то обновляем totalInfo
        getDraftPassport()
        {
            if( this.passport )
            {
                this.stage = this.passport.json.step;
                this.totalInfo.organization = this.passport.organization;
                this.totalInfo.title = this.passport.title;
                this.totalInfo.date  = this.passport.date;
                this.totalInfo.json  = this.passport.json;
            }
        },
        // hospitals
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

				this.organizations = organizationData;
			});
		},
    },
    mounted () {
        this.getDraftPassport();
        // !
        this.searchHospital('1')
    },
    // !
    components: {ModelSelect },
}
</script>
