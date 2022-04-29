<template>
<div class="motivation-task-switch">

    <ul class="task-switch__tapes">
        <li @click="selectActiveTasks(1, 4)" :class="{'switch-active': selected === 1}">
            Командный игрок <span>(5 / 25)</span>
        </li>

        <li @click="selectActiveTasks(2, 5)" :class="{'switch-active': selected === 2}">
            Генератор идей <span>(5 / 40)</span>
        </li>

        <li @click="selectActiveTasks(3, 6)" :class="{'switch-active': selected === 3}">
            Сердцеед <span>(10 / 60)</span>
        </li>

        <li @click="selectActiveTasks(4, 7)" :class="{'switch-active': selected === 4}">
            SuperStar <span>(0 / 50)</span>
        </li>
    </ul>

</div>
</template>

<script>


export default {
    name: 'EnvolveTasks',
    data () {
        return {
            selected: 1,
        }
    },
    methods: {
        selectActiveTasks(item, task) {
            this.selected = item;

            this.$emit('selectActiveTasks', {
                task: task
            })
        }
    }
}

</script>