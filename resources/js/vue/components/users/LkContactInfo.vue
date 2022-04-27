<template>
<div class="lk-info" v-if="!lodash.isEmpty(fields) && canViewViewer()">
    <div class="lk-info__header">
        <div class="header-title">
            <h4>Контактная информация</h4>
        </div>
        <div v-if="status === 'user' || status === 'admin'" class="lk-block-reduct" @click="contactReduct(true)">
            <img src="/images/icons/reduct-icon.svg" alt="">
        </div>
    </div>
    <template v-if="hasFields()">
        <template v-for="field in fields">
            <div class="lk-info-block" :key="field.id" v-if="contactInfo.where('slug', field.slug).count() > 0 && ( contactInfo.where('slug', field.slug).first().pivot.is_show == 1 && status === 'viewer' || ( status === 'user' || status === 'admin' ) )">
                <span class="contact-title">{{ field.name }}:</span>
                <span class="cantact-value">{{ contactInfo.where('slug', field.slug).first().pivot.value }}</span>
            </div>
        </template>
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
    name: 'LkContactInfo',
    props: [
        'contactInfo',
        'status'
    ],
    data(){
        return {
            reductContactInfo: false,
            fields: []
        }
    },
    mounted()
    {
        var vm = this;
        vm.fields = vm.$parent.userFields;
    },
    methods: {
        hasFields()
        {
            var vm = this;
            return vm.contactInfo.whereIn('slug', collect(vm.fields).pluck('slug').all()).count() > 0;
        },
        canViewViewer()
        {
            var vm = this;

            if( vm.status === 'user' || vm.status === 'admin' )
            {
                return true;
            }

            if( vm.contactInfo.whereIn('slug', collect(vm.fields).pluck('slug').all()).count() == 0 )
            {
                return false;
            }

            var count = vm.contactInfo.whereIn('slug', collect(vm.fields).pluck('slug').all()).filter((value, key) => {
                return value.pivot.is_show == 1;
            }).count();
            
            if( count == 0 )
            {
                return false;
            }

            return true;
        },
        contactReduct(reduct)
        {
            reduct == true ? this.reductContactInfo = true : this.reductContactInfo = false;
            this.$emit('contactReduct', {
                reductContactInfo: this.reductContactInfo
            });
        }
    }
}
</script>
