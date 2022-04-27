<template>
	<div class="row">

		<div class="modal modal-contact-share" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title">Отправка уведомления</h5>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
							</button>
					</div>
					<div v-if="showModal" class="modal-body">
						<div class="row greybg">
							<div class="col-md-12 pb-3">
								<div class="input_description mt-3 mb-1">
									<p class="text">Кому:</p>
								</div>
								<multi-select :options="options" :selected-options="SelectedOptions" placeholder="Выберите пользователя" @select="onSelect" />
								<has-error :form="form" field="users" />
							</div>
							<div class="col-md-12 pb-3">
								<div class="input_description mt-3 mb-1"><p class="text">Тема:</p></div>
								<input class="form-control" type="text" v-model="form.subject" placeholder="" name="subject" autocomplete="subject" />
								<has-error :form="form" field="subject" />
							</div>
							<div class="col-md-12 pb-3">
								<div class="input_description mt-3 mb-1"><p class="text">Напишите сообщение:</p></div>
								<textarea class="form-control" type="text" v-model="form.message" placeholder="" name="message" autocomplete="message"></textarea>
								<has-error :form="form" field="message" />
							</div>
						</div>
						<div class="row">
							<div class="col-md-12 align-center"><button @click.prevent="sendNotification" class="btn btn-red red">{{sendRequestBtn}}</button></div>
						</div>

						<!-- <div v-if="activeRequests.length > 0" class="row mt-3 pt-3 border-top">
							<div  class="col-md-12 mb-3">
								<table class="table table-sm table-hover mb-0">
							<h5><b>Активные запросы</b></h5>
								<tr>
									<th scope="col">Поля</th>
									<th scope="col">Сообщение</th>
									<th scope="col">Действия</th>
								</tr>
								<tr v-for="(activeRequest, index) in activeRequests">
									<td>{{activeRequest.fields}}</td>
									<td>{{activeRequest.message}}</td>
									<td><a @click.prevent="deleteRequest(activeRequest.id, index)" href="#" class="btn blue p-1 pl-3 pr-3">Удалить запрос</a></td>
								</tr>

							</table>
							</div>
						</div> -->
					</div>
				</div>
			</div>
		</div>


		<div v-if="modalDeleteUser" class="modal modal-contact-delete" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title">Удаление пользователя</h5>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span>
								</button>
						</div>
						<div class="modal-body">

							<div class="row greybg">
								<div class="col-md-12 pb-3">
									Вы действительно хотите удалить пользователя {{modalDeleteUser.name}}?
								</div>
							</div>

							<div class="row">

								<div class="col-md-12 align-center">
									<button class="btn" data-dismiss="modal" aria-label="Закрыть">Отмена</button>
									<button @click.prevent="deleteUser(modalDeleteUser)" class="btn btn-red red">Удалить</button>
								</div>
							</div>

						</div>
				</div>
			</div>
		</div>



		<div class="col-lg-9 col-md-12 pr-0 mb-2">
			<div class="box-search">
				<div class="box-module border_bottom">
					<span style="position: absolute; top: 17px;" class="search_icon"></span>
				<input style="padding-left:20px;width: 100%;" v-model="search" class="search_input" type="search" placeholder="Поиск участников" />
				</div>
				<div class="box-module border_bottom">
					<div class="row">
						<div class="col-md-4">
						</div>
						<div class="col-md-4">
							<a @click.prevent="sortParam == 'dateDown' ? sortParam='dateUp' : sortParam='dateDown'" href="#"><span :class="sortParam == 'dateUp' ? 'sort_up_icon' : 'sort_down_icon'"></span>По дате регистрации</a>
						</div>
						<div class="col-md-4">
						<a @click.prevent="sortParam == 'lastnameUp' ? sortParam='lastnameDown' : sortParam='lastnameUp'" href="#"><span :class="sortParam == 'lastnameUp' ? 'az_icon' : 'za_icon'"></span>По фамилии</a>
						</div>
					</div>
				</div>
				<!-- old -->
				<div v-for="participant in filteredUsersLists" class="box-module border_bottom p-4" :key="participant.id">
					<div class="row d-flex justify-content-between">
						<div class="col-md-3 pr-0 pl-0">
							<div class="avatar-circle small">
								<a :href="route('participant.show', participant)" class="ratio img-responsive img-circle avatar" :style="'background-image:url(\'' + participant.avatar + '\');'" v-if="participant.avatar"></a>
								<a :href="route('participant.show', participant)" class="ratio img-responsive img-circle avatar" v-else></a>
							</div>
							<div class="mt-1 ratingMore">
								<div class="admin-rating">
									<span class="text">Заполненность профиля: {{ participant.rating }}%</span>
									<div :style="{width: [participant.rating +'%']}" class="bar"></div>
								</div>
							</div>
						</div>
						<div class="col-md-5">
							<a class="font-weight-bold" :href="route('participant.show', participant)">
								{{ participant.full_name }}
								<span class="checkStatus">
									<span v-if="!lodash.isEmpty(participant.permissions) && participant.permissions.filter((permission) => { return permission.name == 'verified'}).length > 0" class="markSign">
										<span class="mark_icon"></span>
									</span>
									<template v-if="lodash.isEmpty(participant.permissions) || lodash.isEmpty(participant.permissions) && participant.permissions.filter((permission) => { return permission.name == 'verified'}).length == 0">
										<span class="questionSign">?</span>
									</template>
								</span>
							</a>
							<div class="p-0 bio_text">
								<a @click.prevent="place = (participant.job.organization ? participant.job.organization.name : participant.job.raw_organization)" href="#">{{ participant.job.organization ? participant.job.organization.name : participant.job.raw_organization }}</a>
								<a @click.prevent="role = participant.managment.role" href="#" v-show="participant.managment">{{ ( participant.managment ? ( participant.managment.role ? participant.managment.role.name : '' ) : '' ) }}</a>
								<div v-if="!lodash.isEmpty(participant.referal)"><b>{{ !lodash.isEmpty(participant.referal) ? 'Приглашение от: ' + participant.referal : ''}}</b></div>
								<p v-if="!lodash.isEmpty(participant.job)"><b>{{ !lodash.isEmpty(participant.job.position) ? 'Должность: ' + participant.job.position : ''}}</b></p>
							</div>
						</div>
						<div class="col-md-4">
							<a v-if="lodash.isEmpty(participant.permissions) || !lodash.isEmpty(participant.permissions) && participant.permissions.filter((permission) => { return permission.name == 'verified'}).length == 0" href="#" @click="verify(participant)" class="btn-small red">Верифицировать</a>
							<a style="margin-top: 5px;" v-if="participant.id != sourceUser.id" @click.capture="shareContacts(participant)" data-toggle="modal" data-target=".modal-contact-share" href="#" class="btn-small blue">Отправить уведомление</a>
							<a v-if="participant.rating <= 50" style="margin-top: 5px; background-color: darkred;" @click="messageAboutFillingProfile(participant)" class="btn-small blue">Отправить уведомление о заполнении профиля</a>
							<a v-if="participant.id != sourceUser.id" @click.capture="deleteUserModal(participant)" data-toggle="modal" data-target=".modal-contact-delete" href="#" class="btn-small red float-right mt-3">Удалить пользователя</a>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-lg-3 col-md-12">
			<div class="card">
				<div class="box-search">
					<div class="box-module border_bottom">
						<div class="box-title">Поиск по параметрам</div>
						<div v-if="place" @click="place = null" class="filter_title">
							<span class="close_icon"></span>
							{{place}}
						</div>
						<div v-if="role" @click="role = null" class="filter_title">
							<span class="close_icon"></span>
							{{role.name}}
						</div>
						<div v-if="education" @click="education = null" class="filter_title">
							<span class="close_icon"></span>
							{{education}}
						</div>
					</div>
					<div class="box-module border_bottom">
						<div class="box-title grey">Место работы</div>
						<model-select @searchchange="searchHospital" class="form-control" id="place" name="place" autocomplete="place" :options="places" v-model="place" placeholder="Ваше место работы" />
						<div class="box-description">Начните вводить <b>полное наименование организации</b> или её номер, затем выберите подходящий вариант из списка.</div>
					</div>
					<div class="box-module border_bottom">
						<div class="box-title grey">Роль в проектной деятельности</div>
						<model-list-select class="form-control" id="role" name="role" autocomplete="field5" :list="roles" option-value="id" option-text="name" v-model="role" placeholder="Роль в проектной деятельности" />
					</div>
					<div class="box-module border_bottom">
						<div class="box-title grey">Место обучения</div>
						<input id="education" class="form-control" type="text" placeholder="Место обучения" name="education" v-model="education" autocomplete="education">
					</div>

					<div class="box-module border_bottom">
						<div class="box-title grey">Верификация</div>
						<model-select class="form-control" id="verified" name="verified" autocomplete="verified" :options="verifieds" v-model="verified" placeholder="Верификация" />
					</div>

					<div class="box-module border_bottom">
						<div class="box-title grey">Заполненность профиля</div>
						<model-select class="form-control" id="rating" name="rating" autocomplete="rating" :options="ratings" v-model="rating" placeholder="Заполненность профиля" />
					</div>
				</div>
			</div>
		</div>
	</div>
</template>
<script>
	import { Form, HasError, AlertError, AlertSuccess } from 'vform';
	import { ModelSelect, ModelListSelect, MultiSelect, MultiListSelect } from 'vue-search-select';
	
	export default {
		name: 'index',
		data () {
			return {
				sendRequestBtn: 'Отправить уведомление',
				sortParam: 'dateDown',
				showModal: false,
				search: '',
				place: null,
				role: null,
				modalDeleteUser: null,
				education: null,
				verified: 'all',
				rating: 'all',
				places: [],
				filteredUsersLists: [],
				activeRequests: [],
				activeSharings: [],
				form: new Form({
					subject: '',
					message: '',
					users: [],
					type: 0,
				}),
				targetUser: [],
				shareEmail: [],
				modal: {
					name: '',
				},

				options: [],
				SelectedOptions: [],
				searchText: '', // If value is falsy, reset searchText & searchItem
				items: [],
				lastSelectItem: {},

				roles: [],
				verifieds: [
						{ value: 'all', text: 'Все' },
						{ value: true, text: 'Верифицирован' },
						{ value: false, text: 'Не верифицирован' },
				],
				ratings: [
						{ value: 'all', text: 'Все' },
						{ value: 100, text: 'Полностью заполнен' },
						{ value: 0, text: 'Не полностью заполнен' },
				],
			}
		},
		props: [
			'users',
			'user'
		],
		watch: {
			role: function(newVal, oldVal){
				if( newVal !== null && Number.isInteger(newVal) )
				{
					this.role = this.roles.filter((role) => {
						return role.id === newVal;
					})[0];
				}

				this.filteredUsers();
			},
			place: function(newVal, oldVal){
				this.filteredUsers();
			},
			education: function(newVal, oldVal){
				this.filteredUsers();
			},
			search: function(newVal, oldVal){
				this.filteredUsers();
			},
			sortParam: function(newVal, oldVal){
				this.filteredUsers();
			},
			verified: function(newVal, oldVal){
				this.filteredUsers();
			},
			rating: function(newVal, oldVal){
				this.filteredUsers();
			},
		},
		methods: {
			onSelect(items, lastSelectItem)
			{
				this.form.users = items.map(u => {
					return u.value;
				});
				this.SelectedOptions = items;
				this.lastSelectItem = lastSelectItem;
			},
			// deselect option
			reset()
			{
				this.items = [] // reset
			},
			// select option from parent component
			selectFromParentComponent()
			{
				this.items = _.unionWith(this.items, [this.options[0]], _.isEqual)
			},
			verify(user)
			{
				let vm = this;
				axios
					.post(route('api.users.show.verify', user))
					.then((response) => {
						document.location.reload(true);
					})
					.catch((error) => {
						console.log(error);
					});
			},
			messageAboutFillingProfile(user)
			{
				axios
					.post(route('api.users.show.notification.push-to-fill-profile', user))
					.catch((error) => {
						console.log(error);
					});
			},
			deleteUserModal(user)
			{
				this.modalDeleteUser = user;
			},
			deleteUser(user)
			{
				let vm = this;
				axios
					.delete(route('api.users.show.destroy', user))
					.then((response) => {
						document.location.reload(true);
					})
					.catch((error) => {
						console.log(error);
					});
			},
			async sendNotification()
			{
				const { data } = await this.form.post(route('api.notification.store'));
				this.form.message = '';
				this.form.subject = '';
				this.form.users = [];
				this.SelectedOptions = [];
				this.sendRequestBtn = "Успешно!";
				setTimeout(() => this.sendRequestBtn = 'Отправить ещё одно уведомление', 1000);
			},
			shareContacts(user)
			{
				var vm = this,
					users = [];
				JSON.parse(vm.users).forEach(uelement => {
					users.push({
						value: uelement.id,
						text: uelement.full_name
					});
				});
				vm.options = users;
				if( !collect(vm.SelectedOptions).contains('value', user.id))
				{
					vm.SelectedOptions.push({
							value: user.id,
							text: user.full_name
					});
				}
				vm.sendRequestBtn = "Отправить уведомление";
				vm.showModal = true;
			},
			searchHospital (searchText) {
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
							value: ( organization.name != organization.abbreviation ? organization.name + ' - ' + organization.abbreviation : organization.name ),
							text: ( organization.name != organization.abbreviation ? organization.name + ' - ' + organization.abbreviation : organization.name )
						}
					});

					organizationData.unshift({
						value: null,
						text: 'Моей организации нет в списке'
					});

					this.places = organizationData;
				});
			},
			getRoles()
			{
				let v = this;
				axios
					.get(route('api.users.managment.roles'))
					.then((response) =>{
						this.roles = response.data.data;
					})
					.catch(function(error){
						console.error(error);
					});
			},
			filteredUsers()
			{
				var vm = this;
				var participants = collect(JSON.parse(vm.users));

				vm.place ? vm.place = vm.place : vm.place = null;
				vm.role ? vm.role = vm.role : vm.role = null;
				vm.education ? vm.education = vm.education : vm.education = null;
				
				switch (vm.sortParam)
				{
					case 'lastnameUp':
						participants = participants.sort(function (d1, d2) {
							if( vm.lodash.isEmpty(d1.last_name) )
							{
								return -1;
							}
							if( vm.lodash.isEmpty(d2.last_name) )
							{
								return 1;
							}
							return (d1.last_name.pivot.value.toLowerCase() > d2.last_name.pivot.value.toLowerCase()) ? 1 : -1;
						});
					break;
					case 'lastnameDown':
						participants = participants.sort(function (d1, d2) {
							if( vm.lodash.isEmpty(d1.last_name) )
							{
								return 1;
							}
							if( vm.lodash.isEmpty(d2.last_name) )
							{
								return -1;
							}
							return (d1.last_name.pivot.value.toLowerCase() < d2.last_name.pivot.value.toLowerCase()) ? 1 : -1;
						});
					break;
					case 'dateUp':
						participants = participants.sort(function (d1, d2) { return (d1.created_at > d2.created_at) ? 1 : -1; });
					break;
					case 'dateDown':
						participants = participants.sort(function (d1, d2) {return (d1.created_at < d2.created_at) ? 1 : -1;});
					break;
					default:
					break;
				}

				if( vm.search )
				{
					participants = participants.filter((participant) => {
						if( vm.lodash.isEmpty(participant.full_name) )
						{
							return false;
						}
						return participant.full_name.toLowerCase().includes(vm.search.toLowerCase());
					});
				}
				
				if( vm.place )
				{
					var vm = this;
					participants = participants.filter((participant) => {
						if( !vm.lodash.isEmpty(participant.job) && !vm.lodash.isEmpty(participant.job.organization) )
						{
							return ( !vm.lodash.isEmpty(participant.job.organization) && participant.job.organization.name.toLowerCase().includes(vm.place.toLowerCase()) ) || ( !vm.lodash.isEmpty(participant.job.raw_organization) && participant.job.raw_organization.toLowerCase().includes(vm.place.toLowerCase()) );
						}else{
							return false;
						}
					});
				}
				
				if( vm.role )
				{
					var v = this;
					participants = participants.filter((participant) => {
						if( participant.managment !== null && participant.managment.role !== null )
						{
							return vm.role.id === participant.managment.role.id;
						}
					});
				}
				
				participants = participants.filter((participant) => {
					switch (this.verified)
					{
						case true:
						case 'true':
							return collect(participant.permissions).contains('name', 'verified');
						break;
						case false:
						case 'false':
							return !collect(participant.permissions).contains('name', 'verified');
						break;
						case 'all':
						default:
							return true;
						break;
					}
				});
				
				participants = participants.filter((participant) => {
					switch (this.rating)
					{
						case 100:
							return participant.rating >= 100;
						break;
						case 0:
							return participant.rating < 100;
						break;
						case 'all':
						default:
							return true;
						break;
					}
				});
				
				if( vm.education )
				{
					var v = this,
						findEducation = vm.education.toLowerCase();

					participants = participants.filter((participant) => {
						if( collect(participant.educations).count() )
						{
							return participant.educations.filter(element => {
								return ( element.raw_education !== null && element.raw_education.toLowerCase().includes(findEducation) )
									|| ( element.course_organization !== null && element.course_organization.toLowerCase().includes(findEducation) )
									|| ( element.university !== null && element.university.name.toLowerCase().includes(findEducation) );
							}).length > 0;
							
						}
					});
				}
				
				vm.filteredUsersLists = participants;
			}
		},
		mounted()
		{
			this.getRoles();
			this.filteredUsers();
		},
		created() {
			this.sortParam = 'dateDown'
		},
		computed: {
			sourceUser()
			{
				return JSON.parse(this.user);
			}
		},
		components: {
			ModelSelect,
			MultiSelect,
			MultiListSelect,
			ModelListSelect,
			HasError
		}
	}
</script>