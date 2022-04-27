<template>
<div class="row">

<!-- Модальное окно для обмена контактами -->
<div class="modal modal-contact-share" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
      <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title">Обмен контактами</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
					</button>
              </div>
              <div v-if="showModal" class="modal-body">
                <div class="row">
                	<div class="col-md-6 border-bottom border-right pb-3">
						<div class="row">
							<div class="col-md-4">
								<div class="avatar-circle tiny">
									<a class="ratio img-responsive img-circle avatar" :style="'background-image:url(\'' + sourceUser.avatar + '\');'" v-if="sourceUser.avatar">&nbsp;</a>
									<a class="ratio img-responsive img-circle avatar" v-else>&nbsp;</a>
								</div>
							</div>
							<div class="col-md-8 bio_text">
								<a class="font-weight-bold" :href="route('participant.show', sourceUser)">{{ sourceUser.full_name }}</a>
								<div class="p-0 bio_text">
									<div>{{ sourceUser.job.organization ? sourceUser.job.organization.name : sourceUser.job.raw_organization }}</div>
									<div class="blue_text">{{ ( sourceUser.managment ? ( sourceUser.managment.role ? sourceUser.managment.role.name : '' ) : '' ) }}</div>
								</div>
							</div>
						</div>	
                	</div>
                	<div class="col-md-6 border-bottom border-right pb-3">
                		<div class="row">
							<div class="col-md-4">
								<div class="avatar-circle tiny">
									<a class="ratio img-responsive img-circle avatar" :style="'background-image:url(\'' + targetUser.avatar + '\');'" v-if="targetUser.avatar">&nbsp;</a>
									<a class="ratio img-responsive img-circle avatar" v-else>&nbsp;</a>
								</div>
							</div>
							<div class="col-md-8 bio_text">
								<a class="font-weight-bold" :href="route('participant.show', targetUser)">{{ targetUser.full_name }}</a>
								<div class="p-0 bio_text">
									<div>{{ targetUser.job.organization ? targetUser.job.organization.name : targetUser.job.raw_organization }}</div>
									<div class="blue_text">{{ ( targetUser.managment ? ( targetUser.managment.role ? targetUser.managment.role.name : '' ) : '' ) }}</div>
								</div>
							</div>
						</div>
                	</div>
                </div>
                <div class="row">
	                <div class="col-md-6 border-bottom border-right pb-3">
		                <div class="lk_announcement mt-3 mb-3"><p class="text">1. Отметьте галочкой те контактные данные, которыми вы хотели бы <b>поделиться</b>.</p></div>
						<div class="row bio-share m-0">
									<div class="col-md-12 p-0">
											<div class="row m-0">
												<div class="col-md-3 p-0 bio_legend">Моб. телефон:</div>
												<div class="col-md-5 p-0 bio_text">{{sourceUser.phone.value}} {{form.shareFields.indexOf('6') == -1 ? '(скрыто)' : '(доступно)'}}</div>
												<div v-if="sourceUser.phone" class="col-md-4 p-o"><input v-model="form.shareFields" value="6" type="checkbox" /> Поделиться</div>
											</div>
											
											<div class="row m-0">
												<div class="col-md-3 p-0 bio_legend">E-mail:</div>
												<div class="col-md-5 p-0 bio_text">{{sourceUser.email}}  {{sourceUser.emailShow ? '(доступно)' : '(скрыто)'}}</div>
												<div class="col-md-4 p-o bio_text none"></div>
											</div>
											
											<div class="row m-0">
												<div class="col-md-3 p-0 bio_legend">Skype:</div>
												<div v-if="sourceUser.skype" class="col-md-5 p-0 bio_text">{{sourceUser.skype.value}}  {{form.shareFields.indexOf('8') == -1 ? '(скрыто)' : '(доступно)'}}</div>
												<div v-if="!sourceUser.skype" class="col-md-9 p-0 bio_text none">отсутствует</div>
												<div v-if="sourceUser.skype" class="col-md-4 p-o"><input type="checkbox" v-model="form.shareFields" value="8" /> Поделиться</div>
											</div>
											
										
											<div class="row m-0">
												<div class="col-md-3 p-0 bio_legend">Facebook:</div>
												<div v-if="sourceUser.facebook" class="col-md-5 p-0 bio_text">{{sourceUser.facebook.value}}  {{form.shareFields.indexOf('9') == -1 ? '(скрыто)' : '(доступно)'}}</div>
												<div v-if="!sourceUser.facebook" class="col-md-9 p-0 bio_tex none">отсутствует</div>
												<div v-if="sourceUser.facebook" class="col-md-4 p-o"><input type="checkbox" v-model="form.shareFields" value="9" /> Поделиться</div>
											</div>
											
											
											
											<div class="row m-0">
												<div class="col-md-3 p-0 bio_legend">Вконтакте:</div>
												<div v-if="sourceUser.vkontakte" class="col-md-5 p-0 bio_text">{{sourceUser.vkontakte.value}}  {{form.shareFields.indexOf('10') == -1 ? '(скрыто)' : '(доступно)'}}</div>
												<div v-if="!sourceUser.vkontakte" class="col-md-9 p-0 bio_text none">отсутствует</div>
												<div v-if="sourceUser.vkontakte" class="col-md-4 p-o"><input type="checkbox" v-model="form.shareFields" value="10" /> Поделиться</div>
											</div>
											
											
											
											<div class="row m-0">
												<div class="col-md-3 p-0 bio_legend">Instagram:</div>
												<div v-if="sourceUser.instagram" class="col-md-5 p-0 bio_text">{{sourceUser.instagram.value}}  {{form.shareFields.indexOf('11') == -1 ? '(скрыто)' : '(доступно)'}}</div>
												<div v-if="!sourceUser.instagram" class="col-md-9 p-0 bio_text none">отсутствует</div>
												<div v-if="sourceUser.instagram" class="col-md-4 p-o"><input type="checkbox" v-model="form.shareFields" value="11" /> Поделиться</div>
											</div>
											
											
											<div class="row m-0">
												<div class="col-md-3 p-0 bio_legend">Личный сайт:</div>
												<div v-if="sourceUser.website" class="col-md-5 p-0 bio_text">{{sourceUser.website.value}} {{form.shareFields.indexOf('12') == -1 ? '(скрыто)' : '(доступно)'}}</div>
												<div v-if="!sourceUser.website" class="col-md-9 p-0 bio_text none">отсутствует</div>
												<div v-if="sourceUser.website" class="col-md-4 p-o"><input type="checkbox" v-model="form.shareFields" value="12" /> Поделиться</div>
											</div>
										
									</div>
								</div>
		            </div>
		            
		            <div class="col-md-6 border-bottom border-right pb-3">
		                <div class="lk_announcement mt-3 mb-3"><p class="text">2. Отметьте галочкой те контактные данные, которые вы хотели бы <b>запросить</b>.</p></div>
						<div class="row bio-share m-0">
						<div class="col-md-12 p-0">
						
						<div class="row m-0">
						<div class="col-md-3 p-0 bio_legend">Моб. телефон:</div>
						<div v-if="targetUser.phone && targetUser.phone.ifShow" class="col-md-9 p-0 bio_text">{{targetUser.phone.value}}  {{targetUser.phone.ifShow ? '(доступно)' : '(скрыто)'}}</div>
						<div v-if="targetUser.phone && !targetUser.phone.ifShow" class="col-md-5 p-0 bio_text">По запросу</div>
						<div v-if="!targetUser.phone" class="col-md-9 p-0 bio_text none">отсутствует</div>
						<div v-if="targetUser.phone && !targetUser.phone.ifShow" class="col-md-4 p-o"><input v-model="form.requestFields" value="6" type="checkbox" /> Запросить</div>
						</div>
						
						<div class="row m-0">
						<div class="col-md-3 p-0 bio_legend">E-mail:</div>
						<div class="col-md-5 p-0 bio_text">{{targetUser.email}}</div>
						<div class="col-md-4 p-o bio_text none">(доступно)</div>
						</div>
						
						<div class="row m-0">
						<div class="col-md-3 p-0 bio_legend">Skype:</div>
						<div v-if="targetUser.skype && targetUser.skype.ifShow" class="col-md-9 p-0 bio_text">{{targetUser.skype.value}}  {{targetUser.skype.ifShow ? '(доступно)' : '(скрыто)'}}</div>
						<div v-if="targetUser.skype && !targetUser.skype.ifShow" class="col-md-5 p-0 bio_text">По запросу</div>
						<div v-if="!targetUser.skype" class="col-md-9 p-0 bio_text none">отсутствует</div>
						<div v-if="targetUser.skype && !targetUser.skype.ifShow" class="col-md-4 p-o"><input v-model="form.requestFields" value="8" type="checkbox" /> Запросить</div>
						</div>
						
						
						<div class="row m-0">
						<div class="col-md-3 p-0 bio_legend">Facebook:</div>
						<div v-if="targetUser.facebook && targetUser.facebook.ifShow" class="col-md-9 p-0 bio_text">{{targetUser.facebook.value}}  {{targetUser.facebook.ifShow ? '(доступно)' : '(скрыто)'}}</div>
						<div v-if="targetUser.facebook && !targetUser.facebook.ifShow" class="col-md-5 p-0 bio_text">По запросу</div>
						<div v-if="!targetUser.facebook" class="col-md-9 p-0 bio_text none">отсутствует</div>
						<div v-if="targetUser.facebook && !targetUser.facebook.ifShow" class="col-md-4 p-o"><input v-model="form.requestFields" value="9" type="checkbox" /> Запросить</div>
						</div>
						
						
						
						<div class="row m-0">
						<div class="col-md-3 p-0 bio_legend">Вконтакте:</div>
						<div v-if="targetUser.vkontakte && targetUser.vkontakte.ifShow" class="col-md-9 p-0 bio_text">{{targetUser.vkontakte.value}}  {{targetUser.vkontakte.ifShow ? '(доступно)' : '(скрыто)'}}</div>
						<div v-if="targetUser.vkontakte && !targetUser.vkontakte.ifShow" class="col-md-5 p-0 bio_text">По запросу</div>
						<div v-if="!targetUser.vkontakte" class="col-md-9 p-0 bio_text none">отсутствует</div>
						<div v-if="targetUser.vkontakte && !targetUser.vkontakte.ifShow" class="col-md-4 p-o"><input v-model="form.requestFields" value="10" type="checkbox" /> Запросить</div>
						</div>
						
						
						
						<div class="row m-0">
						<div class="col-md-3 p-0 bio_legend">Instagram:</div>
						<div v-if="targetUser.instagram && targetUser.instagram.ifShow" class="col-md-9 p-0 bio_text">{{targetUser.instagram.value}}  {{targetUser.instagram.ifShow ? '(доступно)' : '(скрыто)'}}</div>
						<div v-if="targetUser.instagram && !targetUser.instagram.ifShow" class="col-md-5 p-0 bio_text">По запросу</div>
						<div v-if="!targetUser.instagram" class="col-md-9 p-0 bio_text none">отсутствует</div>
						<div v-if="targetUser.instagram && !targetUser.instagram.ifShow" class="col-md-4 p-o"><input v-model="form.requestFields" value="11" type="checkbox" /> Запросить</div>
						</div>
						
						
						<div class="row m-0">
						<div class="col-md-3 p-0 bio_legend">Личный сайт:</div>
						<div v-if="targetUser.website && targetUser.website.ifShow" class="col-md-9 p-0 bio_text">{{targetUser.website.value}}  {{targetUser.website.ifShow ? '(доступно)' : '(скрыто)'}}</div>
						<div v-if="targetUser.website && !targetUser.website.ifShow" class="col-md-5 p-0 bio_text">По запросу</div>
						<div v-if="!targetUser.website" class="col-md-9 p-0 bio_text none">отсутствует</div>
						<div v-if="targetUser.website && !targetUser.website.ifShow" class="col-md-4 p-o"><input v-model="form.requestFields" value="12" type="checkbox" /> Запросить</div>
						</div>
						
						</div>
						</div>
		            </div>
	            </div>
	            
	            <div class="row greybg">
		            <div class="col-md-12 pb-3">
		                <div class="lk_announcement mt-3 mb-3"><p class="text">3. При необходимости, можете <b>добавить сообщение</b> к своему запросу в поле ниже. Этот текст увидит получатель запроса.</p></div>
						<textarea class="form-control" type="text" v-model="form.message" placeholder="" name="message" autocomplete="message"></textarea>
		            </div>
		        </div>
	            <div class="row">
		            <div class="col-md-12 align-center"><button @click.prevent="sendRequest" class="btn btn-red red">{{sendRequestBtn}}</button></div>
		        </div>
	            
	            
	            <div v-if="activeRequests.length > 0" class="row mt-3 pt-3 border-top">
              		<div  class="col-md-12 mb-3">
              			<table class="table table-sm table-hover mb-0">
              		<h5><b>Активные запросы</b></h5>
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
              	</div>
              </div>
       </div>
  </div>
</div>


<!-- Список пользователей -->
<div class="col-md-12 pr-0 pl-0">
<div class="box-search p-0">
    <div class="box-module border_bottom">
        <span style="position: absolute; top: 16px;" class="search_icon"></span>
       <input style="padding-left:25px;width: 100%;" v-model="search" class="search_input" type="search" placeholder="Поиск участников" />
    </div>
    <div class="box-module border_bottom"> 
        <div class="search-category-choise">
		    <div class="col-md-4 search-params">
			    <a @click.prevent="sortParam == 'dateDown' ? sortParam='dateUp' : sortParam='dateDown'" href="#"><span :class="sortParam == 'dateUp' ? 'sort_up_icon' : 'sort_down_icon'"></span>По дате регистрации</a>
		    </div>
		    <div class="col-md-4 search-params">
			   <a @click.prevent="sortParam == 'lastnameUp' ? sortParam='lastnameDown' : sortParam='lastnameUp'" href="#"><span :class="sortParam == 'lastnameUp' ? 'az_icon' : 'za_icon'"></span>По фамилии</a>
		    </div>
			<div class="col-md-4 search-params">
				<a @click.prevent="openSearchParams" href="#"><span class="search-params-icon"></span>{{showParams ? 'По параметрам' : 'Закрыть'}}</a>
			</div>
	    </div>
    </div>

	<!-- меню поиска по парметрам -->
	<div class="col-md-12 p-0" :class="{ 'd-none': showParams}">
		<div class="card border-0">
			<div class="box-search">
				<div class="box-module border_bottom">
					<div class="box-title">Поиск по параметрам</div>
					<div v-if="place" @click="place = null" class="filter_title">
						<span class="close_icon"></span>{{place}}
					</div>
					<div v-if="role" @click="role = null" class="filter_title">
						<span class="close_icon"></span>{{role.name}}
					</div>
					<div v-if="education" @click="education = null" class="filter_title">
						<span class="close_icon"></span>{{education}}
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
					<input id="education" class="form-control" type="text" placeholder="Место обучения" name="education" v-model="education" autocomplete="education" >
				</div>
			</div>
		</div>
	</div>


    <div v-for="participant in filteredUsersLists" class="box-module border-bottom p-4" :key="participant.id">
	    <div class="row d-flex justify-content-between">
		    <div class="col-md-3 pr-0 pl-0">
				<div class="avatar-circle small">
					<a :href="route('participant.show', participant)" class="ratio img-responsive img-circle avatar" :style="'background-image:url(\'' + participant.avatar + '\');'" v-if="participant.avatar"></a>
					<a :href="route('participant.show', participant)" class="ratio img-responsive img-circle avatar" v-else></a>
				</div>
		    </div>
		    <div class="col-md-6">
			    <a class="font-weight-bold" :href="route('participant.show', participant)">{{ participant.full_name }}</a>
			    <div class="p-0 bio_text">
				    <a @click.prevent="place = (participant.job.organization ? participant.job.organization.name : participant.job.raw_organization)" href="#">{{ participant.job.organization ? participant.job.organization.name : participant.job.raw_organization }}</a>
				    <a @click.prevent="role = participant.managment.role" href="#" v-show="participant.managment">{{ ( participant.managment ? ( participant.managment.role ? participant.managment.role.name : '' ) : '' ) }}</a>
			    </div>
		    </div>
		    <div class="col-md-3 pr-0 pl-0 ">
			    <a :href="route('participant.show', participant)" class="btn-small blue">Просмотреть профиль</a>
				<!-- <a v-if="participant.id != sourceUser.id" @click.capture="shareContacts(participant)" data-toggle="modal" data-target=".modal-contact-share" href="#" class="btn-small red mt-2">
					Обменяться контактами
				</a> -->
		    </div>
		</div>
    </div>
</div>

</div>

</div>
</template>
<script>
import { Form, HasError, AlertError, AlertSuccess } from 'vform';
import { ModelSelect, ModelListSelect } from 'vue-search-select';

export default {
	name: 'participants-index',
	data () {
		return {
		sendRequestBtn: 'Отправить запрос',
		sortParam: 'dateDown',
		showModal: false,
		search: '',
		place: null,
		role: null,
		education: null,
		places: [],
		ratingwindth: 'width:',
		activeRequests: [],
		activeSharings: [],
        filteredUsersLists: [],
		form: new Form({            
			shareFields: [],
			requestFields: [],
			message: '',
			user_id: '',
			target_id: '',
		}),
		targetUser: [],
		shareEmail: [],
		modal: {
			name: '',
		},
		roles: [],
		showParams: true,
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
    },
	methods: {
		async sendRequest()
        {
			const { data } = await this.form.post('/sharefields');
			this.form.message = '';
			this.sendRequestBtn = "Успешно!";
			setTimeout(() => this.sendRequestBtn = 'Отправить еше один запрос', 1000);
		},
		async deleteRequest (id, index)
        {
			let v = this;
			axios.delete('/requests/'+id)
			.then(function (response) {
				v.activeRequests.splice(index, 1)
			})
			.catch(function (error) {
				console.log(error);
			});

		},
		async getRequests (user_id, target_id)
        {
			let v = this;
			axios.post('/requestsindex', {
				user_id: user_id,
				target_id: target_id
			})
			.then(function (response) {
				v.activeRequests = response.data.data;
				if(response.data.data.length > 0)
					v.form.requestFields = v.activeRequests[v.activeRequests.length-1].fieldIds;
			})
			.catch(function (error) {
				console.log(error);
			});
		},
		async getSharings (user_id, target_id) {
			let v = this;
			axios.post('/sharingsindex', {
				user_id: user_id,
				target_id: target_id
			})
			.then(function (response) {
				v.activeSharings = response.data.data;
				if(response.data.data.length > 0)
					v.form.shareFields = v.activeSharings[v.activeSharings.length-1].fieldIds;
			})
			.catch(function (error) {
				console.log(error);
			});
		},
		shareContacts(user){
			console.log(user);
			this.targetUser = user;
			this.form.user_id = this.sourceUser.id;
			this.form.target_id = this.targetUser.id;
			this.form.shareFields = [];
			this.form.requestFields = [];
			this.activeRequests = [];
			this.activeSharings = [];
			this.sendRequestBtn = "Отправить запрос";
			this.getRequests(this.form.user_id, this.form.target_id);
			this.getSharings(this.form.user_id, this.form.target_id);
			this.showModal = true;	
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
			axios.get(route('api.users.managment.roles'))
			.then((response) =>{
                this.roles = response.data.data;
			})
			.catch(function(error){
				console.error(error);
			});
        },
		openSearchParams() {
			if (this.showParams === false) {
				this.showParams = true;
			} else {
				this.showParams = false;
			}
		},
		sourceUser()
        {
            return collect(JSON.parse(this.user));	
	    },
        filteredUsers()
        {
            let participants = collect(JSON.parse(this.users));

            this.place ? this.place = this.place : this.place = null;
            this.role ? this.role = this.role : this.role = null;
            this.education ? this.education = this.education : this.education = null;
            
            switch (this.sortParam)
            {
                case 'lastnameUp':
                    participants = participants.sort(function (d1, d2) {return (d1.last_name.pivot.value.toLowerCase() > d2.last_name.pivot.value.toLowerCase()) ? 1 : -1;});
                break;
                case 'lastnameDown':
                    participants = participants.sort(function (d1, d2) {return (d1.last_name.pivot.value.toLowerCase() < d2.last_name.pivot.value.toLowerCase()) ? 1 : -1;});
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

            if( this.search )
            {
                participants = participants.filter((participant) => {
                    return participant.full_name.toLowerCase().includes(this.search.toLowerCase());
                });
            }
            
            if( this.place )
            {
            	var v = this;
            	participants = participants.filter((participant) => {
                    if( participant.job !== null &&  participant.job.organization !== null )
                    {
                        return participant.job.organization.id == this.place;
                    }
            	});
            }
            
            if( this.role )
            {
            	var v = this;
            	participants = participants.filter((participant) => {
                    if( participant.managment !== null && participant.managment.role !== null )
                    {
                        return this.role.id === participant.managment.role.id;
                    }
            	});
            }
            
            if( this.education )
            {
            	var v = this,
                    findEducation = this.education.toLowerCase();

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
            
            participants = participants.filter(function(participant){
                participant.permissions = collect(participant.permissions);
                participant.roles = collect(participant.roles);
                return participant.permissions.contains('name', 'verified') || participant.roles.contains('name', 'admin');
            });

            this.filteredUsersLists = participants;
        }
	},
    mounted(){
        this.getRoles();
        this.filteredUsers();
    },
	computed: {
	},
	components: {
		ModelSelect,
		ModelListSelect,
	}
}
</script>