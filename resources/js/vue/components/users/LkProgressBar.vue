<template>
    <div class="lk-progressbar">
        <div class="progress-title">
            <div>
                <span>Заполненность профиля</span>
                <img src="/images/icons/icon-info.svg" alt="" width="16" height="16">
            </div>

            <div class="progressbar__percent-top">{{progress}}%</div>
        </div>

        <div class="progressbar__block">
            <div class="progressbar__total">
                <span  class="progressbar__ready" :style="{width: progress + '%'}"></span>
            </div>
            <div class="progressbar__percent-bottom">{{progress}}%</div>
        </div>
    </div>
</template>
    
<script>

export default {
	name: 'LkProgressBar',
    props: ['progress'],
    data () {
        return {
            screen: window.innerWidth,
        }
    },
}
</script>