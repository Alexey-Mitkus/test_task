<template>
<div class="card-item bg-velvet">
    <div class="motivation-card">        
        <h4 class="card-title bg-velvet">Карта вовлеченности</h4>

        <div class="card-raitings">
            <!-- Свой человек -->
            <div class="raiting-item">
                <div class="raiting-item__title">
                    <img src="/images/motivation/motivation-card-badge-5.svg" alt="">
                    <span>Командный игрок</span>
                </div>
                <div class="raiting-item__rating">
                    <span class="item__rating-percent">{{ params.teammate }}%</span>
                    <div class="item__rating-progressbar">
                        <span class="progressbar-scale" :style="{width: params.teammate + '%'}"></span>
                    </div>
                </div>
            </div>

            <!-- Цифровой доктор -->
            <div class="raiting-item ">
                <div class="raiting-item__title">
                    <img src="/images/motivation/motivation-card-badge-6.svg" alt="">
                    <span>Генератор идей</span>
                </div>
                <div class="raiting-item__rating">
                    <span class="item__rating-percent">{{ params.ideaGenerator }}%</span>
                    <div class="item__rating-progressbar">
                        <span class="progressbar-scale" :style="{width: params.ideaGenerator + '%'}"></span>
                    </div>
                </div>
            </div>

            <!-- Свой человек -->
            <div class="raiting-item">
                <div class="raiting-item__title">
                    <img src="/images/motivation/motivation-card-badge-7.svg" alt="">
                    <span>Сердцеед</span>
                </div>
                <div class="raiting-item__rating">
                    <span class="item__rating-percent">{{ params.heartthrob }}%</span>
                    <div class="item__rating-progressbar">
                        <span class="progressbar-scale" :style="{width: params.heartthrob + '%'}"></span>
                    </div>
                </div>
            </div>

            <!-- Эксперт -->
            <div class="raiting-item">
                <div class="raiting-item__title">
                    <img src="/images/motivation/motivation-card-badge-8.svg" alt="">
                    <span>SuperStar</span>
                </div>
                <div class="raiting-item__rating">
                    <span class="item__rating-percent">{{ params.digitalDoctor }}%</span>
                    <div class="item__rating-progressbar">
                        <span class="progressbar-scale" :style="{width: params.superStar + '%'}"></span>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
</template>

<script>


export default {
    name: 'MotivationCard2',
    props: ['params'],
    data () {
        return {
        }
    },
}

</script>