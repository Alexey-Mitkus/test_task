<template>
    <div class="motivation-proress">
        <!-- title -->
        <div class="motivation__title">
            <h2>Мой прогресс</h2>
        </div>    
        
        <!-- cards -->
        <div class="motivation__cards">
            <!-- first card -->
            <slot name="card1"></slot>   
            
            <!-- second card -->
            <slot name="card2"></slot>   

            <!-- third card -->
            <slot name="card3"></slot>   

        </div>
    </div>
</template>

<script>


export default {
    name: 'MotivationProgress',
    data () {
        return {
        }
    },
}

</script>