<template>
	<div class="row">
		<div class="modal modal-notification" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" v-if="openNotification && openNotificationData">
			<div class="modal-dialog modal-lg">
				<template v-if="openNotificationData.type == 'App\\Notifications\\AdminFillUserProfileNotification' || openNotificationData.type == 'App\Notifications\AdminFillUserProfileNotification'">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title">{{ modal_title }}</h5>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
						</div>
						<div class="modal-body">
							<div>
								<div class="p-3 mb-3 pb-3 border m-3 topModalData">
									<p>Пользователь заполнил профиль, но не верифицирован</p>
									<p><strong>ФИО:</strong> <a :href="route('participant.show', openNotificationData.data.user)">{{ openNotificationData.data.user.full_name }}</a></p>
									<p><strong>Эл. почта:</strong> {{ openNotificationData.data.user.email }}</p>
								</div>
							</div>
							<a @click.prevent="deleteNotification(openNotificationData)" href="#" class="btn blue p-1 pl-3 pr-3">Удалить уведомление</a>
						</div>
					</div>
				</template>
				<template v-if="openNotificationData.type == 'App\\Notifications\\NewUserNotification' || openNotificationData.type == 'App\Notifications\NewUserNotification'">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title">{{ modal_title }}</h5>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
						</div>
						<div class="modal-body">
							<div>
								<div class="p-3 mb-3 pb-3 border m-3 topModalData">
									<p>На проектном сообществе появился новый пользователь.</p>
									<p><strong>ФИО:</strong> <a :href="route('participant.show', openNotificationData.data)">{{ openNotificationData.data.full_name }}</a></p>
									<p><strong>Эл. почта:</strong> {{ openNotificationData.data.email }}</p>
								</div>
							</div>
							<a @click.prevent="deleteNotification(openNotificationData)" href="#" class="btn blue p-1 pl-3 pr-3">Удалить уведомление</a>
						</div>
					</div>
				</template>
			</div>
		</div>
		<div class="modal modal-contact-share" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" v-if="showModal">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title">Отправка уведомления</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">          
						<div class="row greybg">
							<div class="col-md-12 pb-3">
								<div class="input_description mt-3 mb-1">
									<p class="text">Кому:</p>
								</div>
								<multi-select :options="options" :selected-options="SelectedOptions" placeholder="Выберите пользователя" @select="onSelect" />
								<has-error :form="form" field="users" />
							</div>
							<div class="col-md-12 pb-3">
								<div class="input_description mt-3 mb-1">
									<p class="text">Тема:</p>
								</div>
								<input class="form-control" type="text" v-model="form.subject" placeholder="" name="subject" autocomplete="subject" />
								<has-error :form="form" field="subject" />
							</div>
							<div class="col-md-12 pb-3">
								<div class="input_description mt-3 mb-1">
									<p class="text">Напишите сообщение:</p>
								</div>
								<textarea class="form-control" type="text" v-model="form.message" placeholder="" name="message" autocomplete="message"></textarea>
								<has-error :form="form" field="message" />
							</div>
						</div>
						<div class="row">
							<div class="col-md-12 align-center">
								<button @click.prevent="sendNotification" class="btn btn-red red">{{sendRequestBtn}}</button>
							</div>
						</div>
						<!-- <div v-if="activeRequests.length > 0" class="row mt-3 pt-3 border-top">
							<div class="col-md-12 mb-3">
								<h5><b>Активные запросы</b></h5>
								<table class="table table-sm table-hover mb-0">
									<tr>
										<th scope="col">Поля</th>
										<th scope="col">Сообщение</th>
										<th scope="col">Действия</th>
									</tr>
									<tr v-for="(activeRequest, index) in activeRequests" :key="index">
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
		<div class="col-md-12">
			<div class="box-search pt-3 pl-3">
				<button @click.capture="shareContacts()" data-toggle="modal" data-target=".modal-contact-share" class="btn blue mb-3">+ отправить новое уведомление</button> 
				<div v-if="notifications.length == 0" class="box-module border_bottom p-4">
					<div class="row">
						<div class="col-md-12">
							<i>Пока нет уведомлений.</i>
						</div>
					</div>
				</div>
				<div v-for="(notification, index) in notifications" :key="index" class="box-module border_bottom p-4">
					<template v-if="notification.type == 'App\\Notifications\\AdminFillUserProfileNotification' || notification.type == 'App\Notifications\AdminFillUserProfileNotification'">
						<div class="row">
							<div class="col-md-2">
								<div class="avatar-circle small">
									<a :href="route('participant.show', notification.data.user)" class="ratio img-responsive img-circle avatar" :style="'background-image:url(\'' + notification.data.user.avatar + '\');background-size: contain;'" v-if="notification.data.user.avatar"></a>
									<a :href="route('participant.show', notification.data.user)" class="ratio img-responsive img-circle avatar" v-else></a>
								</div>
							</div>
							<div class="col-md-6">
								<div class="notification_subject">
									<div class="font-weight-bold black_text">Требуется верификация!</div>
								</div>
								<div class="user_title">
									<a class="font-weight-bold" :href="route('participant.show', notification.data.user)">{{ notification.data.user.full_name }}</a>
								</div>
								<div class="p-0 bio_text user_description">
									<div class="share_text black_text">
										<p>Пользователь заполнил профиль, но не верифицирован</p>
									</div>
								</div>
								<div class="mt-3 date_text">
									<div class="grey_text">Получено: {{ moment(notification.created_at).locale('ru').format('LLLL') }}</div>
									<div class="grey_text" v-show="notification.read_at">Просмотрено: {{ moment(notification.read_at).locale('ru').format('LLLL') }}</div>
								</div>
							</div>
							<div class="col-md-4">								
								<a @click.capture="showNotification(notification)" data-toggle="modal" data-target=".modal-notification" href="#" class="btn-small red">Открыть уведомление</a>
							</div>
						</div>
					</template>
					<template v-if="notification.type == 'App\\Notifications\\NewUserNotification' || notification.type == 'App\Notifications\NewUserNotification'">
						<div class="row">
							<div class="col-md-2">
								<div class="avatar-circle small">
									<a :href="route('participant.show', notification.data)" class="ratio img-responsive img-circle avatar" :style="'background-image:url(\'' + notification.data.avatar + '\');background-size: contain;'" v-if="notification.data.avatar"></a>
									<a :href="route('participant.show', notification.data)" class="ratio img-responsive img-circle avatar" v-else></a>
								</div>
							</div>
							<div class="col-md-6">
								<div class="notification_subject">
									<div class="font-weight-bold black_text">Новый пользователь!</div>
								</div>
								<div class="user_title">
									<a class="font-weight-bold" :href="route('participant.show', notification.data)">{{ notification.data.full_name }}</a>
								</div>
								<div class="p-0 bio_text user_description">
									<div class="share_text black_text">
										<p>На проектном сообществе появился новый пользователь.</p>
									</div>
								</div>
								<div class="mt-3 date_text">
									<div class="grey_text">Получено: {{ moment(notification.created_at).locale('ru').format('LLLL') }}</div>
									<div class="grey_text" v-show="notification.read_at">Просмотрено: {{ moment(notification.read_at).locale('ru').format('LLLL') }}</div>
								</div>
							</div>
							<div class="col-md-4">								
								<a @click.capture="showNotification(notification)" data-toggle="modal" data-target=".modal-notification" href="#" class="btn-small red">Открыть уведомление</a>
							</div>
						</div>
					</template>
				</div>
			</div>
		</div>
	</div>
</template>
<script>
	import { Form, HasError, AlertError, AlertSuccess } from 'vform';
	import { ModelSelect, MultiSelect, ModelListSelect } from 'vue-search-select';

	export default {
		name: 'Index',
		data(){
			return {
				sendRequestBtn: 'Отправить уведомление',
				sortParam: '',
				modal_title: '',
				showModal: false,
				openNotification: false,
				search: '',
				place: '',
				role: '',
				education: '',
				verified: 'all',
				rating: 'all',
				places: [],
				activeRequests: [],
				activeSharings: [],
				targetUser: [],
				shareEmail: [],
				modal: {
					name: '',
				},
				options: [],
				SelectedOptions: [],
				users: [],
				userInfo:[],
				notifications:[],
				form: new Form({
					subject: '',
					message: '',
					users: [],
					type: 'dashboard-user-messanges'
				})
			}
		},
		props: [
			'user'
		],
		mounted(){
			this.getUsers();
			this.userInfo = JSON.parse(this.user);
			this.notifications = this.userInfo.notifications;

			console.log('this.notifications', this.notifications);
		},
		methods:{
			onSelect(items, lastSelectItem)
			{
				this.form.users = items.map(u => {
					return u.value;
				});
				this.SelectedOptions = items
				this.lastSelectItem = lastSelectItem
			},
			// deselect option
			reset () {
				this.items = [] // reset
			},
			// select option from parent component
			selectFromParentComponent () {
				this.items = _.unionWith(this.items, [this.options[0]], _.isEqual)
			},
			async deleteRequest (id) {
				let v = this;
				axios.delete('/notifications/'+id)
					.then(function (response) {
						window.location.reload();
					})
					.catch(function (error) {
						console.log(error);
					});
			},
			async sendNotification () {
				const { data } = await this.form.post(route('api.notification.store'));
				this.form.message = '';
				this.form.subject = '';
				this.form.users = [];
				this.SelectedOptions = [];
				this.sendRequestBtn = "Успешно!";
				setTimeout(() => this.sendRequestBtn = 'Отправить ещё одно уведомление', 1000);
			},
			deleteNotification(notification)
			{
				let v = this;
				axios
					.delete(route('api.notification.show.destroy', notification))
					.then(function (response) {
						window.location.reload();
					})
					.catch(function (error) {
						console.log(error);
					});
			},
			getUsers()
			{
				let v = this;
				axios
					.get(route('api.users.index'))
					.then(function (response) {
						v.users = response.data.data;
					})
					.catch(function (error) {
						console.log(error);
					});
			},
			showNotification(notification)
			{
				let v = this;
				this.openNotification = false;
				this.modal_title = 'Просмотр уведомления';
				this.openNotification = true;
				this.openNotificationData = notification;
				this.showModal = true;
				if( notification.read_at == null )
				{
					axios.post(route('api.notification.show.read', notification));
				}
			},
			shareContacts(user){
				let v = this;
				let users = [];
				this.users.forEach(uelement => {
					users.push({
						value: uelement.id,
						text: uelement.full_name
					});
				});
				this.options = users;
				this.sendRequestBtn = "Отправить уведомление";
				this.showModal = true;	
			},
		},
		components: {
			ModelSelect,
			MultiSelect,
			ModelListSelect,
			HasError
		}
	}
</script>