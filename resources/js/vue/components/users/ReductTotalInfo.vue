<template>
<div class="lk-info">
    <div class="lk-info__header">
        <div class="header-title">
            <h4>Основная информация</h4>
        </div>
    </div>
    
    <div class="lk-reduct-block">
        <form class="reduct-form">
            <div class="reduct-form__field">
                <div class="ui-kit-input"> 
                    <input type="text" class="iu-input_input" :class="{'border-red': $v.name.$error}"  
                    v-model.trim="name" required  @blur="$v.name.$touch()">
                    <label>Имя</label>
                    <span v-if="$v.name.$error" class="error-icon"></span>
                </div>
            </div>

            <div class="reduct-form__field">
                <div class="ui-kit-input">
                    <input type="text" class="iu-input_input" :class="{'border-red': $v.surname.$error}"  
                    v-model.trim="surname" required  @blur="$v.surname.$touch()">
                    <label>Фамилия</label>
                    <span v-if="$v.surname.$error" class="error-icon"></span>
                </div>
            </div>

            <div class="reduct-form__field">
                <div class="ui-kit-input">
                    <input class="iu-input_input" type="text" v-model.trim="patronymic" required>
                    <label>Отчество</label>
                    <!-- <span class="error-icon"></span> -->
                </div>
            </div>

            <div class="reduct-form__field">
                <div class="ui-kit-input">
                    <the-mask mask="##.##.####" class="iu-input_input" :class="{'border-red': $v.birthDate.$error}" name="year" id="year" :masked="true" type="text" v-model="birthDate" required />
                    <label>Дата Рождения</label>
                    <span v-if="$v.birthDate.$error" class="error-icon"></span>
                    <span v-else class="calendar-icon"></span>
                </div>
                
            </div>
            <div class="reduct-form__field">
                <span class="reduct-form__desc">День рождения указывается для того, 
                    чтобы участники сообщества могли вас поздравить</span>
            </div>

            <div class="reduct-form__checkbox">
                <div class="ui-kit__radio">
                    <input name="theme" type="radio" value="true" v-model="hideDate" id="yes" class="ui-kit__custom-radio">
                    <label for="yes">Показывать дату рождения</label>
                    <input name="theme" type="radio" value="false" v-model="hideDate" id="no" class="ui-kit__custom-radio">
                    <label for="no">Показывать только месяц и день</label>
                </div>
            </div>

            <div class="reduct-form__buttons">
                <button @click.prevent="saveSettings()" class="color-red">Сохранить изменения</button>
                <button @click.prevent="totalReduct(false)">Отменить</button>
            </div>
        </form>
    </div>
</div>
</template>
<script>
import {TheMask} from 'vue-the-mask';
import {required, between } from 'vuelidate/lib/validators';

export default {
	name: 'ReductTotalInfo',
    props: [
        'totalInfo'
    ],
    data () {
        return {
            name: this.totalInfo.firstName,
            surname: this.totalInfo.lastName,  
            patronymic: this.totalInfo.middleName,
            birthDate: this.totalInfo.birthdate,
            hideDate: this.totalInfo.showBirthYear,
            reductEducationInfo: true,
        }
    },
    mounted()
    {
        var vm = this;
    },
    watch:{
        birthDate: function(val){
            var vm = this;
            vm.$v.birthDate.$touch();
        }
    },
    methods: {
        
        totalReduct(reduct) {
            reduct == true ? this.reductTotalInfo = true : this.reductTotalInfo = false;
            
            this.$emit('totalReduct', {
                reductTotalInfo: this.reductTotalInfo
            })
        },
        saveSettings() {
            var vm = this;
            axios
                .post(route('api.users.show.update', vm.$parent.user), {
                    type: 'totalInfo',
                    data: {
                        first_name: vm.name,
                        last_name: vm.surname,
                        middle_name: vm.patronymic,
                        birth_date: vm.birthDate,
                        hideDate: vm.hideDate,
                    }
                })
                .then(response => {
                    vm.totalReduct(false);
                    vm.$parent.getUser(vm.$parent.user);
                })
                .catch(error => {
                    console.error(error)
                });
        }
    },
    validations: {
        name: {
            required
        },
        surname: {
            required
        }, 
        patronymic: {
            required
        }, 
        birthDate: {
            required,
            minValue: (value) => {
                return moment(value, 'DD.MM.YYYY', true).isSameOrAfter('01.01.1900', 'day');
            },
            maxValue: (value) => {
                return moment(value, 'DD.MM.YYYY', true).isSameOrBefore(moment(), 'day');
            },
        }, 
    },
    components: {
		TheMask
	},

}

</script>