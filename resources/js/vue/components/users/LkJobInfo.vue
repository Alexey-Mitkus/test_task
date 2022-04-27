<template>
<div class="lk-info">
    <div class="lk-info__header">
        <div class="header-title">
            <h4>Место работы</h4>
        </div>
        <div v-if="status === 'user' || status === 'admin'" class="lk-block-reduct" @click="jobReduct(true)">
            <img src="/images/icons/reduct-icon.svg" alt="">
        </div>
    </div>
    <template v-if="!lodash.isEmpty(job.tags) || !lodash.isEmpty(organization) || !lodash.isEmpty(job.position)">
        <div class="lk-job-block">
            <h4 class="job-place">{{ organization }}{{ organizationDate }}</h4>
            <p class="position">{{ job.position }}</p>
        </div>
        <div class="lk-product-block" v-if="!lodash.isEmpty(job.tags)">
            <h4 class="title">Опыт:</h4>
            <ul class="desc-list">
                <li v-for="(tag, index) in job.tags" :key="index">{{ tag.pivot.value }}</li>
            </ul>
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
    name: 'LkJobInfo',
    props: [
        'job',
        'status'
    ],
    data () {
        return {
            reductJobInfo: false
        }
    },
    computed:{
        organization()
        {
            var vm = this;
            return ( !vm.lodash.isEmpty(vm.job.organization) ? vm.job.organization.name : ( !vm.lodash.isEmpty(vm.job.raw_organization) ? vm.job.raw_organization : '' ) ) ;
        },
        organizationDate()
        {
            var vm = this;
            return ( !vm.lodash.isEmpty(vm.job.start_at) ? ', (' + moment(vm.job.start_at).format('YYYY') + ')' : null );
        }
    },
    methods: {
        jobReduct(reduct)
        {
            var vm = this;
            vm.reductJobInfo = reduct == true ? true : false;
            vm.$emit('jobReduct', {
                reductJobInfo: vm.reductJobInfo
            })
        }
    }

}
</script>