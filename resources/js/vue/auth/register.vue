<template>
<div class="register__main">
	<!-- Form -->
	<div class="register-form">
		<span class="step">Шаг 1 из 2</span>
		<h4>Вы являетесь сотрудником организации, подведомственной ДЗМ, или сотрудником ДЗМ?</h4>
		<div class="ui-kit__radio mb52">
			<input name="theme" type="radio" value="1" class="ui-kit__custom-radio" id="yes" v-model="employee">
			<label for="yes">Да</label>

			<input name="theme" type="radio" value="0" class="ui-kit__custom-radio" id="no" v-model="employee">
			<label for="no">Нет</label>
		</div>
		<!-- Место работы -->
		<div v-if="employee == 1" class="input-field mb52">
			<div class="ui-kit-input mb52">
				<model-select @searchchange="searchHospital" class="form-control" 
					:class="{ 'border-red': $v.formFields.workplace.$error }" 
					name="field3"  
					required :options="optionsWorkplace" v-model.trim="$v.formFields.workplace.$model" 
					placeholder="Ваше место работы">
				</model-select>

				<template v-if="$v.formFields.workplace.$error">
					<p v-if="!$v.formFields.workplace.required" class="input-alert-msg">Это поле обязательно для заполнения</p>
				</template>
				<div v-else class="box-description ">Начните вводить полное наименование организации или её номер, затем выберите подходящий вариант из списка</div>
			</div>
		</div>

		<div v-else class="input-field mb20">
			<div class="ui-kit-input "> 
				<input class="iu-input_input" type="text" 
					:class="{'border-red': $v.formFields.workplace.$error }"
					v-model.trim="$v.formFields.workplace.$model" required>
				<label>Место работы</label>
				<span :class="{'error-icon': $v.formFields.workplace.$error}"></span>
			</div>
			<template v-if="$v.formFields.workplace.$error">
				<p v-if="!$v.formFields.workplace.required" class="input-alert-msg">Это поле обязательно для заполнения</p>
			</template>
		</div>

		<!-- ФИО и Контактные данные -->
		<div class="mb52 input-field">

			<div class="field-block">
				<div class="ui-kit-input"> 
					<input class="iu-input_input" type="text" 
						:class="{'border-red': $v.formFields.last_name.$error }"
						v-model.trim="$v.formFields.last_name.$model" required>
					<label>Фамилия</label>
					<span :class="{'error-icon': $v.formFields.last_name.$error}"></span>
				</div>
				<template v-if="$v.formFields.last_name.$error">
					<p v-if="!$v.formFields.last_name.required" class="input-alert-msg">Это поле обязательно для заполнения</p>
				</template>
			</div>

			<div class="field-block">
				<div class="ui-kit-input"> 
					<input class="iu-input_input" type="text" 
						:class="{'border-red': $v.formFields.first_name.$error }"
						v-model.trim="$v.formFields.first_name.$model" required>
					<label>Имя</label>
					<span :class="{'error-icon': $v.formFields.first_name.$error}"></span>
				</div>
				<template v-if="$v.formFields.first_name.$error">
					<p v-if="!$v.formFields.first_name.required" class="input-alert-msg">Это поле обязательно для заполнения</p>
				</template>
			</div>

			<div class="field-block">
				<div class="ui-kit-input"> 
					<input class="iu-input_input" type="text" 
						:class="{'border-red': $v.formFields.middle_name.$error }"
						v-model.trim="$v.formFields.middle_name.$model" required>
					<label>Отчество</label>
					<span :class="{'error-icon': $v.formFields.middle_name.$error}"></span>
				</div>
				<template v-if="$v.formFields.middle_name.$error">
					<p v-if="!$v.formFields.middle_name.required" class="input-alert-msg">Это поле обязательно для заполнения</p>
				</template>
			</div>
			
			<div class="field-block">
				<div class="ui-kit-input">
					<the-mask mask="+7(###) ###-##-##" class="iu-input_input"
						:class="{'border-red': $v.formFields.phone.$error }"
						name="phone" id="phone"  
						required v-model.trim="$v.formFields.phone.$model" type="text" 
						:masked="true">
					</the-mask>
					<label>Телефон</label>
					<span :class="{'error-icon': $v.formFields.phone.$error}"></span>
				</div>
				<template v-if="$v.formFields.phone.$error">
					<p v-if="!$v.formFields.phone.required" class="input-alert-msg">Это поле обязательно для заполнения</p>
				</template>
			</div>


			<div class="field-block">
				<div class="ui-kit-input"> 
					<input class="iu-input_input" type="text" 
						:class="{'border-red': $v.formFields.email.$error || emailError }"
						v-model.trim="$v.formFields.email.$model" required>
					<label>E-mail</label>
					<span :class="{'error-icon': $v.formFields.email.$error || emailError }"></span>
				</div>
				<template v-if="$v.formFields.email.$error">
					<p v-if="!$v.formFields.email.email" class="input-alert-msg">Не правильно указан E-mail</p>
					<p v-else-if="!$v.formFields.email.required" class="input-alert-msg">Это поле обязательно для заполнения</p>
					<p v-else-if="!$v.formFields.email.isUnique" class="input-alert-msg">Пользователь с указанным E-mail адресом существует.</p>
				</template>
				<p v-if="emailError" class="input-alert-msg">Такая электронная почта уже занята</p>
			</div>
		</div>

		<!-- Пароль -->
		<h3>Придумайте пароль</h3>
		<div class="input-field">

			<div class="field-block mb52">
				<div class="ui-kit-input "> 
					<input :type="passtype" class="iu-input_input" 
						:class="{'border-red': $v.formFields.password.$error }"
						v-model.trim="$v.formFields.password.$model" required>
					<label>Пароль</label>

					<span @click="chagePassType()" :class="{'eye-icon-close': !passOpen, 'eye-icon-open': passOpen}">
					</span>
				</div>
				<template v-if="$v.formFields.password.$error">
					<p v-if="!$v.formFields.password.required" class="input-alert-msg">Это поле обязательно для заполнения</p>
					<p v-else-if="!$v.formFields.password.minLength" class="input-alert-msg">Пароль должен состоять не менее, чем из 6 символов</p>
				</template>
				<p v-else class="input-msg">Пароль должен состоять не менее, чем из 6 символов</p>
			</div>

			<div class="field-block confirm-pass">
				<div class="ui-kit-input "> 
					<input :type="passtype" class="iu-input_input" 
						:class="{'border-red': $v.formFields.password_confirmation.$error }"
						v-model.trim="$v.formFields.password_confirmation.$model" required>
					<label>Пароль</label>

					<span @click="chagePassType()" :class="{'eye-icon-close': !passOpen, 'eye-icon-open': passOpen}">
					</span>
				</div>
				<template v-if="$v.formFields.password_confirmation.$error">
					<p v-if="!$v.formFields.password_confirmation.sameAsPassword" class="input-alert-msg">Пароли не сопадают</p>
				</template>
			</div>
		</div>

		<p class="reg-desc"> Если вас пригласил к регистрации один из участников сообщества, пожалуйста, укажите его фамилию, имя и отчество в данном поле, это ускорит процесс верификации вашего профиля</p>
		<div class="ui-kit-input"> 
			<input class="iu-input_input" type="text" v-model="formFields.referal" required>
			<label>ФИО пригласившего</label>
		</div>

		<div class="ui-kit__check-item mb52">
			<input id="check" name="check" type="checkbox" class="ui-kit__custom-checkbox" 
				v-model="$v.formFields.agree.$model">
			<label for="check" class="register-check">Соглашаюсь с &nbsp;<a href="#">условиями обработки персональных данных</a></label>
		</div>

		<div class="kb-btns-line">
			<button  v-if="$v.$invalid == true" class="ui-btn-disabled mr-4">Далее</button>
            <button  v-else @click="register()" class="ui-btn-red mr-4">Далее</button>
        </div>

	</div>
	<!-- Baner -->
	<div class="register-picture">
		<h2>После регистрации на площадке вы получите доступ:</h2>
		<ul>
			<li>
				<img src="/images/register/register-community.svg">
				<span>К списку участников</span>
			</li>
			<li>
				<img src="/images/register/register-news.svg">
				<span>Анонсам мероприятий</span>
			</li>
			<li>
				<img src="/images/register/register-helpers.svg">
				<span>Цифровым помощникам</span>
			</li>
			<li>
				<img src="/images/register/register-services.svg">
				<span>Сервисам сообщества</span>
			</li>
			<li>
				<img src="/images/register/register-knowledge-base.svg">
				<span>Базе знаний</span>
			</li>
		</ul>
	</div>
</div>
</template>
<script>
	import { Form, HasError, AlertError, AlertSuccess } from 'vform';
	import { ModelSelect } from 'vue-search-select';
	import MaskedInput from 'vue-masked-input';
	import {TheMask} from 'vue-the-mask';
	import {required, minLength, email, sameAs} from 'vuelidate/lib/validators';

export default {
	name: 'Register',
	data () {
		return {
			type: 'true',
			passtype: 'password',
			passOpen: false,
			emailError: false,
			employee: 1,
			formFields: { 
				workplace: '',
				first_name: '',
				last_name: '',
				middle_name: '',
				phone: '',
				email: '',
				password: '',
				password_confirmation: '',
				referal: '',
				agree: true,
			},
			optionsWorkplace: [],
		}
	},
	methods: {
		// Открывеает или скрывает введенные данные пароля
		chagePassType() {
			this.passOpen == false ? this.passOpen = true : this.passOpen = false;
			this.passOpen == false ? this.passtype = 'password' : this.passtype = 'text'
		},

		// Регистрация
		register()
		{
			let formData = new FormData();
			formData.append('employee', this.employee);
			formData.append('workplace', this.formFields.workplace);
			formData.append('first_name', this.formFields.first_name);
			formData.append('last_name', this.formFields.last_name);
			formData.append('middle_name', this.formFields.middle_name);
			formData.append('phone', this.formFields.phone);
			formData.append('email', this.formFields.email);
			formData.append('password', this.formFields.password);
			formData.append('password_confirmation', this.formFields.password_confirmation);
			formData.append('referal', this.formFields.referal);

			axios
				.post(route('register'), formData)
				.then((response) => {
					window.location.href = route('user.index');
				})
				.catch((error) => {
					console.log(error);
					console.log('error', error.response.data);
					console.log('error.response.data.data', error.response.data.errors);

					//$v.formFields.email.$error
				});
		},

		searchHospital(searchText)
		{
			this.searchText = searchText;

			axios.get(route('api.organizations.index'), {
				params: {
					search: searchText
				}
			})
			.then((response) => {
				var organizationData = response.data.data;
				organizationData = organizationData.map((organization) => {
					return {
						value: organization.id,
						text: ( organization.name != organization.abbreviation ? organization.name + ' - ' + organization.abbreviation : organization.name )
					}
				});

				organizationData.unshift({
					value: 0,
					text: 'Моей организации нет в списке'
				});

				this.optionsWorkplace = organizationData;
			});
		},
	},
	mounted() {
		this.searchHospital('')
	},
	watch: {
		employee () {
			this.formFields.workplace = '';
		}
	},
	components: {
		HasError, AlertError, ModelSelect, MaskedInput, TheMask
	},
	// ВАЛИДАЦИЯ ПОЛЕй
	validations: {
		//fields
		formFields: {
			workplace: {
				required,
			},
			first_name: {
				required,
			},
			last_name: {
				required,
			},
			middle_name: {
				required,
			},
			phone: {
				required,
			},
			email: {
				required,
				email,
				isUnique (value)
				{
					if( value === '' )
					{
						return true;
					}
					return new Promise((resolve, reject) => {
						setTimeout(() => {
							axios
								.post(route('auth.isunique'), {
									email: value
								})
								.then((response) => {
									resolve(true);
								})
								.catch((error) => {
									resolve(false);
								});
						}, 350 + Math.random() * 300);
					});
				},
			},
			password: {
				required,
				minLength: minLength(6)
			},
			password_confirmation: {
				sameAsPassword: sameAs('password')
			},
			agree: {
				sameAs: sameAs( () => true )
			},
		}
	}
}
</script>