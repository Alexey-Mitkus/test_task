<template>
    <div class="motivation">
        <!-- header -->
        <div class="motovation__header">
            <div class="header-content">
                <h1>Система мотивации</h1>
                <p>Система мотивации - это механизм, стимулирующий участников совершенствовать 
                    свои навыки и развивать Проектное сообщество. Система состоит из набора заданий, 
                    за выполнение которых участники могут получать призы
                </p>
            </div>
        </div>

        <!-- accordeon 1 -->
        <components-motivation-accardeon id="accordeon-open1" title="Зачем нужна система мотивации?">
            <template v-slot:content>
                <div class="ui-kit__accardeon-content">
                    <components-motivation-accordeion-content-1 />
                </div>
            </template>
        </components-motivation-accardeon>

        <!-- accordeon 2 -->
        <components-motivation-accardeon id="accordeon-open2" title="Как это работает?">
            <template v-slot:content>
                <div class="ui-kit__accardeon-content">
                    <components-motivation-accordeion-content-2 />
                </div>
            </template>
        </components-motivation-accardeon>

        <!-- accordeon 2 -->
        <components-motivation-accardeon id="accordeon-open3" title="Какие награды получают участники?">
            <template v-slot:content >
                <div class="ui-kit__accardeon-content">
                    <components-motivation-accordeion-content-3 />
                </div>
            </template>
        </components-motivation-accardeon>

        <!-- motivation progress cards-->
        <components-motivation-progress>
            <!-- card 1 -->
            <template v-slot:card1 >
                <components-motivation-card-1  :params="card1Params" />
            </template>

            <!-- card 2 -->
            <template v-slot:card2 >
                <components-motivation-card-block v-if="secondCardBlock" />
                <components-motivation-card-2 v-else  :params="card2Params" />
            </template>
            
            <!-- card 3 -->
            <template v-slot:card3 >
                <components-motivation-card-double @openModal="modalOpen" :bages="bages" />
            </template>
        </components-motivation-progress>

        <!-- Задания -->
        <div class="motivation__task-switch">
            <div class="tasks_title" >
                <h3>Задания</h3>
            </div>

            <div class="tasks_cards">
                <div class="card-active-switch" @click="switcher = 0, achievement = 'ourPeople', secondCardBlock = true"
                    :class="{'switch-active': switcher === 0}">
                    <span>Карта активности</span>
                </div>
                <div class="card-active-involve" @click="switcher = 1, achievement = 'teammate', secondCardBlock = false"
                    :class="{'switch-active': switcher === 1}">
                    <span>Карта вовлеченности</span>
                </div>
            </div>
        </div>

        <!-- Правила -->
        <div class="motivation__rules">
            <h4>Подтверждение задания («Немного правил» тип такого)</h4>
            <p>После того, как вы выполните задание, необходимое для получения бейджа, 
                отправьте администраторам подтверждение. Это может быть скриншот с результатом 
                вашей работы или ссылка на ваше выступление, проект или идею (зависит от задания). 
                Если задание можно выполнить не единожды, необходимо отправлять подтверждение каждый 
                раз, как вы его сделаете.</p>
        </div>
        
        <!-- Блок выбора заданий -->
        <template v-if="switcher === 0">
            <components-motivation-active-tasks @selectActiveTasks="activateTasks" />
        </template>
        <template v-else-if="switcher === 1">
            <components-motivation-envolve-tasks @selectActiveTasks="activateTasks" />
        </template>

        <!-- Блок заданий -->
        <components-motivation-tasks-block :challenges="achievements[achievement]" />

        <!-- модальное окно -->
        <components-motivation-modal :openModal="openModal" @closeModal="modalClose"/>

    </div>
</template>
<script>

export default ({
    name: 'Motivation',
    data () {
        return {
            // Заблокирована ли 2 карта
            secondCardBlock: true,
            //переключатель заданий карточки
            switcher: 0,
            // data challenge items
            openModal: false,
            // active achievement block
            achievement: 'ourPeople',

            // собранные бейджи
            bages: 6,

            // параметры заполнения карточек
            card1Params: {
                ourPeople: 70, digitalDoctor: 90, ambassador: 15, expert: 0
            },
            card2Params: {
                teammate: 70, ideaGenerator: 90, heartthrob: 15, superStar: 0
            },

            // all achievements
            achievements: {
                ourPeople: [
                    {
                        id: 1,
                        title: 'Заполнить профиль на 80% и более и получить верификацию',
                        points: 5,
                        image: '/images/motivation/achivments/verification-achivment.svg'
                    },
                    {
                        id: 2,
                        title: 'Присоединиться к нам в социальных сетях',
                        points: 5,
                        image: '/images/motivation/achivments/social-media-achivment.svg'
                    },

                ],
                digitalDoctor: [
                    {
                        id: 3,
                        title: 'Найти ошибку на сайте и сообщить о ней',
                        points: 5,
                        image: '/images/motivation/achivments/search-achivment.svg'
                    },
                    {
                        id: 4,
                        title: 'Предложить улучшение на сайте сообщества',
                        points: 5,
                        image: '/images/motivation/achivments/arrowup-achivment.svg'
                    }
                ], 
                ambassador: [
                    {
                        id: 5,
                        title: 'Пригласить участниика в сообщество',
                        points: 20,
                        image: '/images/motivation/achivments/members-achivment.svg'
                    },
                    {
                        id: 6,
                        title: 'Поделиться постом о сообществе в соц. сетях',
                        points: 10,
                        image: '/images/motivation/achivments/share-link-achivment.svg'
                    }
                ], 
                expert: [
                    {
                        id: 7,
                        title: 'Выступить в роли спикера на онлайн или офлайн мероприятии',
                        points: 20,
                        image: '/images/motivation/achivments/speaker-achivment.svg'
                    },
                    {
                        id: 8,
                        title: 'Принять участие в подкасте в роли спикера-гостя подкаста',
                        points: 15,
                        image: '/images/motivation/achivments/podcust-achivment.svg'
                    },
                    {
                        id: 9,
                        title: 'Принять участие в подготовке статьи (как автор)',
                        points: 15,
                        image: '/images/motivation/achivments/pencil-achivment.svg'
                    },
                    {
                        id: 10,
                        title: 'Предоставить материал для интервью',
                        points: 10,
                        image: '/images/motivation/achivments/document-folder-achivment.svg'
                    }
                ],
                teammate: [
                    {
                        id: 11,
                        title: 'Принять участие в проектах сообщества в роли участника',
                        points: 10,
                        image: '/images/motivation/achivments/participant-achivment.svg'
                    },
                    {
                        id: 12,
                        title: 'Выступить в роли наставника в проекте',
                        points: 15,
                        image: '/images/motivation/achivments/mentor-achivment.svg'
                    },
                    {
                        id: 13,
                        title: 'Принять участие в проектах сообщества в роли лидера группы',
                        points: 20,
                        image: '/images/motivation/achivments/lead-achivment.svg'
                    },
                ],
                ideaGenerator: [
                    {
                        id: 14,
                        title: 'Предложить в «Банке идей» инициативу, которую впоследствии реализуют',
                        points: 20,
                        image: '/images/motivation/achivments/idea-generator-achivment.svg'
                    },
                ],
                heartthrob: [
                    {
                        id: 15,
                        title: 'Получить благодарность от участника сообщества',
                        points: 5,
                        image: '/images/motivation/achivments/thanks-achivment.svg'
                    },
                    {
                        id: 16,
                        title: 'Поставить "Спасибо" другому участнику (не более 1 раза в неделю одному пользователю, всего 5 лайков на неделю)',
                        points: 2,
                        image: '/images/motivation/achivments/thanks-action-achivment.svg'
                    },
                ],
                superStar: [
                    {
                        id: 17,
                        title: 'Получить благодарность от участника сообщества',
                        points: 20,
                        image: '/images/motivation/achivments/custdev-achivment.svg'
                    },
                    {
                        id: 18,
                        title: 'Стаж в сообществе от 9 месяцев',
                        points: 30,
                        image: '/images/motivation/achivments/work-experience-achivment.svg'
                    },
                ]
            },
        }
    },
    methods: {

        // открытие модального окна с наградами
        modalClose(data) {
            this.openModal = data.modal
        },
        // закрытие модального окна с наградами
        modalOpen(data) {
            this.openModal = data.modal
        },

        // переключение блока с заданиями Карты активности
        activateTasks(data) {
            this.achievement = data.task;
        },
    },
})
</script>
