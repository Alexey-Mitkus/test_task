<template>
<div class="card-item">
    <div class="motivation-card-double">
        <!-- Верхний блок -->
        <div class="up-block">
            <h3 class="up-block__title">Мой статус</h3>

            <!-- бейджи -->
            <div class="up-block__badges">
                <div class="up-block__badge">
                    <img v-if="bages < 5" src="/images/motivation/magister-badge-inactive.svg" alt="">
                    <img v-else src="/images/motivation/magister-badge.svg" alt="">

                    <h5 class="up-block__badge-title" :class="{'title-inactive': bages < 5}">Магистр</h5>
                    <p class="up-block__badge-count">5 бейджей </p>
                </div>

                <div class="up-block__badge">
                    <img v-if="bages < 7" src="/images/motivation/doca-badge-inactive.svg" alt="">
                    <img v-else src="/images/motivation/doca-badge.svg" alt="">

                    <h5 class="up-block__badge-title" :class="{'title-inactive': bages < 7}">Дока</h5>
                    <p class="up-block__badge-count">7 бейджей </p>
                </div>

                <div class="up-block__badge">
                    <img v-if="bages < 8" src="/images/motivation/guru-badge-inactive.svg" alt="">
                    <img v-else src="/images/motivation/guru-badge.svg" alt="">

                    <h5 class="up-block__badge-title" :class="{'title-inactive': bages < 8}">Гуру</h5>
                    <p class="up-block__badge-count">8 бейджей </p>
                </div>
            </div>


            <!-- progress bar -->
            <div class="up-block__badges-progressbar">
                <div class="badges-progressbar">
                    <span class="progressbar-scale" :style="{width: progress + '%'}"></span>
                </div>
                <p class="progressbar-desc">Вы собрали 6 бейджей</p>
            </div>
        </div>

        <!-- Нижний блок -->
        <div class="down-block">
            <div class="down-block__content">
                <img src="/images/motivation/motivation-cup.svg" alt="">
                <div class="down-block__desc">
                    <h4>Мои награды</h4>
                    <p>Получив статус вам будут открыты награды</p>
                </div>
            </div>

            <button @click="openModal()">Посмотреть</button>
        </div>
    </div>
</div>
</template>

<script>


export default {
    name: 'MotivationCardDouble',
    props: ['bages'],
    data () {
        return {
        }
    },
    methods: {
        openModal() {
            this.$emit('openModal', {
                modal: true
            })
        }
    },
    computed: {
        progress() {
            
            switch (this.bages) {
                case 0:
                    return 0;
                    break;
                case 1:
                    return 7;
                    break;
                case 2:
                    return 13;
                    break;
                case 3:
                    return 20;
                    break;
                case 4:
                    return 26;
                    break;
                case 5:
                    return 30;
                    break;
                case 6:
                    return 50;
                    break;
                case 7:
                    return 63;
                    break;
                case 8:
                    return 100;
                    break;
                default:
                    return 0
            }
        },
    },
}

</script>