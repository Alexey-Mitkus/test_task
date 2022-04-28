<template>
<div class="motivation-task-switch">

    <ul class="task-switch__tapes">
        <li @click="selectActiveTasks(1, 'ourPeople')" :class="{'switch-active': selected === 1}">
            Свой человек <span>(5 / 10)</span>
        </li>

        <li @click="selectActiveTasks(2, 'digitalDoctor')" :class="{'switch-active': selected === 2}">
            Цифровой доктор <span>(5 / 10)</span>
        </li>

        <li @click="selectActiveTasks(3, 'ambassador')" :class="{'switch-active': selected === 3}">
            Амбассадор <span>(10 / 40)</span>
        </li>

        <li @click="selectActiveTasks(4, 'expert')" :class="{'switch-active': selected === 4}">
            Эксперт <span>(0 / 30)</span>
        </li>
    </ul>

</div>
</template>

<script>

export default {
    name: 'ActiveTasks',
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