<template>
<div class="lk-info">
    <div class="lk-info__header">
        <div class="header-title">
            <h4>Контактная информация</h4>
        </div>
    </div>
    <div class="lk-reduct-block" >
        <form class="reduct-form">
            <div class="reduct-form__field flex-row" v-for="(field, index) in fields" :key="index">
                <template v-if="field.slug == 'telephone'">
                    <div class="ui-kit-input">
                        <the-mask mask="+7(###) ###-##-##" class="iu-input_input" :class="{'border-red': lodash.isEmpty(form[field.slug].value)}" type="text" :masked="true" v-model="form[field.slug].value" required>
                        </the-mask>
                        <label>{{ field.name }}</label>
                        <span v-if="lodash.isEmpty(form[field.slug].value)" class="error-icon"></span>
                    </div>
                </template>
                <template v-else>
                    <div class="ui-kit-input">
                        <input class="iu-input_input" type="text" v-model="form[field.slug].value" required>
                        <label>{{ field.name }}</label>
                    </div>
                </template>
                <div class="reduct-form__lock" @click="changeVisible(field)">
                    <span :class="{'eye-icon-close': !form[field.slug].is_show, 'eye-icon-open': form[field.slug].is_show}"></span>
                </div>
            </div>
            <div class="reduct-form__buttons">
                <button @click.prevent="saveSettings()" class="color-red">Сохранить изменения</button>
                <button @click.prevent="contactReduct(false)">Отменить</button>
            </div>
        </form>
    </div>
</div>
</template>
<script>
import {TheMask} from 'vue-the-mask'

export default {
	name: 'ReductContactInfo',
    props: [
        'contactInfo'
    ],
    data () {
        return {
            fields:[],
            form:{},
            reductContactInfo: true,
        }
    },
    mounted()
    {
        var vm = this;
        vm.fields = vm.$parent.userFields;

        vm.fields.forEach((field) => {
            if( vm.lodash.isEmpty(vm.form[field.slug]) )
            {
                vm.$set(vm.form, field.slug, {});
            }
            vm.$set(vm.form, field.slug, {
                value: vm.contactInfo.where('slug', field.slug).count() ? !vm.lodash.isEmpty(vm.contactInfo.where('slug', field.slug).first().pivot.value) ? vm.contactInfo.where('slug', field.slug).first().pivot.value : null : null,
                is_show: vm.contactInfo.where('slug', field.slug).count() ? ( vm.contactInfo.where('slug', field.slug).first().pivot.is_show == 1 ? 1 : 0 ) : 0
            });
        });
    },
    methods: {
        contactReduct(reduct) {
            reduct == true ? this.reductContactInfo = true : this.reductContactInfo = false;
            
            this.$emit('contactReduct', {
                reductContactInfo: this.reductContactInfo
            })
        },
        changeVisible(field)
        {
            var vm = this;
            vm.form[field.slug].is_show = vm.form[field.slug].is_show ? 0 : 1;
        },
        saveSettings()
        {
            var vm = this;
            axios
                .post(route('api.users.show.update', vm.$parent.user), {
                    type: 'ContactInfo',
                    data: vm.form
                })
                .then(response => {
                    vm.contactReduct(false);
                    vm.$parent.getUser(vm.$parent.user);
                })
                .catch(error => {
                    console.error(error)
                })
        }
    },
    components: {
		TheMask
	}
}

</script>