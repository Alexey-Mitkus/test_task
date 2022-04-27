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
		@page { margin: 20px 0px; }
		.page-break {
				page-break-after: always;
			}
		.passport{
			margin: 0px 20px;
			height: auto;
			//border: 1px solid #e4e4e4;
			border-radius: 0px;
			overflow: hidden;
		}
		.passport_header{
			background: #903931;
			padding: 20px;
			color: #fff;
			font-size: 22px;
			font-weight: 700;
		}
		.passport_body{
			padding: 40px 20px;
			background: #fff6f6;
		}
		.passport_block{
			margin-bottom: 20px;
		}
		.passport_body_title{
			color: #000;
			font-size: 16px;
			font-weight: 700;
		}
		.passport_body_subtitle{
			color: #fff;
			font-size: 16px;
			font-weight: 700;
			background: #903931;
			padding: 3px 10px;
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
			background: #fff;
			font-size: 14px;
			border-bottom: 3px solid #903931;
			padding: 15px 15px 15px 15px;
			margin-bottom: 25px;
		}
		.formLine{
			margin-top: 15px;
		}
		.formData{
			background: #f3f3f3;
			border-bottom: 2px solid #913831;
			padding: 3px;
		}
		table.customTable {
			width: 100%;
		}
		table.customTable td{
			background: #f3f3f3;
			border-bottom: 2px solid #913831;
			padding: 3px;
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
		<div class="d-flex justify-content-end mb-4">
			<a class="btn btn-primary" href="{{ route('service.digital.passport.show.download', $passport) }}">Экспорт PDF</a>
		</div>
		<div class="passport">
			<div class="passport_header">
				<table>
					<tr>
						<td>
							<img height="46px" src="https://smarthr.moscow/digital_idea__passport.png" />
						</td>
						<td class="pl-3">
							{{ $passport->category->name }}
							<br/>
							«{{ $passport->name }}»
						</td>
					</tr>	
				</table>
			</div>
			<div class="passport_body">
				<div class="passport_block passport_body_form">
					<div class="passport_body_form_row">
						<div class="field_name">
							Организация:
						</div>
						<div class="field_value">
							{{ !empty($passport->organization) ? optional($passport->organization)->name : $passport->raw_organization }}
							<div class="borderLine"></div>
						</div>
						<div class="field_name">
							Дата:
						</div>
						<div class="field_value">
							{{ \Carbon\Carbon::parse($passport->created_at)->format('d.m.Y') }}
							<div class="borderLine"></div>
						</div>
					</div>
				</div>
				<div class="passportPage">
					<div class="passport_block passport_body_subtitle">
						Описание и предпосылки проекта
					</div>
					@if( !empty($passport->description) )
					<b>Описание проекта</b>
					<div class="passport_block passport_content">
						{!! $passport->description !!}
					</div>
					@endif
					@if( !empty($passport->prerequisite) )
					<b>Предпоссылки проекта</b>
					<div class="passport_block passport_content">
						{!! $passport->prerequisite !!}
					</div>
					@endif
					@if( !empty($passport->vision) )
					<b>Видение проекта</b>
					<div class="passport_block passport_content">
						{!! $passport->vision !!}
					</div>
					@endif
					
					@if( $passport->interests->count() )
					<div class="passport_block passport_body_subtitle">
							Лица вовлечённый в проект
					</div>
					<div class="passport_block passport_content">
						
						<table class="customTable">
							<tr>
								<th>Роль:</th>
								<th></th>
								<th>ФИО:</th>
							</tr>
							@foreach($passport->interests as $key => $interest)
							<tr>
								<td>{{ $interest->pivot->role }}</td>
								<td class="blank"></td>
								<td>{{ $interest->pivot->name }}</td>
							</tr>
							<tr>
								<td class="blank"></td>
								<td class="blank"></td>
								<td class="blank"></td>
							</tr>
							@endforeach
						</table>
						@if( $passport->directors->count() )
						<div class="formLine">
							<b>Руководитель проекта:</b>
							<div class="formData">
								{{ $passport->directors->first()->pivot->name }}
							</div>
						</div>
						@endif
						@if( $passport->teams->count() )
						<div class="formLine">
							<b>Команда проекта:</b>
							<table class="customTable">
								@foreach($passport->teams as $key => $member)
								<tr>
									<td>{{ $member->pivot->name }}</td>
								</tr>
								<tr>
									<td class="blank"></td>
								</tr>
								@endforeach
							</table>
						</div>
						@endif
					</div>
					@endif
				</div>
				<div class="passportPage">
					<div class="passport_block passport_body_subtitle">Цели и результаты проекта</div>
					<div class="passport_block passport_content">
						@if( $passport->objectives->count() )
						
							@foreach($passport->objectives as $key => $objective)
							<div class="formLine">
								<b>Цель #{{ $loop->index + 1 }}</b>
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
										<th>Название метрики:</th>
										<td class="blank"></td>
										<th>Целевой показатель</th>
										<td class="blank"></td>
										<th>Было:</th>
										<td class="blank"></td>
										<th>Стало:</th>
									</tr>
									@foreach($passport->metrics as $key => $metric)
									<tr>
										<td>{{ $metric->pivot->name }}</td>
										<td class="blank"></td>
										<td>{{ $metric->pivot->value }}</td>
										<td class="blank"></td>
										<td>{{ $metric->pivot->before }}</td>
										<td class="blank"></td>
										<td>{{ $metric->pivot->after }}</td>
									</tr>
									<tr>
										<td class="blank"></td>
										<td class="blank"></td>
										<td class="blank"></td>
										<td class="blank"></td>
										<td class="blank"></td>
										<td class="blank"></td>
										<td class="blank"></td>
									</tr>
									@endforeach
								</table>
							</div>
						@endif
						@if( $passport->results->count() )
							<div class="formLine">
								<b>Результаты и продукты проекта</b>
								@foreach($passport->results as $key => $result)
									<div class="formLine">
										<div class="formData">
											{{ $result->pivot->name }}
										</div>
									</div>
								@endforeach
							</div>
						@endif
						
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
					@if( $passport->resources->count() || $passport->budgets->count() )
						<div class="passport_block passport_body_subtitle">Ресурсы, бюджет и план проекта</div>
						<div class="passport_block passport_content">
							@if( $passport->resources->count() )
								<div class="formLine">
									<table class="customTable">
										<tr>
											<th width="50%">Ресурс:</th>
											<th width="20px" class="blank"></th>
											<th>ед.</th>
										</tr>
										@foreach($passport->resources as $key => $resource)
										<tr>
											<td>{{ $resource->pivot->name }}</td>
											<td class="blank"></td>
											<td>{{ $resource->pivot->value }}</td>
										</tr>
										<tr>
											<td class="blank"></td>
											<td class="blank"></td>
											<td class="blank"></td>
										</tr>
										@endforeach
									</table>
								</div>
							@endif
							@if( $passport->budgets->count() )
								<div class="formLine">
									<table class="customTable">
										<tr>
											<th width="50%">Статья расходов:</th>
											<th width="20px" class="blank"></th>
											<th>руб.</th>
										</tr>
										@foreach($passport->budgets as $key => $budget)
										<tr>
											<td>{{ $budget->pivot->name }}</td>
											<td class="blank"></td>
											<td>{{ $budget->pivot->value }}</td>
										</tr>
										<tr>
											<td class="blank"></td>
											<td class="blank"></td>
											<td class="blank"></td>
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