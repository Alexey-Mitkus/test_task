<template>
<div class="motivation-tasks-block">
    <div class="tasks-block__title">
        <h4>Для получения бейджа необходимо набрать от {{ challenges.count }} баллов</h4>
        <span>Вы собрали 5 баллов</span>
    </div>

    <div class="tasks-block__tasks">
        <template v-for="item in challenges.achievments">
            <components-motivation-challenge-item :challenge="item"/>
        </template>
    </div>

</div>
</template>

<script>


export default {
    name: 'MotivationTasksBlock',
    props: {
        'challenges': Object,
    },
    data () {
        return {
            selected: 1,
            reset: false,
        }
    },

}

</script>