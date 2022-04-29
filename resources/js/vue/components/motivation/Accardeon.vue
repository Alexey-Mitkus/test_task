<template>
    <div class="ui-kit__accardeon">        
        <!-- acordeon title -->

        <label :for="id">{{ title }}</label>
        <input v-if="user" type="checkbox" :id="id" class="ui-kit__accardeon-show">
        <input v-else type="checkbox" :id="id" class="ui-kit__accardeon-show" checked>

        <span class="open-accardeon-icon"></span>
        <span class="open-accardeon-icon2"></span>

        <!-- content -->
        <slot name="content"></slot>    
    </div>
</template>

<script>


export default {
    name: 'Accardeon',
    props: ['id', 'title', 'user'],
    data () {
        return {
        }
    },
}

</script>