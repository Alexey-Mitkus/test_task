<template>
	<div class="col p-0">
		<!-- Модальное окно -->
		<div class="modal modal-contact-share" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" v-if="showModal">
			<div class="modal-dialog modal-lg">
				<template v-if="openNotificationData.type == 'App\\Notifications\\UserVerifyByAdminNotification' || openNotificationData.type == 'App\Notifications\UserVerifyByAdminNotification'">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title">{{ modal_title }}</h5>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
						</div>
						<div class="modal-body">
							<div>
								<div class="p-3 mb-3 pb-3 border m-3 topModalData">Ваш профиль прошел подтверждение</div>
							</div>
							<a @click.prevent="deleteNotification(openNotificationData)" href="#" class="btn blue p-1 pl-3 pr-3">Удалить уведомление</a>
						</div>
					</div>
				</template>
				<template v-if="openNotificationData.type == 'App\\Notifications\\KnowledgeBaseRejectNotification' || openNotificationData.type == 'App\Notifications\KnowledgeBaseRejectNotification'">
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
									<p>К сожалению, ваш материал не может быть добавлен в Базу знаний.</p>
									<p>&nbsp;</p>
									<p>Причины отклонения публикации: {{ openNotificationData.data.reject }}</p>
								</div>
							</div>
							<a @click.prevent="deleteNotification(openNotificationData)" href="#" class="btn blue p-1 pl-3 pr-3">Удалить уведомление</a>
						</div>
					</div>
				</template>
				<template v-if="openNotificationData.type == 'App\\Notifications\\KnowledgeBaseApprovedNotification' || openNotificationData.type == 'App\Notifications\KnowledgeBaseApprovedNotification'">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title">{{ modal_title }}</h5>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
						</div>
						<div class="modal-body">
							<div>
								<div class="p-3 mb-3 pb-3 border m-3 topModalData">Ваш материал прошел проверку и был опубликован в <a :href="route('knowledge-base.index')">Базе знаний</a></div>
							</div>
							<a @click.prevent="deleteNotification(openNotificationData)" href="#" class="btn blue p-1 pl-3 pr-3">Удалить уведомление</a>
						</div>
					</div>
				</template>
				<template v-if="openNotificationData.type == 'App\\Notifications\\UserMessageNotification' || openNotificationData.type == 'App\Notifications\UserMessageNotification'">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title">{{ modal_title }}</h5>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
						</div>
						<div class="modal-body">
							<div>
								<div class="p-3 mb-3 pb-3 border m-3 topModalData" v-html="openNotificationData.data.message"></div>
							</div>
							<a @click.prevent="deleteNotification(openNotificationData)" href="#" class="btn blue p-1 pl-3 pr-3">Удалить уведомление</a>
						</div>
					</div>
				</template>
			</div>
		</div>

		<!-- Фильтр -->
		<div class="col-md-12 p-0 m-0">
			<div class="side_edit_nav">
				<ul>
					<li @click="filterType = 0" :class="filterType == 0  ? 'active' : ''">Все уведомления</li>
					<li @click="filterType = 1" :class="filterType == 1 ? 'active' : ''">Уведомления от организаторов</li>
					<li @click="filterType = 2" :class="filterType == 2 ? 'active' : ''">Уведомления от участников</li>
				</ul>
			</div>
		</div>

		<!-- Окно Уведомлений -->
		<div class="col-md-12 p-0">
			<div class="box-search">

				<!-- Если уведомлений нет -->
				<div v-if="notifications.length == 0" class="box-module border_bottom p-3">
					<template v-if="filterType == 0">
						<div class="row">
							<div class="col-md-12">
								<i>Пока нет уведомлений.</i>
							</div>
						</div>
					</template>
					<template v-if="filterType == 1">
						<div class="row">
							<div class="col-md-12">
								<i>Для вас нет уведомлений от организаторов.</i>
							</div>
						</div>
					</template>
					<template v-if="filterType == 2">
						<div class="row">
							<div class="col-md-12">
								<i>Для вас нет уведомлений от участников.</i>
							</div>
						</div>
					</template>
				</div>

				<!-- Если уведомления есть -->
				<div v-if="notifications.length > 0">
					<template>
						<div v-for="notification in notifications" class="box-module border_bottom p-3" :key="notification.id">
							<template v-if="notification.type == 'App\\Notifications\\UserVerifyByAdminNotification' || notification.type == 'App\Notifications\UserVerifyByAdminNotification'">
								<div class="row">
									<div class="col-md-3">
										<div class="avatar-circle small">
											<template v-if="notification.data.sender.id == 1">
												<a href="#" class="ratio img-responsive img-circle avatar" :style="'background-image:url(\'/img/logo.svg\');background-color:#fff;background-repeat:no-repeat;background-position: 50% 50%;background-size: contain;'"></a>
											</template>
											<template v-else>
												<a :href="route('participant.show', notification.data.sender)" class="ratio img-responsive img-circle avatar" :style="'background-image:url(\'' + notification.data.sender.avatar + '\');background-size: contain;'"></a>
											</template>
										</div>
									</div>
									<div class="col-md-5 pb-2">
										<div class="user_title">
											<template v-if="notification.data.sender.id == 1">
												<a class="font-weight-bold black_text" href="#">{{ notification.data.sender.full_name }}</a>
											</template>
											<template v-else>
												<a class="font-weight-bold black_text" :href="route('participant.show', notification.data.sender)">{{ notification.data.sender.full_name }}</a> 
											</template>
											<br />
											<div class="font-weight-bold share_text blue_text">отправил(а) вам уведомление</div>
										</div>
										<div class="p-0 bio_text user_description">
											<div class="share_text black_text">Ваш профиль прошел подтверждение</div>
										</div>
									</div>
									<div class="col-md-4">								
										<a @click.capture="showNotification(notification)" data-toggle="modal" data-target=".modal-contact-share" href="#" class="btn-small red mt-3">Открыть уведомление</a>
									</div>
								</div>
							</template>
							<template v-if="notification.type == 'App\\Notifications\\KnowledgeBaseRejectNotification' || notification.type == 'App\Notifications\KnowledgeBaseRejectNotification'">
								<div class="row">
									<div class="col-md-3">
										<div class="avatar-circle small">
											<template v-if="notification.data.sender.id == 1">
												<a href="#" class="ratio img-responsive img-circle avatar" :style="'background-image:url(\'/img/logo.svg\');background-color:#fff;background-repeat:no-repeat;background-position: 50% 50%;background-size: contain;'"></a>
											</template>
											<template v-else>
												<a :href="route('participant.show', notification.data.sender)" class="ratio img-responsive img-circle avatar" :style="'background-image:url(\'' + notification.data.sender.avatar + '\');background-size: contain;'"></a>
											</template>
										</div>
									</div>
									<div class="col-md-5 pb-2">
										<div class="user_title">
											<template v-if="notification.data.sender.id == 1">
												<a class="font-weight-bold black_text" href="#">{{ notification.data.sender.full_name }}</a>
											</template>
											<template v-else>
												<a class="font-weight-bold black_text" :href="route('participant.show', notification.data.sender)">{{ notification.data.sender.full_name }}</a> 
											</template>
											<br />
											<div class="font-weight-bold share_text blue_text">отправил(а) вам уведомление</div>
										</div>
										<div class="p-0 bio_text user_description">
											<div class="share_text black_text">
												<p>К сожалению, ваш материал не может быть добавлен в Базу знаний.</p>
												<p>&nbsp;</p>
												<p>Причины отклонения публикации: {{ notification.data.reject }}</p>
											</div>
										</div>
									</div>
									<div class="col-md-4">								
										<a @click.capture="showNotification(notification)" data-toggle="modal" data-target=".modal-contact-share" href="#" class="btn-small red mt-3">Открыть уведомление</a>
									</div>
								</div>
							</template>
							<template v-if="notification.type == 'App\\Notifications\\KnowledgeBaseApprovedNotification' || notification.type == 'App\Notifications\KnowledgeBaseApprovedNotification'">
								<div class="row">
									<div class="col-md-3">
										<div class="avatar-circle small">
											<template v-if="notification.data.sender.id == 1">
												<a href="#" class="ratio img-responsive img-circle avatar" :style="'background-image:url(\'/img/logo.svg\');background-color:#fff;background-repeat:no-repeat;background-position: 50% 50%;background-size: contain;'"></a>
											</template>
											<template v-else>
												<a :href="route('participant.show', notification.data.sender)" class="ratio img-responsive img-circle avatar" :style="'background-image:url(\'' + notification.data.sender.avatar + '\');background-size: contain;'"></a>
											</template>
										</div>
									</div>
									<div class="col-md-5 pb-2">
										<div class="user_title">
											<template v-if="notification.data.sender.id == 1">
												<a class="font-weight-bold black_text" href="#">{{ notification.data.sender.full_name }}</a>
											</template>
											<template v-else>
												<a class="font-weight-bold black_text" :href="route('participant.show', notification.data.sender)">{{ notification.data.sender.full_name }}</a> 
											</template>
											<br />
											<div class="font-weight-bold share_text blue_text">отправил(а) вам уведомление</div>
										</div>
										<div class="p-0 bio_text user_description">
											<div class="share_text black_text">Ваш материал прошел проверку и был опубликован в <a :href="route('knowledge-base.index')">Базе знаний</a></div>
										</div>
									</div>
									<div class="col-md-4">								
										<a @click.capture="showNotification(notification)" data-toggle="modal" data-target=".modal-contact-share" href="#" class="btn-small red mt-3">Открыть уведомление</a>
									</div>
								</div>
							</template>
							<template v-if="notification.type == 'App\\Notifications\\UserMessageNotification' || notification.type == 'App\Notifications\UserMessageNotification'">
								<div class="row">
									<div class="col-md-3">
										<div class="avatar-circle small">
											<template v-if="notification.data.sender.id == 1">
												<a href="#" class="ratio img-responsive img-circle avatar" :style="'background-image:url(\'/img/logo.svg\');background-color:#fff;background-repeat:no-repeat;background-position: 50% 50%;background-size: contain;'"></a>
											</template>
											<template v-else>
												<a :href="route('participant.show', notification.data.sender)" class="ratio img-responsive img-circle avatar" :style="'background-image:url(\'' + notification.data.sender.avatar + '\');background-size: contain;'"></a>
											</template>
										</div>
									</div>
									<div class="col-md-5 pb-2">
										<div class="user_title">
											<template v-if="notification.data.sender.id == 1">
												<a class="font-weight-bold black_text" href="#">{{ notification.data.sender.full_name }}</a>
											</template>
											<template v-else>
												<a class="font-weight-bold black_text" :href="route('participant.show', notification.data.sender)">{{ notification.data.sender.full_name }}</a> 
											</template>
											<br />
											<div class="font-weight-bold share_text blue_text">отправил(а) вам уведомление</div>
										</div>
										<div class="p-0 bio_text user_description">
											<div class="share_text black_text" v-html="notification.data.message"></div>
										</div>
									</div>
									<div class="col-md-4">								
										<a @click.capture="showNotification(notification)" data-toggle="modal" data-target=".modal-contact-share" href="#" class="btn-small red mt-3">Открыть уведомление</a>
									</div>
								</div>
							</template>
						</div>
					</template>
				</div>
			</div>
		</div>
	</div>

</template>
<script>
	import { Form, HasError, AlertError, AlertSuccess } from 'vform';
	export default {
		name: 'users-notifications-index',
		data () {
			return {
				sendRequestBtn: '',
				modal_title: '',
				acceptRequestBtn: '',
				declineRequestBtn: '',
				changeRequest: false,
				filterType: 0, 
				showModal: false,
				shareContactsOpen: false,
				openNotification: false,
				openNotificationData: null,
				sourceUser: null,
				targetUser: null,
				activeRequest: null,
				activeRequests: [],
				activeSharings: [],
				form: new Form({            
					shareFields: [],
					requestFields: [],
					shareRequestFields: [],
					message: '',
					user_id: '',
					target_id: '',
					user_id_share: '',
					target_id_share: '',
				}),
				notificationsRaw: [],
				notifications: []
			}
		},
		props: [
			'user'
		],
		watch: { 
			filterType: function(newVal, oldVal)
			{
				this.filteredNotifications();
			}
		},
		mounted()
		{
			var vm = this;
			vm.getNotification(vm.user);
		},
		methods: {
			getNotification(user)
			{
				let vm = this;
				axios
					.get(route('api.users.show.notifications', user))
					.then((response) => {
						vm.notificationsRaw = response.data.data;
						vm.filteredNotifications();
					})
					.catch((error) => {
						console.log(error);
					});
			},
			filteredNotifications()
			{
				let vm = this;
				switch(vm.filterType)
				{
					case 1:
						//Уведомления от организаторов
						vm.notifications = vm.notificationsRaw.filter((notification) => {
							if( notification.data.sender !== null && notification.data.sender !== undefined )
							{
								return notification.data.sender.id == 1;
							}
							return true;
						});
					break;
					case 2:
						//Уведомления от участников
						vm.notifications = vm.notificationsRaw.filter((notification) => {
							if( notification.data.sender !== null && notification.data.sender !== undefined )
							{
								return notification.data.sender.id != 1;
							}
							return true;
						});
					break;
					case 0:
					default:
						vm.notifications = vm.notificationsRaw;
					break;
				}
			},
			async sendRequest () {
			const { data } = await this.form.post('/sharefields');
			this.form.message = '';
			this.sendRequestBtn = "Успешно!";
			this.changeRequest = false;
			this.acceptRequest(this.activeRequest.id);
			setTimeout(() => this.sendRequestBtn = 'Отправить запрос с учётом изменений', 1000);
			},
			
			async acceptRequest (id) {
			const { data } = await this.form.post('/acceptRequests/'+id);
			
			document.location.reload(true);
			},
			
			async declineRequest (id) {
			const { data } = await this.form.post('/declineRequest/'+id);
			this.activeRequests = [];
			this.sendRequestBtn = "Успешно!";
			setTimeout(() => this.sendRequestBtn = 'Отправить еще один запрос', 1000);
			},
			
			shareContacts(request){
				this.openNotification = false;
				this.shareContactsOpen = false;
				
				this.sourceUser = request.target;
				this.targetUser = request.user;
				this.form.user_id = request.target.id;
				this.form.target_id = request.user.id;
				this.form.user_id_share = request.target.id;
				this.form.target_id_share = request.user.id;
				this.activeRequest = request;
				this.form.requestFields = [];
				this.form.shareRequestFields = request.fieldIds;
				this.form.shareFields = request.fieldIds;


				this.modal_title = 'Обмен контактами';
				this.sendRequestBtn = "Отправить ответ с учетом изменений";
				this.acceptRequestBtn = "Принять предложение";
				this.declineRequestBtn = "Отказать";

				this.shareContactsOpen = true;
				this.showModal = true;
			},
			showNotification(notification)
			{
				let v = this;
				this.openNotification = false;
				this.modal_title = 'Просмотр уведомления';
				this.openNotification = true;
				this.openNotificationData = notification;
				this.showModal = true;

				console.log('showNotification notification', notification);

				if( notification.read_at == null )
				{
					axios.post(route('api.notification.show.read', notification));
				}
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
			updateStatus(id)
			{
				let v = this;
				axios.post('/notifications/'+id)
				.then(function (response) {
					console.log(response);
				})
				.catch(function (error) {
					console.log(error);
				});

			}

		},
		computed: {},
	}
</script>