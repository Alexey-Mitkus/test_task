<!DOCTYPE html>
<html lang="ru">

<head>
	<meta http-equiv="Content-Type" content="text/html;" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>Документ для печати</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">

	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=PT+Sans:ital,wght@0,400;0,700;1,400;1,700&display=swap" rel="stylesheet">

	<style>
		body{
			font-family: 'PT Sans', sans-serif;
		}
		@page { margin: 30px 0px; }
		.page-break {
				page-break-after: always;
			}
		.subtitle{
			font-size: 20px;
			line-height: 20px;
		}
		.passport{
			margin: 0px 20px;
			height: auto;
			//border: 1px solid #e4e4e4;
			border-radius: 0px;
			overflow: hidden;
		}
		.passport_header{
			background: #fff;
			padding: 0px 20px;
			color: #253639;
			font-size: 28px;
			line-height: 28px;
			font-weight: 700;
		}
		.passport_body{
			padding: 10px 20px;
			background: #fff;
		}
		.passport_block{
			margin-bottom: 0px;
		}
		.passport_body_title{
			color: #000;
			font-size: 16px;
			font-weight: 700;
		}
		.passport_body_subtitle{
			color: #fff;
			font-size: 20px;
			font-weight: 700;
			background: #146B7F;
			border-radius: 4px;
			padding: 10px 10px;
			line-height: 20px;
		}
		.passport_body_form{
			display: table;
			width: 100%;
			flex-wrap: nowrap;
			overflow: hidden;
		}
		.passport_body_form_row {
			display: table-row;
		}
		.passportPage b, .passport_body b{
			font-size: 20px;
			color: #253639;
		}
		.passport_body_form .field_name{
			color: #000;
			font-size: 16px;
			font-weight: 700;
			width: 0%;
			display: table-cell;
		}
		.passport_body_form .field_value{
			min-width: 50%;
			width: 300px;
			position: relative;
			overflow: hidden;
			padding: 0px 10px;
			display: table-cell;
		}
		.passport_body_form .field_value .borderLine{
			position: absolute;
			bottom: 7px;
			height: 1px;
			width: 80%;
			background: transparent;
		}
		.passport_content{
			background: rgba(231, 231, 231, 0.2);
			border-radius: 4px;
			font-size: 14px;
			line-height: 14px;
			padding: 20px 20px;
			margin-bottom: 25px;
		}
		.formLine{
			margin-top: 15px;
		}
		.formData{
			background: rgba(231, 231, 231, 0.2);
			border-radius: 4px;
			line-height: 20px;
			padding: 20px;
		}
		table.customTable {
			width: 100%;
			color: #253639;
			padding: 10px 0px;
			margin-bottom: 25px;
		}
		table.customTable th{
			font-size: 20px;
			line-height: 20px;
		}
		table.customTable td{
			background: rgba(231, 231, 231, 0.2);
			border-radius: 4px;
			line-height: 14px;
			padding: 20px;
		}
		table.customTable .blank{
			background: transparent;
			border-bottom: 0px;
		}
		ol {
		   /* убираем стандартную нумерацию */
		   list-style: none;
		   /* Идентифицируем счетчик и даем ему имя li. Значение счетчика не указано - по умолчанию оно равно 0 */
		   counter-reset: li;
		   padding-inline-start: 15px;
		}
		li:before {
		   /* Определяем элемент, который будет нумероваться — li. Псевдоэлемент before указывает, что содержимое, вставляемое при помощи свойства content, будет располагаться перед пунктами списка. Здесь же устанавливается значение приращения счетчика (по умолчанию равно 1). */
		   counter-increment: li;
		   /* С помощью свойства content выводится номер пункта списка. counters() означает, что генерируемый текст представляет собой значения всех счетчиков с таким именем. Точка в кавычках добавляет разделяющую точку между цифрами, а точка с пробелом добавляется перед содержимым каждого пункта списка */
		   content: counters(li, ".") ". ";
		   font-weight: 700;
		}
	</style>
</head>

<body>
	<div>

		<div class="passport">
			<div class="passport_header">
				<table>
					<tr>
						<td>
							{{ $passport->category->name }}
							<div class="subtitle">
								«{{ $passport->name }}»
							</div>
						</td>
					</tr>
				</table>
			</div>
			<div class="passport_body">
				<div class="passport_block passport_body_form">
					<div>
						<b>Организация</b>
						<table class="customTable">
							<tr>
								<td>{{ !empty($passport->organization) ? optional($passport->organization)->name : $passport->raw_organization }}</td>
							</tr>
						</table>
					</div>
				</div>

				@if( $passport->category->slug <> 'idea')
					<div class="passport_block passport_body_form">
						<div>
							<table class="customTable">
								<tr>
									<th class="mb-2">Текущий статус проекта</th>
									<th></th>
									<th class="mb-2">Дата инициации проекта</th>
									<th></th>
									<th class="mb-2">Дата завершения проекта</th>
								</tr>

								<tr>
									<td>{{ $passport->status }}</td>
									<td class="blank"></td>
									<td>{{ \Carbon\Carbon::parse($passport->start_at)->format('Y-m-d') }}</td>
									<td class="blank"></td>
									<td>{{ \Carbon\Carbon::parse($passport->end_at)->format('Y-m-d') }}</td>
								</tr>
							</table>
						</div>
					</div>
				@endif

				<div class="passportPage">
					<div class="passport_block passport_body_subtitle mb-2">
						@if( $passport->category->slug == 'idea' )
						1. Видение реализации проекта и предпосылки
						@else
						1. Описание и предпосылки проекта
						@endif
					</div>
					@if( !empty($passport->vision) )
						<b>Видение реализации проекта</b>
						<div class="passport_block passport_content">
							{!! $passport->vision !!}
						</div>
					@endif
					@if( !empty($passport->description) )
						<b>Описание проекта</b>
						<table class="customTable">
							<tr>
								<td> {!! $passport->description !!}</td>
							</tr>
						</table>
					@endif
					@if( !empty($passport->prerequisite) )
						<b>Предпоссылки проекта</b>
						<table class="customTable">
							<tr>
								<td> {!! $passport->prerequisite !!}</td>
							</tr>
						</table>
					@endif
					<div class="passport_block passport_body_subtitle mb-2">
						2. Цели и результаты проекта
					</div>
					<div class="passport_block">

						@if( $passport->objectives->count() )
							<b>Цели</b>
							@foreach($passport->objectives as $key => $objective)
							<div class="formLine">
								<div class="formData">
									{{ $objective->pivot->name }}
								</div>
							</div>
							@endforeach
						@endif

						@if( $passport->metrics->count() )
						<div class="formLine">
							<table class="customTable">
								<tr>
									<th class="mb-2" width="40%">Название метрики</th>
									<td width="5%" class="blank"></td>
									<th class="mb-2" width="15%">Целевой показатель</th>
									<td width="5%" class="blank"></td>
									<th class="mb-2" width="15%">Было</th>
									<td width="5%" class="blank"></td>
									<th class="mb-2" width="15%">Стало</th>
								</tr>
								@foreach($passport->metrics as $key => $metric)
								<tr>
									<td>{{ $metric->pivot->name }}</td>
									<td class="blank"></td>
									<td>{{ $metric->pivot->value }}</td>
									<td class="blank"></td>

									@if ( !empty($metric->pivot->before) )
										<td>{{ $metric->pivot->before }}</td>
									@else 
										<td></td>
									@endif

									<td class="blank"></td>
									
									@if ( !empty($metric->pivot->after) )
										<td>{{ $metric->pivot->after }}</td>
									@else 
										<td></td>
									@endif
								</tr>
								@endforeach
							</table>
						</div>
						@endif

						<div class="formLine">
							<b>Результаты и продукты проекта</b>
							@if( $passport->results->count() )
								@foreach($passport->results as $key => $result)
									<div class="formLine">
										<div class="formData">
											{{ $result->pivot->name }}
										</div>
									</div>
								@endforeach
							@endif
						</div>

						@if( $passport->risks->count() )
							<div class="formLine">
								<b>Риски проекта</b>
								@foreach($passport->risks as $key => $risk)
								<div class="formLine">
									<div class="formData">
										{{ $risk->pivot->name }}
									</div>
								</div>
								@endforeach
							</div>
						@endif
					</div>
				</div>
				<div class="passportPage mt-4">
					@if( $passport->interests->count() || $passport->directors->count() || $passport->teams->count() )
						<div class="passport_block passport_body_subtitle">3. Лица, вовлеченные в проект</div>
						<div class="passport_block">
							@if( $passport->directors->count() )
								<div class="formLine">
									<b>Руководитель проекта</b>
									<table class="customTable">
										<tr>
											<td> {{ $passport->directors->first()->pivot->name }}</td>
										</tr>
									</table>
								</div>
							@endif
							@if( $passport->teams->count() )
							<div class="formLine">
								<b>Команда проекта</b>
								<table class="customTable">
									@foreach($passport->teams as $key => $member)
									<tr>
										<td>{{ $member->pivot->name }}</td>
									</tr>
									@endforeach
								</table>
							</div>
							@endif
							<b>Другие заинтересованные лица</b>
							<table class="customTable">
								<tr>
									<th class="mb-2">Роль</th>
									<th></th>
									<th class="mb-2">ФИО</th>
								</tr>
								@if( $passport->interests->count() )
									@foreach($passport->interests as $key => $interest)
									<tr>
										<td>{{ $interest->pivot->role }}</td>
										<td class="blank"></td>
										<td>{{ $interest->pivot->name }}</td>
									</tr>
									@endforeach
								@endif
							</table>
						</div>
						@endif

						@if($passport->resources->count() || $passport->budgets->count() )
							<div class="passport_block passport_body_subtitle">
								4. Ресурсы, бюджет и план проекта
							</div>
							<div class="passport_block">
								@if( $passport->resources->count() )
								<div class="formLine">
								<table class="customTable">
									<tr>
										<th class="mb-2" width="50%">Ресурс</th>
										<th width="20px" class="blank"></th>
										<th class="mb-2">Ед.</th>
									</tr>
									@foreach($passport->resources as $key => $resource)
									<tr>
										<td>{{ $resource->pivot->name }}</td>
										<td class="blank"></td>
										<td>{{ $resource->pivot->value }}</td>
									</tr>
									@endforeach
								</table>
								</div>
								@endif

								@if( $passport->budgets->count() )
									<div class="formLine">
										<table class="customTable">
											<tr>
												<th class="mb-2" width="50%">Бюджет проекта</th>
												<th width="20px" class="blank"></th>
												<th class="mb-2">Руб.</th>
											</tr>
											@foreach($passport->budgets as $key => $budget)
											<tr>
												<td>{{ $budget->pivot->name }}</td>
												<td class="blank"></td>
												<td>{{ $budget->pivot->value }}</td>
											</tr>
											@endforeach
										</table>
									</div>
								@endif
								@if( $passport->plans->count() )
									<div class="formLine">
										<table class="customTable">
											<tr>
												<th class="mb-2" width="50%">План реализации проекта</th>
												<th width="20px" class="blank"></th>
												<th class="mb-2">Дата</th>
											</tr>
											@foreach($passport->plans as $key => $plan)
											<tr>
												<td>{{  $plan->pivot->name }}</td>
												<td class="blank"></td>
												<td>{{ \Carbon\Carbon::parse($plan->pivot->date)->format('Y-m-d') }}</td>
											</tr>
											@endforeach
										</table>
									</div>
								@endif
							</div>
						@endif
				</div>
			</div>
		</div>
	</div>

</body>

</html>