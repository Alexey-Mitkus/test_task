<template>
<div class="card-item bg-blue">
    <div class="motivation-card">        
        <h4 class="card-title bg-blue">Карта активности</h4>

        <div class="card-raitings">
            <!-- Свой человек -->
            <div class="raiting-item">
                <div class="raiting-item__title">
                    <img src="/images/motivation/motivation-card-badge-1.svg" alt="">
                    <span>Свой человек</span>
                </div>
                <div class="raiting-item__rating">
                    <!-- :style="{width: progress + '%'}" -->
                    <span class="item__rating-percent">{{ params.ourPeople }}%</span>
                    <div class="item__rating-progressbar">
                        <span class="progressbar-scale" :style="{width: params.ourPeople + '%'}"></span>
                    </div>
                </div>
            </div>

            <!-- Цифровой доктор -->
            <div class="raiting-item">
                <div class="raiting-item__title">
                    <img src="/images/motivation/motivation-card-badge-2.svg" alt="">
                    <span>Цифровой доктор</span>
                </div>
                <div class="raiting-item__rating">
                    <span class="item__rating-percent">{{ params.digitalDoctor }}%</span>
                    <div class="item__rating-progressbar">
                        <span class="progressbar-scale" :style="{width: params.digitalDoctor + '%'}"></span>
                    </div>
                </div>
            </div>

            <!-- Свой человек -->
            <div class="raiting-item">
                <div class="raiting-item__title">
                    <img src="/images/motivation/motivation-card-badge-3.svg" alt="">
                    <span>Амбассадор</span>
                </div>
                <div class="raiting-item__rating">
                    <span class="item__rating-percent" >{{ params.ambassador }}%</span>
                    <div class="item__rating-progressbar">
                        <span class="progressbar-scale" :style="{width: params.ambassador + '%'}"></span>
                    </div>
                </div>
            </div>

            <!-- Эксперт -->
            <div class="raiting-item">
                <div class="raiting-item__title">
                    <img src="/images/motivation/motivation-card-badge-2.svg" alt="">
                    <span>Эксперт</span>
                </div>
                <div class="raiting-item__rating">
                    <span class="item__rating-percent">{{ params.expert }}%</span>
                    <div class="item__rating-progressbar">
                        <span class="progressbar-scale" :style="{width: params.expert + '%'}"></span>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
</template>

<script>


export default {
    name: 'MotivationCard1',
    props: ['params'],
    data () {
        return {
        }
    },
}

</script>