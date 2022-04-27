<template>
    <div class="lk-photo">
        <span v-if="status === 'user' || status === 'admin' " class="reduct-icon" @click="totalReduct(true)"></span>
        <div class="photo">
            <img width="172" height="172" :src="totalInfo.avatar" alt="">
        </div>
        <div class="lk-user__info">
            <h3 v-if="!lodash.isEmpty(totalInfo.lastName) || !lodash.isEmpty(totalInfo.firstName) || !lodash.isEmpty(totalInfo.middleName)">{{ ( !lodash.isEmpty(totalInfo.lastName) ? totalInfo.lastName : '' ) }} {{ ( !lodash.isEmpty(totalInfo.firstName) ? totalInfo.firstName : '' ) }} {{ ( !lodash.isEmpty(totalInfo.middleName) ? totalInfo.middleName : '' ) }}</h3>
            <span class="status">{{ totalInfo.status }}</span>
            <div class="birth-date" v-if="birthdate">
                <img width="20" height="20" src="/images/icons/birth-cake.svg" alt="">
                <span>{{ birthdate }}</span>
            </div>
        </div>
    </div>
</template>
    
<script>
export default {
	name: 'LkPhoto',
    props: [
        'totalInfo',
        'status'
    ],
    data()
    {
        return {
            reductTotalInfo: false,
        }
    },
    methods: {
        totalReduct(reduct)
        {
            reduct == true ? this.reductTotalInfo = true : this.reductTotalInfo = false;
            this.$emit('totalReduct', {
                reductTotalInfo: this.reductTotalInfo
            })
        }
    },
    computed:{
        birthdate()
        {
            var vm = this;
            if( !vm.lodash.isEmpty(vm.totalInfo) && !vm.lodash.isEmpty(vm.totalInfo.birthdate) )
            {
                return ( vm.totalInfo.showBirthYear == true ? moment(vm.totalInfo.birthdate, 'DD.MM.YYYY').format('DD.MM.YYYY') : moment(vm.totalInfo.birthdate, 'DD.MM.YYYY').format('DD.MM') );
            }
            return null;
        }
    }

}
</script>