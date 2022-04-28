<template>
    <div class="ui-kit__accardeon">        
        <!-- acordeon title -->

        <label :for="id">{{ title }}</label>
        <input type="checkbox" :id="id" class="ui-kit__accardeon-show">
        
        <span class="open-accardeon-icon"></span>
        <span class="open-accardeon-icon2"></span>

        <!-- content -->
        <slot name="content"></slot>    
    </div>
</template>

<script>


export default {
    name: 'Accardeon',
    props: ['id', 'title'],
    data () {
        return {
        }
    },
}

</script>