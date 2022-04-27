<template>
<div v-if="stage != 9" class="show_onboarding">
    <!-- Темный фон над страницей -->
    <div class="onboarding__dark-panel"></div>

    <div class="onboarding__transparent-panel">
        <!-- приветсвие "Гоши" -->
        <div v-if="stage < 1" class="onboarding__bot">
            <div class="onboarding__bot-block">
                <div class="onboarding__bot-block-img"></div>
            </div>
            <div class="onboarding__bot-content">
                <div v-if="robotMsgStep > 0" class="onboarding__bot-message">
                    Здравствуйте! Я Робот Гоша, меня разработали участники Проектного 
                    сообщества. Я познакомлю вас с нашей онлайн-площадкой. Нажмите "Поехали!" для 
                    продолжения.
                </div>
                
                <div v-if="robotMsgStep > 1" class="onboarding__bot-message">
                    Вам точно не нужна помощь в знакомстве с сайтом? Это займет не более 30 секунд...
                </div>

            </div>
            <div  class="onboarding__bot-btns">
                <button class="onboarding__btn" 
                    @click="nextCard">{{robotMsgStep == 1 ? 'Поехали!' : 'Нет, остаться'}}
                </button>
                <button v-if="robotMsgStep == 1" 
                    class="onboarding__btn-grey mt-2" 
                    @click="addRobotMsg">Разберусь самостоятельно
                </button>
                <button v-else 
                    class="onboarding__btn-grey mt-2" 
                    @click="closeOnboarding">Да, выйти
                </button>
            </div>
           
        </div>
        <!-- Карточки онбординга -->
        <div  v-else-if="stage >= 1 & stage <=7" class="onboarding__cards">
            <div class="onboarding__card">
                <div class="onboarding__block-1">
                    <div class="onboarding__card-arrow">
                        <div @click="backCard" class="onboarding__card-arrow-icon"></div>
                    </div>
                    
                    <div class="onboarding__card-image">
                        <img :src="cards[stage].img" alt="">
                    </div>
                    <div class="onboarding__card-title">{{cards[stage].title}}</div>
                    <div class="onboarding__card-descr">{{cards[stage].desription}}</div>
                </div>
                <div class="onboarding__block-2">
                    <div class="onboarding__card-btn">
                        <button class="onboarding__btn pl-3 pr-3" @click="nextCard">
                            {{buttonsText[stage-1]}}</button>
                    </div>
                    <div class="onboarding__card-points">
                        <div class="onboarding__card-point" :class="{'active': stage == 1}"></div>
                        <div class="onboarding__card-point" :class="{'active': stage == 2}"></div>
                        <div class="onboarding__card-point" :class="{'active': stage == 3}"></div>
                        <div class="onboarding__card-point" :class="{'active': stage == 4}"></div>
                        <div class="onboarding__card-point" :class="{'active': stage == 5}"></div>
                        <div class="onboarding__card-point" :class="{'active': stage == 6}"></div>
                        <div class="onboarding__card-point" :class="{'active': stage == 7}"></div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Прощание "Гоши" -->
        <div v-else-if="stage > 6" class="onboarding__bot">
            <div class="onboarding__bot-block">
                <div class="onboarding__bot-block-img"></div>
            </div>
            <div class="onboarding__bot-content">
                <button class="onboarding__arrow-left" @click="backCard" title="Назад"></button>
                <button class="onboarding__arrow-close" @click="closeOnboarding" title="Закрыть онбординг"></button>
                <div  class="onboarding__bot-message">
                    Спасибо, что прошли наш онбординг. Если вы еще не загружали фотографию и не заполняли профиль, это можно сделать прямо сейчас.
                </div>
                <div class="onboarding__bot-message">
                    Чтобы закрыть онбординг, нажмите на крестик в правом верхнем углу.
                </div>
            </div>
            <div  class="onboarding__bot-btns">
                <!-- <button
                    class="onboarding__btn" 
                    @click="loadAvatar">Загрузить фотографию профиля
                </button> -->
                <button class="onboarding__btn-grey" @click="closeOnboarding">
                    <a :href="route('user.index')">Завершить и заполнить профиль</a>
                </button>
            </div>
        </div>
        <!-- Если устройство находится в горизонтальном положении и высота экрана < 723px -->
        <div class="onboarding__alert">
            <div class="onboarding__bot-block-img"></div>
            <div class="onboarding__alert-message">Переверните устройство в вертикальное положение</div>
        </div>
    </div>
</div>  
</template>
<script>
export default {
    name: 'Onboarding',
    data () {
        return {
            stage: this.getLocalStage(),
            cards: [
                {card: 0, title: '', desription: 'Здравствуйте! Я Робот Гоша, меня разработали участники Проектного сообщества. Я познакомлю вас с нашей онлайн-площадкой. Нажмите "Поехали!" для продолжения.', img: '/storage/images/onboarding/onbording__my_page.png'},
                {card: 1, title: 'Моя страница', desription: 'Здесь находится страница вашего профиля. Она нужна, чтобы другие участники могли побольше узнать о вас. Заполните профиль хотя бы на 25%, чтобы получить верификацию и открыть раздел "Участники"', img: '/storage/images/onboarding/onbording__my_page.png'},
                {card: 2, title: 'Участники', desription: 'В этом разделе вы найдете страницы участников, спикеров и экспертов сообщества и сможете обменяться с ними контактами', img: '/storage/images/onboarding/onbording__community.png'},
                {card: 3, title: 'Здравые мысли', desription: 'В разделе "Здравые мысли" можно стать спикером сообщества. Если хотите поделиться своим опытом и знаниями с единомышленниками, здесь можно отправить заявку или посмотреть выступления других участников', img: '/storage/images/onboarding/onbording__healthy_things.png'},
                {card: 4, title: 'Новости', desription: 'Новости сообщества - это раздел, в котором вы сможете прочитать о мероприятиях, вебинарах, проектах и подкастах сообщества. А еще тут есть авторские статьи, новости онлайн-площадки и развернутая версия нашего дайджеста', img: '/storage/images/onboarding/onbording__news.png'},
                {card: 5, title: 'Проекты сообщества', desription: 'В этом разделе можно посмотреть проекты участников сообщества и вдохновиться на воплощение собственных идей.', img: '/storage/images/onboarding/onbording__projects.png'},
                {card: 6, title: 'Сервисы', desription: 'Здесь вы найдете набор сервисов сообщества. С их помощью можно оформить идею проекта, создать его паспорт, получить бесплатную консультацию, принять участие в исследовании. Раздел регулярно пополняется, следите за обновлениями.', img: '/storage/images/onboarding/onbording__services.png'},
                {card: 6, title: 'База знаний', desription: 'Наша база знаний еще не разработана, но мы планируем исправить это в ближайшее время. Следите за обновлениями в новостях!', img: '/storage/images/onboarding/onbording__knowledge_base.png'},
                {card: 7, title: '', desription: 'Спасибо, что прошли наш онбординг. А еще вы стали на шаг ближе к тому, чтобы стать полноправным участником сообщества. Осталось загрузить ваше фото и поподробнее заполнить профиль', img: '/storage/images/onboarding/onbording__knowledge_base.png'},
            ],
            buttonsText: [
                'Спасибо, заполню', 'Ясно, будем знакомиться', 'Звучит здраво :)', 
                'Теперь буду в курсе', 'Будет полезно', 'Интересно, загляну', 'Ждем...',
             ],
            htmlBody: '',
            sideNav: '',
            robotMsgStep: 1,
            chat: '',
            avatar: '',
        }
    },
    props: [],
    computed:  {
        
    },
    mounted: function () {
        document.addEventListener("DOMContentLoaded", () => {
        // получение body
            this.htmlbody = document.body;
        // получение side menu
            this.sideNav = document.querySelectorAll(':scope .side_nav li');
        // получения класса загрузки аватара
            this.avatar = document.querySelector('.uploadTrigger');
        // получение show onboarding
            let onboarding = document.querySelectorAll('.onboarding-show');
            onboarding.forEach((i) => {
                i.addEventListener('click', () => {
                    this.onboardingShow();
                });
            })
        // подсветка пунктов меню если онбординг был закрыт на каком то из этапов
            this.stage--;
            this.nextCard();

        // Если пользователь зашел первый раз в ЛК, то показываем онбординг
            if( this.$cookie.get('users-onboarding-views') == undefined || this.$cookie.get('users-onboarding-views') == null )
            {
                this.stage = 0;
                this.checkedOnboarding();
            }
        });
        
    },
    methods: {
        // Переход на следующий шаг онбординга
        nextCard() {
            this.htmlbody.style.overflow = 'hidden';
            this.stage++;
            if (this.stage < 9) {
                this.delclass();
                this.setLocalStage();
                if (this.stage >= 1 && this.stage <= 7) {
                    this.sideNav.forEach((item) => {
                        if (this.cards[this.stage].title === item.textContent.trim() ) {
                            item.classList.add('onboarding__side-menu');
                        }
                    })
                }          
            } else {
                this.delclass();
                this.htmlbody.style.overflow = '';
                localStorage.removeItem('onboarding-stage');
            }  
        },
        // Возврат на предыдущий шаг онбординга
        backCard() {
            if (this.stage > 0) {
                this.delclass();
                this.stage--;
                this.htmlbody.style.overflow = 'hidden';

                if(this.stage >= 1 && this.stage <= 7) {
                    this.sideNav.forEach((item) => {
                        if (this.cards[this.stage].title === item.textContent.trim()) {
                            item.classList.add('onboarding__side-menu');
                        }
                    })
                }
                this.setLocalStage();
            }
        },
        // Закрытие онбординга + удаление данных из LocalStorage
        closeOnboarding() {
            this.stage = 9;
            localStorage.removeItem('onboarding-stage');
            this.htmlbody.style.overflow = '';
        },
        // Удаление подстветки пунктов меню в side панели
        delclass() {
            this.sideNav.forEach((item) => {
                item.classList.remove('onboarding__side-menu');
            })
        },
        // Отображение второго сообщения от робота
        addRobotMsg () {
            this.robotMsgStep++;
        },
        // Получение шага из LocalStorage, иначе вернет 8 (онбординг закончен)
        getLocalStage() {
            if (localStorage.getItem('onboarding-stage')) {
                return localStorage.getItem('onboarding-stage');
            } else {
                localStorage.setItem('onboarding-stage', 9);
                return 9;
            }
        },
        // установка в LocalStorage шага онбординга
        setLocalStage() {
            localStorage.setItem('onboarding-stage', this.stage);
        },
        // Показ онбординга при клике на иконку + сброс на начальный этап
        onboardingShow() {
            this.stage = 0;
            this.robotMsgStep = 1;
            this.setLocalStage();
            this.htmlbody.style.overflow = 'hidden';
        },
        // Загрузка аватара
        loadAvatar() {
            // let event = new Event("click");
            // this.avatar.dispatchEvent(event);
        },
        // Изменение значения о том, что пользователь уже просмтаривал онбординг
        // и для этого пользоваткя он автоматически запускаться не будет 
        checkedOnboarding() {
            var vm = this;
            vm.$cookie.set('users-onboarding-views', 'true', { expires: '1Y' });
        },
    },
}
</script>
