<template>
    <div class="motivation-modal__background" :class="{'motivation-modal-show': openModal}">
        <div class="motivation-modal__body">
            <div class="motivation-modal__content">
                <img class="ui__close-btn" src="/images/close_popup.svg" alt="" @click="closeModal()">

                <h2>Мои награды</h2>

                <div class="content__bages-total">
                    <span>Вы собрали 6 бейджей</span>
                </div>

                <div class="content__bages-achive-done">
                    <div class="achive-done-blcok">
                        <img src="/images/motivation/awward-active.svg" alt="">
                        <p class="achive-done-title">Брендированные планнер и ручка</p>
                    </div>
                    <div class="achive-done">Получено</div>
                </div>

                <p>Для получения следующих наград необходимо собрать 7 бейджей</p>
                <div class="content__bages-achive-done">
                    <div class="achive-done-blcok">
                        <img src="/images/motivation/awward-inactive.svg" alt="">
                        <p class="achive-done-title">Event (командообразование: лайт вариант с фуршетом)</p>
                    </div>
                    <button class="achive-block">Получить</button>
                </div>
                <div class="content__bages-achive-done">
                    <div class="achive-done-blcok">
                        <img src="/images/motivation/awward-inactive.svg" alt="">
                        <p class="achive-done-title">Мерч (футболка или толстовка)</p>
                    </div>
                    <button class="achive-block">Получить</button>
                </div>

                <p>Для получения следующих наград необходимо собрать 8 бейджей</p>
                <div class="content__bages-achive-done">
                    <div class="achive-done-blcok">
                        <img src="/images/motivation/awward-inactive.svg" alt="">
                        <p class="achive-done-title">Бизнес-завтрак с руководителем ДЗМ или командообразование (выезд за город 
                            с обучением в конце года)</p>
                    </div>
                    <button class="achive-block">Получить</button>
                </div>
                <div class="content__bages-achive-done">
                    <div class="achive-done-blcok">
                        <img src="/images/motivation/awward-inactive.svg" alt="">
                        <p class="achive-done-title">Почетная грамота/благодарность</p>
                    </div>
                    <button class="achive-block">Получить</button>
                </div>

            </div>
        </div>
    </div>
</template>

<script>

export default ({
    name: 'MotivationModal',
    props: ['openModal'],
    data () {
        return {
        }
    }, 
    methods: {
        closeModal() {
            this.$emit('closeModal', {
                modal: false
            })
        },
        
    }
});

</script> 