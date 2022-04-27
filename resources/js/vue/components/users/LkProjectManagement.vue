<template>
<div class="lk-info">
    <div class="lk-info__header">
        <div class="header-title">
            <h4>Проектное управление</h4>
        </div>
        <div v-if="status === 'user' || status === 'admin'" class="lk-block-reduct" @click="projectReduct(true)">
            <img src="/images/icons/reduct-icon.svg" alt="">
        </div>
    </div>
    <template v-if="!lodash.isEmpty(project) && ( !lodash.isEmpty(project.role) || !lodash.isEmpty(project.start_at) && !lodash.isEmpty(project.end_at) || !lodash.isEmpty(project.methodologies) || !lodash.isEmpty(project.getting) || !lodash.isEmpty(project.share) )">
        
        <div class="lk-product-block" v-if="!lodash.isEmpty(project.role)">
            <h4 class="title">Роль в проектной деятельности:</h4>
            <p class="desc">{{ project.role.name }}</p>
        </div>

        <div class="lk-product-block" v-if="!lodash.isEmpty(project.start_at) && !lodash.isEmpty(project.end_at)">
            <h4 class="title">Стаж проектной деятельности:</h4>
            <p class="desc">{{ project.experience }} {{ CountForm(project.experience, ['год', 'года', 'лет'])  }}</p>
        </div>

        <div class="lk-product-block" v-if="!lodash.isEmpty(project.methodologies)">
            <h4 class="title">Используемые методологии:</h4>
            <ul class="desc-list">
                <li v-for="(tag, index) in project.methodologies" :key="index">{{ tag.pivot.value }}</li>
            </ul>
        </div>

        <div class="lk-product-block" v-if="!lodash.isEmpty(project.getting)">
            <h4 class="title">Что вы хотите получить в Проектном сообществе ?</h4>
            <p class="desc">{{ project.getting }}</p>
        </div>

        <div class="lk-product-block" v-if="!lodash.isEmpty(project.share)">
            <h4 class="title">Чем вы готовы поделиться с Проектным сообществом ?</h4>
            <p class="desc">{{ project.share }}</p>
        </div>

    </template>
    <template v-else>
        <div class="lk-education-block">
            <h4 class="title">Вы еще не заполняли данный раздел</h4>
        </div>
    </template>
</div>
</template>
<script>
export default {
    name: 'LkProjectManagement',
    props: [
        'project',
        'status'
    ],
    data(){
        return {
            reductProjectInfo: false,
        }
    },
    methods: {
        projectReduct(reduct)
        {
            var vm = this;

            vm.reductProjectInfo = reduct == true ? true : false;

            vm.$emit('projectReduct', {
                reductProjectInfo: vm.reductProjectInfo
            });
        },
        CountForm(number, titles)
        {
            var number = Math.abs(number);
            
            if( Number.isInteger(number) )
            {
                var cases = [2, 0, 1, 1, 1, 2];  
                return titles[ (number%100>4 && number%100<20)? 2 : cases[(number%10<5)?number%10:5] ];
            }
            return titles[1];
        }
    }

}
</script>